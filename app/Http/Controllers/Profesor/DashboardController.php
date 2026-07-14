<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Aula;
use App\Models\CitaPsicologica;
use App\Models\Nota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Exports\NotasPlantillaExport;
use App\Exports\NotasTutoradosExport;
use App\Imports\NotasImport;
use Maatwebsite\Excel\Facades\Excel;

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

        if (request()->ajax()) {
            return response()->json(['success' => true, 'mensaje' => 'Notas guardadas.']);
        }

        return back()->with('success', '¡Notas guardadas correctamente!');
    }

    /* ──────────────────────────────────────────────
     *  5. NOTAS TUTORADOS (vista Blade)
     * ────────────────────────────────────────────── */
    public function notasTutorados()
    {
        $profesor      = Auth::user();
        $aulasTutoria  = Aula::where('tutor_id', $profesor->id)->get();

        if ($aulasTutoria->isEmpty()) abort(403, 'No eres tutor de ningún salón.');

        $selectedAulaId = request()->query('aula_id');
        $aulaTutoria    = $selectedAulaId 
            ? $aulasTutoria->firstWhere('id', $selectedAulaId) 
            : $aulasTutoria->first();

        if (!$aulaTutoria) {
            $aulaTutoria = $aulasTutoria->first();
        }

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
            'aulaTutoria', 'alumnos', 'asignaciones', 'notasOrganizadas', 'esTutor', 'aulasTutoria'
        ));
    }

    /* ──────────────────────────────────────────────
     *  5b. EXPORTAR NOTAS DE TUTORADOS (Excel)
     * ────────────────────────────────────────────── */
    public function exportarNotasTutorados()
    {
        $profesor     = Auth::user();
        $aulasTutoria = Aula::where('tutor_id', $profesor->id)->get();

        if ($aulasTutoria->isEmpty()) abort(403, 'No eres tutor de ningún salón.');

        $selectedAulaId = request()->query('aula_id');
        $aulaTutoria    = $selectedAulaId
            ? $aulasTutoria->firstWhere('id', $selectedAulaId)
            : $aulasTutoria->first();

        if (!$aulaTutoria) {
            $aulaTutoria = $aulasTutoria->first();
        }

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

        $nombreArchivo = 'notas_tutorados_' . $aulaTutoria->grado . $aulaTutoria->seccion . '.xlsx';

        return Excel::download(
            new NotasTutoradosExport($aulaTutoria, $alumnos, $asignaciones, $notasOrganizadas),
            $nombreArchivo
        );
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

    /* ──────────────────────────────────────────────
     *  8. API — alumnos para derivación a psicología
     * ────────────────────────────────────────────── */
    public function apiAlumnosPsicologia()
    {
        $alumnos = User::query()
            ->where('rol', 'alumno')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get(['id', 'nombres', 'apellidos']);

        return response()->json([
            'alumnos' => $alumnos->map(fn($a) => [
                'id' => $a->id,
                'nombres' => trim($a->nombres),
                'apellidos' => trim($a->apellidos),
            ]),
        ]);
    }

    /* ──────────────────────────────────────────────
     *  9. API — derivar alumno a psicología
     * ────────────────────────────────────────────── */
    public function apiDerivarPsicologia(Request $request)
    {
        $datos = $request->validate([
            'alumno_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn($q) => $q->where('rol', 'alumno')),
            ],
            'motivo' => ['required', 'string', 'min:5', 'max:400'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ]);

        $motivoCompleto = "Motivo: {$datos['motivo']}";
        if (!empty($datos['descripcion'])) {
            $motivoCompleto .= "\n\nDescripción:\n" . trim($datos['descripcion']);
        }

        CitaPsicologica::create([
            'alumno_id' => $datos['alumno_id'],
            'profesor_id' => Auth::id(),
            'psicologo_id' => null,
            'motivo' => $motivoCompleto,
            'fecha_cita' => null,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => 'Derivación enviada a psicología.',
        ]);
    }

    /* ──────────────────────────────────────────────
     *  10. EXPORTAR PLANTILLA DE NOTAS (Excel)
     * ────────────────────────────────────────────── */
    public function exportarPlantilla($asignacion_id)
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

        $nombreArchivo = 'plantilla_notas_' . str_replace(' ', '_', strtolower($asignacion->curso->nombre)) . '_' . $asignacion->aula->grado . $asignacion->aula->seccion . '.xlsx';

        return Excel::download(new NotasPlantillaExport($asignacion, $alumnos, $notas), $nombreArchivo);
    }

    /* ──────────────────────────────────────────────
     *  11. IMPORTAR EXCEL DE NOTAS
     * ────────────────────────────────────────────── */
    public function importarExcel(Request $request, $asignacion_id)
    {
        $profesor   = Auth::user();
        $asignacion = Asignacion::findOrFail($asignacion_id);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $request->validate([
            'archivo_excel' => ['required', 'file', 'mimes:xlsx,xls'],
        ], [
            'archivo_excel.required' => 'Debe seleccionar un archivo.',
            'archivo_excel.file'     => 'El archivo no es válido.',
            'archivo_excel.mimes'    => 'El archivo debe ser un formato Excel (.xlsx, .xls).',
        ]);

        $import = new NotasImport($asignacion->id, $asignacion->aula_id);

        try {
            Excel::import($import, $request->file('archivo_excel'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el archivo Excel. Asegúrese de que no se ha modificado la estructura de la plantilla.');
        }

        $errors = $import->getErrors();
        $updatedCount = $import->getUpdatedCount();

        if (count($errors) > 0) {
            return back()->with('import_errors', $errors)
                ->with('success', "Se procesaron las notas. Se actualizaron {$updatedCount} alumnos, pero hubo errores en algunas filas.");
        }

        return back()->with('success', "¡Excelente! Se actualizaron correctamente las notas de {$updatedCount} alumnos.");
    }
}