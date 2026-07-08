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
use App\Http\Controllers\AulaVirtualController;
use App\Imports\AlumnosImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ConfiguracionWeb;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\InstitucionController;
use App\Http\Controllers\Admin\GestionInstitucionalController;
use App\Http\Controllers\Admin\ServicioEducativoController;
use App\Http\Controllers\Admin\ComunidadEducativaController;
use App\Http\Controllers\Admin\LogroController;
use App\Http\Controllers\Admin\GaleriaInstitucionalController;
use App\Http\Controllers\Admin\NoticiasComunicadosController;

/*
|--------------------------------------------------------------------------
| Sitio público
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $config = ConfiguracionWeb::first() ?? ConfiguracionWeb::create([
        'frase_topbar' => 'Formamos líderes con corazón vallejiano',
        'telefono_contacto' => '+51 927 736 128',
        'correo_contacto' => 'contacto@cesarvallejo.edu.pe'
    ]);
    return view('welcome', compact('config'));
})->name('home');

Route::get('/login', fn() => view('welcome'))->name('login');
Route::get('/nuestra-institucion', function () {
    $info = \App\Models\InstitucionInfo::first() ?? new \App\Models\InstitucionInfo();
    return view('nuestra-institucion', compact('info'));
});
Route::get('/gestion-institucional', function () {
    $info = \App\Models\GestionInstitucional::first() ?? new \App\Models\GestionInstitucional();
    $personal = \App\Models\PersonalInstitucional::whereIn('categoria', ['directivo', 'administrativo'])->get();
    $documentos = \App\Models\DocumentoGestion::all();
    return view('gestion-institucional', compact('info', 'personal', 'documentos'));
})->name('gestion-institucional');
Route::get('/servicio-educativo', function () {
    $info = \App\Models\ServicioEducativo::first() ?? new \App\Models\ServicioEducativo();
    $areas = \App\Models\AreaCurricular::all();
    $proyectos = \App\Models\ProyectoInstitucional::all();
    return view('servicio-educativo', compact('info', 'areas', 'proyectos'));
})->name('servicio-educativo');
Route::get('/comunidad-educativa', function () {
    $info = \App\Models\ComunidadTexto::first() ?? new \App\Models\ComunidadTexto();
    $aliados = \App\Models\AliadoEstrategico::all();
    return view('comunidad-educativa', compact('info', 'aliados'));
})->name('comunidad-educativa');
Route::get('/logros-reconocimientos', function () {
    $logros = \App\Models\Logro::orderBy('fecha', 'desc')->get()->groupBy('categoria');
    return view('logros-reconocimientos', compact('logros'));
})->name('logros-reconocimientos');
Route::get('/galeria-institucional', function () {
    $fotos = \App\Models\Fotografia::all();
    $videos = \App\Models\Video::all();
    $eventos = \App\Models\Evento::orderBy('fecha', 'desc')->get();
    $actividades = \App\Models\ActividadPedagogica::orderBy('fecha', 'desc')->get();
    return view('galeria-institucional', compact('fotos', 'videos', 'eventos', 'actividades'));
})->name('galeria-institucional');
Route::get('/noticias-comunicados', function () {
    $noticias = \App\Models\Noticia::orderBy('fecha', 'desc')->get();
    $comunicados = \App\Models\Comunicado::orderBy('fecha', 'desc')->get();
    $agenda = \App\Models\Agenda::orderBy('fecha_inicio', 'asc')->get();
    $boletines = \App\Models\Boletin::all();
    return view('noticias-comunicados', compact('noticias', 'comunicados', 'agenda', 'boletines'));
})->name('noticias-comunicados');

Route::get('/media/hero/{id}', function ($id) {
    $imagenes = [
        '1' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.42-199bf5fe-ce47-4449-96d1-73c6dfb379ad.png',
        '2' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.41-341b75ce-5f06-4270-b22d-b64cd78b507d.png',
    ];
    $ruta = $imagenes[(string) $id] ?? null;
    if (!$ruta || !is_file($ruta)) abort(404);
    return Response::file($ruta, ['Cache-Control' => 'public, max-age=3600']);
});

Route::get('/media/administracion/{id}', function ($id) {
    $imagenes = [
        'director' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.42__1_-10ce026b-fba2-4751-9f74-2df688c8b529.png',
        'subdirectora' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.42-60ff0871-834a-48fd-9e45-1945dcbf7ffa.png',
        'subdirector' => 'C:\\Users\\ACER\\.cursor\\projects\\c-laragon-www-colegio-vallejo\\assets\\c__Users_ACER_AppData_Roaming_Cursor_User_workspaceStorage_fb5d785d4192113de4a0a3d55c6b69be_images_WhatsApp_Image_2026-05-26_at_11.54.41-64b5437e-bc7a-4e32-9752-d69ba289cf4a.png',
    ];
    $ruta = $imagenes[(string) $id] ?? null;
    if (!$ruta || !is_file($ruta)) abort(404);
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
    // Padres / Alumnos
    Route::middleware('role:padre,alumno')->group(function () {
        Route::get('/padres/notas',  [PadresController::class, 'notas']);
        Route::get('/padres/avisos', [PadresController::class, 'avisos']);
        Route::put('/padres/cuenta', [PadresController::class, 'cuenta']);
    });
});

/*
|--------------------------------------------------------------------------
| Paneles Web (Vistas Blade) - ADMINISTRADOR
|--------------------------------------------------------------------------
*/
// AQUÍ ESTÁ LA SOLUCIÓN: Este bloque ahora está libre y fuera de la API
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    
    Route::get('/admin/importar-profesores', [\App\Http\Controllers\Admin\ImportarProfesoresController::class, 'index'])->name('admin.importar-profesores');

    Route::get('/admin/configuracion', function() {
        $config = \App\Models\ConfiguracionWeb::first() ?? new \App\Models\ConfiguracionWeb();
        return view('admin.configuracion', compact('config'));
    })->name('admin.configuracion');

    Route::post('/admin/configuracion/guardar', function(Request $request) {
        $request->validate([
            'frase_topbar' => 'required|string|max:255',
            'hero_titulo' => 'nullable|string|max:255',
            'hero_subtitulo' => 'nullable|string',
            'banner_inicial' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_facebook' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|string|max:255',
            'link_instagram' => 'nullable|string|max:255',
            'direccion_texto' => 'nullable|string|max:255',
            'link_maps' => 'nullable|string',
        ]);

        $config = \App\Models\ConfiguracionWeb::first() ?? new \App\Models\ConfiguracionWeb();
        $config->frase_topbar = $request->frase_topbar;
        $config->hero_titulo = $request->hero_titulo;
        $config->hero_subtitulo = $request->hero_subtitulo;
        
        $config->link_facebook = $request->link_facebook;
        $config->link_youtube = $request->link_youtube;
        $config->link_instagram = $request->link_instagram;
        $config->direccion_texto = $request->direccion_texto;
        $config->link_maps = $request->link_maps;

        // Si subieron una imagen nueva para el fondo de la web
        if ($request->hasFile('banner_inicial')) {
            $rutaImagen = $request->file('banner_inicial')->store('portal', 'public');
            $config->banner_inicial_url = $rutaImagen;
        }

        // Lógica para el logo
        if ($request->hasFile('logo')) {
            $rutaLogo = $request->file('logo')->store('portal', 'public');
            $config->logo_url = $rutaLogo;
        }

        $config->save();

        return back()->with('success', '¡Portal web actualizado con éxito!');
    })->name('admin.configuracion.guardar');

    Route::post('/admin/avisos', [AvisosController::class, 'store'])->name('admin.avisos.store');

    // Nuestra Institución
    Route::get('/admin/institucion',         [InstitucionController::class, 'index'])->name('admin.institucion.index');
    Route::post('/admin/institucion/guardar',[InstitucionController::class, 'guardar'])->name('admin.institucion.guardar');

    // Gestión Institucional
    Route::get('/admin/gestion-institucional',               [GestionInstitucionalController::class, 'index'])->name('admin.gestion-institucional.index');
    Route::post('/admin/gestion-institucional/guardar',       [GestionInstitucionalController::class, 'guardarTextosOrganigrama'])->name('admin.gestion-institucional.guardar');
    Route::post('/admin/gestion-institucional/personal',      [GestionInstitucionalController::class, 'guardarPersonal'])->name('admin.gestion-institucional.personal.guardar');
    Route::delete('/admin/gestion-institucional/personal/{id}',[GestionInstitucionalController::class, 'eliminarPersonal'])->name('admin.gestion-institucional.personal.eliminar');
    Route::post('/admin/gestion-institucional/documentos',    [GestionInstitucionalController::class, 'guardarDocumento'])->name('admin.gestion-institucional.documentos.guardar');
    Route::delete('/admin/gestion-institucional/documentos/{id}',[GestionInstitucionalController::class, 'eliminarDocumento'])->name('admin.gestion-institucional.documentos.eliminar');

    // Servicio Educativo
    Route::get('/admin/servicio-educativo',               [ServicioEducativoController::class, 'index'])->name('admin.servicio-educativo.index');
    Route::post('/admin/servicio-educativo/guardar',       [ServicioEducativoController::class, 'guardarTextos'])->name('admin.servicio-educativo.guardar');
    Route::post('/admin/servicio-educativo/areas',         [ServicioEducativoController::class, 'guardarArea'])->name('admin.servicio-educativo.areas.guardar');
    Route::delete('/admin/servicio-educativo/areas/{id}',  [ServicioEducativoController::class, 'eliminarArea'])->name('admin.servicio-educativo.areas.eliminar');
    Route::post('/admin/servicio-educativo/proyectos',     [ServicioEducativoController::class, 'guardarProyecto'])->name('admin.servicio-educativo.proyectos.guardar');
    Route::delete('/admin/servicio-educativo/proyectos/{id}',[ServicioEducativoController::class, 'eliminarProyecto'])->name('admin.servicio-educativo.proyectos.eliminar');

    // Comunidad Educativa
    Route::get('/admin/comunidad-educativa',               [ComunidadEducativaController::class, 'index'])->name('admin.comunidad-educativa.index');
    Route::post('/admin/comunidad-educativa/guardar',       [ComunidadEducativaController::class, 'guardarTextosDocumentos'])->name('admin.comunidad-educativa.guardar');
    Route::post('/admin/comunidad-educativa/aliados',         [ComunidadEducativaController::class, 'guardarAliado'])->name('admin.comunidad-educativa.aliados.guardar');
    Route::delete('/admin/comunidad-educativa/aliados/{id}',  [ComunidadEducativaController::class, 'eliminarAliado'])->name('admin.comunidad-educativa.aliados.eliminar');

    // Logros y Reconocimientos
    Route::get('/admin/logros',               [LogroController::class, 'index'])->name('admin.logros.index');
    Route::post('/admin/logros',              [LogroController::class, 'store'])->name('admin.logros.store');
    Route::delete('/admin/logros/{id}',       [LogroController::class, 'destroy'])->name('admin.logros.eliminar');

    // Galería Institucional
    Route::get('/admin/galeria-institucional',               [GaleriaInstitucionalController::class, 'index'])->name('admin.galeria-institucional.index');
    Route::post('/admin/galeria-institucional/fotos',         [GaleriaInstitucionalController::class, 'storeFoto'])->name('admin.galeria-institucional.fotos.store');
    Route::delete('/admin/galeria-institucional/fotos/{id}',  [GaleriaInstitucionalController::class, 'destroyFoto'])->name('admin.galeria-institucional.fotos.eliminar');
    Route::post('/admin/galeria-institucional/videos',        [GaleriaInstitucionalController::class, 'storeVideo'])->name('admin.galeria-institucional.videos.store');
    Route::delete('/admin/galeria-institucional/videos/{id}', [GaleriaInstitucionalController::class, 'destroyVideo'])->name('admin.galeria-institucional.videos.eliminar');
    Route::post('/admin/galeria-institucional/eventos',       [GaleriaInstitucionalController::class, 'storeEvento'])->name('admin.galeria-institucional.eventos.store');
    Route::delete('/admin/galeria-institucional/eventos/{id}',[GaleriaInstitucionalController::class, 'destroyEvento'])->name('admin.galeria-institucional.eventos.eliminar');
    Route::post('/admin/galeria-institucional/actividades',   [GaleriaInstitucionalController::class, 'storeActividad'])->name('admin.galeria-institucional.actividades.store');
    Route::delete('/admin/galeria-institucional/actividades/{id}',[GaleriaInstitucionalController::class, 'destroyActividad'])->name('admin.galeria-institucional.actividades.eliminar');

    // Noticias y Comunicados
    Route::get('/admin/noticias-comunicados',               [NoticiasComunicadosController::class, 'index'])->name('admin.noticias-comunicados.index');
    Route::post('/admin/noticias-comunicados/noticias',         [NoticiasComunicadosController::class, 'storeNoticia'])->name('admin.noticias-comunicados.noticias.store');
    Route::delete('/admin/noticias-comunicados/noticias/{id}',  [NoticiasComunicadosController::class, 'destroyNoticia'])->name('admin.noticias-comunicados.noticias.eliminar');
    Route::post('/admin/noticias-comunicados/comunicados',        [NoticiasComunicadosController::class, 'storeComunicado'])->name('admin.noticias-comunicados.comunicados.store');
    Route::delete('/admin/noticias-comunicados/comunicados/{id}', [NoticiasComunicadosController::class, 'destroyComunicado'])->name('admin.noticias-comunicados.comunicados.eliminar');
    Route::post('/admin/noticias-comunicados/agenda',             [NoticiasComunicadosController::class, 'storeAgenda'])->name('admin.noticias-comunicados.agenda.store');
    Route::delete('/admin/noticias-comunicados/agenda/{id}',      [NoticiasComunicadosController::class, 'destroyAgenda'])->name('admin.noticias-comunicados.agenda.eliminar');
    Route::post('/admin/noticias-comunicados/boletines',          [NoticiasComunicadosController::class, 'storeBoletin'])->name('admin.noticias-comunicados.boletines.store');
    Route::delete('/admin/noticias-comunicados/boletines/{id}',   [NoticiasComunicadosController::class, 'destroyBoletin'])->name('admin.noticias-comunicados.boletines.eliminar');
});

/*
|--------------------------------------------------------------------------
| Paneles Web (Vistas Blade) - PROFESOR Y DIRECTOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:profesor,director'])->group(function () {
    Route::get('/profesor/dashboard', [ProfesorDashboard::class, 'index'])->name('profesor.dashboard');
    Route::get('/profesor/clase/{id}', [ProfesorDashboard::class, 'verClase'])->name('profesor.clase');
    Route::post('/profesor/clase/{id}/notas', [ProfesorDashboard::class, 'guardarNotas'])->name('profesor.notas.guardar');
    Route::get('/profesor/tutorados/notas', [ProfesorDashboard::class, 'notasTutorados'])->name('profesor.tutorados.notas');

    Route::get('/profesor/aula-virtual', [AulaVirtualController::class, 'index'])->name('profesor.aula-virtual.index');
    Route::post('/profesor/aula-virtual/material', [AulaVirtualController::class, 'storeMaterial'])->name('aula-virtual.material.store');
    Route::post('/profesor/aula-virtual/tarea', [AulaVirtualController::class, 'storeTask'])->name('aula-virtual.tarea.store');
    Route::get('/profesor/aula-virtual/{material}', [AulaVirtualController::class, 'show'])->name('aula-virtual.show');

    Route::get('/profesor/psicologia', [DerivacionPsicologicaController::class, 'index'])->name('profesor.psicologia.index');
    Route::post('/profesor/psicologia/derivaciones', [DerivacionPsicologicaController::class, 'store'])->name('profesor.psicologia.store');
});

Route::middleware(['auth', 'role:alumno,padre'])->group(function () {
    Route::get('/padres/dashboard', function () {
        return view('padres.dashboard');
    })->name('padres.dashboard');

    Route::get('/alumno/dashboard', function () {
        return view('alumno.dashboard');
    })->name('alumno.dashboard');

    Route::get('/aula-virtual', [AulaVirtualController::class, 'index'])->name('aula-virtual.index');
    Route::post('/aula-virtual/tarea', [AulaVirtualController::class, 'storeTask'])->name('aula-virtual.tarea.store');
    Route::get('/aula-virtual/{material}', [AulaVirtualController::class, 'show'])->name('aula-virtual.show');
});

/*
|--------------------------------------------------------------------------
| Módulo Psicología (web) - PSICÓLOGO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:psicologo'])->prefix('psicologo')->group(function () {
    Route::get('/dashboard', [PsicologoController::class, 'index'])->name('psicologo.dashboard');
    Route::post('/citas/{cita}/asignar', [PsicologoController::class, 'asignarCita'])->name('psicologo.citas.asignar');
});