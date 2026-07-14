<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VirtualMaterial;
use App\Models\VirtualTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\TaskGradesExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $selectedAulaId = $selectedAssignment?->aula_id;
        $selectedCourseName = optional($selectedAssignment?->curso)->nombre;
        $selectedSection = optional($selectedAssignment?->aula)->grado ? optional($selectedAssignment->aula)->grado . '° ' . optional($selectedAssignment->aula)->seccion : null;

        // Load materials and tasks filtrados por curso Y por aula/sección específica.
        // Nota: los materiales antiguos que quedaron con aula_id = null (publicados
        // antes de que existiera esta columna, en salones ambiguos) se siguen
        // mostrando por seguridad hasta que se reasignen manualmente.
        $materials = VirtualMaterial::with('user')
            ->when($selectedCourseId, function ($q) use ($selectedCourseId, $selectedAulaId) {
                $q->where('curso_id', $selectedCourseId)
                  ->where(function ($q2) use ($selectedAulaId) {
                      $q2->where('aula_id', $selectedAulaId)->orWhereNull('aula_id');
                  });
            })
            ->latest()
            ->get();

        $tasks = VirtualTask::with(['material', 'user'])
            ->when($selectedCourseId, function ($q) use ($selectedCourseId, $selectedAulaId) {
                $q->whereHas('material', function ($q2) use ($selectedCourseId, $selectedAulaId) {
                    $q2->where('curso_id', $selectedCourseId)
                       ->where(function ($q3) use ($selectedAulaId) {
                           $q3->where('aula_id', $selectedAulaId)->orWhereNull('aula_id');
                       });
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
                'selectedAulaId',
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
            'selectedAulaId',
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
            'aula_id' => 'nullable|exists:aulas,id',
            'description' => 'nullable|string',
            'resource_type' => 'required|in:file,video,link',
            'file' => 'nullable|file|max:20480',
            'external_url' => 'nullable|url',
            'due_date' => 'nullable|date',
            'bimestre' => 'required|in:B1,B2,B3,B4',
            'classification' => 'required|in:material,tarea',
        ]);

        // Seguridad: si se indicó aula, verificar que el profesor realmente
        // dicte ese curso en esa aula (evita que alguien manipule el campo
        // oculto del formulario para publicar en un salón ajeno).
        if ($request->filled('aula_id') && $request->filled('curso_id')) {
            $dictaEseCursoEnEsaAula = \App\Models\Asignacion::where('profesor_id', Auth::id())
                ->where('curso_id', $request->curso_id)
                ->where('aula_id', $request->aula_id)
                ->exists();

            if (!$dictaEseCursoEnEsaAula) {
                abort(403, 'No tienes asignado ese curso en el salón indicado.');
            }
        }

        $data = [
            'user_id' => Auth::id(),
            'curso_id' => $request->curso_id,
            'aula_id' => $request->aula_id,
            'title' => $request->title,
            'description' => $request->description,
            'resource_type' => $request->resource_type,
            'due_date' => $request->due_date,
            'bimestre' => $request->bimestre,
            'classification' => $request->classification,
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

        $material = VirtualMaterial::findOrFail($request->material_id);
        
        if ($material->classification !== 'tarea') {
            return back()->with('error', 'Este recurso no es una tarea académica.');
        }

        if ($material->due_date && now()->startOfDay()->gt($material->due_date)) {
            return back()->with('error', 'El plazo de entrega para esta tarea ha vencido.');
        }

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

    public function update(Request $request, VirtualMaterial $material)
    {
        if ($material->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este recurso.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resource_type' => 'required|in:file,video,link',
            'file' => 'nullable|file|max:20480',
            'external_url' => 'nullable|url',
            'due_date' => 'nullable|date',
            'bimestre' => 'required|in:B1,B2,B3,B4',
            'classification' => 'required|in:material,tarea',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'resource_type' => $request->resource_type,
            'due_date' => $request->due_date,
            'bimestre' => $request->bimestre,
            'classification' => $request->classification,
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('aula-virtual', 'public');
        }

        if ($request->filled('external_url')) {
            $data['external_url'] = $request->external_url;
        } else {
            $data['external_url'] = null;
        }

        $material->update($data);

        return back()->with('success', 'Material actualizado correctamente.');
    }

    public function destroy(VirtualMaterial $material)
    {
        if ($material->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este recurso.');
        }

        $material->delete();

        return back()->with('success', 'Material eliminado correctamente.');
    }

    public function gradeTask(Request $request, VirtualTask $task)
    {
        // Verify that the task belongs to a material created by this teacher
        if ($task->material->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'grade' => 'nullable|string|max:10',
        ]);

        $task->update([
            'grade' => $request->grade,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nota guardada correctamente.',
            'grade' => $task->grade,
        ]);
    }

    public function show(VirtualMaterial $material)
    {
        return view('aula-virtual.show', compact('material'));
    }

    /* ──────────────────────────────────────────────
     *  12. EXPORTAR NOTAS DE TAREA (Excel)
     * ────────────────────────────────────────────── */
    public function exportTaskGrades($material_id, $asignacion_id)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Verify the assignment belongs to this teacher/director
        $asignacion = \App\Models\Asignacion::with(['curso', 'aula'])->findOrFail($asignacion_id);
        if ($user->rol !== 'director' && $asignacion->profesor_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        // Get the task (VirtualMaterial with classification 'tarea')
        $material = VirtualMaterial::where('id', $material_id)
            ->where('classification', 'tarea')
            ->firstOrFail();

        // Get enrolled students in this classroom
        $alumnos = User::where('rol', 'alumno')
            ->whereHas('matriculas', function ($q) use ($asignacion) {
                $q->where('aula_id', $asignacion->aula_id);
            })
            ->orderBy('apellidos')
            ->get();

        // Get student submissions for this material
        $submissions = VirtualTask::where('material_id', $material->id)
            ->whereIn('user_id', $alumnos->pluck('id'))
            ->get();

        $nombreArchivo = 'notas_tarea_' . str_replace(' ', '_', strtolower($material->title)) . '_' . $asignacion->aula->grado . $asignacion->aula->seccion . '.xlsx';

        return Excel::download(new TaskGradesExport($material, $alumnos, $submissions), $nombreArchivo);
    }
}
