<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Profesor\DashboardController as ProfesorDashboard;
use App\Http\Controllers\Api\AvisosController;
use App\Http\Controllers\Api\PadresController;
use App\Imports\AlumnosImport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Sitio público
|--------------------------------------------------------------------------
*/
Route::get('/',      fn() => view('welcome'))->name('home');
Route::get('/login', fn() => view('welcome'))->name('login');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::post('/login',  [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Importar alumnos (Excel/CSV)
|--------------------------------------------------------------------------
*/
Route::get('/importar-alumnos', fn() => view('admin.importar-alumnos'))->name('importar-alumnos');
Route::post('/importar-alumnos', function (Request $request) {
    $request->validate(['archivo' => 'required|file']);
    Excel::import(new AlumnosImport, $request->file('archivo'));
    return back()->with('success', '¡Alumnos importados correctamente!');
})->name('importar-alumnos.store');

/*
|--------------------------------------------------------------------------
| API pública (sin auth)
|--------------------------------------------------------------------------
*/
Route::get( '/api/avisos',      [AvisosController::class, 'index']);
Route::post('/padres/registro', [PadresController::class, 'registro']);

/*
|--------------------------------------------------------------------------
| API privada (requiere login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('api')->group(function () {

    // Profesor y Director
    Route::middleware('role:profesor,director')->group(function () {
        Route::get( '/profesor/clase/{id}',            [ProfesorDashboard::class, 'apiClase']);
        Route::post('/profesor/clase/{id}/asistencia', [ProfesorDashboard::class, 'guardarAsistencia']);
        Route::post('/profesor/clase/{id}/notas',      [ProfesorDashboard::class, 'guardarNotas']);
        Route::get( '/profesor/tutorados/notas',       [ProfesorDashboard::class, 'apiTutorados']);
    });

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/admin/avisos', [AvisosController::class, 'store']);
    });

    // Padres / Alumnos
    Route::middleware('role:padre,alumno')->group(function () {
        Route::get('/padres/notas',  [PadresController::class, 'notas']);
        Route::put('/padres/cuenta', [PadresController::class, 'cuenta']);
    });
});
// ==========================================
// API PARA LA PÁGINA PRINCIPAL (WELCOME)
// ==========================================
Route::get('/api/avisos', function () {
    // Jalamos los últimos 5 anuncios de la base de datos
    $avisos = \App\Models\Aviso::latest()->take(5)->get()->map(function($aviso) {
        return [
            'titulo' => $aviso->titulo,
            'contenido' => $aviso->contenido,
            // Formateamos la fecha exactamente como la espera tu JavaScript
            'publicado_at' => $aviso->created_at->format('d/m/Y h:i A')
        ];
    });

    // Devolvemos el paquete de datos en formato JSON
    return response()->json(['avisos' => $avisos]);
});
// API PARA LA PÁGINA PRINCIPAL (WELCOME)
Route::get('/api/avisos', function () {
    $avisos = \App\Models\Aviso::latest()->take(5)->get()->map(function($aviso) {
        return [
            'titulo' => $aviso->titulo,
            'contenido' => $aviso->contenido,
            'publicado_at' => $aviso->created_at->format('d/m/Y h:i A')
        ];
    });

    return response()->json(['avisos' => $avisos]);
});
/*
|--------------------------------------------------------------------------
| Rutas temporales para que el layout admin no rompa
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard',          fn() => redirect('/importar-alumnos'))->name('admin.dashboard');
Route::get('/admin/importar-profesores',fn() => 'Próximamente')->name('admin.importar-profesores');
// Rutas para que el Admin maneje los anuncios de la página principal
Route::post('/admin/avisos', function (Illuminate\Http\Request $request) {
    $request->validate([
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
    ]);

    App\Models\Aviso::create([
        'titulo' => $request->titulo,
        'contenido' => $request->contenido,
        'user_id' => auth()->id(), // El admin que lo publica
    ]);

    return redirect()->back()->with('success', '¡Anuncio publicado con éxito en la página principal!');
})->name('admin.avisos.store');