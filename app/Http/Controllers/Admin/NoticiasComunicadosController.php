<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use App\Models\Comunicado;
use App\Models\Agenda;
use App\Models\Boletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticiasComunicadosController extends Controller
{
    public function index()
    {
        $noticias = Noticia::orderBy('fecha', 'desc')->get();
        $comunicados = Comunicado::orderBy('fecha', 'desc')->get();
        $agenda = Agenda::orderBy('fecha_inicio', 'asc')->get();
        $boletines = Boletin::all();

        return view('admin.noticias-comunicados.index', compact('noticias', 'comunicados', 'agenda', 'boletines'));
    }

    // Noticias
    public function storeNoticia(Request $request)
    {
        $request->validate([
            'titulo'    => 'required|string|max:255',
            'fecha'     => 'required|date',
            'contenido' => 'required|string',
            'imagen'    => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = $request->file('imagen')->store('noticias', 'public');

        Noticia::create([
            'titulo'    => $request->input('titulo'),
            'fecha'     => $request->input('fecha'),
            'contenido' => $request->input('contenido'),
            'imagen'    => $imagePath,
        ]);

        return back()->with('success', '¡Noticia publicada con éxito!');
    }

    public function destroyNoticia($id)
    {
        $noticia = Noticia::findOrFail($id);
        if ($noticia->imagen) {
            Storage::disk('public')->delete($noticia->imagen);
        }
        $noticia->delete();

        return back()->with('success', '¡Noticia eliminada!');
    }

    // Comunicados
    public function storeComunicado(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'fecha'       => 'required|date',
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        $pdfPath = $request->file('archivo_pdf')->store('comunicados', 'public');

        Comunicado::create([
            'titulo'      => $request->input('titulo'),
            'fecha'       => $request->input('fecha'),
            'archivo_pdf' => $pdfPath,
        ]);

        return back()->with('success', '¡Comunicado publicado con éxito!');
    }

    public function destroyComunicado($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        if ($comunicado->archivo_pdf) {
            Storage::disk('public')->delete($comunicado->archivo_pdf);
        }
        $comunicado->delete();

        return back()->with('success', '¡Comunicado eliminado!');
    }

    // Agenda Escolar
    public function storeAgenda(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'lugar'        => 'required|string|max:255',
        ]);

        Agenda::create($request->only(['titulo', 'fecha_inicio', 'fecha_fin', 'lugar']));

        return back()->with('success', '¡Actividad de agenda calendarizada con éxito!');
    }

    public function destroyAgenda($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();

        return back()->with('success', '¡Actividad de agenda eliminada!');
    }

    // Boletines
    public function storeBoletin(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'mes_anio'    => 'required|string|max:100', // Ej: Julio 2026
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        $pdfPath = $request->file('archivo_pdf')->store('boletines', 'public');

        Boletin::create([
            'titulo'      => $request->input('titulo'),
            'mes_anio'    => $request->input('mes_anio'),
            'archivo_pdf' => $pdfPath,
        ]);

        return back()->with('success', '¡Boletín mensual publicado con éxito!');
    }

    public function destroyBoletin($id)
    {
        $boletin = Boletin::findOrFail($id);
        if ($boletin->archivo_pdf) {
            Storage::disk('public')->delete($boletin->archivo_pdf);
        }
        $boletin->delete();

        return back()->with('success', '¡Boletín mensual eliminado!');
    }
}
