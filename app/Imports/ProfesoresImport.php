<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Curso;
use App\Models\Aula;
use App\Models\Asignacion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class ProfesoresImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row['dni']) || empty(trim($row['dni']))) {
                continue; 
            }
          
            $fechaLimpia = is_numeric($row['fecha_nacimiento']) 
                ? Date::excelToDateTimeObject($row['fecha_nacimiento'])->format('Y-m-d') 
                : Carbon::parse($row['fecha_nacimiento'])->format('Y-m-d');

           
            $profesor = User::firstOrCreate(
                ['codigo_usuario' => $row['dni']],
                [
                    'nombres'          => $row['nombres'],
                    'apellidos'        => $row['apellidos'],
                    'celular'          => $row['celular'],
                    'fecha_nacimiento' => $fechaLimpia,
                    'rol'              => 'profesor',
                    'password'         => bcrypt($row['dni']),
                ]
            );

      
            $grado = (int) $row['grado'];
            $seccion = strtoupper(trim($row['seccion']));
            
            if ($grado >= 1 && $grado <= 4) {
               $turno = in_array($seccion, ['A', 'B', 'C', 'D']) ? 'Mañana' : 'Tarde';
            } else {
               $turno = in_array($seccion, ['A', 'B', 'C']) ? 'Mañana' : 'Tarde';
            }

           $aula = Aula::firstOrCreate(
                ['grado' => $row['grado'], 'seccion' => $seccion],
                ['turno' => $turno]
            );

           if (isset($row['es_tutor']) && strtoupper(trim($row['es_tutor'])) === 'SI') {
                $aula->tutor_id = $profesor->id;
                $aula->save();
            }

           $curso = Curso::firstOrCreate(
                ['nombre' => trim($row['curso'])]
            );

           Asignacion::firstOrCreate([
                'profesor_id' => $profesor->id,
                'aula_id'     => $aula->id,
                'curso_id'    => $curso->id,
            ]);
        }
    }
}