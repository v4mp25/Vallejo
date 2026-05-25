<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Aula;
use App\Models\Nota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /* ──────────────────────────────────────────────
     *  1. DASHBOARD
     * ────────────────────────────────────────────── */
    public function index()
    {
        $profesor    = Auth::user();
        $cursos      = Asignacion::with(['curso', 'aula'])
                        ->where('profesor_id', $profesor->id)
                        ->get();
        $aulaTutoria = Aula::where('tutor_id', $profesor->id)->first();
        $esTutor     = $aulaTutoria !== null;
        $salonTutoria = $esTutor
            ? ['nombre' => $aulaTutoria->grado . '° ' . $aulaTutoria->seccion,
               'grado'  => $aulaTutoria->grado . '° Secundaria']
            : null;

        return view('profesor.dashboard', compact('cursos', 'esTutor', 'salonTutoria', 'aulaTutoria'));
    }

    /* ──────────────────────────────────────────────
     *  2. VER CLASE (vista Blade)
     * ────────────────────────────────────────────── */
    public function verClase($asignacion_id)
    {
        $profesor   = Auth::user();
        $asignacion = Asignacion::with(['curso', 'aula'])->findOrFail($asignacion_id);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $alumnos = User::where('rol', 'alumno')
            ->whereHas('matriculas', fn($q) => $q->where('aula_id', $asignacion->aula_id))
            ->orderBy('apellidos')
            ->get();

        $notas = Nota::where('asignacion_id', $asignacion->id)
            ->get()
            ->groupBy('alumno_id');

        $asistenciaHoy = Asistencia::where('asignacion_id', $asignacion->id)
            ->where('fecha', today())
            ->get()
            ->keyBy('alumno_id');

        $aulaTutoria = Aula::where('tutor_id', $profesor->id)->first();
        $esTutor     = $aulaTutoria !== null;

        return view('profesor.clase', compact(
            'asignacion', 'alumnos', 'notas', 'asistenciaHoy', 'esTutor', 'aulaTutoria'
        ));
    }

    /* ──────────────────────────────────────────────
     *  3. GUARDAR ASISTENCIA
     * ────────────────────────────────────────────── */
    public function guardarAsistencia(Request $request, $asignacion_id)
    {
        $profesor   = Auth::user();
        $asignacion = Asignacion::findOrFail($asignacion_id);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        // Soporta tanto JSON (fetch) como form normal
        $data = $request->json()->all() ?: $request->all();

        $fecha       = $data['fecha'] ?? today()->toDateString();
        $asistencias = $data['asistencias'] ?? [];

        foreach ($asistencias as $alumno_id => $info) {
            Asistencia::updateOrCreate(
                [
                    'asignacion_id' => $asignacion->id,
                    'alumno_id'     => $alumno_id,
                    'fecha'         => $fecha,
                ],
                ['estado' => $info['estado'] ?? 'presente']
            );
        }

        return response()->json(['success' => true, 'mensaje' => 'Asistencia guardada.']);
    }

    /* ──────────────────────────────────────────────
     *  4. GUARDAR NOTAS
     * ────────────────────────────────────────────── */
    public function guardarNotas(Request $request, $asignacion_id)
    {
        $profesor   = Auth::user();
        $asignacion = Asignacion::findOrFail($asignacion_id);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $data  = $request->json()->all() ?: $request->all();
        $notas = $data['notas'] ?? [];

        foreach ($notas as $alumno_id => $periodos) {
            foreach ($periodos as $periodo => $info) {
                $calificacion = $info['calificacion'] ?? null;
                if ($calificacion === null || $calificacion === '') continue;

                Nota::updateOrCreate(
                    [
                        'asignacion_id' => $asignacion->id,
                        'alumno_id'     => $alumno_id,
                        'periodo'       => $periodo,
                        'criterio'      => 'Nota final',
                    ],
                    ['calificacion' => $calificacion]
                );
            }
        }

        return response()->json(['success' => true, 'mensaje' => 'Notas guardadas.']);
    }

    /* ──────────────────────────────────────────────
     *  5. NOTAS TUTORADOS (vista Blade)
     * ────────────────────────────────────────────── */
    public function notasTutorados()
    {
        $profesor    = Auth::user();
        $aulaTutoria = Aula::where('tutor_id', $profesor->id)->first();

        if (!$aulaTutoria) abort(403, 'No eres tutor de ningún salón.');

        $alumnos = User::where('rol', 'alumno')
            ->whereHas('matriculas', fn($q) => $q->where('aula_id', $aulaTutoria->id))
            ->orderBy('apellidos')
            ->get();

        $asignaciones  = Asignacion::with('curso')->where('aula_id', $aulaTutoria->id)->get();
        $alumnoIds     = $alumnos->pluck('id');
        $asignacionIds = $asignaciones->pluck('id');

        $notas = Nota::whereIn('alumno_id', $alumnoIds)
            ->whereIn('asignacion_id', $asignacionIds)
            ->get();

        $notasOrganizadas = [];
        foreach ($notas as $nota) {
            $notasOrganizadas[$nota->alumno_id][$nota->asignacion_id][$nota->periodo] = $nota->calificacion;
        }

        $esTutor = true;

        return view('profesor.tutorados', compact(
            'aulaTutoria', 'alumnos', 'asignaciones', 'notasOrganizadas', 'esTutor'
        ));
    }

    /* ──────────────────────────────────────────────
     *  6. API — datos de una clase (JSON para el fetch del frontend)
     *  GET /api/profesor/clase/{id}
     * ────────────────────────────────────────────── */
    public function apiClase($asignacion_id)
    {
        $profesor   = Auth::user();
        $asignacion = Asignacion::with(['curso', 'aula'])->findOrFail($asignacion_id);

        if ($asignacion->profesor_id !== $profesor->id) {
            return response()->json(['error' => 'Sin acceso'], 403);
        }

        $alumnos = User::where('rol', 'alumno')
            ->whereHas('matriculas', fn($q) => $q->where('aula_id', $asignacion->aula_id))
            ->orderBy('apellidos')
            ->get();

        // Notas agrupadas por alumno y periodo
        $notasRaw = Nota::where('asignacion_id', $asignacion->id)
            ->get()
            ->groupBy('alumno_id')
            ->map(fn($ns) => $ns->pluck('calificacion', 'periodo'));

        $resultado = $alumnos->map(fn($a) => [
            'id'        => $a->id,
            'nombres'   => trim($a->nombres),
            'apellidos' => trim($a->apellidos),
            'notas'     => $notasRaw[$a->id] ?? (object)[],
        ]);

        return response()->json(['alumnos' => $resultado]);
    }

    /* ──────────────────────────────────────────────
     *  7. API — notas consolidadas de tutorados (JSON)
     *  GET /api/profesor/tutorados/notas
     * ────────────────────────────────────────────── */
    public function apiTutorados()
    {
        $profesor    = Auth::user();
        $aulaTutoria = Aula::where('tutor_id', $profesor->id)->first();

        if (!$aulaTutoria) {
            return response()->json([
                'salon' => null, 'alumnos' => [], 'asignaciones' => [], 'notas' => []
            ]);
        }

        $alumnos = User::where('rol', 'alumno')
            ->whereHas('matriculas', fn($q) => $q->where('aula_id', $aulaTutoria->id))
            ->orderBy('apellidos')
            ->get();

        $asignaciones  = Asignacion::with('curso')->where('aula_id', $aulaTutoria->id)->get();
        $alumnoIds     = $alumnos->pluck('id');
        $asignacionIds = $asignaciones->pluck('id');

        $notasRaw = Nota::whereIn('alumno_id', $alumnoIds)
            ->whereIn('asignacion_id', $asignacionIds)
            ->get();

        $notas = [];
        foreach ($notasRaw as $n) {
            $notas[$n->alumno_id][$n->asignacion_id][$n->periodo] = $n->calificacion;
        }

        return response()->json([
            'salon'        => $aulaTutoria->grado . '° ' . $aulaTutoria->seccion,
            'alumnos'      => $alumnos->map(fn($a) => [
                'id'        => $a->id,
                'nombres'   => trim($a->nombres),
                'apellidos' => trim($a->apellidos),
            ]),
            'asignaciones' => $asignaciones->map(fn($a) => [
                'id'    => $a->id,
                'curso' => $a->curso->nombre ?? '—',
            ]),
            'notas' => $notas,
        ]);
    }
}