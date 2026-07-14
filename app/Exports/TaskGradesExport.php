<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaskGradesExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $material;
    protected $alumnos;
    protected $submissions;

    public function __construct($material, $alumnos, $submissions)
    {
        $this->material = $material;
        $this->alumnos = $alumnos;
        $this->submissions = $submissions;
    }

    /**
     * Retorna la colección de estudiantes con su respectivo estado de entrega y nota de tarea.
     */
    public function collection()
    {
        return $this->alumnos->map(function ($alumno) {
            $sub = $this->submissions->firstWhere('user_id', $alumno->id);

            return [
                'DNI'         => $alumno->codigo_usuario,
                'Estudiante'  => $alumno->apellidos . ', ' . $alumno->nombres,
                'Fecha_Envio' => $sub ? $sub->created_at->format('d/m/Y H:i') : 'No entregado',
                'Nota'        => $sub ? ($sub->grade ?? 'Sin calificar') : '—',
            ];
        });
    }

    /**
     * Definición de las cabeceras de columnas.
     */
    public function headings(): array
    {
        return [
            'DNI',
            'Estudiante',
            'Fecha de Envío',
            'Nota',
        ];
    }

    /**
     * Título de la pestaña de Excel.
     */
    public function title(): string
    {
        return 'Notas de Tarea';
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
                    'startColor' => ['rgb' => '28A745'], // Verde éxito
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }
}
