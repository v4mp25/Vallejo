<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GestionInstitucional;
use App\Models\PersonalInstitucional;
use App\Models\DocumentoGestion;
use App\Models\OrganoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GestionInstitucionalController extends Controller
{
    public function index()
    {
        $info = GestionInstitucional::first() ?? new GestionInstitucional();
        $personal = PersonalInstitucional::all();
        $documentos = DocumentoGestion::all();
        $coneiDocs = OrganoDocumento::where('organo', 'conei')->get();
        $apafaDocs = OrganoDocumento::where('organo', 'apafa')->get();

        return view('admin.gestion-institucional.index', compact('info', 'personal', 'documentos', 'coneiDocs', 'apafaDocs'));
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
            'categoria' => 'required|in:directivo,administrativo',
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

    public function actualizarPersonal(Request $request, $id)
    {
        $request->validate([
            'nombres'   => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cargo'     => 'required|string|max:255',
            'categoria' => 'required|in:directivo,administrativo',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $miembro = PersonalInstitucional::findOrFail($id);
        $miembro->nombres = $request->input('nombres');
        $miembro->apellidos = $request->input('apellidos');
        $miembro->cargo = $request->input('cargo');
        $miembro->categoria = $request->input('categoria');

        if ($request->hasFile('foto')) {
            if ($miembro->foto) {
                Storage::disk('public')->delete($miembro->foto);
            }
            $miembro->foto = $request->file('foto')->store('personal', 'public');
        }

        $miembro->save();

        return back()->with('success', '¡Miembro del personal institucional actualizado con éxito!');
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

    // CONEI & APAFA Documents
    public function guardarOrganoDocumento(Request $request)
    {
        $request->validate([
            'organo'      => 'required|in:conei,apafa',
            'titulo'      => 'required|string|max:255',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'link'        => 'nullable|string|max:255',
        ]);

        if (!$request->hasFile('archivo_pdf') && !$request->input('link')) {
            return back()->withErrors(['error' => 'Debes subir un archivo PDF o ingresar un enlace.']);
        }

        $pdfPath = null;
        if ($request->hasFile('archivo_pdf')) {
            $pdfPath = $request->file('archivo_pdf')->store('organos', 'public');
        }

        OrganoDocumento::create([
            'organo'      => $request->input('organo'),
            'titulo'      => $request->input('titulo'),
            'archivo_pdf' => $pdfPath,
            'link'        => $request->input('link'),
        ]);

        return back()->with('success', '¡Documento/enlace de participación guardado con éxito!');
    }

    public function eliminarOrganoDocumento($id)
    {
        $doc = OrganoDocumento::findOrFail($id);
        
        if ($doc->archivo_pdf) {
            Storage::disk('public')->delete($doc->archivo_pdf);
        }

        $doc->delete();

        return back()->with('success', '¡Documento/enlace eliminado correctamente!');
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
