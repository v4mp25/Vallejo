<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NotasTutoradosExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $aulaTutoria;
    protected $alumnos;
    protected $asignaciones;
    protected $notasOrganizadas;

    public function __construct($aulaTutoria, $alumnos, $asignaciones, $notasOrganizadas)
    {
        $this->aulaTutoria = $aulaTutoria;
        $this->alumnos = $alumnos;
        $this->asignaciones = $asignaciones;
        $this->notasOrganizadas = $notasOrganizadas;
    }

    /**
     * Retorna una fila por alumno con sus notas de cada curso y bimestre.
     */
    public function collection()
    {
        $periodos = ['B1', 'B2', 'B3', 'B4'];

        return $this->alumnos->map(function ($alumno) use ($periodos) {
            $fila = [
                'DNI' => $alumno->codigo_usuario,
                'Estudiante' => $alumno->apellidos . ', ' . $alumno->nombres,
            ];

            foreach ($this->asignaciones as $asig) {
                $nombreCurso = $asig->curso->nombre ?? 'Curso';
                foreach ($periodos as $periodo) {
                    $nota = $this->notasOrganizadas[$alumno->id][$asig->id][$periodo] ?? null;
                    $fila[$nombreCurso . ' - ' . $periodo] = $nota ?? '';
                }
            }

            return $fila;
        });
    }

    /**
     * Definición de las cabeceras de las columnas.
     */
    public function headings(): array
    {
        $headings = ['DNI', 'Estudiante'];

        foreach ($this->asignaciones as $asig) {
            $nombreCurso = $asig->curso->nombre ?? 'Curso';
            foreach (['I', 'II', 'III', 'IV'] as $numero) {
                $headings[] = $nombreCurso . ' - Bim. ' . $numero;
            }
        }

        return $headings;
    }

    /**
     * Título de la pestaña de la hoja de cálculo.
     */
    public function title(): string
    {
        return 'Notas Tutorados';
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
