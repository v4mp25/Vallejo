@extends('layouts.profesor')

@section('title', 'Mi panel')

@section('page_title', 'Panel del Profesor')
@section('page_subtitle', 'Gestiona tus cursos y tutoría')

@section('content')
    {{-- Candado de Tutoría: solo visible si el profesor es tutor de algún salón --}}
    @if ($esTutor ?? false)
        <section class="cv-tutoria-banner" aria-label="Acceso a tutoría">
            <div>
                <span class="cv-badge-primary mb-2 d-inline-block">Tutoría</span>
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
        </section>
    @endif

    <section>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <span class="cv-badge-primary">Docencia</span>
                <h2 class="cv-page-title mt-2 mb-0">Mis Cursos</h2>
            </div>
        </div>

        @if (count($cursos ?? []) > 0)
            <div class="row g-4">
                @foreach ($cursos as $curso)
                    <div class="col-md-6 col-lg-4">
                        <article class="cv-inst-card h-100">
                            <i class="fas fa-book-open"></i>
                            
                            {{-- Aquí sacamos el nombre del curso desde la base de datos --}}
                            <h4 class="h5 fw-bold mb-2">{{ $curso->curso->nombre ?? 'Materia' }}</h4>
                            
                            <p class="text-muted mb-3">
                                <i class="fas fa-school me-1"></i>
                                {{-- Aquí sacamos el grado y sección desde la base de datos --}}
                                Salón: <strong>{{ $curso->aula->grado ?? '' }} "{{ $curso->aula->seccion ?? '' }}"</strong>
                            </p>
                            
                            {{-- ESTE ES EL BOTÓN CONECTADO. Al darle clic, lleva a la ruta para ver los alumnos --}}
                            <a href="{{ route('profesor.clase', $curso->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                Ingresar al curso
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