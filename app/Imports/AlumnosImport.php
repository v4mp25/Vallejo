<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Aula;
use App\Models\Matricula;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading; // <-- AGREGADO
use Maatwebsite\Excel\Concerns\WithBatchInserts; // <-- AGREGADO
use Carbon\Carbon;

// Le agregamos las interfaces de Chunk y Batch
class AlumnosImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        // 1. Filtro Antifantasmas
        if (empty($row['dni'])) {
            return null;
        }

        // 2. La Magia de la Contraseña
        $primerApellido = trim($row['apellido_paterno']);
        $credencial = $row['dni'] . ucfirst(strtolower($primerApellido)); 

        $apellidosCompletos = $primerApellido . ' ' . trim($row['apellido_materno']);
        
        // 3. Manejo de Fechas
        $fechaNacimiento = null;
        if (!empty($row['fecha_nacimiento'])) {
            try {
                $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $row['fecha_nacimiento'])->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $fechaNacimiento = Carbon::parse($row['fecha_nacimiento'])->format('Y-m-d');
                } catch (\Exception $e2) {
                    $fechaNacimiento = null;
                }
            }
        }

        // 4. Crear o actualizar al Alumno
        $alumno = User::firstOrCreate(
            ['codigo_usuario' => $row['dni']],
            [
                'nombres'          => trim($row['nombres']),
                'apellidos'        => $apellidosCompletos,
                'fecha_nacimiento' => $fechaNacimiento, 
                'rol'              => 'alumno',
                'password'         => bcrypt($credencial),
            ]
        );

        // 5. Buscar el Aula
        $mapaGrados = [
            'PRIMERO' => '1',
            'SEGUNDO' => '2',
            'TERCERO' => '3',
            'CUARTO'  => '4',
            'QUINTO'  => '5',
        ];
        
        $gradoTexto = strtoupper(trim($row['grado']));
        $gradoNumero = $mapaGrados[$gradoTexto] ?? $gradoTexto; 

        $aula = Aula::where('grado', $gradoNumero)
                    ->where('seccion', strtoupper(trim($row['seccion'])))
                    ->first();
                    
        // 6. Matricular al alumno
        if ($aula) {
            Matricula::firstOrCreate([
                'alumno_id' => $alumno->id,
                'aula_id'   => $aula->id,
            ]);
        }

        return $alumno;
    }

    // ==========================================
    // MAGIA DE OPTIMIZACIÓN (NUEVO)
    // ==========================================

    // Le dice a Laravel: Inserta a la base de datos en bloques de 100
    public function batchSize(): int
    {
        return 100;
    }

    // Le dice a Laravel: Lee el archivo CSV en pedazos de 100 filas
    public function chunkSize(): int
    {
        return 100;
    }
}