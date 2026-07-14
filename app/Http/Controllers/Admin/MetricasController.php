<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VirtualMaterial;
use App\Models\CitaPsicologica;
use App\Models\MaterialPsicologia;
use Illuminate\Http\Request;

class MetricasController extends Controller
{
    public function index(Request $request)
    {
        $bimestreSeleccionado = $request->query('bimestre', 'all');

        // 1. Obtener todos los docentes
        $docentes = User::where('rol', 'profesor')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        // 2. Traer los materiales filtrados por bimestre si no es 'all'
        $queryMateriales = VirtualMaterial::query();
        if ($bimestreSeleccionado !== 'all') {
            $queryMateriales->where('bimestre', $bimestreSeleccionado);
        }
        $materiales = $queryMateriales->get();

        // 3. Totales globales filtrados
        $totalDocentes = $docentes->count();
        $totalMateriales = $materiales->where('classification', 'material')->count();
        $totalTareas = $materiales->where('classification', 'tarea')->count();
        $totalRecursos = $materiales->count();

        // 4. Agrupar por docente
        $metricasDocentes = [];
        foreach ($docentes as $docente) {
            $misMateriales = $materiales->where('user_id', $docente->id);
            $cantMateriales = $misMateriales->where('classification', 'material')->count();
            $cantTareas = $misMateriales->where('classification', 'tarea')->count();
            $total = $misMateriales->count();
            $ultimoSubido = $misMateriales->max('created_at');

            // Nivel de cumplimiento adaptado al filtro de bimester:
            if ($bimestreSeleccionado === 'all') {
                if ($total >= 8) {
                    $cumplimiento = 'Excelente';
                    $color = 'success';
                } elseif ($total >= 3) {
                    $cumplimiento = 'Aceptable';
                    $color = 'info text-white';
                } else {
                    $cumplimiento = 'Bajo';
                    $color = 'danger';
                }
            } else {
                if ($total >= 3) {
                    $cumplimiento = 'Excelente';
                    $color = 'success';
                } elseif ($total >= 1) {
                    $cumplimiento = 'Aceptable';
                    $color = 'info text-white';
                } else {
                    $cumplimiento = 'Bajo';
                    $color = 'danger';
                }
            }

            $metricasDocentes[] = [
                'docente' => $docente,
                'materiales' => $cantMateriales,
                'tareas' => $cantTareas,
                'total' => $total,
                'ultimo_subido' => $ultimoSubido ? \Carbon\Carbon::parse($ultimoSubido)->format('d/m/Y h:i A') : 'Nunca',
                'cumplimiento' => $cumplimiento,
                'color' => $color
            ];
        }

        // 5. Obtener psicólogos y sus métricas
        $psicologos = User::where('rol', 'psicologo')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        $mapBimestreMaterial = [
            'all' => 'all',
            'B1' => 'I Bimestre',
            'B2' => 'II Bimestre',
            'B3' => 'III Bimestre',
            'B4' => 'IV Bimestre'
        ];
        $bimestreMaterial = $mapBimestreMaterial[$bimestreSeleccionado] ?? 'all';

        $metricasPsicologos = [];
        foreach ($psicologos as $psicologo) {
            $queryCitas = CitaPsicologica::where('psicologo_id', $psicologo->id);
            $queryMats = MaterialPsicologia::where('psicologo_id', $psicologo->id);

            if ($bimestreSeleccionado !== 'all') {
                $queryCitas->where('bimestre', $bimestreSeleccionado);
            }
            if ($bimestreMaterial !== 'all') {
                $queryMats->where('bimestre', $bimestreMaterial);
            }

            $citas = $queryCitas->get();
            $materialesPsico = $queryMats->get();

            $citasPendientes = $citas->whereIn('estado', ['pendiente', 'citado'])->count();
            $citasAtendidas = $citas->where('estado', 'atendida')->count();
            $materialesCount = $materialesPsico->count();
            $ultimoSubido = $materialesPsico->max('created_at');

            // Nivel de actividad
            $totalActividades = $citas->count() + $materialesCount;
            if ($totalActividades >= 10) {
                $actividad = 'Alta';
                $color = 'success';
            } elseif ($totalActividades >= 3) {
                $actividad = 'Media';
                $color = 'info text-white';
            } else {
                $actividad = 'Baja';
                $color = 'warning text-dark';
            }

            $metricasPsicologos[] = [
                'psicologo' => $psicologo,
                'citas_pendientes' => $citasPendientes,
                'citas_atendidas' => $citasAtendidas,
                'materiales' => $materialesCount,
                'ultimo_subido' => $ultimoSubido ? \Carbon\Carbon::parse($ultimoSubido)->format('d/m/Y h:i A') : 'Nunca',
                'cumplimiento' => $actividad,
                'color' => $color
            ];
        }

        return view('admin.metricas.index', compact(
            'totalDocentes',
            'totalMateriales',
            'totalTareas',
            'totalRecursos',
            'metricasDocentes',
            'metricasPsicologos',
            'bimestreSeleccionado'
        ));
    }
}
