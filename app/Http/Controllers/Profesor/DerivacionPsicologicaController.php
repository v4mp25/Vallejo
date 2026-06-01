<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\CitaPsicologica;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DerivacionPsicologicaController extends Controller
{
    public function index()
{
    $profesor = Auth::user();

    // 1. Obtenemos los IDs de las aulas donde enseña este profesor
    $aulasIds = \App\Models\Asignacion::where('profesor_id', $profesor->id)
        ->pluck('aula_id')
        ->unique();

    // 2. Obtenemos los IDs de los alumnos matriculados en esas aulas
    $alumnosIds = \App\Models\Matricula::whereIn('aula_id', $aulasIds)
        ->pluck('alumno_id')
        ->unique();

    // 3. Ahora SÍ traemos solo a los alumnos que le pertenecen a este profe
    $alumnos = User::query()
        ->whereIn('id', $alumnosIds)
        ->where('rol', 'alumno')
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get(['id', 'nombres', 'apellidos']);

    $derivaciones = CitaPsicologica::query()
        ->where('profesor_id', $profesor->id)
        ->latest()
        ->limit(20)
        ->get();

    $ids = $derivaciones
        ->flatMap(fn ($cita) => [$cita->alumno_id, $cita->psicologo_id])
        ->filter()
        ->unique()
        ->values();

    $usuarios = User::query()
        ->whereIn('id', $ids)
        ->get(['id', 'nombres', 'apellidos'])
        ->keyBy('id');

    return view('profesor.psicologia.index', compact('alumnos', 'derivaciones', 'usuarios'));
}
    public function store(Request $request)
    {
        $datos = $request->validate([
            'alumno_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('rol', 'alumno')),
            ],
            'motivo' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'alumno_id.required' => 'Selecciona un alumno para la derivación.',
            'alumno_id.exists' => 'El alumno seleccionado no es válido.',
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
        ]);

        CitaPsicologica::create([
            'alumno_id' => $datos['alumno_id'],
            'profesor_id' => Auth::id(),
            'psicologo_id' => null,
            'motivo' => $datos['motivo'],
            'fecha_cita' => null,
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('profesor.psicologia.index')
            ->with('success', 'Derivación registrada correctamente.');
    }
}

