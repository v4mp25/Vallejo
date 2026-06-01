<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Profesor\DashboardController as ProfesorDashboard;
use App\Http\Controllers\Profesor\DerivacionPsicologicaController;
use App\Http\Controllers\Psicologo\PsicologoController;
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
Route::get('/media/hero/{id}', function ($id) {
    $imagenes = [
        '1' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.42-199bf5fe-ce47-4449-96d1-73c6dfb379ad.png',
        '2' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.41-341b75ce-5f06-4270-b22d-b64cd78b507d.png',
    ];

    $ruta = $imagenes[(string) $id] ?? null;
    if (!$ruta || !is_file($ruta)) {
        abort(404);
    }

    return Response::file($ruta, ['Cache-Control' => 'public, max-age=3600']);
});
Route::get('/media/administracion/{id}', function ($id) {
    $imagenes = [
        'director' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.42__1_-10ce026b-fba2-4751-9f74-2df688c8b529.png',
        'subdirectora' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.42-60ff0871-834a-48fd-9e45-1945dcbf7ffa.png',
        'subdirector' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.41-64b5437e-bc7a-4e32-9752-d69ba289cf4a.png',
    ];

    $ruta = $imagenes[(string) $id] ?? null;
    if (!$ruta || !is_file($ruta)) {
        abort(404);
    }

    return Response::file($ruta, ['Cache-Control' => 'public, max-age=3600']);
});

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
    Route::middleware('role:profesor')->group(function () {
        Route::get('/profesor/psicologia/alumnos', [ProfesorDashboard::class, 'apiAlumnosPsicologia']);
        Route::post('/profesor/psicologia/derivar', [ProfesorDashboard::class, 'apiDerivarPsicologia']);
    });

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/admin/avisos', [AvisosController::class, 'store']);
    });

    // Padres / Alumnos
    Route::middleware('role:padre,alumno')->group(function () {
        Route::get('/padres/notas',  [PadresController::class, 'notas']);
        Route::get('/padres/avisos', [PadresController::class, 'avisos']);
        Route::put('/padres/cuenta', [PadresController::class, 'cuenta']);
    });
});

/*
|--------------------------------------------------------------------------
| Módulo Psicología (web)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:profesor'])->group(function () {
    Route::get('/profesor/psicologia', [DerivacionPsicologicaController::class, 'index'])
        ->name('profesor.psicologia.index');
    Route::post('/profesor/psicologia/derivaciones', [DerivacionPsicologicaController::class, 'store'])
        ->name('profesor.psicologia.store');
});

Route::middleware(['auth', 'role:psicologo'])->prefix('psicologo')->group(function () {
    Route::get('/dashboard', [PsicologoController::class, 'index'])->name('psicologo.dashboard');
    Route::post('/citas/{cita}/asignar', [PsicologoController::class, 'asignarCita'])
        ->name('psicologo.citas.asignar');
});
/*
|--------------------------------------------------------------------------
| Rutas temporales para que el layout admin no rompa
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard',          fn() => redirect('/importar-alumnos'))->name('admin.dashboard');
Route::get('/admin/importar-profesores',fn() => 'Próximamente')->name('admin.importar-profesores');
// Ruta web legacy de admin para publicar avisos (misma lógica que API)
Route::post('/admin/avisos', [AvisosController::class, 'store'])->name('admin.avisos.store');