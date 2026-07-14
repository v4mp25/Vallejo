<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>I.E. César Vallejo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --cv-primary: #0148A4;
            --cv-primary-dark: #023d7a;
            --cv-light-bg: #f8f9ff;
            --cv-shadow: 0 8px 32px rgba(1, 72, 164, 0.12);
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Roboto', sans-serif !important; margin: 0; color: #333; -webkit-font-smoothing: antialiased; }

        /* ===== SITIO PÚBLICO ===== */
        .cv-navbar-wrap { position: absolute; top: 0; left: 0; right: 0; z-index: 100; }
        .cv-navbar-wrap .navbar { background: transparent !important; transition: background .35s, box-shadow .35s; }
        .cv-navbar-wrap.scrolled .navbar { background: rgba(255,255,255,.97) !important; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .cv-navbar-wrap .nav-link { color: #fff !important; font-weight: 500; font-size: 0.9rem; padding: 10px 12px !important; }
        @media (min-width: 1200px) and (max-width: 1399px) {
            .cv-navbar-wrap .nav-link { font-size: 0.78rem !important; padding: 10px 5px !important; }
            .navbar-brand span.fw-bold { font-size: 1.05rem !important; }
            .navbar-brand span:last-child { font-size: 0.55rem !important; }
            .navbar-brand img { height: 40px !important; }
        }
        .cv-navbar-wrap.scrolled .nav-link:not(.btn-login) { color: #333 !important; }
        
        /* ===== MENU DESPLEGABLE ===== */
        @media (min-width: 1200px) {
            .cv-dropdown-hover:hover > .dropdown-menu { display: block; margin-top: 0; animation: fadeIn .3s ease; }
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .cv-dropdown-hover .dropdown-menu { border-radius: 12px; padding: 10px 0; }
        .cv-dropdown-hover .dropdown-item { padding: 10px 20px; font-weight: 500; color: #444; transition: all 0.2s; }
        .cv-dropdown-hover .dropdown-item:hover { background-color: var(--cv-light-bg); color: var(--cv-primary); padding-left: 25px; }

        .btn-login { background: var(--cv-primary) !important; border: 2px solid var(--cv-primary) !important; color: #fff !important; border-radius: 50px !important; padding: 10px 26px !important; font-weight: 600 !important; cursor: pointer !important; transition: transform .2s, box-shadow .2s; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(1,72,164,.35); color: #fff !important; }
        
        /* ===== NUEVO HERO OPTIMIZADO ===== */
       .cv-hero { position: relative; height: 60vh; min-height: 420px; display: flex; align-items: center; justify-content: flex-start; text-align: left; color: #fff; overflow: hidden; }.cv-hero-bg { position: absolute; inset: 0; background: #002c6a; }
        .cv-hero-bg img { width: 100%; height: 100%; object-fit: cover; }
        .cv-hero-overlay { position: absolute; inset: 0; background: linear-gradient(135deg, rgba(1, 44, 106, 0.9) 0%, rgba(1, 72, 164, 0.6) 50%, rgba(0, 0, 0, 0.4) 100%); z-index: 1; }
       .cv-hero-content { position: relative; z-index: 2; max-width: 850px; padding: 20px; padding-left: 8%; }
        .cv-hero-content h1 { font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 700; text-shadow: 0 4px 15px rgba(0,0,0,0.5); }
      .cv-hero-content p { font-size: 1.1rem; opacity: 0.95; text-shadow: 0 2px 8px rgba(0,0,0,0.5); margin-left: 0; } 
        .cv-aviso-card { background: #fff; border: 1px solid #e8ecf0; border-left: 4px solid var(--cv-primary); border-radius: 12px; padding: 20px 24px; text-align: left; box-shadow: var(--cv-shadow); margin-bottom: 12px; }
        .admin-section { padding: 60px 24px 80px; background: #fff; }
        .copyright-cesarvallejo { background: var(--cv-primary); color: rgba(255,255,255,.9); text-align: center; padding: 22px; font-size: .9rem; }

        /* ===== MODAL AUTH ===== */
        .cv-overlay-auth { position: fixed; inset: 0; background: rgba(1, 30, 60, 0.65); backdrop-filter: blur(6px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px; }
        .cv-overlay-auth.activo { display: flex; }
        .cv-auth-box { background: #fff; border-radius: 20px; max-width: 440px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 24px 80px rgba(0, 0, 0, 0.28); animation: cvModalIn .35s ease; }
        @keyframes cvModalIn { from { opacity: 0; transform: scale(.94) translateY(12px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .cv-auth-header { background: linear-gradient(135deg, var(--cv-primary), var(--cv-primary-dark)); color: #fff; padding: 28px 28px 24px; position: relative; }
        .cv-auth-header .cv-auth-logo { width: 56px; height: 56px; background: rgba(255,255,255,.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 1.6rem; }
        .cv-auth-header h5 { font-weight: 700; font-size: 1.25rem; }
        .cv-auth-close { position: absolute; top: 16px; right: 16px; background: rgba(255,255,255,.2); border: none; color: #fff; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .cv-auth-body { padding: 28px; }
        .cv-auth-tabs { display: flex; gap: 8px; background: var(--cv-light-bg); padding: 6px; border-radius: 12px; margin-bottom: 24px; }
        .cv-auth-tab { flex: 1; border: none; background: transparent; padding: 12px 16px; border-radius: 10px; font-weight: 600; font-size: .9rem; color: #666; cursor: pointer; transition: all .2s; }
        .cv-auth-tab.active { background: #fff; color: var(--cv-primary); box-shadow: 0 2px 12px rgba(1,72,164,.15); }
        .cv-auth-pane { display: none; }
        .cv-auth-pane.active { display: block; }
        .cv-field label { font-weight: 600; font-size: .85rem; color: #444; margin-bottom: 6px; display: block; }
        .cv-field { margin-bottom: 18px; }
        .cv-input-wrap { display: flex; align-items: stretch; border: 1px solid #dde3eb; border-radius: 10px; overflow: hidden; }
        .cv-input-wrap .cv-input-icon { background: var(--cv-light-bg); padding: 0 14px; display: flex; align-items: center; color: var(--cv-primary); }
        .cv-input-wrap input { border: none !important; box-shadow: none !important; flex: 1; padding: 12px 14px; outline: none; }
        .cv-btn-submit { width: 100%; background: var(--cv-primary); border: none; color: #fff; padding: 14px 24px; border-radius: 50px; font-weight: 600; cursor: pointer; }
        .cv-check-gmail { background: var(--cv-light-bg); border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; }
        .cv-login-error { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; font-size: .88rem; display: none; }
        .cv-login-error.visible { display: block; }

        @media (max-width: 1199px) {
            .cv-navbar-wrap .nav-link { color: #333 !important; }
            .cv-navbar-wrap .navbar { background: #fff !important; }
        }
    </style>
</head>
<body>

    <div class="py-1" style="background-color: var(--cv-primary-dark); font-size: 0.85rem;">
        <div class="container d-flex justify-content-end text-white">
            <span class="fw-semibold fst-italic">
                <i class="fas fa-quote-left text-warning me-1 small"></i>
                {{ $config->frase_topbar ?? 'xddd líderes con corazón vallejiano' }}
                <i class="fas fa-quote-right text-warning ms-1 small"></i>
            </span>
        </div>
    </div>

    <div class="cv-navbar-wrap" id="cv-navbar-wrap" style="position: relative; background-color: var(--cv-primary);">
        <nav class="navbar navbar-expand-xl navbar-dark px-4 px-lg-5 py-2">
            <a class="navbar-brand p-0 d-flex align-items-center gap-2" href="{{ url('/') }}">
    @if(isset($config) && $config->logo_url)
        <img id="logo-img" src="{{ asset('storage/' . $config->logo_url) }}" alt="I.E. César Vallejo" height="50">
    @else
        <img id="logo-img" src="{{ asset('img/Yachachin1.png') }}" alt="I.E. César Vallejo" height="50">
    @endif
    <div class="d-flex flex-column text-white">
        <span class="fw-bold" style="font-size: 1.2rem; letter-spacing: 0.5px; line-height: 1;">CÉSAR VALLEJO</span>
        <span style="font-size: 0.65rem; letter-spacing: 1px; opacity: 0.8;">INSTITUCIÓN EDUCATIVA</span>
    </div>
</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <a class="nav-link text-white fw-bold" href="{{ url('/') }}">Inicio</a>
                    
                    <div class="nav-item dropdown cv-dropdown-hover">
                        <a class="nav-link dropdown-toggle text-white" href="{{ url('/nuestra-institucion') }}" id="navbarInstitucion">
                            Institución
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarInstitucion">
                            <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#resena') }}"><i class="fas fa-history text-warning me-2"></i>Reseña Histórica</a></li>
                            <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#identidad') }}"><i class="fas fa-bullseye text-warning me-2"></i>Identidad e Ideario</a></li>
                            <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#simbolos') }}"><i class="fas fa-flag text-warning me-2"></i>Símbolos Escolares</a></li>
                            <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#infraestructura') }}"><i class="fas fa-building text-warning me-2"></i>Infraestructura</a></li>
                        </ul>
                    </div>

                    <div class="nav-item dropdown cv-dropdown-hover">
                        <a class="nav-link dropdown-toggle text-white" href="{{ url('/gestion-institucional') }}" id="navbarGestion">
                            Gestión
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarGestion">
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#personal') }}"><i class="fas fa-users text-primary me-2"></i>3.1 Personal Institucional</a></li>
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#organigrama') }}"><i class="fas fa-sitemap text-success me-2"></i>3.2 Organigrama</a></li>
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#organos') }}"><i class="fas fa-handshake text-warning me-2"></i>3.3 Órganos de Participación</a></li>
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#instrumentos') }}"><i class="fas fa-file-pdf text-danger me-2"></i>3.4 Instrumentos de Gestión</a></li>
                        </ul>
                    </div>
                    <div class="nav-item dropdown cv-dropdown-hover">
                        <a class="nav-link dropdown-toggle text-white" href="{{ url('/servicio-educativo') }}" id="navbarServicio">
                            Servicio
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarServicio">
                            <li><a class="dropdown-item" href="{{ url('/servicio-educativo#areas') }}"><i class="fas fa-book text-primary me-2"></i>4.1 Áreas Curriculares</a></li>
                            <li><a class="dropdown-item" href="{{ url('/servicio-educativo#propuesta') }}"><i class="fas fa-chalkboard-teacher text-success me-2"></i>4.2 Propuesta Pedagógica</a></li>
                            <li><a class="dropdown-item" href="{{ url('/servicio-educativo#proyectos') }}"><i class="fas fa-rocket text-warning me-2"></i>4.3 Proyectos Bandera</a></li>
                        </ul>
                    </div>

                    <div class="nav-item dropdown cv-dropdown-hover">
                        <a class="nav-link dropdown-toggle text-white" href="{{ url('/comunidad-educativa') }}" id="navbarComunidad">
                            Comunidad
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarComunidad">
                            <li><a class="dropdown-item" href="{{ url('/comunidad-educativa#estudiantes') }}"><i class="fas fa-user-graduate text-primary me-2"></i>6.1 Estudiantes</a></li>
                            <li><a class="dropdown-item" href="{{ url('/comunidad-educativa#padres') }}"><i class="fas fa-user-friends text-success me-2"></i>6.2 Padres de Familia</a></li>
                            <li><a class="dropdown-item" href="{{ url('/comunidad-educativa#exalumnos') }}"><i class="fas fa-user-tie text-warning me-2"></i>6.3 Exalumnos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/comunidad-educativa#aliados') }}"><i class="fas fa-handshake text-danger me-2"></i>6.4 Aliados Estratégicos</a></li>
                        </ul>
                    </div>

                    <div class="nav-item dropdown cv-dropdown-hover">
                        <a class="nav-link dropdown-toggle text-white" href="{{ url('/logros-reconocimientos') }}" id="navbarLogros">
                            Logros
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarLogros">
                            <li><a class="dropdown-item" href="{{ url('/logros-reconocimientos#academico') }}"><i class="fas fa-book-reader text-primary me-2"></i>7.1 Académicos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/logros-reconocimientos#deportivo') }}"><i class="fas fa-running text-success me-2"></i>7.2 Deportivos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/logros-reconocimientos#artistico') }}"><i class="fas fa-palette text-warning me-2"></i>7.3 Artísticos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/logros-reconocimientos#cientifico') }}"><i class="fas fa-flask text-danger me-2"></i>7.4 Científicos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/logros-reconocimientos#institucional') }}"><i class="fas fa-award text-secondary me-2"></i>7.5 Reconocimientos</a></li>
                        </ul>
                    </div>

                    <div class="nav-item dropdown cv-dropdown-hover">
                        <a class="nav-link dropdown-toggle text-white" href="{{ url('/galeria-institucional') }}" id="navbarGaleria">
                            Galería
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarGaleria">
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#fotos') }}"><i class="fas fa-camera text-primary me-2"></i>8.1 Fotografías</a></li>
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#videos') }}"><i class="fas fa-video text-success me-2"></i>8.2 Videos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#eventos') }}"><i class="fas fa-calendar-day text-warning me-2"></i>8.3 Eventos Cívicos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#actividades') }}"><i class="fas fa-chalkboard text-danger me-2"></i>8.4 Actividades</a></li>
                        </ul>
                    </div>



                    <a class="nav-link btn-login ms-lg-2 bg-warning text-dark border-warning fw-bold shadow-sm" id="btn-abrir-login" role="button" style="font-size: 0.85rem; padding: 8px 18px !important;">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <section class="cv-hero" id="cv-hero">
        <div class="cv-hero-bg">
            @if(isset($config) && $config->banner_inicial_url)
                <img src="{{ asset('storage/' . $config->banner_inicial_url) }}" alt="I.E. César Vallejo" id="hero-img">
            @else
                <img src="{{ asset('assets/carousel-3.jpg') }}" alt="I.E. César Vallejo" id="hero-img">
            @endif
        </div>
        <div class="cv-hero-overlay"></div>
       <div class="cv-hero-overlay"></div>
        <div class="cv-hero-content">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3 tracking-wider shadow-sm" style="font-size: 0.75rem;">¡Bienvenidos a nuestra Plataforma Digital!</span>
            
            <h1 id="hero-title" class="mb-3 text-white">
                {{ $config->hero_titulo ?? 'Formando el futuro con excelencia' }}
            </h1>
            
            <p id="hero-subtitle" class="mb-4 text-white-50 max-w-xl mx-auto">
                {{ $config->hero_subtitulo ?? 'formamos.' }}
            </p>
            
            <button type="button" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow" id="btn-hero-login">
                Acceder al Sistema <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    </section>

    <div class="container py-5" id="seccion-avisos-publicos">
        <div class="row g-4">
            
            {{-- Columna 1: Próximas Actividades --}}
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm border-top border-danger border-4 rounded-4 p-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger-subtle text-danger rounded-circle p-2 me-2"><i class="fas fa-calendar-day fs-5"></i></div>
                            <h5 class="fw-bold mb-0 text-dark">Próximas Actividades</h5>
                        </div>
                        <hr class="text-muted">
                        <ul class="list-group list-group-flush small overflow-auto" style="max-height: 380px; padding-right: 4px;">
                            @forelse($actividadesProximas as $act)
                                <li class="list-group-item px-0 bg-transparent py-3">
                                    <span class="badge bg-danger mb-1 text-uppercase">{{ $act->fecha->translatedFormat('d M') }}</span>
                                    <p class="fw-bold text-dark mb-0">{{ $act->titulo }}</p>
                                    <small class="text-muted">{{ $act->descripcion }}</small>
                                </li>
                            @empty
                                <li class="list-group-item px-0 bg-transparent py-5 text-center text-muted">
                                    <i class="fas fa-calendar-times fa-2x mb-2 text-danger opacity-50"></i>
                                    <p class="mb-0 small fw-semibold">No hay actividades programadas próximamente.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Columna 2: Avisos Oficiales --}}
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm border-top border-warning border-4 rounded-4 p-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-2 me-2"><i class="fas fa-bullhorn fs-5"></i></div>
                            <h5 class="fw-bold mb-0 text-dark">Avisos Oficiales</h5>
                        </div>
                        <hr class="text-muted">
                        <div id="lista-avisos-publicos" class="overflow-auto animate-fade-in" style="max-height: 380px; padding-right: 4px;">
                            @forelse($comunicados as $com)
                                <div class="cv-aviso-card mb-3 p-3">
                                    <h6 class="fw-bold text-dark mb-1">
                                        @if($com->archivo_pdf)
                                            <i class="fas fa-file-pdf text-danger me-1"></i>
                                        @else
                                            <i class="fas fa-file-alt text-primary me-1"></i>
                                        @endif
                                        {{ $com->titulo }}
                                    </h6>
                                    @if($com->contenido)
                                        <p class="mb-2 small text-muted">{{ $com->contenido }}</p>
                                    @endif
                                    @if($com->archivo_pdf)
                                        <a href="{{ asset('storage/' . $com->archivo_pdf) }}" target="_blank" class="btn btn-outline-success btn-xs rounded-pill fw-bold py-1 px-3 mb-2 shadow-sm" style="font-size: 0.75rem;">
                                            <i class="fas fa-download me-1"></i> Descargar PDF
                                        </a>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-primary fw-bold" style="font-size: 0.75rem;">
                                            <i class="fas fa-calendar-alt me-1"></i> {{ $com->fecha->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-bullhorn fa-2x mb-2 text-warning opacity-50"></i>
                                    <p class="mb-0 small fw-semibold">No hay comunicados oficiales en este momento.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna 3: Noticias Destacadas --}}
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm border-top border-primary border-4 rounded-4 p-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 me-2"><i class="fas fa-newspaper fs-5"></i></div>
                            <h5 class="fw-bold mb-0 text-dark">Noticias Destacadas</h5>
                        </div>
                        <hr class="text-muted">
                        
                        <div class="overflow-auto" style="max-height: 380px; padding-right: 4px;">
                            @forelse($noticias as $noticia)
                                <div class="card border-0 bg-transparent mb-4">
                                    <img src="{{ asset('storage/' . $noticia->imagen) }}" class="card-img-top rounded-3 shadow-sm mb-2" style="height: 150px; width: 100%; object-fit: cover;" alt="{{ $noticia->titulo }}">
                                    <span class="text-xs text-primary fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                                        <i class="far fa-calendar-alt me-1"></i> {{ $noticia->fecha->format('d/m/Y') }}
                                    </span>
                                    <h6 class="fw-bold text-dark mt-1 mb-1">{{ $noticia->titulo }}</h6>
                                    <p class="text-muted small mb-2" style="font-size: 0.8rem; line-height: 1.4">{{ Str::limit($noticia->contenido, 110) }}</p>
                                    <a href="#" class="text-primary small fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalNoticiaHome{{ $noticia->id }}">
                                        Leer noticia completa <i class="fas fa-chevron-right" style="font-size: 9px"></i>
                                    </a>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-newspaper fa-2x mb-2 text-primary opacity-50"></i>
                                    <p class="mb-0 small fw-semibold">No hay noticias publicadas en este momento.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Agenda Escolar y Boletines (9.3 & 9.4) --}}
    <section class="py-5 bg-light border-top border-bottom border-light-subtle">
        <div class="container py-2">
            <div class="row g-5">
                {{-- 9.3: Agenda Escolar --}}
                <div class="col-lg-7">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-2 me-2" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-calendar-alt fs-5"></i></div>
                        <h4 class="fw-bold mb-0 text-dark">Agenda Escolar de Actividades</h4>
                    </div>
                    
                    @if(count($agenda) > 0)
                        <div class="d-flex flex-column gap-3 overflow-auto" style="max-height: 480px; padding-right: 4px;">
                            @foreach($agenda as $act)
                                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 5px solid var(--cv-primary) !important;">
                                    <div class="row align-items-center">
                                        <div class="col-md-3 border-end text-center">
                                            <div class="small fw-bold text-uppercase text-secondary">Fecha</div>
                                            <div class="h5 fw-bold text-primary mb-0 mt-1">
                                                @if($act->fecha_inicio->equalTo($act->fecha_fin))
                                                    {{ $act->fecha_inicio->format('d/m') }}
                                                @else
                                                    {{ $act->fecha_inicio->format('d/m') }} - {{ $act->fecha_fin->format('d/m') }}
                                                @endif
                                            </div>
                                            <div class="small text-muted">{{ $act->fecha_inicio->format('Y') }}</div>
                                        </div>
                                        <div class="col-md-9 ps-md-4 mt-2 mt-md-0">
                                            <h6 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">{{ $act->titulo }}</h6>
                                            <span class="badge bg-light text-primary border rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.75rem;">
                                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $act->lugar }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted bg-white rounded-4 border border-light shadow-sm">
                            <i class="fas fa-calendar-times fs-2 mb-2 opacity-50 text-warning"></i>
                            <p class="mb-0 fw-semibold small">No hay actividades calendarizadas en este momento.</p>
                        </div>
                    @endif
                </div>
                
                {{-- 9.4: Boletines --}}
                <div class="col-lg-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger-subtle text-danger rounded-circle p-2 me-2" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-book-open fs-5"></i></div>
                        <h4 class="fw-bold mb-0 text-dark">Boletines Informativos</h4>
                    </div>
                    
                    @if(count($boletines) > 0)
                        <div class="row g-3 overflow-auto" style="max-height: 480px; padding-right: 4px;">
                            @foreach($boletines as $bol)
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-danger bg-opacity-10 text-danger rounded p-3">
                                                    <i class="fas fa-file-pdf fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">{{ $bol->titulo }}</h6>
                                                    <small class="text-muted"><i class="far fa-calendar me-1"></i>{{ $bol->mes_anio }}</small>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $bol->archivo_pdf) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3">
                                                <i class="fas fa-eye me-1"></i> Leer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted bg-white rounded-4 border border-light shadow-sm">
                            <i class="fas fa-folder-open fs-2 mb-2 opacity-50 text-danger"></i>
                            <p class="mb-0 fw-semibold small">No se tienen boletines publicados en este momento.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 text-white" style="background-color: var(--cv-primary-dark); border-top: 4px solid #ffc107;">
        <div class="container">
            <div class="row align-items-center g-3 text-center text-md-start">
                
                {{-- Izquierda: Redes sociales --}}
                <div class="col-md-3 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                    <span class="small opacity-90">Síguenos en nuestras redes:</span>
                    <a href="{{ $config->link_facebook ?? 'https://www.facebook.com' }}" target="_blank" class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 32px; height: 32px; background-color: #1877f2; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-facebook-f text-white" style="font-size: 0.85rem;"></i>
                    </a>
                    <a href="{{ $config->link_youtube ?? 'https://www.youtube.com' }}" target="_blank" class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 32px; height: 32px; background-color: #ff0000; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-youtube text-white" style="font-size: 0.85rem;"></i>
                    </a>
                    <a href="{{ $config->link_instagram ?? 'https://www.instagram.com' }}" target="_blank" class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 32px; height: 32px; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285AEB 90%); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-instagram text-white" style="font-size: 0.85rem;"></i>
                    </a>
                </div>

                {{-- Centro: Frase institucional --}}
                <div class="col-md-5 text-center">
                    <span class="fst-italic" style="font-family: Georgia, 'Times New Roman', serif; font-size: 1.05rem; letter-spacing: 0.3px;">
                        "{{ $config->frase_topbar ?? 'Formamos líderes con corazón vallejiano' }}"
                    </span>
                </div>

                {{-- Derecha: Ubicación y correo --}}
                <div class="col-md-4 text-md-end text-center">
                    <div class="d-flex flex-column gap-1 small align-items-md-end align-items-center">
                        @if(isset($config) && $config->link_maps)
                            <a href="{{ $config->link_maps }}" target="_blank" class="text-white text-decoration-none" style="transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                <i class="fas fa-map-marker-alt text-warning me-1"></i> {{ $config->direccion_texto ?? 'Amarilis, Huánuco, Perú' }}
                            </a>
                        @else
                            <span class="text-white opacity-90">
                                <i class="fas fa-map-marker-alt text-warning me-1"></i> {{ $config->direccion_texto ?? 'Amarilis, Huánuco, Perú' }}
                            </span>
                        @endif

                        <a href="mailto:{{ $config->correo_contacto ?? 'contacto@cesarvallejo.edu.pe' }}" class="text-white text-decoration-none" style="transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                            <i class="fas fa-envelope text-warning me-1"></i> {{ $config->correo_contacto ?? 'contacto@cesarvallejo.edu.pe' }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="copyright-cesarvallejo" style="background-color: #011e3c; padding: 15px; text-align: center; color: rgba(255,255,255,.9);">
        <p class="mb-0 small">Copyright &copy; {{ date('Y') }} I.E. César Vallejo — Todos los derechos reservados.</p>
    </footer>

    {{-- ===== MODAL LOGIN ===== --}}
    <div class="cv-overlay-auth" id="modal-auth">
        <div class="cv-auth-box">
            <div class="cv-auth-header text-center">
                <button type="button" class="cv-auth-close" id="btn-cerrar-auth" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
                <div class="cv-auth-logo"><i class="fas fa-graduation-cap"></i></div>
                <h5 class="mb-1">Acceso al sistema</h5>
                <small class="opacity-75">I.E. César Vallejo</small>
            </div>
            <div class="cv-auth-body">

                {{-- Pestaña Login --}}
                <div class="cv-auth-pane active" id="pane-login">
                    <div class="cv-login-error" id="login-error"></div>
                    <form id="form-login">
                        @csrf
                        <div class="cv-field">
                            <label>Usuario / DNI</label>
                            <div class="cv-input-wrap">
                                <span class="cv-input-icon"><i class="fas fa-user"></i></span>
                                <input type="text" name="codigo_usuario" placeholder="Tu código de usuario" required>
                            </div>
                        </div>
                        <div class="cv-field">
                            <label>Contraseña</label>
                            <div class="cv-input-wrap">
                                <span class="cv-input-icon"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>
                        <button type="submit" class="cv-btn-submit" id="btn-login-submit">
                            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modalAuth = document.getElementById('modal-auth');

    function abrirModalAuth() { modalAuth.classList.add('activo'); }
    function cerrarModalAuth() { modalAuth.classList.remove('activo'); }


    modalAuth.addEventListener('click', e => { if (e.target === modalAuth) cerrarModalAuth(); });

    /* ===== Navbar background en scroll ===== */
    const navbarWrap = document.getElementById('cv-navbar-wrap');
    window.addEventListener('scroll', () => {
        navbarWrap?.classList.toggle('scrolled', window.scrollY > 60);
    });

    document.getElementById('btn-abrir-login').addEventListener('click', e => { e.preventDefault(); abrirModalAuth(); });
    document.getElementById('btn-hero-login')?.addEventListener('click', () => abrirModalAuth());
    document.getElementById('btn-cerrar-auth').addEventListener('click', cerrarModalAuth);

    // Abrir modal automáticamente si viene ?login=1 en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('login')) {
        abrirModalAuth();
    }

    /* ===== LOGIN ===== */
    document.getElementById('form-login').addEventListener('submit', async e => {
        e.preventDefault();
        const btn = document.getElementById('btn-login-submit');
        const errDiv = document.getElementById('login-error');
        const form = e.target;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Ingresando...';
        errDiv.classList.remove('visible');

        const body = new URLSearchParams({
            codigo_usuario: form.codigo_usuario.value,
            password:       form.password.value,
            _token:         CSRF,
        });

        try {
            const data = await fetch('{{ route("login.post") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
                body: body.toString()
            }).then(r => r.json());

            if (data.success) {
                window.location.href = data.redirect || '/'; 
            } else {
                errDiv.textContent = data.message || 'Código o contraseña incorrectos.';
                errDiv.classList.add('visible');
            }
        } catch (err) {
            errDiv.textContent = 'Error de conexión. Intenta de nuevo.';
            errDiv.classList.add('visible');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Ingresar';
        }
    });
    </script>

    {{-- MODALS DE NOTICIAS PÚBLICAS --}}
    @foreach($noticias as $noticia)
        <div class="modal fade" id="modalNoticiaHome{{ $noticia->id }}" tabindex="-1" aria-hidden="true" style="z-index: 10555;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <img src="{{ asset('storage/' . $noticia->imagen) }}" class="img-fluid rounded-3 mb-3 w-100" style="max-height: 400px; object-fit: cover;" alt="{{ $noticia->titulo }}">
                        <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill mb-2 small fw-semibold">
                            <i class="far fa-calendar-alt me-1"></i>{{ $noticia->fecha->format('d/m/Y') }}
                        </span>
                        <h3 class="fw-bold text-dark mb-3">{{ $noticia->titulo }}</h3>
                        <p class="text-muted" style="white-space: pre-line; line-height: 1.6;">{{ $noticia->contenido }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>