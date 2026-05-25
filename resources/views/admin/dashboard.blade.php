@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page_title', 'Panel de Administración')
@section('page_subtitle', 'Resumen del sistema escolar')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="cv-card card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-cv-primary bg-opacity-10 p-3">
                    <i class="fas fa-chalkboard-teacher fa-2x cv-primary"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Profesores registrados</p>
                    <h3 class="mb-0 fw-bold">{{ $profesores->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cv-card card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-cv-primary bg-opacity-10 p-3">
                    <i class="fas fa-door-open fa-2x cv-primary"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Aulas creadas</p>
                    <h3 class="mb-0 fw-bold">{{ $aulas->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cv-card card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-cv-primary bg-opacity-10 p-3">
                    <i class="fas fa-file-excel fa-2x cv-primary"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Importación masiva</p>
                    <a href="{{ route('admin.importar-profesores') }}" class="fw-semibold cv-primary text-decoration-none">
                        Importar profesores →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="cv-card card">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-users me-2 cv-primary"></i> Profesores registrados</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover cv-table mb-0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>Cursos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($profesores as $profesor)
                                    <tr>
                                        <td>{{ $profesor->nombre }}</td>
                                        <td>{{ $profesor->dni }}</td>
                                        <td class="small text-muted">{{ $profesor->cursos ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Sin profesores aún.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="cv-card card">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-school me-2 cv-primary"></i> Aulas creadas</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover cv-table mb-0">
                            <thead>
                                <tr>
                                    <th>Aula</th>
                                    <th>Grado</th>
                                    <th>Tutor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($aulas as $aula)
                                    <tr>
                                        <td>{{ $aula->nombre }}</td>
                                        <td>{{ $aula->grado }}</td>
                                        <td>{{ $aula->tutor }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Sin aulas aún.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
