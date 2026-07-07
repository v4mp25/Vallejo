@extends('layouts.alumno')

@section('title', 'Aula Virtual')
@section('page_title', 'Aula Virtual')
@section('page_subtitle', 'Selecciona un curso para comenzar')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Mis cursos</h5>
                    @if ($courses->isEmpty())
                        <p class="text-muted">No tienes cursos asignados aún.</p>
                    @else
                        <div class="list-group">
                            @foreach ($courses as $curso)
                                <a href="?asignacion_id={{ $curso->id }}" class="list-group-item list-group-item-action {{ isset($selectedAssignmentId) && $selectedAssignmentId == $curso->id ? 'active' : '' }}">
                                    <strong>{{ $curso->curso->nombre ?? 'Curso' }}</strong>
                                    <div class="small text-muted">Sección {{ $curso->aula->seccion ?? '—' }} · {{ $curso->aula->grado ?? '—' }}</div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-3">Avisos rápidos</h5>
                    @if ($avisos->isEmpty())
                        <p class="text-muted">No hay avisos para este curso.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($avisos as $aviso)
                                <li class="list-group-item px-0 py-2 border-0">
                                    <strong>{{ Str::limit($aviso->titulo, 25) }}</strong>
                                    <div class="small text-muted">{{ Str::limit($aviso->contenido, 55) }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <div class="cv-card p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h4 class="mb-1">Aula Virtual</h4>
                                <p class="text-muted mb-0">Visualiza los recursos y entregas del curso seleccionado.</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">{{ $selectedCourseName ?? 'Sin curso seleccionado' }}</span>
                                @if ($selectedSection)
                                    <span class="badge bg-secondary">{{ $selectedSection }}</span>
                                @endif
                            </div>
                        </div>

                        @if (! isset($selectedAssignmentId))
                            <div class="alert alert-info mb-0">
                                Selecciona un curso a la izquierda para ver tus recursos disponibles.
                            </div>
                        @else
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="cv-status-card">
                                        <div class="icon bg-primary bg-opacity-15 text-primary mb-3">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <div class="h5 mb-1">{{ $materials->count() }}</div>
                                        <div class="text-muted">Recursos</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="cv-status-card">
                                        <div class="icon bg-success bg-opacity-15 text-success mb-3">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                        <div class="h5 mb-1">{{ $avisos->count() }}</div>
                                        <div class="text-muted">Avisos</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="cv-status-card">
                                        <div class="icon bg-warning bg-opacity-15 text-warning mb-3">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="h5 mb-1">{{ $materials->whereNotNull('due_date')->count() }}</div>
                                        <div class="text-muted">Con fecha</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="cv-inst-card h-100">
                        <i class="fas fa-bullhorn"></i>
                        <h5 class="mt-3 mb-2">Avisos del curso</h5>
                        <p class="small text-muted mb-3">Revisa los mensajes más recientes de tu profesor.</p>
                        @if ($avisos->isEmpty())
                            <p class="text-muted small mb-0">No hay avisos nuevos.</p>
                        @else
                            <ul class="list-unstyled mb-0">
                                @foreach ($avisos->take(3) as $aviso)
                                    <li class="mb-2">
                                        <strong>{{ Str::limit($aviso->titulo, 28) }}</strong>
                                        <div class="small text-muted">{{ Str::limit($aviso->contenido, 68) }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Recursos disponibles</h5>
                    @if (! isset($selectedAssignmentId))
                        <div class="alert alert-info mb-0">
                            Selecciona un curso a la izquierda para comenzar.
                        </div>
                    @elseif ($materials->isEmpty())
                        <div class="alert alert-warning mb-0">
                            No hay recursos publicados todavía para este curso.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach ($materials as $material)
                                <a href="{{ route('aula-virtual.show', $material) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between gap-3">
                                        <div>
                                            <h6 class="mb-1">{{ $material->title }}</h6>
                                            <p class="mb-1 text-muted small">{{ Str::limit($material->description ?? 'Sin descripción', 100) }}</p>
                                            <span class="badge bg-light text-dark">{{ ucfirst($material->resource_type) }}</span>
                                        </div>
                                        <div class="text-muted small text-end">
                                            <div>{{ optional($material->due_date)->format('d/m/Y') ?? 'Sin fecha' }}</div>
                                            <div>Por {{ $material->user->nombres ?? 'Docente' }}</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-3">Subir tarea</h5>
                    @if (! isset($selectedAssignmentId))
                        <div class="alert alert-secondary mb-0">
                            Primero selecciona un curso y luego podrás subir tu tarea.
                        </div>
                    @elseif ($materials->isEmpty())
                        <div class="alert alert-warning mb-0">
                            No hay recursos disponibles para entregar tareas en este curso.
                        </div>
                    @else
                        <form action="{{ route('aula-virtual.tarea.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="curso_id" value="{{ $selectedCourseId ?? '' }}">
                            <div class="mb-3">
                                <label class="form-label">Material</label>
                                <select name="material_id" class="form-select" required>
                                    <option value="">Seleccione un recurso</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">{{ $material->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Respuesta</label>
                                <textarea name="submission_text" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Archivo adjunto</label>
                                <input type="file" name="submission_file" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar tarea</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
