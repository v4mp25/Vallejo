<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
         * Conecta con tu backend:
         * - $profesores = Profesor::latest()->take(50)->get();
         * - $aulas = Aula::with('grado')->latest()->get();
         */
        $profesores = collect([
            (object) ['id' => 1, 'nombre' => 'Juan Pérez García', 'dni' => '12345678', 'email' => 'jperez@ie.edu.pe', 'cursos' => 'Matemáticas, Física'],
            (object) ['id' => 2, 'nombre' => 'María López Ruiz', 'dni' => '87654321', 'email' => 'mlopez@ie.edu.pe', 'cursos' => 'Comunicación'],
        ]);

        $aulas = collect([
            (object) ['id' => 1, 'nombre' => '1ro A', 'grado' => '1° Secundaria', 'tutor' => 'Juan Pérez García'],
            (object) ['id' => 2, 'nombre' => '2do B', 'grado' => '2° Secundaria', 'tutor' => 'Sin asignar'],
        ]);

        return view('admin.dashboard', compact('profesores', 'aulas'));
    }
}
