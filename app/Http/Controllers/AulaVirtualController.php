<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VirtualMaterial;
use App\Models\VirtualTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AulaVirtualController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $selectedAssignmentId = $request->query('asignacion_id');

        // Determine courses available depending on role
        $courses = collect();

        if ($user->rol === 'profesor' || $user->rol === 'director') {
            $courses = \App\Models\Asignacion::where('profesor_id', $user->id)
                ->with(['curso', 'aula'])
                ->get()
                ->filter(function ($asignacion) {
                    return $asignacion->curso && $asignacion->aula;
                })
                ->unique(function ($asignacion) {
                    return $asignacion->curso_id . '-' . $asignacion->aula_id;
                })
                ->values();
        }

        if (in_array($user->rol, ['alumno', 'padre'], true)) {
            $aulaIds = \App\Models\Matricula::where('alumno_id', $user->id)->pluck('aula_id');
            $courses = \App\Models\Asignacion::whereIn('aula_id', $aulaIds)
                ->with(['curso', 'aula'])
                ->get()
                ->filter(function ($asignacion) {
                    return $asignacion->curso && $asignacion->aula;
                })
                ->unique(function ($asignacion) {
                    return $asignacion->curso_id . '-' . $asignacion->aula_id;
                })
                ->values();
        }

        $selectedAssignment = null;
        if ($selectedAssignmentId) {
            $selectedAssignment = $courses->firstWhere('id', $selectedAssignmentId);
        }

        if (! $selectedAssignment && $courses->isNotEmpty()) {
            $selectedAssignment = $courses->first();
            $selectedAssignmentId = $selectedAssignment->id;
        }

        $selectedCourseId = $selectedAssignment?->curso_id;
        $selectedCourseName = optional($selectedAssignment?->curso)->nombre;
        $selectedSection = optional($selectedAssignment?->aula)->grado ? optional($selectedAssignment->aula)->grado . '° ' . optional($selectedAssignment->aula)->seccion : null;

        // Load materials and tasks filtered by selected course
        $materials = VirtualMaterial::with('user')
            ->when($selectedCourseId, function ($q) use ($selectedCourseId) {
                $q->where('curso_id', $selectedCourseId);
            })
            ->latest()
            ->get();

        $tasks = VirtualTask::with(['material', 'user'])
            ->when($selectedCourseId, function ($q) use ($selectedCourseId) {
                $q->whereHas('material', function ($q2) use ($selectedCourseId) {
                    $q2->where('curso_id', $selectedCourseId);
                });
            })
            ->latest()
            ->get();

        // Load avisos for the selected course (students) or created by profesor
        if ($user->rol === 'profesor' || $user->rol === 'director') {
            $avisos = \App\Models\Aviso::where('creado_por', $user->id)
                ->when($selectedCourseId, function ($q) use ($selectedCourseId) {
                    $q->where('curso_id', $selectedCourseId);
                })
                ->latest()
                ->get();

            return view('aula-virtual.profesor', compact(
                'materials',
                'tasks',
                'courses',
                'selectedAssignmentId',
                'selectedCourseId',
                'selectedCourseName',
                'selectedSection',
                'avisos'
            ));
        }

        $avisos = \App\Models\Aviso::when($selectedCourseId, function ($q) use ($selectedCourseId) {
            $q->where('curso_id', $selectedCourseId);
        })->latest()->get();

        return view('aula-virtual.estudiante', compact(
            'materials',
            'tasks',
            'courses',
            'selectedAssignmentId',
            'selectedCourseId',
            'selectedCourseName',
            'selectedSection',
            'avisos'
        ));
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'curso_id' => 'nullable|exists:cursos,id',
            'description' => 'nullable|string',
            'resource_type' => 'required|in:file,video,link',
            'file' => 'nullable|file|max:20480',
            'external_url' => 'nullable|url',
            'due_date' => 'nullable|date',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'curso_id' => $request->curso_id,
            'title' => $request->title,
            'description' => $request->description,
            'resource_type' => $request->resource_type,
            'due_date' => $request->due_date,
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('aula-virtual', 'public');
        }

        if ($request->filled('external_url')) {
            $data['external_url'] = $request->external_url;
        }

        VirtualMaterial::create($data);

        return back()->with('success', 'Material publicado correctamente.');
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:virtual_materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'submission_file' => 'nullable|file|max:20480',
            'submission_text' => 'nullable|string',
        ]);

        $data = [
            'material_id' => $request->material_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'submission_text' => $request->submission_text,
        ];

        if ($request->hasFile('submission_file')) {
            $data['submission_file_path'] = $request->file('submission_file')->store('aula-virtual/tareas', 'public');
        }

        VirtualTask::create($data);

        return back()->with('success', 'Tarea enviada correctamente.');
    }

    public function show(VirtualMaterial $material)
    {
        return view('aula-virtual.show', compact('material'));
    }
}
