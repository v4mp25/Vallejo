@extends('layouts.admin')

@section('title', 'Métricas de Cumplimiento')

@section('content')
<section class="mb-4">
    <div class="cv-card card-body p-4 shadow-sm border-0">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="cv-badge-primary mb-2 d-inline-block">Aula Virtual</span>
                <h2 class="cv-page-title mt-1 mb-1">Métricas de Cumplimiento Docente</h2>
                <p class="text-muted mb-0">Monitorea el cumplimiento y la actividad de los profesores al subir material educativo y tareas.</p>
            </div>
        </div>
    </div>
</section>

{{-- Filtros de Bimestre --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-dark"><i class="fas fa-filter text-primary me-2"></i>Filtrar por Bimestre:</span>
            @if ($bimestreSeleccionado !== 'all')
                <span class="badge bg-primary text-uppercase" style="font-size:0.8rem;">
                    Bimestre: {{ str_replace('B', '', $bimestreSeleccionado) }}
                </span>
            @else
                <span class="badge bg-secondary text-uppercase" style="font-size:0.8rem;">
                    Todos los Bimestres
                </span>
            @endif
        </div>
        <div class="btn-group" role="group" aria-label="Filtrar por Bimestre">
            <a href="{{ route('admin.metricas.index', ['bimestre' => 'all']) }}" 
               class="btn btn-outline-primary px-3 rounded-start-pill {{ $bimestreSeleccionado === 'all' ? 'active text-white' : '' }}">
                Todos los Bimestres
            </a>
            <a href="{{ route('admin.metricas.index', ['bimestre' => 'B1']) }}" 
               class="btn btn-outline-primary px-3 {{ $bimestreSeleccionado === 'B1' ? 'active text-white' : '' }}">
                Bimestre I
            </a>
            <a href="{{ route('admin.metricas.index', ['bimestre' => 'B2']) }}" 
               class="btn btn-outline-primary px-3 {{ $bimestreSeleccionado === 'B2' ? 'active text-white' : '' }}">
                Bimestre II
            </a>
            <a href="{{ route('admin.metricas.index', ['bimestre' => 'B3']) }}" 
               class="btn btn-outline-primary px-3 {{ $bimestreSeleccionado === 'B3' ? 'active text-white' : '' }}">
                Bimestre III
            </a>
            <a href="{{ route('admin.metricas.index', ['bimestre' => 'B4']) }}" 
               class="btn btn-outline-primary px-3 rounded-end-pill {{ $bimestreSeleccionado === 'B4' ? 'active text-white' : '' }}">
                Bimestre IV
            </a>
        </div>
    </div>
</div>

{{-- Tarjetas de Estadísticas --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Docentes Registrados</small>
                    <h3 class="mb-0 text-primary fw-bold">{{ $totalDocentes }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                    <i class="fas fa-chalkboard-teacher fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Materiales de Estudio</small>
                    <h3 class="mb-0 text-info fw-bold">{{ $totalMateriales }}</h3>
                </div>
                <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                    <i class="fas fa-book-reader fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Tareas Creadas</small>
                    <h3 class="mb-0 text-warning fw-bold">{{ $totalTareas }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                    <i class="fas fa-tasks fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Total Recursos Subidos</small>
                    <h3 class="mb-0 text-success fw-bold">{{ $totalRecursos }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                    <i class="fas fa-cloud-upload-alt fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabla de Cumplimiento Docente --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line text-primary me-2"></i>Actividad de Aula Virtual por Docente</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle cv-table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Docente</th>
                        <th class="text-center">Materiales de Estudio</th>
                        <th class="text-center">Tareas</th>
                        <th class="text-center">Total Recursos</th>
                        <th>Última Publicación</th>
                        <th>Nivel de Actividad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metricasDocentes as $metrica)
                        <tr>
                            <td class="fw-bold text-dark">
                                {{ $metrica['docente']->apellidos }}, {{ $metrica['docente']->nombres }}
                                <br>
                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Código: {{ $metrica['docente']->codigo_usuario }}</small>
                            </td>
                            <td class="text-center fs-5 text-secondary">{{ $metrica['materiales'] }}</td>
                            <td class="text-center fs-5 text-secondary">{{ $metrica['tareas'] }}</td>
                            <td class="text-center fs-5 fw-bold text-primary">{{ $metrica['total'] }}</td>
                            <td>
                                <span class="small text-muted">
                                    <i class="far fa-clock me-1"></i>{{ $metrica['ultimo_subido'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $metrica['color'] }} px-3 py-2 rounded-pill fw-semibold text-uppercase" style="font-size: 0.75rem;">
                                    {{ $metrica['cumplimiento'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tabla de Actividad de Psicología --}}
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-0 pt-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-brain text-primary me-2"></i>Actividad del Departamento de Psicología</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle cv-table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Psicólogo(a)</th>
                        <th class="text-center">Citas Pendientes / Programadas</th>
                        <th class="text-center">Citas Atendidas con Éxito</th>
                        <th class="text-center">Materiales y Tests Publicados</th>
                        <th>Último Material Publicado</th>
                        <th>Nivel de Actividad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metricasPsicologos as $metrica)
                        <tr>
                            <td class="fw-bold text-dark">
                                {{ $metrica['psicologo']->apellidos }}, {{ $metrica['psicologo']->nombres }}
                                <br>
                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Código: {{ $metrica['psicologo']->codigo_usuario }}</small>
                            </td>
                            <td class="text-center fs-5 text-secondary">{{ $metrica['citas_pendientes'] }}</td>
                            <td class="text-center fs-5 text-success fw-bold">{{ $metrica['citas_atendidas'] }}</td>
                            <td class="text-center fs-5 text-secondary">{{ $metrica['materiales'] }}</td>
                            <td>
                                <span class="small text-muted">
                                    <i class="far fa-clock me-1"></i>{{ $metrica['ultimo_subido'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $metrica['color'] }} px-3 py-2 rounded-pill fw-semibold text-uppercase" style="font-size: 0.75rem;">
                                    {{ $metrica['cumplimiento'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
