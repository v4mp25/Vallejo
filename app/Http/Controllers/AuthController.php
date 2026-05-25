<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos que el usuario haya llenado ambos campos
        $credentials = $request->validate([
            'codigo_usuario' => ['required'],
            'password' => ['required'],
        ]);

        // 2. Laravel intenta hacer match con la base de datos
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Por ahora mandamos un texto simple para comprobar que funciona
            return "¡Login exitoso! Bienvenido al sistema, " . Auth::user()->nombres . " " . Auth::user()->apellidos;
        }

        // 3. Si se equivoca de clave, lo regresamos a la pantalla de login
        return back()->withErrors([
            'codigo_usuario' => 'El código o la contraseña son incorrectos.',
        ]);
    }
}