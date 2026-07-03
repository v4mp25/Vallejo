<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
   
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

        // 1. Verificamos si la contraseña es correcta
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Código o contraseña incorrectos.',
            ], 401);
        }

        // 2. Iniciamos la sesión de forma segura
        $request->session()->regenerate();
        $user = Auth::user();

        // 3. Decidimos a qué pantalla enviarlo según su rol
        $redirectUrl = '/'; 
        
        switch ($user->rol) {
            case 'admin':
                $redirectUrl = '/admin/dashboard';
                break;
            case 'profesor':
            case 'director':
                $redirectUrl = '/profesor/dashboard';
                break;
            case 'psicologo':
                $redirectUrl = '/psicologo/dashboard';
                break;
            case 'padre':
                $redirectUrl = '/padres/dashboard';
                break;
            case 'alumno':
                $redirectUrl = '/alumno/dashboard';
                break;
        }

        // 4. Le devolvemos la ruta al JavaScript del welcome.blade.php
        return response()->json([
            'success'  => true,
            'redirect' => $redirectUrl,
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

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
