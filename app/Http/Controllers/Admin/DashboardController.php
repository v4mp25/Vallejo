<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\ConfiguracionWeb;

class DashboardController extends Controller
{
    public function index(): View
    {
        $config = ConfiguracionWeb::first() ?? new ConfiguracionWeb();

        return view('admin.configuracion', compact('config'));
    }
}