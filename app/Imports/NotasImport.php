<?php

namespace App\Imports;

use App\Models\Nota;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NotasImport implements ToCollection, WithHeadingRow
{
    protected $asignacionId;
    protected $aulaId;
    protected $updatedCount = 0;
    protected $errors = [];

    public function __construct($asignacionId, $aulaId)
    {
        $this->asignacionId = $asignacionId;
        $this->aulaId = $aulaId;
    }

    /**
     * Procesa la colección de filas del Excel.
     */
    public function collection(Collection $rows)
    {
        // Obtener los IDs de los alumnos matriculados en esta aula
        $validStudentIds = User::where('rol', 'alumno')
            ->whereHas('matriculas', function ($q) {
                $q->where('aula_id', $this->aulaId);
            })
            ->pluck('id')
            ->toArray();

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // Número de fila real (1-based + 1 de la cabecera)

            // Buscar por id_estudiante o DNI
            $studentId = $row['id_estudiante'] ?? null;
            $dni = $row['dni'] ?? null;

            if (empty($studentId) && empty($dni)) {
                // Fila vacía, la omitimos
                continue;
            }

            $alumno = null;
            if (!empty($studentId)) {
                $alumno = User::find($studentId);
            }
            if (!$alumno && !empty($dni)) {
                $alumno = User::where('codigo_usuario', $dni)->first();
            }

            if (!$alumno) {
                $this->errors[] = "Fila {$rowNum}: No se encontró al estudiante con ID '{$studentId}' o DNI '{$dni}'.";
                continue;
            }

            if (!in_array($alumno->id, $validStudentIds)) {
                $nombreCompleto = $row['estudiante'] ?? ($alumno->apellidos . ', ' . $alumno->nombres);
                $this->errors[] = "Fila {$rowNum}: El estudiante '{$nombreCompleto}' no está matriculado en esta aula.";
                continue;
            }

            // Mapear los bimestres a los periodos del sistema
            $bimestres = [
                'B1' => $row['bimestre_i'] ?? null,
                'B2' => $row['bimestre_ii'] ?? null,
                'B3' => $row['bimestre_iii'] ?? null,
                'B4' => $row['bimestre_iv'] ?? null,
            ];

            $hasUpdated = false;
            foreach ($bimestres as $periodo => $val) {
                if ($val === null || trim($val) === '') {
                    // Si está vacío, no sobrescribimos ni borramos (por seguridad/flexibilidad)
                    continue;
                }

                $calificacion = trim($val);

                // Normalización de notas cualitativas (letras)
                if (in_array(strtolower($calificacion), ['a', 'b', 'c', 'd', 'ad'])) {
                    $calificacion = strtoupper($calificacion);
                }

                // Guardar o actualizar la nota
                Nota::updateOrCreate(
                    [
                        'asignacion_id' => $this->asignacionId,
                        'alumno_id'     => $alumno->id,
                        'periodo'       => $periodo,
                        'criterio'      => 'Nota final',
                    ],
                    ['calificacion' => $calificacion]
                );
                $hasUpdated = true;
            }

            if ($hasUpdated) {
                $this->updatedCount++;
            }
        }
    }

    /**
     * Retorna el número de estudiantes cuyas notas fueron actualizadas.
     */
    public function getUpdatedCount(): int
    {
        return $this->updatedCount;
    }

    /**
     * Retorna los errores acumulados durante la importación.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
