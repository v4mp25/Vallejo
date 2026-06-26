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
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $materials = VirtualMaterial::with('user')->latest()->get();
        $tasks = VirtualTask::with(['material', 'user'])->latest()->get();

        if ($user->rol === 'profesor' || $user->rol === 'director') {
            return view('aula-virtual.profesor', compact('materials', 'tasks'));
        }

        if (in_array($user->rol, ['alumno', 'padre'], true)) {
            return view('aula-virtual.estudiante', compact('materials', 'tasks'));
        }

        return view('aula-virtual.estudiante', compact('materials', 'tasks'));
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resource_type' => 'required|in:file,video,link',
            'file' => 'nullable|file|max:20480',
            'external_url' => 'nullable|url',
            'due_date' => 'nullable|date',
        ]);

        $data = [
            'user_id' => Auth::id(),
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
