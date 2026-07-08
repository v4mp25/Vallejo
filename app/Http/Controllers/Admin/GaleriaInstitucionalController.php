<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fotografia;
use App\Models\Video;
use App\Models\Evento;
use App\Models\ActividadPedagogica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriaInstitucionalController extends Controller
{
    public function index()
    {
        $fotos = Fotografia::all();
        $videos = Video::all();
        $eventos = Evento::orderBy('fecha', 'desc')->get();
        $actividades = ActividadPedagogica::orderBy('fecha', 'desc')->get();

        return view('admin.galeria-institucional.index', compact('fotos', 'videos', 'eventos', 'actividades'));
    }

    // Fotografías
    public function storeFoto(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = $request->file('imagen')->store('galeria/fotos', 'public');

        Fotografia::create([
            'titulo' => $request->input('titulo'),
            'imagen' => $imagePath,
        ]);

        return back()->with('success', '¡Fotografía agregada a la galería!');
    }

    public function destroyFoto($id)
    {
        $foto = Fotografia::findOrFail($id);
        if ($foto->imagen) {
            Storage::disk('public')->delete($foto->imagen);
        }
        $foto->delete();

        return back()->with('success', '¡Fotografía eliminada con éxito!');
    }

    // Videos
    public function storeVideo(Request $request)
    {
        $request->validate([
            'titulo'    => 'required|string|max:255',
            'url_video' => 'required|url',
        ]);

        Video::create($request->only(['titulo', 'url_video']));

        return back()->with('success', '¡Video agregado a la galería!');
    }

    public function destroyVideo($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return back()->with('success', '¡Video eliminado con éxito!');
    }

    // Eventos
    public function storeEvento(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha'       => 'required|date',
            'imagen'      => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = $request->file('imagen')->store('galeria/eventos', 'public');

        Evento::create([
            'titulo'      => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'fecha'       => $request->input('fecha'),
            'imagen'      => $imagePath,
        ]);

        return back()->with('success', '¡Evento cívico registrado con éxito!');
    }

    public function destroyEvento($id)
    {
        $evento = Evento::findOrFail($id);
        if ($evento->imagen) {
            Storage::disk('public')->delete($evento->imagen);
        }
        $evento->delete();

        return back()->with('success', '¡Evento cívico eliminado!');
    }

    // Actividades Pedagógicas
    public function storeActividad(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha'       => 'required|date',
            'imagen'      => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = $request->file('imagen')->store('galeria/actividades', 'public');

        ActividadPedagogica::create([
            'titulo'      => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'fecha'       => $request->input('fecha'),
            'imagen'      => $imagePath,
        ]);

        return back()->with('success', '¡Actividad pedagógica registrada con éxito!');
    }

    public function destroyActividad($id)
    {
        $actividad = ActividadPedagogica::findOrFail($id);
        if ($actividad->imagen) {
            Storage::disk('public')->delete($actividad->imagen);
        }
        $actividad->delete();

        return back()->with('success', '¡Actividad pedagógica eliminada!');
    }
}
