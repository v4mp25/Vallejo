<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Aviso;
use App\Models\CitaPsicologica;
use App\Models\Nota;
use App\Models\Padre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PadresController extends Controller
{
    /** GET /api/padres/notas — notas del hijo vinculado */
    public function notas()
    {
        $user  = Auth::user();
        $padre = Padre::where('user_id', $user->id)->first();

        if (!$padre) {
            return response()->json(['estudiante' => null, 'notas' => []]);
        }

        $alumno = User::find($padre->estudiante_id);
        if (!$alumno) {
            return response()->json(['estudiante' => null, 'notas' => []]);
        }

        // Todas las notas del alumno con el nombre del curso
        $notasRaw = Nota::where('alumno_id', $alumno->id)
            ->with('asignacion.curso')
            ->get();

        // Agrupar por curso: [{ curso, B1, B2, B3, B4 }]
        $porCurso = [];
        foreach ($notasRaw as $nota) {
            $curso = $nota->asignacion->curso->nombre ?? 'Curso';
            if (!isset($porCurso[$curso])) {
                $porCurso[$curso] = ['curso' => $curso, 'B1' => null, 'B2' => null, 'B3' => null, 'B4' => null];
            }
            $porCurso[$curso][$nota->periodo] = $nota->calificacion;
        }

        return response()->json([
            'estudiante' => trim($alumno->apellidos) . ', ' . trim($alumno->nombres),
            'notas'      => array_values($porCurso),
        ]);
    }

    /** GET /api/padres/avisos — avisos institucionales + recomendaciones psicológicas */
    public function avisos()
    {
        $user = Auth::user();

        $alumno = null;
        if ($user->rol === 'alumno') {
            $alumno = $user;
        } elseif ($user->rol === 'padre') {
            $padre = Padre::where('user_id', $user->id)->first();
            if ($padre) {
                $alumno = User::find($padre->estudiante_id);
            }
        }

        $avisos = Aviso::orderByDesc('created_at')
            ->get()
            ->map(fn($a) => [
                'titulo' => $a->titulo,
                'contenido' => $a->contenido,
                'publicado_at' => \Carbon\Carbon::parse($a->publicado_at ?? $a->created_at)->format('d/m/Y h:i A'),
            ])
            ->values();

        $recomendaciones = collect();
        if ($alumno) {
            $recomendaciones = CitaPsicologica::query()
                ->where('alumno_id', $alumno->id)
                ->whereNotNull('psicologo_id')
                ->whereIn('estado', ['pendiente', 'atendida'])
                ->latest()
                ->get(['motivo', 'fecha_cita', 'estado', 'created_at'])
                ->map(fn($c) => [
                    'mensaje' => $c->motivo,
                    'estado' => $c->estado,
                    'fecha_cita' => $c->fecha_cita ? \Carbon\Carbon::parse($c->fecha_cita)->format('d/m/Y h:i A') : null,
                    'registrado_at' => $c->created_at?->format('d/m/Y h:i A'),
                ])
                ->values();
        }

        return response()->json([
            'avisos' => $avisos,
            'recomendaciones' => $recomendaciones,
        ]);
    }

    /** PUT /api/padres/cuenta — guardar preferencia de Gmail */
    public function cuenta(Request $request)
    {
        $user  = Auth::user();
        $padre = Padre::where('user_id', $user->id)->first();

        $request->validate([
            'codigo_usuario' => 'nullable|string|unique:users,codigo_usuario,' . $user->id,
            'password' => 'nullable|min:6',
            'recibir_avisos_email' => 'nullable|boolean',
        ]);

        $userData = [];
        if ($request->filled('codigo_usuario')) {
            $userData['codigo_usuario'] = $request->codigo_usuario;
        }
        if ($request->filled('password')) {
            $userData['password'] = $request->password;
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        if ($padre) {
            $padre->update([
                'recibir_avisos_email' => $request->boolean('recibir_avisos_email'),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Tu cuenta se actualizó correctamente.');
    }

    /** POST /padres/registro — registro de nuevo padre */
    public function registro(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string',
            'email'             => 'required|email|unique:users,codigo_usuario',
            'dni'               => 'required|string',
            'codigo_estudiante' => 'required|string',
            'password'          => 'required|min:6',
        ]);

        // Buscar al estudiante por su DNI (codigo_usuario)
        $estudiante = User::where('codigo_usuario', $request->codigo_estudiante)
            ->where('rol', 'alumno')
            ->first();

        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un alumno con ese código de vinculación.',
            ], 422);
        }

        // Crear el usuario padre
        $partes    = explode(' ', trim($request->nombre), 2);
        $nombres   = $partes[0] ?? $request->nombre;
        $apellidos = $partes[1] ?? '';

        $user = User::create([
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'codigo_usuario' => $request->email,
            'rol'            => 'padre',
            'password'       => Hash::make($request->password),
        ]);

        // Crear el vínculo padre-alumno
        Padre::create([
            'user_id'              => $user->id,
            'estudiante_id'        => $estudiante->id,
            'recibir_avisos_email' => $request->boolean('recibir_avisos_email'),
        ]);

        // Login automático tras registro
        auth()->login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'user'    => [
                'id'                   => $user->id,
                'nombres'              => trim($user->nombres),
                'apellidos'            => trim($user->apellidos),
                'rol'                  => 'padre',
                'recibir_avisos_email' => $request->boolean('recibir_avisos_email'),
            ],
        ]);
    }
}
