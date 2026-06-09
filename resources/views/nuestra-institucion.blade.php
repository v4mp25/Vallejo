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
        :root {
            --cv-primary: #0148A4;
            --cv-primary-dark: #023d7a;
            --cv-light-bg: #f8f9ff;
            --cv-shadow: 0 8px 32px rgba(1, 72, 164, 0.12);
        }
        body { font-family: 'Roboto', sans-serif !important; background-color: #f4f6f9; color: #333; }
        
        /* Navbar para página interna (fondo blanco estático) */
        .cv-navbar-interna { background: #fff !important; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .cv-navbar-interna .nav-link { color: #333 !important; font-weight: 500; padding: 12px 16px !important; }
        
        /* Menú Dropdown (Igual que en welcome) */
        @media (min-width: 992px) {
            .cv-dropdown-hover:hover > .dropdown-menu { display: block; margin-top: 0; animation: fadeIn .3s ease; }
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .cv-dropdown-hover .dropdown-menu { border-radius: 12px; padding: 10px 0; }
        .cv-dropdown-hover .dropdown-item { padding: 10px 20px; font-weight: 500; color: #444; transition: all 0.2s; }
        .cv-dropdown-hover .dropdown-item:hover { background-color: var(--cv-light-bg); color: var(--cv-primary); padding-left: 25px; }
        
        .btn-login { background: var(--cv-primary) !important; color: #fff !important; border-radius: 50px !important; padding: 10px 26px !important; font-weight: 600 !important; }
        
        /* Encabezado */
        .header-institucion { background: linear-gradient(to right, var(--cv-primary-dark), var(--cv-primary)); color: white; padding: 60px 0; text-align: center; }
        
        /* Estilos de las pestañas laterales */
        .nav-pills-custom .nav-link { color: #666; font-weight: 500; border-radius: 10px; padding: 15px 20px; text-align: left; margin-bottom: 8px; transition: all 0.3s; background: #fff; border: 1px solid #eee; }
        .nav-pills-custom .nav-link:hover { background: var(--cv-light-bg); color: var(--cv-primary); }
        .nav-pills-custom .nav-link.active { background: var(--cv-primary); color: #fff; box-shadow: var(--cv-shadow); border-color: var(--cv-primary); }
        
        .tab-content-box { background: #fff; border-radius: 16px; padding: 40px; box-shadow: var(--cv-shadow); min-height: 500px; }
        .copyright-cesarvallejo { background: var(--cv-primary); color: rgba(255,255,255,.9); text-align: center; padding: 22px; margin-top: 40px;}
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light cv-navbar-interna px-4 px-lg-5 py-3 sticky-top">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('img/Yachachin1.png') }}" alt="I.E. César Vallejo" height="52"
                 onerror="this.outerHTML='<span class=\'fw-bold\' style=\'color: var(--cv-primary); font-size:1.1rem\'>I.E. César Vallejo</span>'">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <i class="fas fa-bars text-dark"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <a class="nav-link" href="{{ url('/') }}">Inicio</a>
                
                <div class="nav-item dropdown cv-dropdown-hover">
                    <a class="nav-link dropdown-toggle" style="color: var(--cv-primary) !important; font-weight: 700;" href="{{ url('/nuestra-institucion') }}" id="navbarInstitucion">
                        Nuestra Institución
                    </a>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarInstitucion">
                        <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#resena') }}"><i class="fas fa-history text-warning me-2"></i>Reseña Histórica</a></li>
                        <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#identidad') }}"><i class="fas fa-bullseye text-warning me-2"></i>Identidad e Ideario</a></li>
                        <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#simbolos') }}"><i class="fas fa-flag text-warning me-2"></i>Símbolos Escolares</a></li>
                        <li><a class="dropdown-item" href="{{ url('/nuestra-institucion#infraestructura') }}"><i class="fas fa-building text-warning me-2"></i>Infraestructura</a></li>
                    </ul>
                </div>

                <a class="nav-link" href="{{ url('/') }}#seccion-avisos-publicos">Avisos</a>
                <a class="nav-link btn-login ms-lg-3" href="{{ url('/login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i> Intranet
                </a>
            </div>
        </div>
    </nav>

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
                    <button class="nav-link active" id="btn-resena" data-bs-toggle="pill" data-bs-target="#resena" type="button" role="tab" aria-selected="true">
                        <i class="fas fa-history me-2"></i> 2.1 Reseña Histórica
                    </button>
                    <button class="nav-link" id="btn-identidad" data-bs-toggle="pill" data-bs-target="#identidad" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-bullseye me-2"></i> 2.2 Identidad e Ideario
                    </button>
                    <button class="nav-link" id="btn-simbolos" data-bs-toggle="pill" data-bs-target="#simbolos" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-flag me-2"></i> 2.3 Símbolos Escolares
                    </button>
                    <button class="nav-link" id="btn-infraestructura" data-bs-toggle="pill" data-bs-target="#infraestructura" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-building me-2"></i> 2.4 Infraestructura
                    </button>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content tab-content-box" id="v-pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="resena" role="tabpanel" tabindex="0">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Reseña Histórica</h3>
                        <p class="text-muted mt-4 lh-lg">La Institución Educativa "César Vallejo", ubicada en el pujante distrito de Amarilis, provincia de Huánuco, nació bajo el imperativo de brindar educación de alto nivel a las nuevas generaciones del sector. A lo largo de los años, ha consolidado su prestigio convirtiéndose en un baluarte del aprendizaje integral.</p>
                        
                        <h5 class="fw-bold mt-4"><i class="fas fa-stream text-warning me-2"></i>Línea de Tiempo</h5>
                        <ul class="list-group list-group-flush mt-3 text-muted">
                            <li class="list-group-item bg-transparent"><strong>Hito Fundacional:</strong> Apertura y primera convocatoria de matrícula comunitaria.</li>
                            <li class="list-group-item bg-transparent"><strong>Modernización Tecnológica:</strong> Implementación de las aulas de innovación pedagógica (AIP).</li>
                        </ul>
                    </div>

                    <div class="tab-pane fade" id="identidad" role="tabpanel" tabindex="0">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Identidad Institucional</h3>
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: var(--cv-light-bg); border-left: 4px solid var(--cv-primary);">
                                    <h5 class="fw-bold" style="color: var(--cv-primary)"><i class="fas fa-eye me-2"></i>Misión</h5>
                                    <p class="text-muted small mb-0">Somos una institución educativa que brinda formación integral de calidad a estudiantes de la región, desarrollando competencias cognitivas, socioemocionales y axiológicas basadas en la autodisciplina y el trabajo constante.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                                    <h5 class="fw-bold text-warning"><i class="fas fa-mountain me-2"></i>Visión</h5>
                                    <p class="text-muted small mb-0">Consolidarnos como una comunidad educativa líder y acreditada en la región Huánuco, reconocida por la excelencia académica de sus egresados, la innovación y el firme compromiso ciudadano.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 text-center rounded border" style="background-color: #f8f9fa;">
                            <h5 class="mb-0 fw-bold" style="color: var(--cv-primary-dark);">Lema: <span class="text-warning">"Estudio, Trabajo y Disciplina"</span></h5>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="simbolos" role="tabpanel" tabindex="0">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Símbolos Institucionales</h3>
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <h5 class="fw-bold text-dark"><i class="fas fa-music" style="color: var(--cv-primary);"></i> Himno Vallejiano</h5>
                                <p class="text-muted">Letras que ensalzan la memoria del insigne poeta César Vallejo Mendoza, instando a la juventud estudiosa a forjar su destino a través del esfuerzo constante y el amor por la patria.</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold text-dark"><i class="fas fa-tshirt" style="color: var(--cv-primary);"></i> Uniforme Oficial</h5>
                                <p class="text-muted">El uniforme representa la sobriedad, el orden y la disciplina del estudiante vallejiano, portado con orgullo y respeto en cada acto cívico e institucional.</p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="infraestructura" role="tabpanel" tabindex="0">
                        <h3 class="fw-bold" style="color: var(--cv-primary); border-bottom: 2px solid var(--cv-light-bg); padding-bottom: 10px;">Infraestructura Educativa</h3>
                        <p class="text-muted mt-3 mb-4">Contamos con modernos espacios diseñados para optimizar los procesos de enseñanza-aprendizaje:</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px; background:var(--cv-light-bg); color:var(--cv-primary); font-size:1.5rem;"><i class="fas fa-chalkboard-teacher"></i></div>
                                    <div><h6 class="fw-bold mb-1">Aulas Modernas</h6><small class="text-muted">Mobiliario ergonómico.</small></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px; background:#e8f5e9; color:#2e7d32; font-size:1.5rem;"><i class="fas fa-flask"></i></div>
                                    <div><h6 class="fw-bold mb-1">Laboratorios</h6><small class="text-muted">Ciencias experimentales.</small></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px; background:#fff8e1; color:#f57f17; font-size:1.5rem;"><i class="fas fa-book"></i></div>
                                    <div><h6 class="fw-bold mb-1">Biblioteca</h6><small class="text-muted">Catálogo bibliográfico.</small></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px; background:#f3e5f5; color:#6a1b9a; font-size:1.5rem;"><i class="fas fa-laptop-code"></i></div>
                                    <div><h6 class="fw-bold mb-1">Aula Innovación (AIP)</h6><small class="text-muted">Estaciones informáticas.</small></div>
                                </div>
                            </div>
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
            // Función para activar la pestaña correcta según el "#" en la URL
            function activarPestanaPorHash() {
                // Obtiene la parte después del "#" (ej: "simbolos")
                let hash = window.location.hash.substring(1);
                
                if (hash) {
                    // Busca el botón que controla el contenido con ese ID
                    let botonPestana = document.querySelector(`button[data-bs-target="#${hash}"]`);
                    if (botonPestana) {
                        // Usa la API de Bootstrap para simular un click/mostrarla
                        let tabInstance = new bootstrap.Tab(botonPestana);
                        tabInstance.show();
                        // Realizar un pequeño scroll suave hacia arriba para que se vea bien
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            }

            // Ejecutar al cargar la página por primera vez
            activarPestanaPorHash();

            // Ejecutar si el usuario hace click en el menú superior mientras YA ESTÁ en la página
            window.addEventListener('hashchange', activarPestanaPorHash);
        });
    </script>
</body>
</html>