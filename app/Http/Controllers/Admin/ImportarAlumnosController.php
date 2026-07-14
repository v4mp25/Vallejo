<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exports\PlantillaAlumnosExport;
use App\Http\Controllers\Controller;
use App\Imports\AlumnosImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

/**
 * Gestiona la importación masiva de alumnos (y su matrícula) mediante un
 * archivo Excel, así como la descarga de la plantilla de ejemplo.
 */
class ImportarAlumnosController extends Controller
{
    /**
     * Muestra el formulario de importación masiva de alumnos.
     */
    public function index(): View
    {
        return view('admin.estudiantes.importar');
    }

    /**
     * Descarga la plantilla Excel vacía con las cabeceras esperadas.
     */
    public function plantilla(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new PlantillaAlumnosExport(), 'plantilla_alumnos.xlsx');
    }

    /**
     * Procesa el archivo Excel subido: crea/matricula a los alumnos fila por fila.
     */
    public function procesar(Request $request): RedirectResponse
    {
        $request->validate([
            'archivo_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'archivo_excel.required' => 'Debes seleccionar un archivo Excel.',
            'archivo_excel.file' => 'El archivo enviado no es válido.',
            'archivo_excel.mimes' => 'El archivo debe tener formato Excel (.xlsx o .xls).',
            'archivo_excel.max' => 'El archivo no debe superar los 10 MB.',
        ]);

        $import = new AlumnosImport();

        try {
            Excel::import($import, $request->file('archivo_excel'));
        } catch (Throwable $e) {
            Log::error('Error al importar alumnos: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with(
                'error',
                'No se pudo procesar el archivo. Verifica que sea un Excel válido y respete el formato de la plantilla.'
            );
        }

        $exitosas = $import->getFilasExitosas();
        $conError = $import->getFilasConError();
        $errores = $import->getErrores();

        if ($exitosas === 0 && $conError > 0) {
            return back()
                ->with('error', 'No se pudo importar ningún alumno. Revisa los errores detallados abajo.')
                ->with('import_errors', $errores);
        }

        $mensaje = "Se procesaron correctamente {$exitosas} alumno(s).";
        if ($conError > 0) {
            $mensaje .= " {$conError} fila(s) presentaron errores y fueron omitidas.";
        }

        return back()
            ->with('success', $mensaje)
            ->with('import_errors', $errores);
    }
}
