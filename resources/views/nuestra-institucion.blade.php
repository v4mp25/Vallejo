<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestra Institución - I.E. César Vallejo</title>
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
        .nav-pills-custom .nav-link { color: #666; font-weight: 500; border-radius: 10px; padding: 15px 20px; text-align: left; margin-bottom: 8px; transition: all 0.3s; background: #fff; border: 1px solid #eee; }
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
                    <a class="nav-link text-white fw-bold" href="{{ url('/') }}">Inicio</a>
                    
                    <div class="nav-item dropdown cv-dropdown-hover">
                        <a class="nav-link dropdown-toggle text-white fw-bold" style="color: #fff !important;" href="{{ url('/nuestra-institucion') }}" id="navbarInstitucion">
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



                    <a class="nav-link btn-login ms-lg-2 bg-warning text-dark border-warning fw-bold shadow-sm" href="{{ url('/login') }}" style="font-size: 0.85rem; padding: 8px 18px !important; border-radius: 50px !important;">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <header class="header-institucion">
        <div class="container">
            <h1 class="fw-bold mb-3"><i class="fas fa-school me-2"></i> Identidad Institucional</h1>
            <p class="lead opacity-75">Conoce la historia, pilares y espacios que sostienen nuestro modelo educativo.</p>
        </div>
    </header>

    <div class="container py-5">
        <div class="row g-4">

            <div class="col-lg-3">
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#resena" type="button">
                        <i class="fas fa-history me-2"></i> 2.1 Reseña Histórica
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#identidad" type="button">
                        <i class="fas fa-bullseye me-2"></i> 2.2 Identidad e Ideario
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#simbolos" type="button">
                        <i class="fas fa-flag me-2"></i> 2.3 Símbolos Escolares
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#infraestructura" type="button">
                        <i class="fas fa-building me-2"></i> 2.4 Infraestructura
                    </button>
                    @if(($info->linea_tiempo ?? null) && count($info->linea_tiempo) > 0)
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#timeline" type="button">
                        <i class="fas fa-clock me-2"></i> 2.5 Línea de Tiempo
                    </button>
                    @endif
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content tab-content-box" id="v-pills-tabContent">

                    {{-- 2.1 Reseña Histórica --}}
                    <div class="tab-pane fade show active" id="resena" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Reseña Histórica</h3>
                        @if($info->resena_historica ?? null)
                            <p class="text-muted mt-4 lh-lg" style="white-space: pre-line">{{ $info->resena_historica }}</p>
                        @else
                            <p class="text-muted mt-4 lh-lg">La Institución Educativa "César Vallejo", ubicada en el pujante distrito de Amarilis, provincia de Huánuco, nació bajo el imperativo de brindar educación de alto nivel a las nuevas generaciones del sector.</p>
                        @endif

                        @if(($info->linea_tiempo ?? null) && count($info->linea_tiempo) > 0)
                        <h5 class="fw-bold mt-4"><i class="fas fa-stream text-warning me-2"></i>Línea de Tiempo</h5>
                        <ul class="list-group list-group-flush mt-3">
                            @foreach($info->linea_tiempo as $ev)
                            <li class="list-group-item bg-transparent">
                                <strong>{{ $ev['anio'] }}:</strong> {{ $ev['evento'] }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    {{-- 2.2 Identidad Institucional --}}
                    <div class="tab-pane fade" id="identidad" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Identidad Institucional</h3>

                        @if($info->lema ?? null)
                        <div class="mt-4 p-3 text-center rounded border" style="background-color: #f8f9fa;">
                            <h5 class="mb-0 fw-bold" style="color: var(--cv-primary-dark);">Lema: <span class="text-warning">"{{ $info->lema }}"</span></h5>
                        </div>
                        @endif

                        <div class="row g-4 mt-2">
                            {{-- Misión --}}
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: var(--cv-light-bg); border-left: 4px solid var(--cv-primary);">
                                    <h5 class="fw-bold" style="color: var(--cv-primary);"><i class="fas fa-eye me-2"></i>Misión</h5>
                                    <p class="text-muted small mb-0" style="white-space: pre-line;">{{ $info->mision ?? 'Somos una institución educativa que brinda formación integral de calidad a estudiantes de la región.' }}</p>
                                </div>
                            </div>
                            
                            {{-- Visión --}}
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                                    <h5 class="fw-bold text-warning" style="color: #f59e0b !important;"><i class="fas fa-mountain me-2"></i>Visión</h5>
                                    <p class="text-muted small mb-0" style="white-space: pre-line;">{{ $info->vision ?? 'Consolidarnos como una comunidad educativa líder y acreditada en la región Huánuco.' }}</p>
                                </div>
                            </div>

                            {{-- Valores --}}
                            @if($info->valores ?? null)
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: #fff5f5; border-left: 4px solid #dc3545;">
                                    <h5 class="fw-bold text-danger" style="color: #dc3545 !important;"><i class="fas fa-heart me-2"></i>Valores</h5>
                                    <p class="text-muted small mb-0" style="white-space: pre-line;">{{ $info->valores }}</p>
                                </div>
                            </div>
                            @endif

                            {{-- Principios --}}
                            @if($info->principios ?? null)
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: #f0f4ff; border-left: 4px solid #ffc107;">
                                    <h5 class="fw-bold text-warning" style="color: #ffc107 !important;"><i class="fas fa-star me-2"></i>Principios</h5>
                                    <p class="text-muted small mb-0" style="white-space: pre-line;">{{ $info->principios }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2.3 Símbolos Institucionales --}}
                    <div class="tab-pane fade" id="simbolos" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Símbolos Institucionales</h3>
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <h5 class="fw-bold text-dark"><i class="fas fa-music" style="color: var(--cv-primary);"></i> Himno Vallejiano</h5>
                                @if($info->letra_himno ?? null)
                                    <p class="text-muted mt-2" style="white-space: pre-line; font-style: italic;">{{ $info->letra_himno }}</p>
                                @else
                                    <p class="text-muted">Letras que ensalzan la memoria del insigne poeta César Vallejo Mendoza.</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold text-dark"><i class="fas fa-tshirt" style="color: var(--cv-primary);"></i> Uniforme Oficial</h5>
                                @if($info->uniforme_descripcion ?? null)
                                    <p class="text-muted mt-2">{{ $info->uniforme_descripcion }}</p>
                                @else
                                    <p class="text-muted">El uniforme representa la sobriedad, el orden y la disciplina del estudiante vallejiano.</p>
                                @endif
                                {{-- Galería de imágenes del uniforme --}}
                                @if(($info->uniforme_imagenes ?? null) && count($info->uniforme_imagenes) > 0)
                                    <div class="row g-2 mt-3">
                                        @foreach($info->uniforme_imagenes as $img)
                                            <div class="col-6 col-sm-4">
                                                <div onclick="showUniformeCarousel({{ $loop->index }})" title="Ver imagen en galería" class="d-block overflow-hidden rounded-3 shadow-sm border" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='scale(1.04)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)';">
                                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid w-100" style="height: 120px; width: 100%; object-fit: cover;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($info->uniforme_imagen ?? null)
                                    {{-- Fallback si solo existe la imagen única antigua --}}
                                    <div class="mt-3">
                                        <div onclick="showUniformeCarousel(0)" style="cursor: pointer; transition: transform 0.2s;" class="d-inline-block overflow-hidden rounded-3 shadow-sm border" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                            <img src="{{ asset('storage/' . $info->uniforme_imagen) }}" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- MODAL UNIFORME CON CARRUSEL --}}
                        <div class="modal fade" id="modalUniforme" tabindex="-1" aria-labelledby="modalUniformeLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-dark" id="modalUniformeLabel">Galería del Uniforme Oficial</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $uniformeImgs = $info->uniforme_imagenes ?? [];
                                            if (empty($uniformeImgs) && $info->uniforme_imagen) {
                                                $uniformeImgs = [$info->uniforme_imagen];
                                            }
                                        @endphp
                                        @if(count($uniformeImgs) > 0)
                                            <div id="carouselUniforme" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-indicators">
                                                    @foreach($uniformeImgs as $idx => $img)
                                                        <button type="button" data-bs-target="#carouselUniforme" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}" aria-current="{{ $idx === 0 ? 'true' : 'false' }}"></button>
                                                    @endforeach
                                                </div>
                                                <div class="carousel-inner rounded-3 shadow-sm">
                                                    @foreach($uniformeImgs as $idx => $img)
                                                        <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                                            <img src="{{ asset('storage/' . $img) }}" class="d-block w-100" style="height: 450px; object-fit: cover;" alt="Uniforme Oficial">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselUniforme" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Anterior</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carouselUniforme" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Siguiente</span>
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center py-5 text-muted">
                                                <i class="fas fa-images fs-1 mb-3 text-warning opacity-50"></i>
                                                <p class="mb-0 fw-semibold">Las fotos del uniforme oficial estarán disponibles próximamente.</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function showUniformeCarousel(index) {
                                // Mover carrusel al index clicado
                                const carouselEl = document.querySelector('#carouselUniforme');
                                if (carouselEl) {
                                    const carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                                    carousel.to(index);
                                }
                                // Abrir modal
                                const modalEl = document.querySelector('#modalUniforme');
                                if (modalEl) {
                                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                                    modal.show();
                                }
                            }
                        </script>
                    </div>

                    {{-- 2.4 Infraestructura --}}
                    <div class="tab-pane fade" id="infraestructura" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Infraestructura Educativa</h3>
                        
                        @if($info->infraestructura_descripcion ?? null)
                            <p class="text-muted mt-3 lh-lg" style="white-space: pre-line;">{{ $info->infraestructura_descripcion }}</p>
                        @else
                            <p class="text-muted mt-3 mb-4">Contamos con modernos espacios diseñados para optimizar los procesos de enseñanza-aprendizaje:</p>
                        @endif

                        <div class="row g-3">
                            {{-- Card 1: Aulas --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-white" 
                                     style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;" 
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'; this.style.borderColor='var(--cv-primary)';" 
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#dee2e6';"
                                     data-bs-toggle="modal" data-bs-target="#modalAulas">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;background:var(--cv-light-bg);color:var(--cv-primary);font-size:1.5rem;"><i class="fas fa-chalkboard-teacher"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Aulas Modernas</h6>
                                        <small class="text-muted">Mobiliario ergonómico. Ver fotos <i class="fas fa-chevron-right ms-1 text-primary" style="font-size: 0.75rem;"></i></small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Card 2: Laboratorios --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-white" 
                                     style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;" 
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'; this.style.borderColor='#2e7d32';" 
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#dee2e6';"
                                     data-bs-toggle="modal" data-bs-target="#modalLabs">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;background:#e8f5e9;color:#2e7d32;font-size:1.5rem;"><i class="fas fa-flask"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Laboratorios</h6>
                                        <small class="text-muted">Ciencias experimentales. Ver fotos <i class="fas fa-chevron-right ms-1 text-success" style="font-size: 0.75rem;"></i></small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Card 3: Biblioteca --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-white" 
                                     style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;" 
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'; this.style.borderColor='#f57f17';" 
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#dee2e6';"
                                     data-bs-toggle="modal" data-bs-target="#modalBiblio">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;background:#fff8e1;color:#f57f17;font-size:1.5rem;"><i class="fas fa-book"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Biblioteca</h6>
                                        <small class="text-muted">Catálogo bibliográfico. Ver fotos <i class="fas fa-chevron-right ms-1 text-warning" style="font-size: 0.75rem;"></i></small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Card 4: Aula AIP --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-white" 
                                     style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;" 
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'; this.style.borderColor='#6a1b9a';" 
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#dee2e6';"
                                     data-bs-toggle="modal" data-bs-target="#modalAIP">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;background:#f3e5f5;color:#6a1b9a;font-size:1.5rem;"><i class="fas fa-laptop-code"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Aula Innovación (AIP)</h6>
                                        <small class="text-muted">Estaciones informáticas. Ver fotos <i class="fas fa-chevron-right ms-1 text-danger" style="font-size: 0.75rem;"></i></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MODALES CON CARRUSELES --}}
                        @php
                            $modals = [
                                'modalAulas'  => ['titulo' => 'Galería de Aulas Modernas', 'campo' => 'infra_aulas_imagenes', 'id_car' => 'carAulas'],
                                'modalLabs'   => ['titulo' => 'Galería de Laboratorios', 'campo' => 'infra_labs_imagenes', 'id_car' => 'carLabs'],
                                'modalBiblio' => ['titulo' => 'Galería de Biblioteca', 'campo' => 'infra_biblio_imagenes', 'id_car' => 'carBiblio'],
                                'modalAIP'    => ['titulo' => 'Galería de Aula de Innovación (AIP)', 'campo' => 'infra_aip_imagenes', 'id_car' => 'carAIP'],
                            ];
                        @endphp

                        @foreach($modals as $modalId => $modal)
                            <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark" id="{{ $modalId }}Label">{{ $modal['titulo'] }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            @php $imgs = $info->{$modal['campo']} ?? []; @endphp
                                            @if(count($imgs) > 0)
                                                <div id="{{ $modal['id_car'] }}" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        @foreach($imgs as $idx => $img)
                                                            <button type="button" data-bs-target="#{{ $modal['id_car'] }}" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}" aria-current="{{ $idx === 0 ? 'true' : 'false' }}"></button>
                                                        @endforeach
                                                    </div>
                                                    <div class="carousel-inner rounded-3 shadow-sm">
                                                        @foreach($imgs as $idx => $img)
                                                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                                                <img src="{{ asset('storage/' . $img) }}" class="d-block w-100" style="height: 450px; object-fit: cover;" alt="Infraestructura">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $modal['id_car'] }}" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Anterior</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-bs-target="#{{ $modal['id_car'] }}" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Siguiente</span>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="text-center py-5 text-muted">
                                                    <i class="fas fa-images fs-1 mb-3 text-warning opacity-50"></i>
                                                    <p class="mb-0 fw-semibold">Las fotos de esta categoría estarán disponibles próximamente.</p>
                                                    <small class="text-muted d-block mt-1">El personal administrativo está actualizando el catálogo de imágenes.</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- 2.5 Línea de Tiempo --}}
                    @if(($info->linea_tiempo ?? null) && count($info->linea_tiempo) > 0)
                    <div class="tab-pane fade" id="timeline" role="tabpanel">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Línea de Tiempo</h3>
                        <div class="mt-4">
                            @foreach($info->linea_tiempo as $ev)
                            <div class="d-flex gap-3 mb-4">
                                <div class="text-center" style="min-width: 80px;">
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: var(--cv-primary); font-size: 1rem;">{{ $ev['anio'] }}</span>
                                </div>
                                <div class="p-3 rounded flex-grow-1" style="background: var(--cv-light-bg); border-left: 3px solid var(--cv-primary);">
                                    <p class="mb-0 text-muted">{{ $ev['evento'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

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