<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogroController extends Controller
{
    public function index()
    {
        $logros = Logro::orderBy('fecha', 'desc')->orderBy('created_at', 'desc')->get();
        return view('admin.logros.index', compact('logros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'categoria'   => 'required|string|in:Académicos,Deportivos,Artísticos,Científicos,Institucionales',
            'fecha'       => 'nullable|date',
            'descripcion' => 'nullable|string',
            'imagen'      => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imagePath = $request->file('imagen')->store('logros', 'public');

        Logro::create([
            'titulo'      => $request->input('titulo'),
            'categoria'   => $request->input('categoria'),
            'fecha'       => $request->input('fecha'),
            'descripcion' => $request->input('descripcion'),
            'imagen'      => $imagePath,
        ]);

        return back()->with('success', '¡Logro o reconocimiento registrado con éxito!');
    }

    public function destroy($id)
    {
        $logro = Logro::findOrFail($id);

        if ($logro->imagen) {
            Storage::disk('public')->delete($logro->imagen);
        }

        $logro->delete();

        return back()->with('success', '¡Logro o reconocimiento eliminado!');
    }
}
