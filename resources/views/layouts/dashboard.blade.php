@extends('layouts.profesor')

@section('title', 'Mi panel')

@section('content')
    {{-- Banner de tutoría (solo si es tutor) --}}
    @if ($esTutor)
        <section class="cv-tutoria-banner" aria-label="Acceso a tutoría">
            <div>
                <span class="cv-badge-primary mb-2 d-inline-block"
                      style="background:rgba(255,255,255,.2); color:#fff;">
                    Tutoría
                </span>
                <h3 class="h4 mb-1">
                    <i class="fas fa-door-open me-2"></i> Mi Salón / Tutoría
                </h3>
                <p class="mb-0 opacity-90">
                    Eres tutor del salón
                    <strong>{{ $salonTutoria['nombre'] ?? '—' }}</strong>
                    @if (!empty($salonTutoria['grado']))
                        ({{ $salonTutoria['grado'] }})
                    @endif
                </p>
            </div>
            <a href="{{ route('profesor.tutorados.notas') }}" class="btn btn-light">
                <i class="fas fa-chart-line me-1"></i> Ver notas de tutorados
            </a>
        </section>
    @endif

    {{-- Mis cursos --}}
    <section id="cursos">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <span class="cv-badge-primary">Docencia</span>
                <h2 class="cv-page-title mt-2 mb-0">Mis Cursos</h2>
            </div>
            <span class="text-muted small">
                {{ count($cursos) }} curso{{ count($cursos) !== 1 ? 's' : '' }} asignado{{ count($cursos) !== 1 ? 's' : '' }}
            </span>
        </div>

        @if (count($cursos) > 0)
            <div class="row g-4">
                @foreach ($cursos as $asignacion)
                    <div class="col-md-6 col-lg-4">
                        <article class="cv-inst-card h-100">
                            <i class="fas fa-book-open"></i>
                            <h4 class="h5 fw-bold mb-2">{{ $asignacion->curso->nombre ?? 'Materia' }}</h4>
                            <p class="text-muted mb-3">
                                <i class="fas fa-school me-1"></i>
                                Salón: <strong>{{ $asignacion->aula->grado ?? '' }}° "{{ $asignacion->aula->seccion ?? '' }}"</strong>
                                @if ($asignacion->aula->turno)
                                    <br><i class="fas fa-clock me-1"></i> {{ $asignacion->aula->turno }}
                                @endif
                            </p>
                            <a href="{{ route('profesor.clase', $asignacion->id) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="fas fa-arrow-right me-1"></i> Ingresar al curso
                            </a>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="cv-card card-body text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">Aún no tienes cursos asignados.</p>
            </div>
        @endif
    </section>
@endsection
