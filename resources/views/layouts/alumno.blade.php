<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Alumno') — I.E. César Vallejo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/cesarvallejo.css') }}" rel="stylesheet">
</head>
<body class="cv-app">
    <div class="cv-main" style="min-height: 100vh;">
        <div class="cv-topbar">
            <div>
                <h1 class="cv-page-title mb-0">Portal del Alumno</h1>
                <p class="text-muted small mb-0">I.E. César Vallejo</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('aula-virtual.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-laptop-house me-1"></i> Aula Virtual
                </a>
                <button type="button" class="btn btn-outline-danger btn-sm"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-1"></i> Salir
                </button>
            </div>
        </div>
        <main class="cv-content">
            @yield('content')
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
