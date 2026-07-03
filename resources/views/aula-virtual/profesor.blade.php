@extends('layouts.profesor')

@section('title', 'Aula Virtual')
@section('page_title', 'Aula Virtual')
@section('page_subtitle', 'Gestiona recursos, tareas y entregas')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1">Publicar material</h4>
                        <p class="text-muted mb-0">Comparte recursos, enlaces o videos con tus estudiantes.</p>
                    </div>
                </div>
                <form action="{{ route('aula-virtual.material.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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

    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Programar tarea</h4>
                <form action="{{ route('aula-virtual.tarea.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Material asociado</label>
                        <select name="material_id" class="form-select" required>
                            <option value="">Seleccione un recurso</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Título de la tarea</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de entrega</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo de respuesta (opcional)</label>
                        <input type="file" name="submission_file" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texto de respuesta (opcional)</label>
                        <textarea name="submission_text" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">Guardar tarea</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 card shadow-sm border-0">
    <div class="card-body">
        <h4 class="mb-3">Recursos recientes</h4>
        @if ($materials->isEmpty())
            <p class="text-muted mb-0">Aún no hay materiales publicados.</p>
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
@endsection
