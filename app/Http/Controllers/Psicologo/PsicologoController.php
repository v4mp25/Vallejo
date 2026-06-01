<?php

namespace App\Http\Controllers\Psicologo;

use App\Http\Controllers\Controller;
use App\Models\CitaPsicologica;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PsicologoController extends Controller
{
    public function index()
    {
        $citas = CitaPsicologica::query()
            ->latest()
            ->get();

        $ids = $citas
            ->flatMap(fn ($cita) => [$cita->alumno_id, $cita->profesor_id, $cita->psicologo_id])
            ->filter()
            ->unique()
            ->values();

        $usuarios = User::query()
            ->whereIn('id', $ids)
            ->get(['id', 'nombres', 'apellidos'])
            ->keyBy('id');

        $pendientes = $citas->where('estado', 'pendiente')->values();

        $resumen = [
            'pendientes' => $citas->where('estado', 'pendiente')->count(),
            'atendidas' => $citas->where('estado', 'atendida')->count(),
            'canceladas' => $citas->where('estado', 'cancelada')->count(),
        ];

        return view('psicologo.dashboard', compact('pendientes', 'usuarios', 'resumen'));
    }

    public function asignarCita(Request $request, CitaPsicologica $cita)
    {
        $datos = $request->validate([
            'fecha_cita' => ['required', 'date'],
        ], [
            'fecha_cita.required' => 'La fecha y hora de atención es obligatoria.',
            'fecha_cita.date' => 'La fecha seleccionada no es válida.',
        ]);

        $cita->update([
            'fecha_cita' => $datos['fecha_cita'],
            'psicologo_id' => Auth::id(),
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('psicologo.dashboard')
            ->with('success', 'Cita asignada correctamente.');
    }
}

