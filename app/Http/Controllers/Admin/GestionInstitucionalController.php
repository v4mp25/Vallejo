<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GestionInstitucional;
use App\Models\PersonalInstitucional;
use App\Models\DocumentoGestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GestionInstitucionalController extends Controller
{
    public function index()
    {
        $info = GestionInstitucional::first() ?? new GestionInstitucional();
        $personal = PersonalInstitucional::all();
        $documentos = DocumentoGestion::all();

        return view('admin.gestion-institucional.index', compact('info', 'personal', 'documentos'));
    }

    public function guardarTextosOrganigrama(Request $request)
    {
        $request->validate([
            'conei_descripcion'   => 'nullable|string',
            'apafa_descripcion'   => 'nullable|string',
            'organigrama_imagen'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $info = GestionInstitucional::first() ?? new GestionInstitucional();
        
        $info->conei_descripcion = $request->input('conei_descripcion');
        $info->apafa_descripcion = $request->input('apafa_descripcion');

        // Eliminar organigrama actual
        if ($request->has('eliminar_organigrama') && $request->input('eliminar_organigrama') == '1') {
            if ($info->organigrama_imagen) {
                Storage::disk('public')->delete($info->organigrama_imagen);
                $info->organigrama_imagen = null;
            }
        }

        // Subir nuevo organigrama
        if ($request->hasFile('organigrama_imagen')) {
            if ($info->organigrama_imagen) {
                Storage::disk('public')->delete($info->organigrama_imagen);
            }
            $info->organigrama_imagen = $request->file('organigrama_imagen')->store('institucion', 'public');
        }

        $info->save();

        return back()->with('success', '¡Información de CONEI, APAFA y Organigrama actualizada!');
    }

    public function guardarPersonal(Request $request)
    {
        $request->validate([
            'nombres'   => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cargo'     => 'required|string|max:255',
            'categoria' => 'required|in:directivo,docente,administrativo',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('personal', 'public');
        }

        PersonalInstitucional::create([
            'nombres'   => $request->input('nombres'),
            'apellidos' => $request->input('apellidos'),
            'cargo'     => $request->input('cargo'),
            'categoria' => $request->input('categoria'),
            'foto'      => $fotoPath,
        ]);

        return back()->with('success', '¡Miembro del personal institucional agregado con éxito!');
    }

    public function eliminarPersonal($id)
    {
        $miembro = PersonalInstitucional::findOrFail($id);
        
        if ($miembro->foto) {
            Storage::disk('public')->delete($miembro->foto);
        }

        $miembro->delete();

        return back()->with('success', '¡Miembro del personal institucional eliminado!');
    }

    public function guardarDocumento(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'tipo'        => 'required|string|max:100',
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        $pdfPath = $request->file('archivo_pdf')->store('documentos', 'public');

        DocumentoGestion::create([
            'titulo'      => $request->input('titulo'),
            'tipo'        => $request->input('tipo'),
            'archivo_pdf' => $pdfPath,
        ]);

        return back()->with('success', '¡Documento de gestión subido correctamente!');
    }

    public function eliminarDocumento($id)
    {
        $doc = DocumentoGestion::findOrFail($id);
        
        if ($doc->archivo_pdf) {
            Storage::disk('public')->delete($doc->archivo_pdf);
        }

        $doc->delete();

        return back()->with('success', '¡Documento de gestión eliminado!');
    }
}
