@extends('layouts.profesor')

@section('title', 'Aula Virtual')
@section('page_title', 'Aula Virtual')
@section('page_subtitle', 'Gestiona recursos, tareas y entregas')

@section('content')
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
                <h5 class="mb-3">Avisos</h5>
                @if ($avisos->isEmpty())
                    <p class="text-muted">No hay avisos publicados.</p>
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
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1">Aula Virtual</h4>
                        <p class="text-muted mb-0">Organiza recursos y tareas por curso y sección.</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary">{{ $selectedCourseName ?? 'Seleciona un curso' }}</span>
                        @if ($selectedSection)
                            <span class="badge bg-secondary">{{ $selectedSection }}</span>
                        @endif
                    </div>
                </div>

                @if (! isset($selectedAssignmentId))
                    <div class="alert alert-info mb-0">
                        Selecciona un curso en el menú izquierdo para ver recursos y tareas.
                    </div>
                @else
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-book-open fa-lg text-primary"></i>
                                    <div>
                                        <h6 class="mb-0">Recursos</h6>
                                        <small class="text-muted">Materiales disponibles</small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, max(5, $materials->count() * 20)) }}%;"></div>
                                </div>
                                <div class="small text-muted mt-2">{{ $materials->count() }} recurso(s)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-tasks fa-lg text-success"></i>
                                    <div>
                                        <h6 class="mb-0">Tareas</h6>
                                        <small class="text-muted">Entrega trabajos y revisa pendientes</small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, max(5, $tasks->count() * 20)) }}%;"></div>
                                </div>
                                <div class="small text-muted mt-2">{{ $tasks->count() }} tarea(s) asociadas</div>
                            </div>
                        </div>
                    </div>

                    @if ($materials->isEmpty())
                        <div class="alert alert-warning mt-3 mb-0">
                            No hay materiales publicados para este curso.
                        </div>
                    @else
                        <div class="list-group mt-3">
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
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Publicar material</h5>
                <form action="{{ route('aula-virtual.material.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="curso_id" value="{{ $selectedCourseId ?? '' }}">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de recurso</label>
                            <select name="resource_type" class="form-select">
                                <option value="file">Archivo</option>
                                <option value="video">Video</option>
                                <option value="link">Enlace</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de entrega</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Archivo</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Enlace externo</label>
                            <input type="url" name="external_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Publicar recurso</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
