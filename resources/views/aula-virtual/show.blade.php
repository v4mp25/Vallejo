@extends(auth()->user()->rol === 'alumno' || auth()->user()->rol === 'padre' ? 'layouts.alumno' : (auth()->user()->rol === 'admin' || auth()->user()->rol === 'director' ? 'layouts.admin' : 'layouts.profesor'))

@section('title', 'Detalle del recurso')
@section('page_title', 'Detalle del recurso')
@section('page_subtitle', 'Consulta el material compartido')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h3 class="mb-3">{{ $material->title }}</h3>
        <p class="text-muted">{{ $material->description }}</p>
        <div class="mb-3">
            <span class="badge bg-primary">{{ ucfirst($material->resource_type) }}</span>
            @if ($material->due_date)
                <span class="badge bg-secondary">Entrega: {{ $material->due_date->format('d/m/Y') }}</span>
            @endif
        </div>
        @if ($material->file_path)
            <a href="{{ Storage::disk('public')->url($material->file_path) }}" class="btn btn-outline-primary" target="_blank">Descargar recurso</a>
        @endif
        @if ($material->external_url)
            <a href="{{ $material->external_url }}" class="btn btn-outline-secondary" target="_blank">Abrir enlace</a>
        @endif
    </div>
</div>
@endsection
