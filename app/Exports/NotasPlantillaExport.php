<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NotasPlantillaExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $asignacion;
    protected $alumnos;
    protected $notas;

    public function __construct($asignacion, $alumnos, $notas)
    {
        $this->asignacion = $asignacion;
        $this->alumnos = $alumnos;
        $this->notas = $notas;
    }

    /**
     * Retorna la colección de datos de los alumnos con sus notas actuales.
     */
    public function collection()
    {
        return $this->alumnos->map(function ($alumno) {
            $notasAlumno = $this->notas->get($alumno->id) ?? collect();

            return [
                'ID_Estudiante' => $alumno->id,
                'DNI'           => $alumno->codigo_usuario,
                'Estudiante'    => $alumno->apellidos . ', ' . $alumno->nombres,
                'Bimestre_I'    => $notasAlumno->firstWhere('periodo', 'B1')->calificacion ?? '',
                'Bimestre_II'   => $notasAlumno->firstWhere('periodo', 'B2')->calificacion ?? '',
                'Bimestre_III'  => $notasAlumno->firstWhere('periodo', 'B3')->calificacion ?? '',
                'Bimestre_IV'   => $notasAlumno->firstWhere('periodo', 'B4')->calificacion ?? '',
            ];
        });
    }

    /**
     * Definición de las cabeceras de las columnas.
     */
    public function headings(): array
    {
        return [
            'ID_Estudiante',
            'DNI',
            'Estudiante',
            'Bimestre I',
            'Bimestre II',
            'Bimestre III',
            'Bimestre IV',
        ];
    }

    /**
     * Título de la pestaña de la hoja de cálculo.
     */
    public function title(): string
    {
        return 'Registro de Notas';
    }

    /**
     * Estilos aplicados a la hoja de cálculo.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0148A4'], // Azul institucional César Vallejo
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }
}
