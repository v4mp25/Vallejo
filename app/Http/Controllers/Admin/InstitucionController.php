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
            'uniforme_imagenes.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'eliminar_imagenes.*'         => 'nullable|string',
            'infraestructura_descripcion' => 'nullable|string',
            'infra_aulas_imagenes.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'infra_labs_imagenes.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'infra_biblio_imagenes.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'infra_aip_imagenes.*'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'eliminar_infra_aulas.*'      => 'nullable|string',
            'eliminar_infra_labs.*'       => 'nullable|string',
            'eliminar_infra_biblio.*'     => 'nullable|string',
            'eliminar_infra_aip.*'        => 'nullable|string',
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

        // Imagen uniforme (antigua imagen única)
        if ($request->has('eliminar_imagen_single') && $request->input('eliminar_imagen_single') == '1') {
            if ($info->uniforme_imagen) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($info->uniforme_imagen);
                $info->uniforme_imagen = null;
            }
        }
        if ($request->hasFile('uniforme_imagen')) {
            if ($info->uniforme_imagen) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($info->uniforme_imagen);
            }
            $info->uniforme_imagen = $request->file('uniforme_imagen')->store('institucion', 'public');
        }

        // Galería de imágenes del uniforme (Múltiples)
        $imagenes = $info->uniforme_imagenes ?? [];

        // Eliminar imágenes de la galería seleccionadas
        if ($request->has('eliminar_imagenes')) {
            $eliminar = $request->input('eliminar_imagenes', []);
            foreach ($eliminar as $imgEliminar) {
                if (($key = array_search($imgEliminar, $imagenes)) !== false) {
                    unset($imagenes[$key]);
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($imgEliminar);
                }
            }
            $imagenes = array_values($imagenes); // Reindexar
        }

        // Subir nuevas imágenes a la galería
        if ($request->hasFile('uniforme_imagenes')) {
            foreach ($request->file('uniforme_imagenes') as $file) {
                $ruta = $file->store('institucion', 'public');
                $imagenes[] = $ruta;
            }
        }

        $info->uniforme_imagenes = count($imagenes) ? $imagenes : null;

        // Categorías de fotos de infraestructura (Aulas, Labs, Biblioteca, AIP)
        $infraCategorias = [
            'aulas'  => ['db' => 'infra_aulas_imagenes', 'file' => 'infra_aulas_imagenes', 'del' => 'eliminar_infra_aulas'],
            'labs'   => ['db' => 'infra_labs_imagenes', 'file' => 'infra_labs_imagenes', 'del' => 'eliminar_infra_labs'],
            'biblio' => ['db' => 'infra_biblio_imagenes', 'file' => 'infra_biblio_imagenes', 'del' => 'eliminar_infra_biblio'],
            'aip'    => ['db' => 'infra_aip_imagenes', 'file' => 'infra_aip_imagenes', 'del' => 'eliminar_infra_aip'],
        ];

        foreach ($infraCategorias as $fields) {
            $dbField = $fields['db'];
            $fileField = $fields['file'];
            $delField = $fields['del'];

            $imgList = $info->$dbField ?? [];

            // Eliminar imágenes seleccionadas
            if ($request->has($delField)) {
                $eliminar = $request->input($delField, []);
                foreach ($eliminar as $imgEliminar) {
                    if (($idx = array_search($imgEliminar, $imgList)) !== false) {
                        unset($imgList[$idx]);
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($imgEliminar);
                    }
                }
                $imgList = array_values($imgList); // Reindexar
            }

            // Subir nuevas imágenes
            if ($request->hasFile($fileField)) {
                foreach ($request->file($fileField) as $file) {
                    $ruta = $file->store('institucion', 'public');
                    $imgList[] = $ruta;
                }
            }

            $info->$dbField = count($imgList) ? $imgList : null;
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
