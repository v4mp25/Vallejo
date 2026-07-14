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
    <div class="cv-dashboard-wrap">
        <aside class="cv-sidebar">
            <div class="cv-sidebar-brand">
                <img src="{{ asset('img/Yachachin1.png') }}" alt="I.E. César Vallejo"
                     onerror="this.outerHTML='<i class=\'fas fa-graduation-cap fa-2x mb-2\' style=\'color:#fff\'></i>'">
                <h5 class="mb-0 mt-2">Portal del Alumno</h5>
            </div>

            <nav class="cv-sidebar-nav">
                <div class="cv-sidebar-group-title px-3 py-2 text-uppercase small opacity-75"
                     style="font-size:.7rem; letter-spacing:1px;">
                    Mi panel
                </div>

                <a href="{{ route('alumno.dashboard') }}"
                   class="{{ request()->routeIs('alumno.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="{{ route('aula-virtual.index') }}"
                   class="{{ request()->routeIs('aula-virtual.*') ? 'active' : '' }}">
                    <i class="fas fa-laptop-house"></i> Aula Virtual
                </a>
                <a href="{{ route('alumno.psicologia.index') }}"
                   class="{{ request()->routeIs('alumno.psicologia.*') ? 'active' : '' }}">
                    <i class="fas fa-brain"></i> Psicología
                </a>
                <a href="{{ route('alumno.auxiliar.index') }}"
                   class="{{ request()->routeIs('alumno.auxiliar.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Auxiliar
                </a>
            </nav>

            <div class="p-3 border-top border-light border-opacity-25">
                <div class="text-center mb-2 small opacity-75">
                    {{ Auth::user()->nombres ?? Auth::user()->name ?? 'Alumno' }} {{ Auth::user()->apellidos ?? '' }}
                </div>
                <button type="button" class="btn btn-outline-light btn-sm w-100"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                </button>
            </div>
        </aside>

        <div class="cv-main">
            @include('partials.topbar')

            <main class="cv-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
