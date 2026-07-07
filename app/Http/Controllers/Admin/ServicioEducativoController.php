<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicioEducativo;
use App\Models\AreaCurricular;
use App\Models\ProyectoInstitucional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioEducativoController extends Controller
{
    public function index()
    {
        $info = ServicioEducativo::first() ?? new ServicioEducativo();
        $areas = AreaCurricular::all();
        $proyectos = ProyectoInstitucional::all();

        return view('admin.servicio-educativo.index', compact('info', 'areas', 'proyectos'));
    }

    public function guardarTextos(Request $request)
    {
        $request->validate([
            'nivel_secundaria'      => 'nullable|string',
            'enfoque_competencias'  => 'nullable|string',
            'innovacion_pedagogica' => 'nullable|string',
            'tutoria_orientacion'   => 'nullable|string',
            'educacion_inclusiva'   => 'nullable|string',
        ]);

        $info = ServicioEducativo::first() ?? new ServicioEducativo();
        $info->fill($request->only([
            'nivel_secundaria',
            'enfoque_competencias',
            'innovacion_pedagogica',
            'tutoria_orientacion',
            'educacion_inclusiva'
        ]));
        $info->save();

        return back()->with('success', '¡Textos descriptivos del Servicio Educativo actualizados!');
    }

    public function guardarArea(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'icono'       => 'required|string|max:100',
            'descripcion' => 'required|string',
        ]);

        AreaCurricular::create($request->only(['nombre', 'icono', 'descripcion']));

        return back()->with('success', '¡Área curricular agregada con éxito!');
    }

    public function eliminarArea($id)
    {
        $area = AreaCurricular::findOrFail($id);
        $area->delete();

        return back()->with('success', '¡Área curricular eliminada!');
    }

    public function guardarProyecto(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen'      => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = $request->file('imagen')->store('proyectos', 'public');

        ProyectoInstitucional::create([
            'titulo'      => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'imagen'      => $imagePath,
        ]);

        return back()->with('success', '¡Proyecto institucional guardado con éxito!');
    }

    public function eliminarProyecto($id)
    {
        $proyecto = ProyectoInstitucional::findOrFail($id);

        if ($proyecto->imagen) {
            Storage::disk('public')->delete($proyecto->imagen);
        }

        $proyecto->delete();

        return back()->with('success', '¡Proyecto institucional eliminado!');
    }
}
