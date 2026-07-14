<aside class="cv-sidebar">
    <div class="cv-sidebar-brand py-4 px-3" style="background: linear-gradient(135deg, #01367c 0%, #0148A4 100%); border-bottom: 1px solid rgba(255, 255, 255, 0.12);">
        <div class="d-flex align-items-center gap-3 justify-content-start text-start">
            <img src="{{ asset('img/Yachachin1.png') }}" alt="I.E. César Vallejo" style="height: 50px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));" onerror="this.style.display='none'">
            <div class="d-flex flex-column">
                <span class="fw-bold text-white text-uppercase" style="font-size: 0.95rem; letter-spacing: 1px; line-height: 1.2;">César Vallejo</span>
                <span class="text-warning fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1.5px; margin-top: 2px;">Administración</span>
            </div>
        </div>
    </div>
    <nav class="cv-sidebar-nav">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
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
        <a href="{{ route('admin.docentes.index') }}"
           class="{{ request()->routeIs('admin.docentes*') ? 'active' : '' }}">
            <i class="fas fa-id-card"></i> Gestión de Personal
        </a>

        <a href="#submenuEstudiantes" data-bs-toggle="collapse" role="button"
           aria-expanded="{{ request()->routeIs('admin.estudiantes.*') ? 'true' : 'false' }}"
           aria-controls="submenuEstudiantes"
           class="cv-sidebar-toggle {{ request()->routeIs('admin.estudiantes.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i> Gestión de Estudiantes
            <i class="fas fa-chevron-down cv-sidebar-toggle-caret ms-auto"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.estudiantes.*') ? 'show' : '' }}" id="submenuEstudiantes">
            <a href="{{ route('admin.estudiantes.importar') }}"
               class="cv-sidebar-sublink {{ request()->routeIs('admin.estudiantes.importar*') ? 'active' : '' }}">
                <i class="fas fa-file-excel"></i> Importar Alumnos
            </a>
        </div>
        <a href="{{ route('admin.metricas.index') }}"
           class="{{ request()->routeIs('admin.metricas*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Métricas
        </a>

        {{-- Configuración Avanzada: solo visible para el rol Director --}}
        @if(auth()->user()->rol === 'director')
            <a href="#submenuConfigAvanzada" data-bs-toggle="collapse" role="button"
               aria-expanded="{{ request()->routeIs('admin.cierre-ano.*') ? 'true' : 'false' }}"
               aria-controls="submenuConfigAvanzada"
               class="cv-sidebar-toggle {{ request()->routeIs('admin.cierre-ano.*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i> Configuración Avanzada
                <i class="fas fa-chevron-down cv-sidebar-toggle-caret ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.cierre-ano.*') ? 'show' : '' }}" id="submenuConfigAvanzada">
                <a href="{{ route('admin.cierre-ano.index') }}"
                   class="cv-sidebar-sublink {{ request()->routeIs('admin.cierre-ano.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Cierre de Año Académico
                </a>
            </div>
        @endif
    </nav>
    <div class="p-3 border-top border-light border-opacity-25">
        <button type="button" class="btn btn-outline-light btn-sm w-100"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
        </button>
    </div>
</aside>

@push('styles')
<style>
    .cv-sidebar-toggle { cursor: pointer; }
    .cv-sidebar-toggle-caret { transition: transform 0.2s ease; font-size: 0.75rem; }
    .cv-sidebar-toggle[aria-expanded="true"] .cv-sidebar-toggle-caret { transform: rotate(180deg); }
    .cv-sidebar-sublink {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px 10px 46px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
    }
    .cv-sidebar-sublink:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }
    .cv-sidebar-sublink.active {
        background: rgba(255, 255, 255, 0.15);
        color: #ffc107;
        border-left: 4px solid #ffc107;
        padding-left: 42px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.cv-sidebar-toggle[data-bs-toggle="collapse"]').forEach(function (toggle) {
            var targetId = toggle.getAttribute('href');
            var submenu = targetId ? document.querySelector(targetId) : null;
            if (!submenu) return;

            submenu.addEventListener('show.bs.collapse', function () { toggle.setAttribute('aria-expanded', 'true'); });
            submenu.addEventListener('hide.bs.collapse', function () { toggle.setAttribute('aria-expanded', 'false'); });
        });
    });
</script>
@endpush
