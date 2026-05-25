<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Login — devuelve JSON para que el frontend JS lo consuma.
     */
    public function login(Request $request)
    {
        $request->validate([
            'codigo_usuario' => 'required',
            'password'       => 'required',
        ]);

        $credentials = [
            'codigo_usuario' => $request->codigo_usuario,
            'password'       => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Código o contraseña incorrectos.',
            ], 401);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Datos extra según el rol
        $extra = [];

        if ($user->rol === 'profesor' || $user->rol === 'director') {
            $aulaTutoria = Aula::where('tutor_id', $user->id)->first();
            $extra['esTutor'] = $aulaTutoria !== null;
            $extra['salonTutoria'] = $aulaTutoria
                ? ['nombre' => $aulaTutoria->grado . '° ' . $aulaTutoria->seccion]
                : null;

            // Cargar cursos del profesor para el sidebar
            if ($user->rol === 'profesor') {
                $extra['cursos'] = Asignacion::with(['curso', 'aula'])
                    ->where('profesor_id', $user->id)
                    ->get()
                    ->map(fn($a) => [
                        'id'     => $a->id,
                        'nombre' => $a->curso->nombre ?? 'Curso',
                        'aula'   => ($a->aula->grado ?? '') . '° ' . ($a->aula->seccion ?? ''),
                    ]);
            }
        }

        return response()->json([
            'success' => true,
            'user'    => array_merge([
                'id'       => $user->id,
                'nombres'  => trim($user->nombres),
                'apellidos'=> trim($user->apellidos),
                'rol'      => $user->rol,
            ], $extra),
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }
}
