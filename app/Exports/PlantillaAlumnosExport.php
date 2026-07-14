<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Genera la plantilla Excel vacía (solo cabeceras) que el administrador
 * debe descargar y completar para la importación masiva de alumnos.
 */
class PlantillaAlumnosExport implements WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    /**
     * Cabeceras obligatorias, en el orden exacto que espera AlumnosImport.
     *
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'nombres',
            'apellidos',
            'dni',
            'grado',
            'seccion',
            'turno',
            'año_academico',
            'celular_apoderado',
        ];
    }

    public function title(): string
    {
        return 'Plantilla Alumnos';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0148A4'], // Azul institucional César Vallejo
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
