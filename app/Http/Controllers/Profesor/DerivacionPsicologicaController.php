<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\CitaPsicologica;
use App\Models\User;
use App\Models\Asignacion;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DerivacionPsicologicaController extends Controller
{
    public function index()
    {
        $profesor = Auth::user();

        // 1. Obtenemos los cursos asignados al profesor
        $cursos = Asignacion::with(['curso', 'aula'])
            ->where('profesor_id', $profesor->id)
            ->get()
            ->filter(fn($c) => $c->curso && $c->aula);

        // 2. Alumnos agrupados por aula
        $alumnosPorAula = [];
        foreach ($cursos as $curso) {
            $alumnosPorAula[$curso->aula_id] = User::where('rol', 'alumno')
                ->whereHas('matriculas', fn($q) => $q->where('aula_id', $curso->aula_id))
                ->orderBy('apellidos')
                ->orderBy('nombres')
                ->get(['id', 'nombres', 'apellidos']);
        }

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

        // Lista de psicólogos disponibles para asignar en la derivación
        $psicologos = User::where('rol', 'psicologo')
            ->where('estado', true)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get(['id', 'nombres', 'apellidos']);

        return view('profesor.psicologia.index', compact('cursos', 'alumnosPorAula', 'derivaciones', 'usuarios', 'psicologos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'alumno_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('rol', 'alumno')),
            ],
            'psicologo_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('rol', 'psicologo')),
            ],
            'motivo' => ['required', 'string', 'min:10', 'max:2000'],
            'bimestre' => ['required', 'in:B1,B2,B3,B4'],
        ], [
            'alumno_id.required' => 'Selecciona un alumno para la derivación.',
            'alumno_id.exists' => 'El alumno seleccionado no es válido.',
            'psicologo_id.exists' => 'El psicólogo seleccionado no es válido.',
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
            'bimestre.required' => 'El bimestre académico es obligatorio.',
        ]);

        CitaPsicologica::create([
            'alumno_id' => $datos['alumno_id'],
            'profesor_id' => Auth::id(),
            'psicologo_id' => $datos['psicologo_id'] ?? null,
            'motivo' => $datos['motivo'],
            'bimestre' => $datos['bimestre'],
            'fecha_cita' => null,
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('profesor.psicologia.index')
            ->with('success', 'Derivación registrada correctamente.');
    }
}
