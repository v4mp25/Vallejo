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
    {{-- assets/style.css queda como referencia; el CSS completo está inline abajo --}}
    <style>
        :root {
            --cv-primary: #0148A4;
            --cv-primary-dark: #023d7a;
            --cv-light-bg: #f8f9ff;
            --cv-shadow: 0 8px 32px rgba(1, 72, 164, 0.12);
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Roboto', sans-serif !important;
            margin: 0;
            color: #333;
            -webkit-font-smoothing: antialiased;
        }
        #sistema-interno { display: none; }
        #sistema-interno.activo { display: block; }
        #sitio-publico.oculto { display: none; }

        /* ===== SITIO PÚBLICO ===== */
        .cv-navbar-wrap { position: absolute; top: 0; left: 0; right: 0; z-index: 100; }
        .cv-navbar-wrap .navbar { background: transparent !important; transition: background .35s, box-shadow .35s; }
        .cv-navbar-wrap.scrolled .navbar { background: rgba(255,255,255,.97) !important; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .cv-navbar-wrap .nav-link { color: #fff !important; font-weight: 500; font-size: 1rem; padding: 12px 16px !important; }
        .cv-navbar-wrap.scrolled .nav-link:not(.btn-login) { color: #333 !important; }
        .btn-login { background: var(--cv-primary) !important; border: 2px solid var(--cv-primary) !important; color: #fff !important; border-radius: 50px !important; padding: 10px 26px !important; font-weight: 600 !important; cursor: pointer !important; pointer-events: auto !important; transition: transform .2s, box-shadow .2s; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(1,72,164,.35); color: #fff !important; }
        .cv-hero { position: relative; min-height: 88vh; display: flex; align-items: center; justify-content: center; text-align: center; color: #fff; overflow: hidden; }
        .cv-hero-bg { position: absolute; inset: 0; background: linear-gradient(135deg, #a4016b 0%, #5c0142 50%, #0148A4 100%); }
        .cv-hero-bg img { width: 100%; height: 100%; object-fit: cover; opacity: .45; }
        .cv-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(90, 1, 40, 0.4), rgba(1,72,164,.75)); }
        .cv-hero-content { position: relative; z-index: 2; max-width: 820px; padding: 120px 24px 80px; }
        .cv-hero-content h1 { font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 700; text-shadow: 0 4px 20px rgba(0,0,0,.2); }
        .cv-hero-content p { font-size: 1.15rem; opacity: .95; }
        .cv-hero-dots { position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 3; }
        .cv-hero-dots span { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,.4); cursor: pointer; }
        .cv-hero-dots span.active { background: #fff; width: 28px; border-radius: 5px; }
        .cv-section-avisos { padding: 80px 24px; background: #fff; text-align: center; }
        .cv-section-avisos .icon-wrap { width: 80px; height: 80px; margin: 0 auto 20px; background: var(--cv-light-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--cv-primary); font-size: 2rem; }
        .cv-aviso-card { background: #fff; border: 1px solid #e8ecf0; border-left: 4px solid var(--cv-primary); border-radius: 12px; padding: 20px 24px; text-align: left; box-shadow: var(--cv-shadow); margin-bottom: 12px; }
        .institucion-section { padding: 70px 24px; background: #fff; }
        .inst-header { text-align: center; margin-bottom: 48px; }
        .inst-badge { display: inline-block; background: var(--cv-primary); color: #fff; font-size: .75rem; font-weight: 600; padding: 6px 18px; border-radius: 20px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px; }
        .inst-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; max-width: 1100px; margin: 0 auto; }
        .inst-card { background: var(--cv-light-bg); border-radius: 12px; padding: 32px 24px; text-align: center; border-top: 4px solid var(--cv-primary); transition: transform .25s, box-shadow .25s; }
        .inst-card:hover { transform: translateY(-4px); box-shadow: var(--cv-shadow); }
        .inst-card i { font-size: 2.2rem; color: var(--cv-primary); margin-bottom: 14px; }
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
        .cv-auth-close:hover { background: rgba(255,255,255,.35); }
        .cv-auth-body { padding: 28px; }
        .cv-auth-tabs { display: flex; gap: 8px; background: var(--cv-light-bg); padding: 6px; border-radius: 12px; margin-bottom: 24px; }
        .cv-auth-tab { flex: 1; border: none; background: transparent; padding: 12px 16px; border-radius: 10px; font-weight: 600; font-size: .9rem; color: #666; cursor: pointer; transition: all .2s; }
        .cv-auth-tab.active { background: #fff; color: var(--cv-primary); box-shadow: 0 2px 12px rgba(1,72,164,.15); }
        .cv-auth-pane { display: none; }
        .cv-auth-pane.active { display: block; }
        .cv-field label { font-weight: 600; font-size: .85rem; color: #444; margin-bottom: 6px; display: block; }
        .cv-field { margin-bottom: 18px; }
        .cv-input-wrap { display: flex; align-items: stretch; border: 1px solid #dde3eb; border-radius: 10px; overflow: hidden; transition: border-color .2s, box-shadow .2s; }
        .cv-input-wrap:focus-within { border-color: var(--cv-primary); box-shadow: 0 0 0 3px rgba(1,72,164,.12); }
        .cv-input-wrap .cv-input-icon { background: var(--cv-light-bg); padding: 0 14px; display: flex; align-items: center; color: var(--cv-primary); }
        .cv-input-wrap input, .cv-input-wrap select { border: none !important; box-shadow: none !important; flex: 1; padding: 12px 14px; font-size: .95rem; outline: none; }
        .cv-btn-submit { width: 100%; background: var(--cv-primary); border: none; color: #fff; padding: 14px 24px; border-radius: 50px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: transform .2s, box-shadow .2s; }
        .cv-btn-submit:hover { background: var(--cv-primary-dark); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(1,72,164,.35); }
        .cv-btn-submit:disabled { opacity: .65; cursor: not-allowed; transform: none; }
        .cv-demo-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 14px; margin-bottom: 18px; font-size: .8rem; color: #92400e; }
        .cv-check-gmail { background: var(--cv-light-bg); border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; }
        .cv-login-error { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; font-size: .88rem; display: none; }
        .cv-login-error.visible { display: block; }

        /* ===== SISTEMA INTERNO ===== */
        .cv-dashboard-wrap { display: flex; min-height: 100vh; font-family: 'Roboto', sans-serif; background: #f0f4f8; }
        .cv-sidebar { width: 272px; background: var(--cv-primary); color: #fff; flex-shrink: 0; display: flex; flex-direction: column; }
        .cv-sidebar-brand { padding: 22px 18px; text-align: center; border-bottom: 1px solid rgba(255,255,255,.15); }
        .cv-sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .cv-sidebar-group-title { padding: 10px 24px 6px; font-size: .72rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.55); font-weight: 600; }
        .cv-nav-btn { display: flex; align-items: center; gap: 10px; padding: 11px 24px 11px 32px; color: rgba(255,255,255,.88); width: 100%; border: none; background: transparent; text-align: left; cursor: pointer; font-size: .92rem; font-weight: 500; }
        .cv-nav-btn:hover, .cv-nav-btn.active { background: rgba(255,255,255,.15); color: #fff; }
        .cv-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .cv-topbar { background: #fff; padding: 16px 28px; border-bottom: 1px solid #e8ecf0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .cv-content { padding: 28px; flex: 1; }
        .cv-page-title { font-size: 1.5rem; font-weight: 700; color: #222; margin: 0; }
        .cv-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(1,72,164,.08); }
        .cv-inst-card { background: var(--cv-light-bg); border-radius: 12px; padding: 24px; border-top: 4px solid var(--cv-primary); height: 100%; }
        .cv-badge-primary { background: var(--cv-primary); color: #fff; font-size: .7rem; font-weight: 600; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; }
        .cv-table thead { background: var(--cv-light-bg); color: var(--cv-primary); }
        .cv-table th { font-weight: 600; font-size: .85rem; border-bottom: 2px solid var(--cv-primary); }
        .cv-hidden { display: none !important; }
        .cv-primary { color: var(--cv-primary) !important; }
        .cv-salon-header { background: linear-gradient(135deg, var(--cv-primary), #023d7a); color: #fff; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
        .cv-breadcrumb-back { border: none; background: transparent; color: var(--cv-primary); font-weight: 600; padding: 0; margin-bottom: 16px; cursor: pointer; }
        .tabla-notas-editable input { width: 64px; text-align: center; }
        .cv-btn-volver-sitio { border-radius: 50px; }
        .cv-main .btn-primary { background: var(--cv-primary); border-color: var(--cv-primary); }
        @media (max-width: 991px) {
            .cv-navbar-wrap .nav-link { color: #333 !important; }
            .cv-navbar-wrap .navbar { background: #fff !important; }
            .cv-dashboard-wrap { flex-direction: column; }
            .cv-sidebar { width: 100%; }
        }
    </style>
</head>
<body>

{{-- ===== SITIO PÚBLICO ===== --}}
<div id="sitio-publico">
    <div class="cv-navbar-wrap" id="cv-navbar-wrap">
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3">
            <a class="navbar-brand p-0" href="#">
                <img id="logo-img" src="{{ asset('img/Yachachin1.png') }}" alt="I.E. César Vallejo" height="52"
                     onerror="this.outerHTML='<span class=\'fw-bold text-white\' style=\'font-size:1.1rem\'>I.E. César Vallejo</span>'">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <a class="nav-link" href="#">Inicio</a>
                    <a class="nav-link" href="#seccion-avisos-publicos">Avisos</a>
                    <a class="nav-link" href="#">Calendario</a>
                    <a class="nav-link" href="#">Repositorio</a>
                    <a class="nav-link" href="#">Contáctanos</a>
                    <a class="nav-link btn-login ms-lg-3" id="btn-abrir-login" role="button">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <section class="cv-hero" id="cv-hero">
        <div class="cv-hero-bg">
            <img src="{{ asset('assets/carousel-3.jpg') }}" alt="" id="hero-img" onerror="this.style.display='none'">
        </div>
        <div class="cv-hero-overlay"></div>
        <div class="cv-hero-content">
            <h1 id="hero-title">Formando el futuro con excelencia</h1>
            <p id="hero-subtitle" class="mb-4">I.E. César Vallejo — Educación con valores en Huánuco</p>
            <button type="button" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold" id="btn-hero-login">
                Acceder al sistema <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
        <div class="cv-hero-dots" id="hero-dots">
            <span data-slide="0"></span>
            <span data-slide="1" class="active"></span>
            <span data-slide="2"></span>
        </div>
    </section>

    <section class="cv-section-avisos" id="seccion-avisos-publicos">
        <div class="icon-wrap"><i class="fas fa-bullhorn"></i></div>
        <h2 class="fw-bold mb-2">Avisos institucionales</h2>
        <p class="text-muted mb-4 mx-auto" style="max-width:520px">Comunicados oficiales para la comunidad educativa</p>
        <div id="lista-avisos-publicos" class="mx-auto" style="max-width:640px"></div>
    </section>

    <section class="institucion-section">
        <div class="inst-header">
            <span class="inst-badge">Nuestra Institución</span>
            <h2 class="fw-bold">I.E. César Vallejo</h2>
            <p class="text-muted">Formando estudiantes con valores, conocimiento y compromiso.</p>
        </div>
        <div class="inst-grid">
            <div class="inst-card"><i class="fas fa-map-marker-alt"></i><h4 class="fw-bold">Ubicación</h4><p class="text-muted mb-0">Huánuco, Perú</p></div>
            <div class="inst-card"><i class="fas fa-graduation-cap"></i><h4 class="fw-bold">Niveles</h4><p class="text-muted mb-0">Secundaria</p></div>
            <div class="inst-card"><i class="fas fa-phone-alt"></i><h4 class="fw-bold">Contacto</h4><p class="text-muted mb-0">contacto@cesarvallejo.edu.pe</p></div>
            <div class="inst-card"><i class="fas fa-clock"></i><h4 class="fw-bold">Horario</h4><p class="text-muted mb-0">Lun–Vie 7:30 am – 3:00 pm</p></div>
            <div class="inst-card"><i class="fas fa-users"></i><h4 class="fw-bold">Visión</h4><p class="text-muted mb-0">Ser una institución educativa de calidad académica, organizacional y de gestión que contribuya al desarrollo integral de estudiantes capaces de contribuir al progreso de la sociedad y del medio ambiente</p></div>
            <div class="inst-card"><i class="fas fa-bullseye"></i><h4 class="fw-bold">Misión</h4><p class="text-muted mb-0">Somos I.E. lider, que brinda una educacion integral de calidad los estudiantes, en una infraestructura adecuada desarrolando capacidades y valores con docentes innovadores, estrategias pertinentes, utlizando las tecnologias del momento que permitan asimilar aprendizajes esperados, utiles para la vida.</p></div>
        </div>
    </section>

    <footer class="copyright-cesarvallejo">
        <p class="mb-0">Copyright &copy; {{ date('Y') }} I.E. César Vallejo</p>
    </footer>
</div>

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
            <div class="cv-auth-tabs">
                <button type="button" class="cv-auth-tab active" data-auth-tab="login">Ingresar</button>
                <button type="button" class="cv-auth-tab" data-auth-tab="registro">Registro padres</button>
            </div>

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

            {{-- Pestaña Registro Padres --}}
            <div class="cv-auth-pane" id="pane-registro">
                <div class="cv-login-error" id="registro-error"></div>
                <form id="form-registro-padres">
                    @csrf
                    <div class="cv-field">
                        <label>Nombre completo</label>
                        <div class="cv-input-wrap">
                            <input type="text" name="nombre" placeholder="Apellidos y nombres" required>
                        </div>
                    </div>
                    <div class="cv-field">
                        <label>Correo Gmail</label>
                        <div class="cv-input-wrap">
                            <span class="cv-input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" placeholder="correo@gmail.com" required>
                        </div>
                    </div>
                    <div class="cv-field">
                        <label>DNI del Padre</label>
                        <div class="cv-input-wrap">
                            <input type="text" name="dni" required>
                        </div>
                    </div>
                    <div class="cv-field">
                        <label>DNI estudiante</label>
                        <div class="cv-input-wrap">
                            <input type="text" name="codigo_estudiante" placeholder="Código del colegio" required>
                        </div>
                    </div>
                    <div class="cv-field">
                        <label>Contraseña</label>
                        <div class="cv-input-wrap">
                            <span class="cv-input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" required>
                        </div>
                    </div>
                    <div class="cv-check-gmail form-check">
                        <input type="checkbox" class="form-check-input" id="recibir-avisos-email" name="recibir_avisos_email" checked>
                        <label class="form-check-label" for="recibir-avisos-email">
                            Recibir avisos institucionales por <strong>Gmail</strong>
                        </label>
                    </div>
                    <button type="submit" class="cv-btn-submit">
                        <i class="fas fa-user-plus me-2"></i>Registrarme
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ===== SISTEMA INTERNO (paneles JS — igual que el HTML original) ===== --}}
<div id="sistema-interno">
    <section id="panel-admin" class="cv-hidden">
        <div class="cv-dashboard-wrap">
            <aside class="cv-sidebar" id="sidebar-admin"></aside>
            <div class="cv-main">
                <header class="cv-topbar">
                    <div><h1 class="cv-page-title" id="admin-titulo">Mi panel</h1><p class="text-muted small mb-0" id="admin-subtitulo"></p></div>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm cv-btn-volver-sitio me-2">Ver sitio</button>
                        <span class="text-muted small" id="topbar-user-admin"></span>
                    </div>
                </header>
                <main class="cv-content" id="admin-contenido"></main>
            </div>
        </div>
    </section>

    <section id="panel-director" class="cv-hidden">
        <div class="cv-dashboard-wrap">
            <aside class="cv-sidebar" id="sidebar-director"></aside>
            <div class="cv-main">
                <header class="cv-topbar">
                    <div><h1 class="cv-page-title" id="dir-titulo">Mi panel</h1><p class="text-muted small mb-0" id="dir-subtitulo"></p></div>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm cv-btn-volver-sitio me-2">Ver sitio</button>
                        <span class="text-muted small" id="topbar-user-dir"></span>
                    </div>
                </header>
                <main class="cv-content" id="dir-contenido"></main>
            </div>
        </div>
    </section>

    <section id="panel-profesor" class="cv-hidden">
        <div class="cv-dashboard-wrap">
            <aside class="cv-sidebar" id="sidebar-profesor"></aside>
            <div class="cv-main">
                <header class="cv-topbar">
                    <div><h1 class="cv-page-title" id="prof-titulo">Mi panel</h1><p class="text-muted small mb-0" id="prof-subtitulo"></p></div>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm cv-btn-volver-sitio me-2">Ver sitio</button>
                        <span class="text-muted small" id="topbar-user-prof"></span>
                    </div>
                </header>
                <main class="cv-content" id="prof-contenido"></main>
            </div>
        </div>
    </section>

    <section id="panel-padres" class="cv-hidden">
        <div class="cv-dashboard-wrap">
            <aside class="cv-sidebar" id="sidebar-padres"></aside>
            <div class="cv-main">
                <header class="cv-topbar">
                    <div><h1 class="cv-page-title" id="pad-titulo">Portal de padres</h1><p class="text-muted small mb-0" id="pad-subtitulo"></p></div>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm cv-btn-volver-sitio me-2">Ver sitio</button>
                        <button class="btn btn-outline-danger btn-sm" data-logout>Salir</button>
                    </div>
                </header>
                <main class="cv-content" id="pad-contenido"></main>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ===== CONFIGURACIÓN ===== */
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ===== Estado global del usuario logueado ===== */
let usuarioActual = null; // { id, nombres, apellidos, rol, esTutor, cursos, salonTutoria }
let cursoActual   = null;

/* ===== UI helpers ===== */
const sitio     = document.getElementById('sitio-publico');
const sistema   = document.getElementById('sistema-interno');
const modalAuth = document.getElementById('modal-auth');
const paneles = {
    admin:    document.getElementById('panel-admin'),
    director: document.getElementById('panel-director'),
    profesor: document.getElementById('panel-profesor'),
    padres:   document.getElementById('panel-padres'),
};

function abrirModalAuth() { modalAuth.classList.add('activo'); }
function cerrarModalAuth() { modalAuth.classList.remove('activo'); }

function entrarSistema(usuario) {
    usuarioActual = usuario;
    cerrarModalAuth();
    sitio.classList.add('oculto');
    sistema.classList.add('activo');
    Object.values(paneles).forEach(p => p.classList.add('cv-hidden'));

    const rol = usuario.rol;
    if (rol === 'admin')    { initAdmin();    paneles.admin.classList.remove('cv-hidden'); }
    else if (rol === 'director') { initDirector(); paneles.director.classList.remove('cv-hidden'); }
    else if (rol === 'profesor') { initProfesor(); paneles.profesor.classList.remove('cv-hidden'); }
    else if (rol === 'padre' || rol === 'alumno') { initPadres(); paneles.padres.classList.remove('cv-hidden'); }
}

function salirSistema() {
    // Llamar a logout en Laravel
    fetch('{{ route("logout") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
    }).finally(() => {
        usuarioActual = null;
        sistema.classList.remove('activo');
        sitio.classList.remove('oculto');
        Object.values(paneles).forEach(p => p.classList.add('cv-hidden'));
    });
}

/* ===== Pestañas auth ===== */
document.querySelectorAll('.cv-auth-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.cv-auth-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.cv-auth-pane').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById('pane-' + tab.dataset.authTab).classList.add('active');
    });
});
modalAuth.addEventListener('click', e => { if (e.target === modalAuth) cerrarModalAuth(); });

/* ===== Navbar scroll + hero ===== */
const navbarWrap = document.getElementById('cv-navbar-wrap');
window.addEventListener('scroll', () => {
    navbarWrap?.classList.toggle('scrolled', window.scrollY > 60);
});
const heroSlides = [
    { img: '{{ asset("assets/carousel-2.jpg") }}', title: 'Excelencia académica', sub: 'Materiales y recursos para toda la comunidad' },
    { img: '{{ asset("assets/carousel-3.jpg") }}', title: 'Formando el futuro con excelencia', sub: 'I.E. César Vallejo — Huánuco' },
    { img: '{{ asset("assets/carousel-1.jpg") }}', title: 'Valores y compromiso', sub: 'Educación inicial, primaria y secundaria' },
];
let heroIdx = 1;
function setHeroSlide(i) {
    heroIdx = i;
    const s = heroSlides[i];
    const img = document.getElementById('hero-img');
    if (img) { img.src = s.img; img.style.display = ''; img.onerror = function(){ this.style.display='none'; }; }
    document.getElementById('hero-title').textContent     = s.title;
    document.getElementById('hero-subtitle').textContent  = s.sub;
    document.querySelectorAll('#hero-dots span').forEach((d, j) => d.classList.toggle('active', j === i));
}
document.querySelectorAll('#hero-dots span').forEach(d => {
    d.addEventListener('click', () => setHeroSlide(+d.dataset.slide));
});
setInterval(() => setHeroSlide((heroIdx + 1) % heroSlides.length), 6000);

document.getElementById('btn-abrir-login').addEventListener('click', e => { e.preventDefault(); abrirModalAuth(); });
document.getElementById('btn-hero-login')?.addEventListener('click', () => abrirModalAuth());
document.getElementById('btn-cerrar-auth').addEventListener('click', cerrarModalAuth);
document.querySelectorAll('.cv-btn-volver-sitio').forEach(b => b.addEventListener('click', salirSistema));
document.querySelectorAll('[data-logout]').forEach(b => b.addEventListener('click', salirSistema));

/* ===== SIDEBAR helper ===== */
function crearSidebar(id, items, onClick, logoutId, nombreUsuario) {
    const el = document.getElementById(id);
    el.innerHTML = `
        <div class="cv-sidebar-brand">
            <i class="fas fa-graduation-cap fa-2x mb-2"></i>
            <h5 class="mb-0">I.E. César Vallejo</h5>
            <small class="opacity-75">${nombreUsuario || ''}</small>
        </div>
        <nav class="cv-sidebar-nav">
            <div class="cv-sidebar-group-title">Mi panel</div>
            <div id="${id}-menu"></div>
        </nav>
        <div class="p-3 border-top border-light border-opacity-25">
            <button type="button" class="btn btn-outline-light btn-sm w-100" id="${logoutId}">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </button>
        </div>`;
    const menu = document.getElementById(id + '-menu');
    items.filter(i => !i.hidden).forEach(item => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'cv-nav-btn';
        btn.dataset.vista = item.id;
        btn.innerHTML = `<i class="fas ${item.icon}"></i> ${item.label}`;
        btn.onclick = () => onClick(item.id);
        menu.appendChild(btn);
    });
    document.getElementById(logoutId).onclick = salirSistema;
}
function activarNav(sidebarId, vista) {
    document.querySelectorAll(`#${sidebarId}-menu .cv-nav-btn`).forEach(b => {
        b.classList.toggle('active', b.dataset.vista === vista);
    });
}

/* ===== API helpers ===== */
async function apiFetch(url, opts = {}) {
    const res = await fetch(url, {
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', ...opts.headers },
        ...opts
    });
    return res.json();
}

/* ===== LOGIN (conectado a Laravel) ===== */
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
            entrarSistema(data.user);
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

/* ===== REGISTRO PADRES ===== */
document.getElementById('form-registro-padres').addEventListener('submit', async e => {
    e.preventDefault();
    const errDiv = document.getElementById('registro-error');
    const form = e.target;
    errDiv.classList.remove('visible');

    const body = new URLSearchParams({
        nombre:              form.nombre.value,
        email:               form.email.value,
        dni:                 form.dni.value,
        codigo_estudiante:   form.codigo_estudiante.value,
        password:            form.password.value,
        recibir_avisos_email: form.recibir_avisos_email.checked ? '1' : '0',
        _token:              CSRF,
    });

    try {
        const data = await fetch('/padres/registro', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
            body: body.toString()
        }).then(r => r.json());

        if (data.success) {
            entrarSistema(data.user);
        } else {
            errDiv.textContent = data.message || 'Error al registrarse. Verifica los datos.';
            errDiv.classList.add('visible');
        }
    } catch (err) {
        errDiv.textContent = 'Error de conexión.';
        errDiv.classList.add('visible');
    }
});

/* ===== NOTAS — helpers de vistas (sin cambios del original) ===== */
function htmlNotasEditables(curso) {
    const rows = (curso.estudiantes || []).map(e => `
        <tr>
            <td>${e.apellidos}, ${e.nombres}</td>
            <td><input type="number" min="0" max="20" step="0.5" class="form-control form-control-sm" value="${e.notas?.B1 ?? ''}" data-alumno="${e.id}" data-periodo="B1"></td>
            <td><input type="number" min="0" max="20" step="0.5" class="form-control form-control-sm" value="${e.notas?.B2 ?? ''}" data-alumno="${e.id}" data-periodo="B2"></td>
            <td><input type="number" min="0" max="20" step="0.5" class="form-control form-control-sm" value="${e.notas?.B3 ?? ''}" data-alumno="${e.id}" data-periodo="B3"></td>
            <td><input type="number" min="0" max="20" step="0.5" class="form-control form-control-sm" value="${e.notas?.B4 ?? ''}" data-alumno="${e.id}" data-periodo="B4"></td>
        </tr>`).join('');
    return `
        <button type="button" class="cv-breadcrumb-back btn-volver-curso"><i class="fas fa-arrow-left"></i> Volver</button>
        <div class="cv-card card-body p-4">
            <span class="cv-badge-primary">Notas</span>
            <h2 class="cv-page-title mt-2">${curso.nombre} — ${curso.aula}</h2>
            <table class="table cv-table tabla-notas-editable mt-3">
                <thead><tr><th>Estudiante</th><th>Bim. I</th><th>Bim. II</th><th>Bim. III</th><th>Bim. IV</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>
            <button type="button" class="btn btn-primary rounded-pill mt-2" id="btn-guardar-notas">
                <i class="fas fa-save"></i> Guardar notas
            </button>
        </div>`;
}

function htmlCurso(curso) {
    const filas = (curso.estudiantes || []).map((e, i) => `
        <tr><td>${e.apellidos}, ${e.nombres}</td>
            <td><input type="radio" name="a${i}" value="presente" checked></td>
            <td><input type="radio" name="a${i}" value="tardanza"></td>
            <td><input type="radio" name="a${i}" value="falta"></td>
        </tr>`).join('');
    return `
        <button type="button" class="cv-breadcrumb-back btn-volver-lista"><i class="fas fa-arrow-left"></i> Volver</button>
        <div class="cv-card card-body p-4">
            <h2 class="cv-page-title">${curso.nombre}</h2>
            <p class="text-muted">Salón ${curso.aula}</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary rounded-pill btn-ver-asistencia"><i class="fas fa-clipboard-check"></i> Registrar asistencia</button>
                <button class="btn btn-outline-primary rounded-pill btn-ver-notas"><i class="fas fa-edit"></i> Registrar notas</button>
            </div>
            <div id="sub-asistencia" class="cv-hidden mt-4 pt-4 border-top">
                <h5>Asistencia del día</h5>
                <input type="date" id="fecha-asistencia" class="form-control mb-3" style="max-width:200px" value="${new Date().toISOString().split('T')[0]}">
                <table class="table cv-table"><thead><tr><th>Estudiante</th><th>Presente</th><th>Tardanza</th><th>Falta</th></tr></thead>
                <tbody>${filas}</tbody></table>
                <button class="btn btn-primary rounded-pill" id="btn-guardar-asistencia">Guardar asistencia</button>
            </div>
        </div>`;
}

function bindCursoView(contenedor, onVolverLista) {
    contenedor.querySelector('.btn-volver-lista')?.addEventListener('click', onVolverLista);
    contenedor.querySelector('.btn-ver-asistencia')?.addEventListener('click', () => {
        document.getElementById('sub-asistencia').classList.remove('cv-hidden');
    });
    contenedor.querySelector('.btn-ver-notas')?.addEventListener('click', async () => {
        // Cargar estudiantes con sus notas desde la API
        const data = await apiFetch(`/api/profesor/clase/${cursoActual.id}`);
        cursoActual.estudiantes = data.alumnos || [];
        contenedor.innerHTML = htmlNotasEditables(cursoActual);
        contenedor.querySelector('.btn-volver-curso').onclick = () => {
            contenedor.innerHTML = htmlCurso(cursoActual);
            bindCursoView(contenedor, onVolverLista);
        };
        document.getElementById('btn-guardar-notas')?.addEventListener('click', async () => {
            const inputs = contenedor.querySelectorAll('input[data-alumno]');
            const notas = {};
            inputs.forEach(inp => {
                const a = inp.dataset.alumno, p = inp.dataset.periodo;
                if (!notas[a]) notas[a] = {};
                notas[a][p] = { calificacion: inp.value, periodo: p };
            });
            await apiFetch(`/api/profesor/clase/${cursoActual.id}/notas`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ notas })
            });
            alert('Notas guardadas correctamente.');
        });
    });
    document.getElementById('btn-guardar-asistencia')?.addEventListener('click', async () => {
        const fecha = document.getElementById('fecha-asistencia').value;
        const radios = contenedor.querySelectorAll('[name^="a"]');
        const asistencias = {};
        (cursoActual.estudiantes || []).forEach((e, i) => {
            const sel = contenedor.querySelector(`input[name="a${i}"]:checked`);
            asistencias[e.id] = { estado: sel?.value || 'presente' };
        });
        await apiFetch(`/api/profesor/clase/${cursoActual.id}/asistencia`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ fecha, asistencias })
        });
        alert('Asistencia guardada.');
    });
}

/* ===== ADMIN ===== */
const adminItems = [
    { id: 'resumen',      label: 'Inicio',            icon: 'fa-home' },
    { id: 'avisos-crear', label: 'Crear aviso',       icon: 'fa-plus-circle' },
    { id: 'avisos-lista', label: 'Gestionar avisos',  icon: 'fa-bullhorn' },
];
function renderAdmin(v) {
    const c = document.getElementById('admin-contenido');
    activarNav('sidebar-admin', v);
    const t = { resumen: ['Inicio','Administración'], 'avisos-crear': ['Crear aviso','Publicar en web y padres'], 'avisos-lista': ['Avisos','Publicados'] };
    document.getElementById('admin-titulo').textContent = t[v][0];
    document.getElementById('admin-subtitulo').textContent = t[v][1];
    if (v === 'resumen') {
        c.innerHTML = '<div class="cv-card card-body p-4"><p>Gestiona <strong>avisos</strong> para el sitio público y padres.</p></div>';
    } else if (v === 'avisos-crear') {
        c.innerHTML = `
            <div class="cv-card card-body p-4">
                <form id="form-aviso">
                    <div class="mb-3"><label class="form-label fw-semibold">Título</label><input type="text" class="form-control" name="titulo" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Contenido</label><textarea class="form-control" rows="5" name="contenido" required></textarea></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Imagen (opcional)</label><input type="file" class="form-control" name="imagen" accept="image/*"></div>
                    <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-paper-plane"></i> Publicar aviso</button>
                </form>
            </div>`;
        document.getElementById('form-aviso').onsubmit = async e => {
            e.preventDefault();
            const fd = new FormData(e.target);
            fd.append('_token', CSRF);
            const res = await fetch('/api/admin/avisos', { method: 'POST', headers: { 'Accept': 'application/json' }, body: fd });
            const data = await res.json();
            alert(data.success ? 'Aviso publicado.' : (data.message || 'Error al publicar.'));
        };
    } else {
        apiFetch('/api/admin/avisos').then(data => {
            const rows = (data.avisos || []).map(a => `
                <tr><td>${a.titulo}</td><td>${a.publicado_at || '—'}</td>
                <td><span class="badge bg-success">Publicado</span></td></tr>`).join('');
            c.innerHTML = `<div class="cv-card card-body"><table class="table cv-table">
                <thead><tr><th>Título</th><th>Fecha</th><th>Estado</th></tr></thead>
                <tbody>${rows || '<tr><td colspan="3" class="text-center text-muted">Sin avisos</td></tr>'}</tbody>
            </table></div>`;
        });
    }
}
function initAdmin() {
    const nombre = usuarioActual ? `${usuarioActual.nombres} ${usuarioActual.apellidos}` : '';
    crearSidebar('sidebar-admin', adminItems, renderAdmin, 'logout-admin', nombre);
    renderAdmin('resumen');
}

/* ===== DIRECTOR ===== */
function getDirectorItems() {
    const items = [
        { id: 'resumen',    label: 'Resumen',     icon: 'fa-chart-pie' },
        { id: 'avisos-ver', label: 'Ver avisos',  icon: 'fa-bullhorn' },
    ];
    if (usuarioActual?.esTutor) items.push({ id: 'notas-tutorados', label: 'Notas de tutorados', icon: 'fa-chart-line' });
    return items;
}
function renderDirector(v) {
    const c = document.getElementById('dir-contenido');
    activarNav('sidebar-director', v);
    if (v === 'resumen') {
        document.getElementById('dir-titulo').textContent = 'Resumen';
        c.innerHTML = '<div class="cv-card card-body p-4"><p>Vista general institucional (solo lectura).</p></div>';
    } else if (v === 'avisos-ver') {
        document.getElementById('dir-titulo').textContent = 'Avisos';
        apiFetch('/api/avisos').then(data => {
            const cards = (data.avisos || []).map(a => `
                <div class="cv-aviso-card mb-2">
                    <h6 class="fw-bold mb-1 cv-primary">${a.titulo}</h6>
                    <p class="text-muted small mb-0">${a.contenido}</p>
                </div>`).join('') || '<p class="text-muted">Sin avisos.</p>';
            c.innerHTML = `<div class="cv-card card-body p-4">${cards}</div>`;
        });
    } else if (v === 'notas-tutorados') {
        document.getElementById('dir-titulo').textContent = 'Notas de tutorados';
        apiFetch('/api/profesor/tutorados/notas').then(data => {
            c.innerHTML = renderTablaTutorados(data);
        });
    }
}
function initDirector() {
    const nombre = usuarioActual ? `${usuarioActual.nombres} ${usuarioActual.apellidos}` : '';
    crearSidebar('sidebar-director', getDirectorItems(), renderDirector, 'logout-dir', nombre);
    renderDirector('resumen');
}

/* ===== PROFESOR ===== */
function getProfItems() {
    const items = [
        { id: 'resumen', label: 'Inicio',     icon: 'fa-home' },
        { id: 'cursos',  label: 'Mis cursos', icon: 'fa-book-open' },
    ];
    if (usuarioActual?.esTutor) {
        items.push({ id: 'notas-tutorados', label: 'Notas de tutorados', icon: 'fa-chart-line' });
    }
    return items;
}
function renderProf(v) {
    const c = document.getElementById('prof-contenido');
    activarNav('sidebar-profesor', v);
    if (v === 'resumen') {
        document.getElementById('prof-titulo').textContent = 'Inicio';
        const msg = usuarioActual?.esTutor
            ? `<p class="mt-2">Eres tutor del salón <strong>${usuarioActual.salonTutoria?.nombre || ''}</strong>. Revisa <strong>Notas de tutorados</strong>.</p>`
            : '';
        c.innerHTML = `<div class="cv-card card-body p-4"><p>Bienvenido, ${usuarioActual?.nombres || 'profesor'}.</p>${msg}</div>`;
    } else if (v === 'cursos') {
        document.getElementById('prof-titulo').textContent = 'Mis cursos';
        const cursos = usuarioActual?.cursos || [];
        if (!cursos.length) {
            c.innerHTML = '<div class="cv-card card-body text-center py-5"><i class="fas fa-inbox fa-3x text-muted mb-3"></i><p class="text-muted">Sin cursos asignados.</p></div>';
            return;
        }
        c.innerHTML = `<div class="row g-4">${cursos.map(cu => `
            <div class="col-md-6 col-lg-4"><article class="cv-inst-card">
                <i class="fas fa-book-open cv-primary fa-2x mb-2"></i>
                <h5 class="fw-bold">${cu.nombre}</h5>
                <p class="text-muted">${cu.aula}</p>
                <button type="button" class="btn btn-primary rounded-pill btn-entrar-curso" data-id="${cu.id}">Ingresar al curso</button>
            </article></div>`).join('')}</div>`;
        c.querySelectorAll('.btn-entrar-curso').forEach(btn => {
            btn.onclick = async () => {
                const curso = cursos.find(x => x.id == btn.dataset.id);
                const data  = await apiFetch(`/api/profesor/clase/${curso.id}`);
                cursoActual = { ...curso, estudiantes: data.alumnos || [] };
                c.innerHTML = htmlCurso(cursoActual);
                bindCursoView(c, () => renderProf('cursos'));
            };
        });
    } else if (v === 'notas-tutorados') {
        document.getElementById('prof-titulo').textContent = 'Notas de tutorados';
        apiFetch('/api/profesor/tutorados/notas').then(data => {
            c.innerHTML = renderTablaTutorados(data);
        });
    }
}
function initProfesor() {
    const nombre = usuarioActual ? `${usuarioActual.nombres} ${usuarioActual.apellidos}` : '';
    crearSidebar('sidebar-profesor', getProfItems(), renderProf, 'logout-prof', nombre);
    renderProf('resumen');
}

function renderTablaTutorados(data) {
    if (!data.alumnos?.length) return '<div class="cv-card card-body p-4"><p class="text-muted">Sin tutorados.</p></div>';
    const asigs   = data.asignaciones || [];
    const notas   = data.notas || {};
    const heads   = asigs.map(a => `<th colspan="4" class="text-center">${a.curso}</th>`).join('');
    const subheads = asigs.map(() => '<th>B1</th><th>B2</th><th>B3</th><th>B4</th>').join('');
    const rows = data.alumnos.map(al => {
        const celdas = asigs.map(a => ['B1','B2','B3','B4'].map(p => {
            const n = notas[al.id]?.[a.id]?.[p] ?? '—';
            return `<td class="${n !== '—' && n < 11 ? 'text-danger fw-bold' : ''}">${n}</td>`;
        }).join('')).join('');
        return `<tr><td><strong>${al.apellidos}</strong>, ${al.nombres}</td>${celdas}</tr>`;
    }).join('');
    return `
        <div class="cv-salon-header">
            <h4><i class="fas fa-users me-2"></i>Notas — Salón ${data.salon || ''}</h4>
        </div>
        <div class="cv-card card-body table-responsive">
            <table class="table cv-table table-bordered table-sm">
                <thead><tr><th rowspan="2">Estudiante</th>${heads}</tr><tr>${subheads}</tr></thead>
                <tbody>${rows}</tbody>
            </table>
        </div>`;
}

/* ===== PADRES ===== */
const padresItems = [
    { id: 'notas',  label: 'Notas de mi hijo(a)', icon: 'fa-chart-line' },
    { id: 'avisos', label: 'Avisos',               icon: 'fa-bullhorn' },
    { id: 'cuenta', label: 'Mi cuenta / Gmail',    icon: 'fa-cog' },
];
function renderPadres(v) {
    const c = document.getElementById('pad-contenido');
    activarNav('sidebar-padres', v);
    if (v === 'notas') {
        document.getElementById('pad-titulo').textContent = 'Notas';
        apiFetch('/api/padres/notas').then(data => {
            const rows = (data.notas || []).map(n =>
                `<tr><td>${n.curso}</td><td>${n.B1??'—'}</td><td>${n.B2??'—'}</td><td>${n.B3??'—'}</td><td>${n.B4??'—'}</td></tr>`
            ).join('') || '<tr><td colspan="5" class="text-center text-muted">Sin notas registradas.</td></tr>';
            c.innerHTML = `<div class="cv-card card-body p-4">
                <h5>${data.estudiante || ''}</h5>
                <table class="table cv-table mt-3">
                    <thead><tr><th>Curso</th><th>B I</th><th>B II</th><th>B III</th><th>B IV</th></tr></thead>
                    <tbody>${rows}</tbody>
                </table></div>`;
        });
    } else if (v === 'avisos') {
        document.getElementById('pad-titulo').textContent = 'Avisos';
        apiFetch('/api/avisos').then(data => {
            const cards = (data.avisos || []).map(a => `
                <div class="cv-aviso-card mb-2">
                    <h6 class="fw-bold mb-1 cv-primary">${a.titulo}</h6>
                    <p class="text-muted small mb-0">${a.contenido}</p>
                </div>`).join('') || '<p class="text-muted">Sin avisos.</p>';
            c.innerHTML = `<div class="cv-card card-body p-4">${cards}</div>`;
        });
    } else {
        document.getElementById('pad-titulo').textContent = 'Mi cuenta';
        c.innerHTML = `<div class="cv-card card-body p-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="switch-gmail" ${usuarioActual?.recibir_avisos_email ? 'checked' : ''}>
                <label class="form-check-label" for="switch-gmail">Recibir avisos por <strong>Gmail</strong></label>
            </div>
            <button class="btn btn-primary rounded-pill mt-3" id="btn-guardar-gmail">Guardar preferencia</button>`;
        document.getElementById('btn-guardar-gmail').onclick = async () => {
            const checked = document.getElementById('switch-gmail').checked;
            await apiFetch('/api/padres/cuenta', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ recibir_avisos_email: checked })
            });
            alert('Preferencia guardada.');
        };
    }
}
function initPadres() {
    const nombre = usuarioActual ? `${usuarioActual.nombres} ${usuarioActual.apellidos}` : '';
    crearSidebar('sidebar-padres', padresItems, renderPadres, 'logout-pad', nombre);
    renderPadres('notas');
}

/* ===== Avisos públicos ===== */
fetch('/api/avisos')
    .then(response => response.json())
    .then(data => {
        const contenedor = document.getElementById('lista-avisos-publicos');
        if (data.avisos && data.avisos.length > 0) {
            contenedor.innerHTML = data.avisos.map(aviso => {
                
                // ¡ESTA ES LA PARTE QUE CREA EL LUGAR PARA LA IMAGEN!
                let imagenHtml = '';
                if (aviso.imagen) {
                    // Solo si hay imagen, arma la etiqueta <img>
                    imagenHtml = `<img src="/storage/${aviso.imagen}" class="img-fluid rounded mb-3 w-100" style="max-height: 250px; object-fit: cover;" alt="${aviso.titulo}">`;
                }

                return `
                <div class="cv-aviso-card mb-4">
                    ${imagenHtml} 
                    <h5 class="fw-bold cv-primary">${aviso.titulo}</h5>
                    <p class="mb-2">${aviso.contenido}</p>
                    <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> ${aviso.publicado_at}</small>
                </div>
            `}).join('');
        } else {
             contenedor.innerHTML = '<p class="text-muted text-center">No hay avisos publicados en este momento.</p>';
        }
    })
    .catch(error => console.error('Error cargando avisos:', error));
</script>
</body>
</html>
