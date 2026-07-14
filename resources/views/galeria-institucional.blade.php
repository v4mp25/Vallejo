<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería Institucional - I.E. César Vallejo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --cv-primary: #0148A4; --cv-primary-dark: #023d7a; --cv-light-bg: #f8f9ff; --cv-shadow: 0 8px 32px rgba(1,72,164,0.12); }
        body { font-family: 'Roboto', sans-serif !important; background-color: #f4f6f9; color: #333; }
        .cv-navbar-wrap { position: relative; background-color: var(--cv-primary); }
        .cv-navbar-wrap .nav-link { color: #fff !important; font-weight: 500; font-size: 0.9rem; padding: 10px 12px !important; }
        @media (min-width: 1200px) and (max-width: 1399px) {
            .cv-navbar-wrap .nav-link { font-size: 0.78rem !important; padding: 10px 5px !important; }
            .navbar-brand span.fw-bold { font-size: 1.05rem !important; }
            .navbar-brand span:last-child { font-size: 0.55rem !important; }
            .navbar-brand img { height: 40px !important; }
        }
        @media (min-width: 1200px) { .cv-dropdown-hover:hover > .dropdown-menu { display: block; margin-top: 0; animation: fadeIn .3s ease; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .cv-dropdown-hover .dropdown-menu { border-radius: 12px; padding: 10px 0; }
        .cv-dropdown-hover .dropdown-item { padding: 10px 20px; font-weight: 500; color: #444; transition: all 0.2s; }
        .cv-dropdown-hover .dropdown-item:hover { background-color: var(--cv-light-bg); color: var(--cv-primary); padding-left: 25px; }
        .btn-login { background: var(--cv-primary) !important; color: #fff !important; border-radius: 50px !important; padding: 10px 26px !important; font-weight: 600 !important; transition: transform .2s, box-shadow .2s; }
        .header-institucion { background: linear-gradient(to right, var(--cv-primary-dark), var(--cv-primary)); color: white; padding: 60px 0; text-align: center; }
        .nav-pills-custom .nav-link { color: #666; font-weight: 500; border-radius: 10px; padding: 15px 20px; text-align: left; margin-bottom: 8px; transition: all 0.3s; background: #fff; border: 1px solid #eee; width: 100%; }
        .nav-pills-custom .nav-link:hover { background: var(--cv-light-bg); color: var(--cv-primary); }
        .nav-pills-custom .nav-link.active { background: var(--cv-primary); color: #fff; box-shadow: var(--cv-shadow); border-color: var(--cv-primary); }
        .tab-content-box { background: #fff; border-radius: 16px; padding: 40px; box-shadow: var(--cv-shadow); min-height: 500px; }
        .copyright-cesarvallejo { background: var(--cv-primary); color: rgba(255,255,255,.9); text-align: center; padding: 22px; margin-top: 40px;}
        .lh-lg { line-height: 1.9; }
        .cv-gallery-card { transition: transform 0.25s ease, box-shadow 0.25s ease; cursor: pointer; border: 1px solid #f1f1f1 !important; }
        .cv-gallery-card:hover { transform: scale(1.02); box-shadow: 0 10px 25px rgba(1,72,164,0.15) !important; }
    </style>
</head>
<body>

    <div class="py-1" style="background-color: var(--cv-primary-dark); font-size: 0.85rem;">
        <div class="container d-flex justify-content-end text-white">
            <span class="fw-semibold fst-italic">
                <i class="fas fa-quote-left text-warning me-1 small"></i>
                {{ $config->frase_topbar ?? 'Formamos líderes con corazón vallejiano' }}
                <i class="fas fa-quote-right text-warning ms-1 small"></i>
            </span>
        </div>
    </div>

    <div class="cv-navbar-wrap" style="position: relative; background-color: var(--cv-primary);">
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
                    <a class="nav-link text-white" href="{{ url('/') }}">Inicio</a>
                    
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
                        <a class="nav-link dropdown-toggle text-white fw-bold" href="{{ url('/galeria-institucional') }}" id="navbarGaleria" style="color: #ffc107 !important;">
                            Galería
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarGaleria">
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#fotos') }}"><i class="fas fa-camera text-primary me-2"></i>8.1 Fotografías</a></li>
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#videos') }}"><i class="fas fa-video text-success me-2"></i>8.2 Videos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#eventos') }}"><i class="fas fa-calendar-day text-warning me-2"></i>8.3 Eventos Cívicos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/galeria-institucional#actividades') }}"><i class="fas fa-chalkboard text-danger me-2"></i>8.4 Actividades</a></li>
                        </ul>
                    </div>



                    <a class="nav-link btn-login ms-lg-2 bg-warning text-dark border-warning fw-bold shadow-sm" href="{{ url('/login') }}" style="font-size: 0.85rem; padding: 8px 18px !important; border-radius: 50px !important;">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <header class="header-institucion">
        <div class="container">
            <h1 class="fw-bold mb-3"><i class="fas fa-images me-2"></i> Galería Institucional</h1>
            <p class="lead opacity-75">Nuestros recuerdos, desfiles, videos y actividades académicas en imágenes.</p>
        </div>
    </header>

    <div class="container py-5">
        <div class="row g-4">

            {{-- Sidebar Navegación Interna --}}
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#fotos" type="button">
                        <i class="fas fa-camera me-2"></i> 8.1 Fotografías
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#videos" type="button">
                        <i class="fas fa-video me-2"></i> 8.2 Videos
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#eventos" type="button">
                        <i class="fas fa-calendar-day me-2"></i> 8.3 Eventos Cívicos
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#actividades" type="button">
                        <i class="fas fa-chalkboard me-2"></i> 8.4 Actividades
                    </button>
                </div>
            </div>

            {{-- Contenido de Pestañas --}}
            <div class="col-lg-9">
                <div class="tab-content tab-content-box" id="v-pills-tabContent">

                    {{-- 8.1 Fotos --}}
                    <div class="tab-pane fade show active" id="fotos" role="tabpanel">
                        <h3 class="fw-bold text-dark border-bottom pb-2 mb-4" style="border-bottom-color: var(--cv-light-bg) !important;">
                            <i class="fas fa-camera me-2 text-primary"></i> Galería de Fotos
                        </h3>

                        @if(count($fotos) > 0)
                            <div class="row g-4">
                                @foreach($fotos as $foto)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden cv-gallery-card h-100" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $foto->id }}">
                                            <img src="{{ asset('storage/' . $foto->imagen) }}" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;" alt="{{ $foto->titulo }}">
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $foto->titulo }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-images fs-1 mb-3 text-warning opacity-50"></i>
                                <p class="mb-0 fw-semibold">No se tienen fotografías cargadas aún.</p>
                            </div>
                        @endif
                    </div>

                    {{-- 8.2 Videos --}}
                    <div class="tab-pane fade" id="videos" role="tabpanel">
                        <h3 class="fw-bold text-dark border-bottom pb-2 mb-4" style="border-bottom-color: var(--cv-light-bg) !important;">
                            <i class="fas fa-video me-2 text-success"></i> Galería de Videos
                        </h3>

                        @if(count($videos) > 0)
                            <div class="row g-4">
                                @foreach($videos as $video)
                                    @php
                                        $video_id = '';
                                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video->url_video, $match)) {
                                            $video_id = $match[1];
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="border: 1px solid #f1f1f1 !important;">
                                            @if($video_id)
                                                <div class="ratio ratio-16x9">
                                                    <iframe src="https://www.youtube.com/embed/{{ $video_id }}" title="{{ $video->titulo }}" allowfullscreen></iframe>
                                                </div>
                                            @else
                                                <div class="bg-dark text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                                                    <i class="fab fa-youtube fs-1 text-danger"></i>
                                                </div>
                                            @endif
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold text-dark mb-0">{{ $video->titulo }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fab fa-youtube fs-1 mb-3 text-danger opacity-50"></i>
                                <p class="mb-0 fw-semibold">No se tienen videos registrados actualmente.</p>
                            </div>
                        @endif
                    </div>

                    {{-- 8.3 Eventos --}}
                    <div class="tab-pane fade" id="eventos" role="tabpanel">
                        <h3 class="fw-bold text-dark border-bottom pb-2 mb-4" style="border-bottom-color: var(--cv-light-bg) !important;">
                            <i class="fas fa-calendar-day me-2 text-warning"></i> Eventos Cívicos e Institucionales
                        </h3>

                        @if(count($eventos) > 0)
                            <div class="row g-4">
                                @foreach($eventos as $evento)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 cv-gallery-card" data-bs-toggle="modal" data-bs-target="#modalEvento{{ $evento->id }}">
                                            <img src="{{ asset('storage/' . $evento->imagen) }}" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;" alt="{{ $evento->titulo }}">
                                            <div class="card-body p-4">
                                                <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill mb-2 small fw-semibold">
                                                    <i class="far fa-calendar-alt me-1"></i>{{ $evento->fecha->format('d/m/Y') }}
                                                </span>
                                                <h5 class="fw-bold text-dark mb-2">{{ $evento->titulo }}</h5>
                                                <p class="text-muted mb-0 small text-truncate">{{ $evento->descripcion }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-alt fs-1 mb-3 text-warning opacity-50"></i>
                                <p class="mb-0 fw-semibold">No hay registros de eventos cívicos todavía.</p>
                            </div>
                        @endif
                    </div>

                    {{-- 8.4 Actividades --}}
                    <div class="tab-pane fade" id="actividades" role="tabpanel">
                        <h3 class="fw-bold text-dark border-bottom pb-2 mb-4" style="border-bottom-color: var(--cv-light-bg) !important;">
                            <i class="fas fa-chalkboard-teacher me-2 text-danger"></i> Actividades Pedagógicas
                        </h3>

                        @if(count($actividades) > 0)
                            <div class="row g-4">
                                @foreach($actividades as $act)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 cv-gallery-card" data-bs-toggle="modal" data-bs-target="#modalActividad{{ $act->id }}">
                                            <img src="{{ asset('storage/' . $act->imagen) }}" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;" alt="{{ $act->titulo }}">
                                            <div class="card-body p-4">
                                                <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill mb-2 small fw-semibold">
                                                    <i class="far fa-calendar-alt me-1"></i>{{ $act->fecha->format('d/m/Y') }}
                                                </span>
                                                <h5 class="fw-bold text-dark mb-2">{{ $act->titulo }}</h5>
                                                <p class="text-muted mb-0 small text-truncate">{{ $act->descripcion }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-laptop-code fs-1 mb-3 text-danger opacity-50"></i>
                                <p class="mb-0 fw-semibold">No hay actividades pedagógicas registradas aún.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modales Lightbox Fotos --}}
    @foreach($fotos as $foto)
        <div class="modal fade" id="modalFoto{{ $foto->id }}" tabindex="-1" aria-labelledby="modalFotoLabel{{ $foto->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0 text-center">
                        <img src="{{ asset('storage/' . $foto->imagen) }}" class="img-fluid rounded-3 shadow-xs" style="max-height: 500px;" alt="{{ $foto->titulo }}">
                        <h5 class="fw-bold text-dark mt-3 mb-0" id="modalFotoLabel{{ $foto->id }}">{{ $foto->titulo }}</h5>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modales Lightbox Eventos --}}
    @foreach($eventos as $evento)
        <div class="modal fade" id="modalEvento{{ $evento->id }}" tabindex="-1" aria-labelledby="modalEventoLabel{{ $evento->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $evento->imagen) }}" class="img-fluid rounded-3 shadow-xs" style="max-height: 480px;" alt="{{ $evento->titulo }}">
                        </div>
                        <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill mb-2 small fw-semibold">
                            <i class="far fa-calendar-alt me-1"></i>{{ $evento->fecha->format('d/m/Y') }}
                        </span>
                        <h4 class="fw-bold text-dark mb-3" id="modalEventoLabel{{ $evento->id }}">{{ $evento->titulo }}</h4>
                        <p class="text-muted lh-lg" style="white-space: pre-line;">{{ $evento->descripcion }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modales Lightbox Actividades --}}
    @foreach($actividades as $act)
        <div class="modal fade" id="modalActividad{{ $act->id }}" tabindex="-1" aria-labelledby="modalActividadLabel{{ $act->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $act->imagen) }}" class="img-fluid rounded-3 shadow-xs" style="max-height: 480px;" alt="{{ $act->titulo }}">
                        </div>
                        <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill mb-2 small fw-semibold">
                            <i class="far fa-calendar-alt me-1"></i>{{ $act->fecha->format('d/m/Y') }}
                        </span>
                        <h4 class="fw-bold text-dark mb-3" id="modalActividadLabel{{ $act->id }}">{{ $act->titulo }}</h4>
                        <p class="text-muted lh-lg" style="white-space: pre-line;">{{ $act->descripcion }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <footer class="copyright-cesarvallejo">
        <p class="mb-0">Copyright &copy; {{ date('Y') }} I.E. César Vallejo</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function activarPestanaPorHash() {
                let hash = window.location.hash.substring(1);
                if (hash) {
                    let boton = document.querySelector(`button[data-bs-target="#${hash}"]`);
                    if (boton) {
                        new bootstrap.Tab(boton).show();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            }
            activarPestanaPorHash();
            window.addEventListener('hashchange', activarPestanaPorHash);
        });
    </script>
</body>
</html>
