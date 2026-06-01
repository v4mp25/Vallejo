<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\User; 
use App\Models\Aula; 

class DashboardController extends Controller
{
    public function index(): View
    {
      
        $profesores = User::where('rol', 'profesor')
                          ->latest()
                          ->take(50)
                          ->get();

      
        $aulas = Aula::latest()->get();

     
        return view('admin.dashboard', compact('profesores', 'aulas'));
    }
}