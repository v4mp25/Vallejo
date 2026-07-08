<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Educativo - I.E. César Vallejo</title>
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
        @media (min-width: 992px) { .cv-dropdown-hover:hover > .dropdown-menu { display: block; margin-top: 0; animation: fadeIn .3s ease; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .cv-dropdown-hover .dropdown-menu { border-radius: 12px; padding: 10px 0; }
        .cv-dropdown-hover .dropdown-item { padding: 10px 20px; font-weight: 500; color: #444; transition: all 0.2s; }
        .cv-dropdown-hover .dropdown-item:hover { background-color: var(--cv-light-bg); color: var(--cv-primary); padding-left: 25px; }
        .btn-login { background: var(--cv-primary) !important; color: #fff !important; border-radius: 50px !important; padding: 10px 26px !important; font-weight: 600 !important; transition: transform .2s, box-shadow .2s; }
        .header-institucion { background: linear-gradient(to right, var(--cv-primary-dark), var(--cv-primary)); color: white; padding: 60px 0; text-align: center; }
        .nav-pills-custom .nav-link { color: #666; font-weight: 500; border-radius: 10px; padding: 15px 20px; text-align: left; margin-bottom: 8px; transition: all 0.3s; background: #fff; border: 1px solid #eee; width: 100%; }
        .cv-hover-project { transition: transform 0.25s ease, box-shadow 0.25s ease; cursor: pointer; border: 1px solid #f1f1f1 !important; }
        .cv-hover-project:hover { transform: scale(1.02); box-shadow: 0 10px 25px rgba(1,72,164,0.15) !important; }
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
        <nav class="navbar navbar-expand-lg navbar-dark px-4 px-lg-5 py-2">
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
                            Nuestra Institución
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
                            Gestión Institucional
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarGestion">
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#personal') }}"><i class="fas fa-users text-primary me-2"></i>3.1 Personal Institucional</a></li>
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#organigrama') }}"><i class="fas fa-sitemap text-success me-2"></i>3.2 Organigrama</a></li>
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#organos') }}"><i class="fas fa-handshake text-warning me-2"></i>3.3 Órganos de Participación</a></li>
                            <li><a class="dropdown-item" href="{{ url('/gestion-institucional#instrumentos') }}"><i class="fas fa-file-pdf text-danger me-2"></i>3.4 Instrumentos de Gestión</a></li>
                        </ul>
                    </div>

                    <a class="nav-link text-white fw-bold" href="{{ url('/servicio-educativo') }}" style="color: #ffc107 !important;">Servicio Educativo</a>
                    <a class="nav-link text-white" href="{{ url('/comunidad-educativa') }}">Comunidad Educativa</a>
                    <a class="nav-link text-white" href="{{ url('/logros-reconocimientos') }}">Logros y Reconocimientos</a>
                    <a class="nav-link text-white" href="{{ url('/galeria-institucional') }}">Galería Institucional</a>
                    <a class="nav-link text-white" href="{{ url('/noticias-comunicados') }}">Noticias y Comunicados</a>

                    <a class="nav-link btn-login ms-lg-2 bg-warning text-dark border-warning fw-bold shadow-sm" href="{{ url('/login') }}" style="font-size: 0.85rem; padding: 8px 18px !important; border-radius: 50px !important;">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <header class="header-institucion">
        <div class="container">
            <h1 class="fw-bold mb-3"><i class="fas fa-graduation-cap me-2"></i> Servicio Educativo</h1>
            <p class="lead opacity-75">Nuestra propuesta curricular, enfoque pedagógico y proyectos estudiantiles.</p>
        </div>
    </header>

    <div class="container py-5">
        <div class="row g-4">

            {{-- Sidebar Navegación Interna --}}
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#areas" type="button">
                        <i class="fas fa-book me-2"></i> 4.1 Áreas Curriculares
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#propuesta" type="button">
                        <i class="fas fa-chalkboard-teacher me-2"></i> 4.2 Propuesta Pedagógica
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#proyectos" type="button">
                        <i class="fas fa-rocket me-2"></i> 4.3 Proyectos Bandera
                    </button>
                </div>
            </div>

            {{-- Contenido de Pestañas --}}
            <div class="col-lg-9">
                <div class="tab-content tab-content-box" id="v-pills-tabContent">

                    {{-- 4.1 Áreas Curriculares --}}
                    <div class="tab-pane fade show active" id="areas" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Áreas Curriculares</h3>
                        
                        @if(count($areas) > 0)
                            <div class="row g-4 mt-3">
                                @foreach($areas as $area)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="border: 1px solid #f1f1f1 !important;">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="rounded bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px; font-size: 1.6rem;">
                                                    <i class="{{ $area->icono }}"></i>
                                                </div>
                                                <h5 class="fw-bold text-dark mb-0">{{ $area->nombre }}</h5>
                                            </div>
                                            <p class="text-muted mb-0 small lh-lg" style="white-space: pre-line;">{{ $area->descripcion }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-book-open fs-1 mb-3 text-warning opacity-50"></i>
                                <p class="mb-0 fw-semibold">Las áreas curriculares no están cargadas aún.</p>
                                <small class="text-muted d-block mt-1">El personal de coordinación pedagógica está actualizando la malla curricular.</small>
                            </div>
                        @endif
                    </div>

                    {{-- 4.2 Propuesta Pedagógica --}}
                    <div class="tab-pane fade" id="propuesta" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Propuesta Pedagógica</h3>
                        
                        <div class="mt-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-primary">
                                        <h5 class="fw-bold text-dark mb-2"><i class="fas fa-school me-2 text-primary"></i> Nivel Secundaria</h5>
                                        <p class="text-muted mb-0 lh-lg" style="white-space: pre-line;">{{ $info->nivel_secundaria ?? 'Ofrecemos una educación de nivel secundaria orientada al desarrollo integral de los estudiantes de primero a quinto grado, preparándolos para la educación superior y el ejercicio de una ciudadanía activa.' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-success h-100">
                                        <h5 class="fw-bold text-dark mb-2"><i class="fas fa-bullseye me-2 text-success"></i> Enfoque por Competencias</h5>
                                        <p class="text-muted mb-0 lh-lg" style="white-space: pre-line;">{{ $info->enfoque_competencias ?? 'Implementamos el Currículo Nacional de Educación Básica (CNEB) bajo el enfoque por competencias, desarrollando habilidades analíticas, críticas y creativas en entornos colaborativos.' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-warning h-100">
                                        <h5 class="fw-bold text-dark mb-2"><i class="fas fa-laptop-code me-2 text-warning"></i> Innovación Pedagógica (AIP)</h5>
                                        <p class="text-muted mb-0 lh-lg" style="white-space: pre-line;">{{ $info->innovacion_pedagogica ?? 'Contamos con el Aula de Innovación Pedagógica (AIP) para integrar las Tecnologías de la Información y Comunicación (TIC) dentro del proceso de aprendizaje de todas las materias.' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-danger h-100">
                                        <h5 class="fw-bold text-dark mb-2"><i class="fas fa-heart me-2 text-danger"></i> Tutoría y Orientación</h5>
                                        <p class="text-muted mb-0 lh-lg" style="white-space: pre-line;">{{ $info->tutoria_orientacion ?? 'Brindamos un acompañamiento socioafectivo y cognitivo permanente de los estudiantes para potenciar su crecimiento personal y la convivencia armoniosa.' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-info h-100">
                                        <h5 class="fw-bold text-dark mb-2"><i class="fas fa-universal-access me-2 text-info"></i> Educación Inclusiva</h5>
                                        <p class="text-muted mb-0 lh-lg" style="white-space: pre-line;">{{ $info->educacion_inclusiva ?? 'Atendemos la diversidad de nuestra población estudiantil mediante adaptaciones curriculares, garantizando una educación de calidad para los alumnos con necesidades educativas especiales.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4.3 Proyectos Bandera --}}
                    <div class="tab-pane fade" id="proyectos" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Proyectos Bandera</h3>
                        
                        @if(count($proyectos) > 0)
                            <div class="row g-4 mt-3">
                                @foreach($proyectos as $p)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 cv-hover-project" data-bs-toggle="modal" data-bs-target="#modalProyecto{{ $p->id }}">
                                            <img src="{{ asset('storage/' . $p->imagen) }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;" alt="Proyecto Bandera">
                                            <div class="card-body p-4">
                                                <h5 class="fw-bold text-dark mb-2">{{ $p->titulo }}</h5>
                                                <p class="text-muted mb-0 small lh-lg" style="white-space: pre-line;">{{ $p->descripcion }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-rocket fs-1 mb-3 text-warning opacity-50"></i>
                                <p class="mb-0 fw-semibold">No hay proyectos bandera registrados actualmente.</p>
                                <small class="text-muted d-block mt-1">Nuestros estudiantes están preparando los proyectos de innovación escolar de este año.</small>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <footer class="copyright-cesarvallejo">
        <p class="mb-0">Copyright &copy; {{ date('Y') }} I.E. César Vallejo</p>
    </footer>

    @if(count($proyectos) > 0)
        @foreach($proyectos as $p)
            <!-- Modal Proyecto {{ $p->id }} -->
            <div class="modal fade" id="modalProyecto{{ $p->id }}" tabindex="-1" aria-labelledby="modalProyectoLabel{{ $p->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-0 pb-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4 pb-4 pt-0">
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . $p->imagen) }}" class="img-fluid rounded-3 shadow-xs" style="max-height: 480px;" alt="{{ $p->titulo }}">
                            </div>
                            <h4 class="fw-bold text-dark mb-3" id="modalProyectoLabel{{ $p->id }}">{{ $p->titulo }}</h4>
                            <p class="text-muted lh-lg" style="white-space: pre-line;">{{ $p->descripcion }}</p>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

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
