<aside class="cv-sidebar">
    <div class="cv-sidebar-brand">
        <img src="{{ asset('img/Yachachin1.png') }}" alt="I.E. César Vallejo" onerror="this.style.display='none'">
        <h5 class="mb-0 mt-2">Administración</h5>
    </div>
    <nav class="cv-sidebar-nav">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('admin.importar-profesores') }}"
           class="{{ request()->routeIs('admin.importar-profesores*') ? 'active' : '' }}">
            <i class="fas fa-file-excel"></i> Importar Profesores
        </a>
    </nav>
    <div class="p-3 border-top border-light border-opacity-25">
        <button type="button" class="btn btn-outline-light btn-sm w-100"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
        </button>
    </div>
</aside>
