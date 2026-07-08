<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComunidadTexto;
use App\Models\AliadoEstrategico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComunidadEducativaController extends Controller
{
    public function index()
    {
        $info = ComunidadTexto::first() ?? new ComunidadTexto();
        $aliados = AliadoEstrategico::all();

        return view('admin.comunidad-educativa.index', compact('info', 'aliados'));
    }

    public function guardarTextosDocumentos(Request $request)
    {
        $request->validate([
            'estudiantes_texto'    => 'nullable|string',
            'padres_texto'         => 'nullable|string',
            'exalumnos_texto'      => 'nullable|string',
            'reglamento_pdf'       => 'nullable|file|mimes:pdf|max:10240',
            'cronograma_notes_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $info = ComunidadTexto::first() ?? new ComunidadTexto();
        
        $info->estudiantes_texto = $request->input('estudiantes_texto');
        $info->padres_texto = $request->input('padres_texto');
        $info->exalumnos_texto = $request->input('exalumnos_texto');

        // Manejo de archivo Reglamento Escolar PDF
        if ($request->hasFile('reglamento_pdf')) {
            if ($info->reglamento_pdf) {
                Storage::disk('public')->delete($info->reglamento_pdf);
            }
            $info->reglamento_pdf = $request->file('reglamento_pdf')->store('comunidad', 'public');
        }

        // Manejo de archivo Cronograma de Notas PDF
        if ($request->hasFile('cronograma_notes_pdf')) {
            if ($info->cronograma_notes_pdf) {
                Storage::disk('public')->delete($info->cronograma_notes_pdf);
            }
            $info->cronograma_notes_pdf = $request->file('cronograma_notes_pdf')->store('comunidad', 'public');
        }

        $info->save();

        return back()->with('success', '¡Información general de la Comunidad Educativa actualizada con éxito!');
    }

    public function guardarAliado(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'logo'        => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'enlace_web'  => 'nullable|string|max:255',
        ]);

        $logoPath = $request->file('logo')->store('aliados', 'public');

        AliadoEstrategico::create([
            'nombre'      => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'logo'        => $logoPath,
            'enlace_web'  => $request->input('enlace_web'),
        ]);

        return back()->with('success', '¡Aliado estratégico registrado con éxito!');
    }

    public function eliminarAliado($id)
    {
        $aliado = AliadoEstrategico::findOrFail($id);

        if ($aliado->logo) {
            Storage::disk('public')->delete($aliado->logo);
        }

        $aliado->delete();

        return back()->with('success', '¡Aliado estratégico eliminado!');
    }
}
