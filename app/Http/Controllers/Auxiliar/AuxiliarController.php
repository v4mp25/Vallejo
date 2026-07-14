<?php

namespace App\Http\Controllers\Auxiliar;

use App\Http\Controllers\Controller;
use App\Models\DerivacionAuxiliar;
use App\Models\MaterialAuxiliar;
use App\Models\User;
use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuxiliarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol === 'secretaria') {
            $citas = DerivacionAuxiliar::latest()->get();
        } else {
            $citas = DerivacionAuxiliar::where('auxiliar_id', $user->id)
                ->latest()
                ->get();
        }

        $ids = $citas
            ->flatMap(fn ($cita) => [$cita->alumno_id, $cita->profesor_id, $cita->auxiliar_id])
            ->filter()
            ->unique()
            ->values();

        $usuarios = User::query()
            ->whereIn('id', $ids)
            ->with(['matriculas.aula'])
            ->get()
            ->keyBy('id');

        $pendientes = $citas->whereIn('estado', ['pendiente', 'citado'])->values();

        $resumen = [
            'pendientes' => $citas->whereIn('estado', ['pendiente', 'citado'])->count(),
            'atendidas'  => $citas->where('estado', 'atendida')->count(),
        ];

        $atendidasPorBimestre = $citas->where('estado', 'atendida')
            ->groupBy('bimestre')
            ->sortBy(fn($group, $key) => $key);

        $bimestreLabels = [
            'B1' => 'Primer Bimestre (I)',
            'B2' => 'Segundo Bimestre (II)',
            'B3' => 'Tercer Bimestre (III)',
            'B4' => 'Cuarto Bimestre (IV)',
        ];

        // Aulas para publicar material
        $aulasPorTurno = Aula::orderBy('grado')->orderBy('seccion')->get()->groupBy('turno');

        // Historial de materiales publicados por este auxiliar
        $materialesPublicados = MaterialAuxiliar::where('auxiliar_id', Auth::id())
            ->with('aulas')
            ->latest()
            ->get();

        $order = ['I Bimestre' => 1, 'II Bimestre' => 2, 'III Bimestre' => 3, 'IV Bimestre' => 4];
        $materialesPorBimestreDoc = $materialesPublicados->groupBy('bimestre')
            ->sortBy(fn($group, $key) => $order[$key] ?? 99);

        return view('auxiliar.dashboard', compact(
            'pendientes',
            'usuarios',
            'resumen',
            'atendidasPorBimestre',
            'bimestreLabels',
            'aulasPorTurno',
            'materialesPorBimestreDoc'
        ));
    }

    public function asignarCita(Request $request, DerivacionAuxiliar $cita)
    {
        $datos = $request->validate([
            'fecha_cita' => ['required', 'date'],
        ], [
            'fecha_cita.required' => 'La fecha y hora de atención es obligatoria.',
            'fecha_cita.date'     => 'La fecha seleccionada no es válida.',
        ]);

        $cita->update([
            'fecha_cita'  => $datos['fecha_cita'],
            'auxiliar_id' => Auth::id(),
            'estado'      => 'citado',
        ]);

        return redirect()
            ->route('auxiliar.dashboard')
            ->with('success', 'Cita asignada correctamente.');
    }

    public function marcarAtendida(DerivacionAuxiliar $cita)
    {
        if (Auth::user()->rol !== 'secretaria' && $cita->auxiliar_id !== Auth::id()) {
            abort(403);
        }

        $cita->update(['estado' => 'atendida']);

        return redirect()
            ->route('auxiliar.dashboard')
            ->with('success', 'Cita marcada como atendida.');
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'enlace'       => 'nullable|string|max:255',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fecha_limite' => 'nullable|date',
            'bimestre'     => 'required|in:I Bimestre,II Bimestre,III Bimestre,IV Bimestre',
            'aulas'        => 'required|array',
            'aulas.*'      => 'exists:aulas,id',
        ], [
            'titulo.required'   => 'El título es obligatorio.',
            'bimestre.required' => 'El bimestre es obligatorio.',
            'aulas.required'    => 'Debes seleccionar al menos un salón.',
            'imagen.image'      => 'El archivo debe ser una imagen válida.',
            'imagen.max'        => 'La imagen no debe superar los 2MB.',
        ]);

        DB::transaction(function () use ($request) {
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('auxiliar', 'public');
            }

            $material = MaterialAuxiliar::create([
                'auxiliar_id' => Auth::id(),
                'titulo'      => $request->input('titulo'),
                'descripcion' => $request->input('descripcion'),
                'enlace'      => $request->input('enlace'),
                'imagen'      => $imagenPath,
                'fecha_limite'=> $request->input('fecha_limite'),
                'bimestre'    => $request->input('bimestre'),
            ]);

            $material->aulas()->sync($request->input('aulas', []));
        });

        return redirect()
            ->route('auxiliar.dashboard')
            ->with('success', 'Material / Comunicado publicado con éxito.');
    }

    public function destroyMaterial($id)
    {
        $material = MaterialAuxiliar::where('auxiliar_id', Auth::id())->findOrFail($id);

        if ($material->imagen) {
            Storage::disk('public')->delete($material->imagen);
        }

        $material->delete();

        return redirect()
            ->route('auxiliar.dashboard')
            ->with('success', 'Material eliminado correctamente.');
    }
}
