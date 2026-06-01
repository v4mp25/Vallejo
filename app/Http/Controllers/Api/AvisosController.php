<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aviso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvisosController extends Controller
{
    /** GET /api/avisos — lista pública */
    /** GET /api/avisos — lista pública */
    public function index()
    {
        $avisos = Aviso::orderByDesc('created_at')
            ->get()
            ->map(fn($a) => [
                'id'          => $a->id,
                'titulo'      => $a->titulo,
                'contenido'   => $a->contenido,
                'imagen'      => $a->imagen_path,
                'imagen_url'  => $a->imagen_path ? Storage::disk('public')->url($a->imagen_path) : null,
                'publicado_at'=> $a->publicado_at
                    ? \Carbon\Carbon::parse($a->publicado_at)->format('d/m/Y h:i A')
                    : \Carbon\Carbon::parse($a->created_at)->format('d/m/Y h:i A'),
            ]);

        return response()->json(['avisos' => $avisos]);
    }

    /** POST /api/admin/avisos — crear aviso (solo admin) */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'    => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen'    => 'nullable|image|max:5120',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('avisos', 'public');
        }

        $aviso = Aviso::create([
            'titulo'       => $request->titulo,
            'contenido'    => $request->contenido,
            'imagen_path'  => $imagenPath,
            'publicado_at' => now(),
            'creado_por'   => Auth::id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'aviso' => $aviso]);
        }

        return redirect()->back()->with('success', '¡Aviso publicado con éxito!');
    }
}
