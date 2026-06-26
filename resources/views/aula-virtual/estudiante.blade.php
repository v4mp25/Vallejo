@extends('layouts.alumno')

@section('title', 'Aula Virtual')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="mb-3">Materiales disponibles</h3>
                    @if ($materials->isEmpty())
                        <p class="text-muted">No hay materiales publicados todavía.</p>
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
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="mb-3">Entregar tarea</h3>
                    <form action="{{ route('aula-virtual.tarea.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
