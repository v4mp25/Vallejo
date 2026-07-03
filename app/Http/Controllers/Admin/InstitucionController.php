<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitucionInfo;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    public function index()
    {
        $info = InstitucionInfo::first() ?? new InstitucionInfo();
        return view('admin.institucion.index', compact('info'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'lema'                        => 'nullable|string|max:255',
            'resena_historica'            => 'nullable|string',
            'mision'                      => 'nullable|string',
            'vision'                      => 'nullable|string',
            'valores'                     => 'nullable|string',
            'principios'                  => 'nullable|string',
            'letra_himno'                 => 'nullable|string',
            'uniforme_descripcion'        => 'nullable|string|max:500',
            'uniforme_imagen'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'infraestructura_descripcion' => 'nullable|string',
            // línea de tiempo
            'lt_anio.*'                   => 'nullable|string|max:10',
            'lt_evento.*'                 => 'nullable|string|max:300',
        ]);

        $info = InstitucionInfo::first() ?? new InstitucionInfo();

        $info->fill($request->only([
            'resena_historica', 'mision', 'vision', 'valores',
            'principios', 'lema', 'letra_himno',
            'uniforme_descripcion', 'infraestructura_descripcion',
        ]));

        // Imagen uniforme
        if ($request->hasFile('uniforme_imagen')) {
            $info->uniforme_imagen = $request->file('uniforme_imagen')->store('institucion', 'public');
        }

        // Línea de tiempo: combinar arrays lt_anio[] y lt_evento[]
        $anios   = $request->input('lt_anio', []);
        $eventos = $request->input('lt_evento', []);
        $linea   = [];
        foreach ($anios as $i => $anio) {
            if (!empty($anio) || !empty($eventos[$i])) {
                $linea[] = ['anio' => $anio, 'evento' => $eventos[$i] ?? ''];
            }
        }
        $info->linea_tiempo = count($linea) ? $linea : null;

        $info->save();

        return back()->with('success', '¡Información institucional actualizada!');
    }
}
