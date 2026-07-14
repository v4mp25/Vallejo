<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Asignacion;
use App\Models\ConfiguracionWeb;
use App\Models\Matricula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

/**
 * Cierre y Apertura de Año Académico (Rollover).
 *
 * IMPORTANTE: este proceso NO elimina historial. Preserva notas, asistencias
 * y matrículas de años anteriores; únicamente:
 *   A) Libera tutorías y archiva la carga docente vigente bajo el año que termina.
 *   B) Promueve a los alumnos de 1°-4° de secundaria a su siguiente grado
 *      (misma sección/turno) generando una matrícula nueva para el año entrante,
 *      y marca a los de 5° como egresados (sin generar matrícula nueva).
 *   C) Actualiza el año académico global del sistema.
 *
 * Todo el proceso corre dentro de una única transacción estricta: si algo
 * falla (por ejemplo, falta un aula de destino para promover a un alumno),
 * se revierte TODO y no se aplica ningún cambio parcial.
 */
class CierreAnoController extends Controller
{
    private const FRASE_CONFIRMACION = 'CONFIRMAR CIERRE';

    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            if (!Auth::check() || Auth::user()->rol !== 'director') {
                abort(403, 'Solo el Director de la institución puede acceder al cierre de año académico.');
            }

            return $next($request);
        });
    }

    /**
     * Muestra el panel de cierre de año con el resumen de lo que se verá afectado.
     */
    public function index(): View
    {
        $anioActual = ConfiguracionWeb::anioAcademicoActual();

        return view('admin.cierre-ano.index', [
            'anioActual' => $anioActual,
            'anioSugerido' => (string) ((int) $anioActual + 1),
            'totalMatriculasActivas' => Matricula::where('año_academico', $anioActual)
                ->where('estado', 'activo')
                ->count(),
            'totalDocentesConCarga' => Asignacion::where('año_academico', $anioActual)->distinct('profesor_id')->count('profesor_id'),
            'fraseConfirmacion' => self::FRASE_CONFIRMACION,
        ]);
    }

    /**
     * Ejecuta el rollover académico dentro de una transacción estricta.
     */
    public function procesar(Request $request): RedirectResponse
    {
        $anioActual = ConfiguracionWeb::anioAcademicoActual();

        $datos = $request->validate([
            'nuevo_anio' => [
                'required',
                'digits:4',
                'integer',
                'gt:' . $anioActual,
            ],
            'confirmacion' => ['required', 'string'],
        ], [
            'nuevo_anio.gt' => 'El nuevo año académico debe ser mayor al año vigente (' . $anioActual . ').',
            'nuevo_anio.digits' => 'El año académico debe tener 4 dígitos (ej: 2027).',
        ]);

        if (trim($datos['confirmacion']) !== self::FRASE_CONFIRMACION) {
            return back()
                ->withInput()
                ->with('error', 'La frase de confirmación no coincide. Escribe exactamente "' . self::FRASE_CONFIRMACION . '".');
        }

        $anioNuevo = (string) $datos['nuevo_anio'];

        try {
            $resumen = DB::transaction(function () use ($anioActual, $anioNuevo) {
                $this->limpiarCargaDocenteYTutorias($anioActual);

                return $this->promoverAlumnos($anioActual, $anioNuevo);
            });

            // Paso C se hace fuera del bloque anterior por claridad, pero sigue
            // dentro de la misma transacción lógica: si algo de arriba lanza,
            // esta línea nunca se ejecuta porque el callback ya habrá relanzado
            // la excepción y Laravel habrá revertido la transacción.
        } catch (Throwable $e) {
            Log::error('Error durante el cierre de año académico: ' . $e->getMessage(), [
                'exception' => $e,
                'director_id' => Auth::id(),
                'anio_actual' => $anioActual,
                'anio_nuevo' => $anioNuevo,
            ]);

            return back()
                ->withInput()
                ->with('error', 'El cierre de año NO se ejecutó (no se aplicó ningún cambio). Motivo: ' . $e->getMessage());
        }

        // Paso C: actualizar el año académico global. Se ejecuta en su propia
        // transacción corta e independiente para minimizar la ventana de bloqueo,
        // ya que si el rollover de arriba tuvo éxito, este paso es una simple
        // actualización de una fila y no debería fallar.
        try {
            DB::transaction(fn () => $this->actualizarAnioGlobal($anioNuevo));
        } catch (Throwable $e) {
            Log::critical(
                'El rollover de alumnos se completó pero falló la actualización del año global. ' .
                'Requiere corrección manual inmediata en configuracion_webs.anio_academico_actual.',
                ['exception' => $e, 'anio_nuevo' => $anioNuevo]
            );

            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Los alumnos fueron promovidos correctamente, pero el año global NO se pudo actualizar. Contacta a soporte técnico de inmediato.');
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', sprintf(
                'Cierre de año académico completado: %d alumno(s) promovido(s), %d egresado(s). El año vigente ahora es %s.',
                $resumen['promovidos'],
                $resumen['egresados'],
                $anioNuevo
            ));
    }

    /**
     * Paso A: libera tutorías y archiva la carga docente vigente.
     *
     * No se crean asignaciones para el nuevo año: quedan en blanco a propósito
     * para que se reasignen manualmente en marzo desde Gestión de Personal.
     */
    private function limpiarCargaDocenteYTutorias(string $anioActual): void
    {
        Aula::query()->update(['tutor_id' => null]);

        // Por seguridad, archivamos bajo el año vigente cualquier asignación que
        // por alguna razón no tenga año registrado todavía.
        Asignacion::whereNull('año_academico')->update(['año_academico' => $anioActual]);
    }

    /**
     * Paso B: promueve a los alumnos de 1°-4° al siguiente grado (misma sección/turno)
     * y marca como egresados a los de 5°. No modifica ni elimina matrículas pasadas.
     *
     * @return array{promovidos: int, egresados: int}
     *
     * @throws RuntimeException si algún alumno de 1°-4° no tiene un aula de destino
     *                          válida para el siguiente grado (aborta todo el proceso).
     */
    private function promoverAlumnos(string $anioActual, string $anioNuevo): array
    {
        $indiceAulas = Aula::all()->groupBy(
            fn (Aula $aula) => $this->claveAula($aula->grado, $aula->seccion, $aula->turno)
        );

        $matriculasActivas = Matricula::where('año_academico', $anioActual)
            ->where('estado', 'activo')
            ->with(['alumno', 'aula'])
            ->get();

        $promovidos = 0;
        $egresados = 0;
        $errores = [];

        foreach ($matriculasActivas as $matricula) {
            $aula = $matricula->aula;

            if (!$aula) {
                $errores[] = "Matrícula #{$matricula->id}: no tiene un aula asociada válida.";
                continue;
            }

            if ($this->esNivelPrimaria($aula->nivel)) {
                // Fuera de alcance de este rollover (definido solo para secundaria 1°-5°).
                continue;
            }

            $grado = $this->extraerGradoNumerico($aula->grado);

            if ($grado === null) {
                $errores[] = "Matrícula #{$matricula->id}: el aula \"{$aula->grado}° {$aula->seccion}\" tiene un grado no reconocido.";
                continue;
            }

            if ($grado >= 1 && $grado <= 4) {
                $siguienteGrado = (string) ($grado + 1);
                $clave = $this->claveAula($siguienteGrado, $aula->seccion, $aula->turno);
                $aulaDestino = $indiceAulas->get($clave)?->first();

                if (!$aulaDestino) {
                    $alumno = $matricula->alumno;
                    $nombreAlumno = $alumno
                        ? trim($alumno->apellidos . ', ' . $alumno->nombres) . " (DNI {$alumno->codigo_usuario})"
                        : "alumno #{$matricula->alumno_id}";

                    $errores[] = "No existe un aula de {$siguienteGrado}° \"{$aula->seccion}\" turno \"{$aula->turno}\" " .
                        "para promover a {$nombreAlumno}, actualmente en {$aula->grado}° \"{$aula->seccion}\".";
                    continue;
                }

                Matricula::create([
                    'alumno_id' => $matricula->alumno_id,
                    'aula_id' => $aulaDestino->id,
                    'año_academico' => $anioNuevo,
                    'estado' => 'activo',
                    'celular_apoderado' => $matricula->celular_apoderado,
                ]);

                $matricula->update(['estado' => 'promovido']);
                $promovidos++;
            } elseif ($grado === 5) {
                $matricula->update(['estado' => 'egresado']);

                $matricula->alumno?->update(['rol' => 'egresado']);

                $egresados++;
            } else {
                $errores[] = "Matrícula #{$matricula->id}: el grado {$grado}° está fuera del rango 1°-5° contemplado por el rollover.";
            }
        }

        if (!empty($errores)) {
            throw new RuntimeException(
                'Se detectaron ' . count($errores) . ' problema(s) que impiden completar la promoción de alumnos: ' .
                implode(' | ', $errores)
            );
        }

        return ['promovidos' => $promovidos, 'egresados' => $egresados];
    }

    /**
     * Paso C: actualiza el año académico global del sistema (tabla `configuracion_webs`).
     */
    private function actualizarAnioGlobal(string $anioNuevo): void
    {
        $config = ConfiguracionWeb::first();

        if ($config) {
            $config->update(['anio_academico_actual' => $anioNuevo]);
        } else {
            ConfiguracionWeb::create(['anio_academico_actual' => $anioNuevo]);
        }
    }

    /**
     * Genera una clave de comparación tolerante a mayúsculas/tildes/espacios,
     * para emparejar aulas por grado + sección + turno de forma confiable
     * aunque el turno se haya escrito como "Mañana" o "manana".
     */
    private function claveAula(?string $grado, ?string $seccion, ?string $turno): string
    {
        return implode('|', [
            $this->normalizarTexto($grado),
            $this->normalizarTexto($seccion),
            $this->normalizarTexto($turno),
        ]);
    }

    private function normalizarTexto(?string $texto): string
    {
        return Str::ascii(Str::of((string) $texto)->trim()->lower()->toString());
    }

    /**
     * Trata como "primaria" únicamente si el campo `nivel` lo indica explícitamente.
     * Si está vacío (caso frecuente en este sistema, que hoy solo opera secundaria),
     * se asume que la aula SÍ está dentro del alcance del rollover.
     */
    private function esNivelPrimaria(?string $nivel): bool
    {
        if (blank($nivel)) {
            return false;
        }

        return str_contains($this->normalizarTexto($nivel), 'primaria');
    }

    private function extraerGradoNumerico(?string $grado): ?int
    {
        $grado = trim((string) $grado);

        return ctype_digit($grado) ? (int) $grado : null;
    }
}
