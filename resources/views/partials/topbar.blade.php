<header class="cv-topbar">
    <div>
        <h1 class="cv-page-title mb-0">@yield('page_title', 'Panel')</h1>
        @hasSection('page_subtitle')
            <p class="text-muted small mb-0">@yield('page_subtitle')</p>
        @endif
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted d-none d-md-inline">
            <i class="fas fa-user-circle me-1"></i>
            {{ auth()->user()->name ?? auth()->user()->nombre ?? 'Usuario' }}
        </span>
    </div>
</header>
