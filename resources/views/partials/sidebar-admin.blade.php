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
        <a href="{{ route('admin.institucion.index') }}"
           class="{{ request()->routeIs('admin.institucion*') ? 'active' : '' }}">
            <i class="fas fa-school"></i> Nuestra Institución
        </a>
        <a href="{{ route('admin.gestion-institucional.index') }}"
           class="{{ request()->routeIs('admin.gestion-institucional*') ? 'active' : '' }}">
            <i class="fas fa-folder-open"></i> Gestión Institucional
        </a>
        <a href="{{ route('admin.servicio-educativo.index') }}"
           class="{{ request()->routeIs('admin.servicio-educativo*') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i> Servicio Educativo
        </a>
        <a href="{{ route('admin.comunidad-educativa.index') }}"
           class="{{ request()->routeIs('admin.comunidad-educativa*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Comunidad Educativa
        </a>
        <a href="{{ route('admin.logros.index') }}"
           class="{{ request()->routeIs('admin.logros*') ? 'active' : '' }}">
            <i class="fas fa-trophy"></i> Logros y Reconocimientos
        </a>
        <a href="{{ route('admin.galeria-institucional.index') }}"
           class="{{ request()->routeIs('admin.galeria-institucional*') ? 'active' : '' }}">
            <i class="fas fa-images"></i> Galería Institucional
        </a>
        <a href="{{ route('admin.noticias-comunicados.index') }}"
           class="{{ request()->routeIs('admin.noticias-comunicados*') ? 'active' : '' }}">
            <i class="fas fa-bullhorn"></i> Noticias y Comunicados
        </a>
    </nav>
    <div class="p-3 border-top border-light border-opacity-25">
        <button type="button" class="btn btn-outline-light btn-sm w-100"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
        </button>
    </div>
</aside>
