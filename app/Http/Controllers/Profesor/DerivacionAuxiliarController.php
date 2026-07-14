<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\DerivacionAuxiliar;
use App\Models\User;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DerivacionAuxiliarController extends Controller
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

        // 3. Auxiliares (usuarios con rol auxiliar) para el desplegable
        $auxiliares = User::where('rol', 'auxiliar')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get(['id', 'nombres', 'apellidos']);

        // 4. Historial de derivaciones del docente a Auxiliar
        $derivaciones = DerivacionAuxiliar::query()
            ->where('profesor_id', $profesor->id)
            ->latest()
            ->limit(20)
            ->get();

        $ids = $derivaciones
            ->flatMap(fn ($cita) => [$cita->alumno_id, $cita->auxiliar_id])
            ->filter()
            ->unique()
            ->values();

        $usuarios = User::query()
            ->whereIn('id', $ids)
            ->get(['id', 'nombres', 'apellidos'])
            ->keyBy('id');

        return view('profesor.auxiliar.index', compact('cursos', 'alumnosPorAula', 'auxiliares', 'derivaciones', 'usuarios'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'alumno_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('rol', 'alumno')),
            ],
            'auxiliar_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('rol', 'auxiliar')),
            ],
            'motivo' => ['required', 'string', 'min:10', 'max:2000'],
            'bimestre' => ['required', 'in:B1,B2,B3,B4'],
        ], [
            'alumno_id.required' => 'Selecciona un alumno para la derivación.',
            'alumno_id.exists' => 'El alumno seleccionado no es válido.',
            'auxiliar_id.required' => 'Selecciona un auxiliar para atender el caso.',
            'auxiliar_id.exists' => 'El auxiliar seleccionado no es válido.',
            'motivo.required' => 'El motivo de la derivación es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
            'bimestre.required' => 'El bimester académico es obligatorio.',
        ]);

        DerivacionAuxiliar::create([
            'alumno_id' => $datos['alumno_id'],
            'profesor_id' => Auth::id(),
            'auxiliar_id' => $datos['auxiliar_id'],
            'motivo' => $datos['motivo'],
            'bimestre' => $datos['bimestre'],
            'fecha_cita' => null,
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('profesor.auxiliar.index')
            ->with('success', 'Derivación al Auxiliar registrada correctamente.');
    }
}
