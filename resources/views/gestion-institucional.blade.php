<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Institucional - I.E. César Vallejo</title>
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
                        <a class="nav-link dropdown-toggle text-white fw-bold" style="color: #ffc107 !important;" href="{{ url('/gestion-institucional') }}" id="navbarGestion">
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



                    <a class="nav-link btn-login ms-lg-2 bg-warning text-dark border-warning fw-bold shadow-sm" href="{{ url('/login') }}" style="font-size: 0.85rem; padding: 8px 18px !important; border-radius: 50px !important;">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <header class="header-institucion">
        <div class="container">
            <h1 class="fw-bold mb-3"><i class="fas fa-folder-open me-2"></i> Gestión Institucional</h1>
            <p class="lead opacity-75">Conoce la organización, normativas y personal de nuestra comunidad escolar.</p>
        </div>
    </header>

    <div class="container py-5">
        <div class="row g-4">

            {{-- Sidebar Navegación Interna --}}
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#personal" type="button">
                        <i class="fas fa-users me-2"></i> 3.1 Personal Institucional
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#organigrama" type="button">
                        <i class="fas fa-sitemap me-2"></i> 3.2 Organigrama
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#organos" type="button">
                        <i class="fas fa-handshake me-2"></i> 3.3 Órganos de Participación
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#instrumentos" type="button">
                        <i class="fas fa-file-pdf me-2"></i> 3.4 Instrumentos de Gestión
                    </button>
                </div>
            </div>

            {{-- Contenido de Pestañas --}}
            <div class="col-lg-9">
                <div class="tab-content tab-content-box" id="v-pills-tabContent">

                    {{-- 3.1 Personal Institucional --}}
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Personal Institucional</h3>
                        
                        @php
                            $directivos = $personal->where('categoria', 'directivo');
                            $administrativos = $personal->where('categoria', 'administrativo');
                        @endphp

                        {{-- Sección Directivos --}}
                        <div class="mt-4 mb-5">
                            <h5 class="fw-bold text-dark border-start border-4 border-primary ps-2 mb-4">
                                <i class="fas fa-user-tie text-primary me-2"></i> Equipo Directivo y Jerárquico
                            </h5>
                            @if(count($directivos) > 0)
                                <div class="row g-4">
                                    @foreach($directivos as $dir)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100" style="transition: transform .2s; border: 1px solid #f1f1f1 !important;">
                                                <div class="position-relative d-inline-block mx-auto mb-3">
                                                    @if($dir->foto)
                                                        <img src="{{ asset('storage/' . $dir->foto) }}" alt="{{ $dir->nombres }}" class="rounded-circle border border-3 border-primary shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle border border-3 border-primary bg-light d-flex align-items-center justify-content-center mx-auto text-primary shadow-sm" style="width: 150px; height: 150px; font-size: 3.8rem;">
                                                            <i class="fas fa-user-tie"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">{{ $dir->nombres }} {{ $dir->apellidos }}</h6>
                                                <span class="badge bg-light text-primary px-3 py-2 rounded-pill fw-semibold border small d-inline-block mt-2">{{ $dir->cargo }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 bg-light rounded-3 text-muted">
                                    <i class="fas fa-user-shield fs-2 mb-2 opacity-50"></i>
                                    <p class="mb-0">El equipo directivo estará listado próximamente.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Sección Administrativos --}}
                        <div class="mt-4">
                            <h5 class="fw-bold text-dark border-start border-4 border-secondary ps-2 mb-4">
                                <i class="fas fa-user-cog text-secondary me-2"></i> Personal Administrativo y de Apoyo
                            </h5>
                            @if(count($administrativos) > 0)
                                <div class="row g-4">
                                    @foreach($administrativos as $adm)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100" style="transition: transform .2s; border: 1px solid #f1f1f1 !important;">
                                                <div class="position-relative d-inline-block mx-auto mb-3">
                                                    @if($adm->foto)
                                                        <img src="{{ asset('storage/' . $adm->foto) }}" alt="{{ $adm->nombres }}" class="rounded-circle border border-3 border-secondary shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle border border-3 border-secondary bg-light d-flex align-items-center justify-content-center mx-auto text-secondary shadow-sm" style="width: 150px; height: 150px; font-size: 3.8rem;">
                                                            <i class="fas fa-user-cog"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">{{ $adm->nombres }} {{ $adm->apellidos }}</h6>
                                                <span class="badge bg-light text-secondary px-3 py-2 rounded-pill fw-semibold border small d-inline-block mt-2">{{ $adm->cargo }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 bg-light rounded-3 text-muted">
                                    <i class="fas fa-user-cog fs-2 mb-2 opacity-50"></i>
                                    <p class="mb-0">El personal administrativo estará listado próximamente.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 3.2 Organigrama --}}
                    <div class="tab-pane fade" id="organigrama" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Organigrama Institucional</h3>
                        <div class="text-center py-4 mt-4">
                            @if($info->organigrama_imagen)
                                <div class="p-3 border rounded-4 bg-light shadow-sm d-inline-block">
                                    <a href="{{ asset('storage/' . $info->organigrama_imagen) }}" target="_blank" title="Ver organigrama ampliado">
                                        <img src="{{ asset('storage/' . $info->organigrama_imagen) }}" alt="Organigrama Institucional" class="img-fluid rounded-3" style="max-height: 550px;">
                                    </a>
                                    <small class="text-muted d-block mt-3"><i class="fas fa-search-plus me-1"></i> Haz clic en la imagen para ampliar en una pestaña nueva.</small>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-sitemap fs-1 mb-3 text-warning opacity-50"></i>
                                    <p class="mb-0 fw-semibold">El organigrama no está disponible actualmente.</p>
                                    <small class="text-muted d-block mt-1">La dirección está actualizando la estructura organizacional del presente año.</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 3.3 Órganos de Participación (CONEI y APAFA) --}}
                    <div class="tab-pane fade" id="organos" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Órganos de Participación</h3>
                        
                        <div class="row g-4 mt-3">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 border-start border-4 border-primary">
                                    <h4 class="fw-bold mb-3" style="color: var(--cv-primary);"><i class="fas fa-gavel me-2"></i> CONEI</h4>
                                    <p class="text-muted lh-lg mb-3" style="white-space: pre-line">{{ $info->conei_descripcion ?? 'El Consejo Educativo Institucional (CONEI) es el órgano de participación, concertación y vigilancia ciudadana de la institución educativa. Está conformado por la Directora, representantes de los docentes, estudiantes, personal administrativo y padres de familia, colaborando en la gestión de calidad, transparencia y convivencia democrática.' }}</p>
                                    @if(count($coneiDocs) > 0)
                                        <hr class="text-muted my-3">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-folder-open text-primary me-1"></i> Documentos y Enlaces Oficiales:</h6>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($coneiDocs as $doc)
                                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                                                    <span class="small fw-semibold text-dark text-truncate me-2" title="{{ $doc->titulo }}">{{ $doc->titulo }}</span>
                                                    <div class="d-flex gap-1">
                                                        @if($doc->archivo_pdf)
                                                            <a href="{{ asset('storage/' . $doc->archivo_pdf) }}" target="_blank" class="btn btn-sm btn-outline-danger px-2.5 py-0.5 rounded-pill" style="font-size: 0.75rem; font-weight: 600;">
                                                                <i class="fas fa-file-pdf"></i> PDF
                                                            </a>
                                                        @endif
                                                        @if($doc->link)
                                                            <a href="{{ $doc->link }}" target="_blank" class="btn btn-sm btn-outline-primary px-2.5 py-0.5 rounded-pill" style="font-size: 0.75rem; font-weight: 600;">
                                                                <i class="fas fa-external-link-alt"></i> Link
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 border-start border-4 border-warning">
                                    <h4 class="fw-bold mb-3" style="color: #ff9800;"><i class="fas fa-handshake me-2"></i> APAFA</h4>
                                    <p class="text-muted lh-lg mb-3" style="white-space: pre-line">{{ $info->apafa_descripcion ?? 'La Asociación de Padres de Familia (APAFA) es una organización que canaliza la participación de los padres de familia, tutores y curadores en el proceso educativo de sus hijos. Trabaja activamente en la mejora de la infraestructura escolar y en el apoyo de las actividades académicas y recreativas del colegio.' }}</p>
                                    @if(count($apafaDocs) > 0)
                                        <hr class="text-muted my-3">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-folder-open text-warning me-1"></i> Documentos y Enlaces Oficiales:</h6>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($apafaDocs as $doc)
                                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                                                    <span class="small fw-semibold text-dark text-truncate me-2" title="{{ $doc->titulo }}">{{ $doc->titulo }}</span>
                                                    <div class="d-flex gap-1">
                                                        @if($doc->archivo_pdf)
                                                            <a href="{{ asset('storage/' . $doc->archivo_pdf) }}" target="_blank" class="btn btn-sm btn-outline-danger px-2.5 py-0.5 rounded-pill" style="font-size: 0.75rem; font-weight: 600;">
                                                                <i class="fas fa-file-pdf"></i> PDF
                                                            </a>
                                                        @endif
                                                        @if($doc->link)
                                                            <a href="{{ $doc->link }}" target="_blank" class="btn btn-sm btn-outline-primary px-2.5 py-0.5 rounded-pill" style="font-size: 0.75rem; font-weight: 600;">
                                                                <i class="fas fa-external-link-alt"></i> Link
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3.4 Instrumentos de Gestión --}}
                    <div class="tab-pane fade" id="instrumentos" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Instrumentos de Gestión</h3>
                        
                        <div class="mt-4">
                            @if(count($documentos) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover border rounded-4 overflow-hidden">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Documento</th>
                                                <th>Tipo</th>
                                                <th class="text-center pe-4">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($documentos as $doc)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center text-danger" style="width: 40px; height: 40px; min-width: 40px;">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </div>
                                                            <div>
                                                                <span class="fw-semibold text-dark d-block">{{ $doc->titulo }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">{{ $doc->tipo }}</span>
                                                    </td>
                                                    <td class="text-center pe-4">
                                                        <a href="{{ asset('storage/' . $doc->archivo_pdf) }}" target="_blank" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm btn-sm">
                                                            <i class="fas fa-eye me-1"></i> Visualizar PDF
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-file-pdf fs-1 mb-3 text-danger opacity-50"></i>
                                    <p class="mb-0 fw-semibold">Los documentos de gestión no están disponibles para descarga en este momento.</p>
                                    <small class="text-muted d-block mt-1">Secretaría está digitalizando los instrumentos para su publicación.</small>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

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
