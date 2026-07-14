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

    // 4.1 Áreas Curriculares
    public function guardarArea(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'imagen'      => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'descripcion' => 'required|string',
        ]);

        $imagePath = $request->file('imagen')->store('areas', 'public');

        AreaCurricular::create([
            'nombre'      => $request->input('nombre'),
            'imagen'      => $imagePath,
            'descripcion' => $request->input('descripcion'),
        ]);

        return back()->with('success', '¡Área curricular agregada con éxito!');
    }

    public function actualizarArea(Request $request, $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'descripcion' => 'required|string',
        ]);

        $area = AreaCurricular::findOrFail($id);
        $area->nombre = $request->input('nombre');
        $area->descripcion = $request->input('descripcion');

        if ($request->hasFile('imagen')) {
            if ($area->imagen) {
                Storage::disk('public')->delete($area->imagen);
            }
            $area->imagen = $request->file('imagen')->store('areas', 'public');
        }

        $area->save();

        return back()->with('success', '¡Área curricular actualizada con éxito!');
    }

    public function eliminarArea($id)
    {
        $area = AreaCurricular::findOrFail($id);
        if ($area->imagen) {
            Storage::disk('public')->delete($area->imagen);
        }
        $area->delete();

        return back()->with('success', '¡Área curricular eliminada!');
    }

    // 4.3 Proyectos Bandera
    public function guardarProyecto(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen'      => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link'        => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('imagen')->store('proyectos', 'public');

        ProyectoInstitucional::create([
            'titulo'      => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'imagen'      => $imagePath,
            'link'        => $request->input('link'),
        ]);

        return back()->with('success', '¡Proyecto institucional guardado con éxito!');
    }

    public function actualizarProyecto(Request $request, $id)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link'        => 'nullable|string|max:255',
        ]);

        $proyecto = ProyectoInstitucional::findOrFail($id);
        $proyecto->titulo = $request->input('titulo');
        $proyecto->descripcion = $request->input('descripcion');
        $proyecto->link = $request->input('link');

        if ($request->hasFile('imagen')) {
            if ($proyecto->imagen) {
                Storage::disk('public')->delete($proyecto->imagen);
            }
            $proyecto->imagen = $request->file('imagen')->store('proyectos', 'public');
        }

        $proyecto->save();

        return back()->with('success', '¡Proyecto institucional actualizado con éxito!');
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
