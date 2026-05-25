<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImportarProfesoresController extends Controller
{
    public function create(): View
    {
        return view('admin.importar-profesores');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'archivo' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'archivo.required' => 'Selecciona un archivo Excel para importar.',
            'archivo.mimes' => 'El archivo debe ser Excel (.xlsx, .xls) o CSV.',
        ]);

        /*
         * Aquí conectarás tu lógica de importación:
         * Excel::import(new ProfesoresImport, $request->file('archivo'));
         */

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Archivo recibido. Conecta tu importador en ImportarProfesoresController@store.');
    }
}
