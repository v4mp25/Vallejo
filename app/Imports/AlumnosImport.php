<?php

namespace App\Imports;

use App\Models\Aula;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

/**
 * Importa alumnos y los matricula en su aula correspondiente a partir de un
 * archivo Excel (.xlsx). Cada fila se procesa dentro de su propia transacción,
 * de forma que un error en una fila no afecta a las demás.
 *
 * Cabeceras esperadas: nombres, apellidos, dni, grado, seccion, turno,
 * año_academico, celular_apoderado.
 *
 * Nota: el formateador de cabeceras de Laravel Excel ("slug") transforma
 * "año_academico" en "ano_academico" (quita tildes/eñes), por lo que el
 * código busca ambas variantes por seguridad.
 */
class AlumnosImport implements ToCollection, WithHeadingRow
{
    /** @var int Total de filas procesadas correctamente (creadas o ya existentes). */
    private int $filasExitosas = 0;

    /** @var int Total de filas omitidas por errores de datos o de negocio. */
    private int $filasConError = 0;

    /** @var array<int, string> Detalle de errores por fila, listo para mostrar al usuario. */
    private array $errores = [];

    /**
     * Punto de entrada de Laravel Excel: procesa todas las filas del archivo.
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            // +2 porque el índice es base 0 y la fila 1 del Excel es la cabecera.
            $numeroFila = $index + 2;

            $datos = $this->normalizarFila($row);

            // Ignoramos silenciosamente filas completamente vacías (plantillas con espacios de más).
            if ($this->filaEstaVacia($datos)) {
                continue;
            }

            $validacion = Validator::make($datos, [
                'nombres' => ['required', 'string', 'max:255'],
                'apellidos' => ['required', 'string', 'max:255'],
                'dni' => ['required', 'string', 'max:20'],
                'grado' => ['required', 'string', 'max:20'],
                'seccion' => ['required', 'string', 'max:10'],
                'turno' => ['required', 'string', 'max:20'],
                'año_academico' => ['required', 'string', 'max:10'],
                'celular_apoderado' => ['nullable', 'string', 'max:20'],
            ]);

            if ($validacion->fails()) {
                $mensajes = implode(' | ', $validacion->errors()->all());
                $this->registrarError($numeroFila, "Datos inválidos: {$mensajes}");
                continue;
            }

            try {
                DB::transaction(function () use ($datos, $numeroFila): void {
                    $this->procesarFila($datos, $numeroFila);
                });

                $this->filasExitosas++;
            } catch (Throwable $e) {
                // La transacción de esta fila ya fue revertida automáticamente por DB::transaction().
                $this->registrarError($numeroFila, $e->getMessage());
            }
        }
    }

    /**
     * Procesa una fila válida: crea/recupera al alumno, ubica su aula y genera la matrícula.
     *
     * @throws \RuntimeException cuando el aula no existe.
     */
    private function procesarFila(array $datos, int $numeroFila): void
    {
        // Paso A: crear o recuperar al usuario alumno.
        $alumno = User::firstOrCreate(
            ['codigo_usuario' => $datos['dni']],
            [
                'nombres' => $datos['nombres'],
                'apellidos' => $datos['apellidos'],
                'rol' => 'alumno',
                'password' => Hash::make($datos['dni']),
                'estado' => true,
            ]
        );

        // Paso B: ubicar el aula por grado + sección + turno.
        $aula = Aula::where('grado', $datos['grado'])
            ->where('seccion', $datos['seccion'])
            ->get()
            ->first(fn (Aula $candidata) => $this->normalizarTexto($candidata->turno) === $this->normalizarTexto($datos['turno']));

        if (!$aula) {
            throw new \RuntimeException(
                "Fila {$numeroFila}: no se encontró un aula para grado '{$datos['grado']}', sección '{$datos['seccion']}' y turno '{$datos['turno']}'."
            );
        }

        // Paso C: crear (o actualizar) la matrícula del alumno en esa aula/año.
        Matricula::updateOrCreate(
            [
                'alumno_id' => $alumno->id,
                'aula_id' => $aula->id,
                'año_academico' => $datos['año_academico'],
            ],
            [
                'estado' => 'activo',
                'celular_apoderado' => $datos['celular_apoderado'] ?: null,
            ]
        );
    }

    /**
     * Extrae y limpia los campos relevantes de una fila del Excel, tolerando
     * que las cabeceras con tilde/eñe hayan sido transliteradas por el
     * formateador de Laravel Excel (año_academico -> ano_academico).
     *
     * @param  \Illuminate\Support\Collection<string, mixed>  $row
     * @return array<string, string>
     */
    private function normalizarFila(Collection $row): array
    {
        $obtener = fn (string ...$claves): string => trim((string) collect($claves)
            ->map(fn (string $clave) => $row->get($clave))
            ->first(fn ($valor) => filled($valor)));

        return [
            'nombres' => $obtener('nombres'),
            'apellidos' => $obtener('apellidos'),
            'dni' => $obtener('dni'),
            'grado' => $obtener('grado'),
            'seccion' => Str::upper($obtener('seccion')),
            'turno' => $obtener('turno'),
            'año_academico' => $obtener('ano_academico', 'año_academico'),
            'celular_apoderado' => $obtener('celular_apoderado'),
        ];
    }

    /**
     * Compara textos ignorando mayúsculas, tildes y espacios extra (para turnos como "Mañana"/"manana").
     */
    private function normalizarTexto(?string $texto): string
    {
        $texto = Str::of((string) $texto)->trim()->lower()->toString();

        return Str::ascii($texto);
    }

    private function filaEstaVacia(array $datos): bool
    {
        return $datos['nombres'] === '' && $datos['apellidos'] === '' && $datos['dni'] === '';
    }

    private function registrarError(int $numeroFila, string $mensaje): void
    {
        $this->filasConError++;
        $this->errores[] = Str::startsWith($mensaje, "Fila {$numeroFila}")
            ? $mensaje
            : "Fila {$numeroFila}: {$mensaje}";
    }

    public function getFilasExitosas(): int
    {
        return $this->filasExitosas;
    }

    public function getFilasConError(): int
    {
        return $this->filasConError;
    }

    /**
     * @return array<int, string>
     */
    public function getErrores(): array
    {
        return $this->errores;
    }
}
