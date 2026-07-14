<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\CitaPsicologica;
use App\Models\User;
use App\Models\MaterialPsicologia;
use App\Models\MaterialAuxiliar;
use App\Models\VirtualMaterial;
use App\Models\VirtualTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $alumno = Auth::user();

        $matricula = $alumno->matriculas()->with('aula')->latest()->first();
        $aula = $matricula->aula ?? null;
        $aulaId = $matricula->aula_id ?? null;

        $asignaciones = collect();

        if ($aulaId) {
            $asignaciones = Asignacion::where('aula_id', $aulaId)
                ->with([
                    'curso',
                    'profesor',
                    'notas' => fn ($q) => $q->where('alumno_id', $alumno->id),
                ])
                ->get()
                ->filter(fn ($asignacion) => $asignacion->curso)
                ->values();
        }

        // Periodos de bimestre esperados en la tabla `notas`
        $periodos = ['B1', 'B2', 'B3', 'B4'];

        // Libreta de notas: cada curso con sus notas por bimestre + promedio
        $libreta = $asignaciones->map(function ($asignacion) use ($periodos) {
            $notasPorPeriodo = [];
            $sumaCalificaciones = 0;
            $cantidadCalificadas = 0;

            foreach ($periodos as $periodo) {
                $nota = $asignacion->notas->firstWhere('periodo', $periodo);
                $notasPorPeriodo[$periodo] = $nota->calificacion ?? null;

                if ($nota && is_numeric($nota->calificacion)) {
                    $sumaCalificaciones += (float) $nota->calificacion;
                    $cantidadCalificadas++;
                }
            }

            $promedio = $cantidadCalificadas > 0
                ? round($sumaCalificaciones / $cantidadCalificadas, 1)
                : null;

            return [
                'curso' => $asignacion->curso,
                'profesor' => $asignacion->profesor,
                'notas' => $notasPorPeriodo,
                'promedio' => $promedio,
            ];
        });

        // Tracker de tareas: total publicadas / enviadas / pendientes por curso
        $tareasPorCurso = $asignaciones->map(function ($asignacion) use ($alumno) {
            $cursoId = $asignacion->curso_id;

            $tareaIds = VirtualMaterial::where('curso_id', $cursoId)
                ->where('classification', 'tarea')
                ->pluck('id');

            $totales = $tareaIds->count();

            $enviadas = $totales > 0
                ? VirtualTask::where('user_id', $alumno->id)
                    ->whereIn('material_id', $tareaIds)
                    ->count()
                : 0;

            $pendientes = max($totales - $enviadas, 0);

            $porcentaje = $totales > 0 ? (int) round(($enviadas / $totales) * 100) : 0;

            return [
                'curso' => $asignacion->curso,
                'total' => $totales,
                'enviadas' => $enviadas,
                'pendientes' => $pendientes,
                'porcentaje' => $porcentaje,
            ];
        });

        return view('alumno.dashboard', compact('aula', 'libreta', 'tareasPorCurso'));
    }

    public function psicologia(): View
    {
        $alumno = Auth::user();

        $citas = CitaPsicologica::where('alumno_id', $alumno->id)
            ->latest()
            ->get();

        $ids = $citas
            ->flatMap(fn ($cita) => [$cita->profesor_id, $cita->psicologo_id])
            ->filter()
            ->unique()
            ->values();

        $usuarios = User::query()
            ->whereIn('id', $ids)
            ->get(['id', 'nombres', 'apellidos'])
            ->keyBy('id');

        // Fetch psychological materials for the student's classroom
        $matricula = $alumno->matriculas()->first();
        $aulaId = $matricula ? $matricula->aula_id : null;

        if ($aulaId) {
            $now = now();
            $materiales = MaterialPsicologia::whereHas('aulas', function ($q) use ($aulaId) {
                $q->where('aula_id', $aulaId);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('fecha_limite')
                  ->orWhere('fecha_limite', '>=', $now);
            })
            ->with('psicologo')
            ->latest()
            ->get();
        } else {
            $materiales = collect();
        }

        // Group by bimester with custom sort order
        $order = [
            'I Bimestre' => 1,
            'II Bimestre' => 2,
            'III Bimestre' => 3,
            'IV Bimestre' => 4
        ];

        $materialesPorBimestre = $materiales->groupBy('bimestre')->sortBy(fn($group, $key) => $order[$key] ?? 99);

        return view('alumno.psicologia.index', compact('citas', 'usuarios', 'materialesPorBimestre'));
    }

    public function auxiliar(): View
    {
        $alumno = Auth::user();

        $citas = \App\Models\DerivacionAuxiliar::where('alumno_id', $alumno->id)
            ->latest()
            ->get();

        $ids = $citas
            ->flatMap(fn ($cita) => [$cita->profesor_id, $cita->auxiliar_id])
            ->filter()
            ->unique()
            ->values();

        $usuarios = User::query()
            ->whereIn('id', $ids)
            ->get(['id', 'nombres', 'apellidos'])
            ->keyBy('id');

        // Materiales del auxiliar para el aula del alumno
        $matricula = $alumno->matriculas()->first();
        $aulaId = $matricula ? $matricula->aula_id : null;

        $order = ['I Bimestre' => 1, 'II Bimestre' => 2, 'III Bimestre' => 3, 'IV Bimestre' => 4];

        if ($aulaId) {
            $now = now();
            $materiales = MaterialAuxiliar::whereHas('aulas', function ($q) use ($aulaId) {
                    $q->where('aula_id', $aulaId);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('fecha_limite')
                      ->orWhere('fecha_limite', '>=', $now);
                })
                ->with('auxiliar')
                ->latest()
                ->get();
        } else {
            $materiales = collect();
        }

        $materialesPorBimestre = $materiales->groupBy('bimestre')->sortBy(fn($group, $key) => $order[$key] ?? 99);

        return view('alumno.auxiliar.index', compact('citas', 'usuarios', 'materialesPorBimestre'));
    }
}
