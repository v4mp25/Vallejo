@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fas fa-home me-2 text-primary"></i> Panel Principal</h2>
        <span class="text-muted">{{ now()->format('d de F, Y') }}</span>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card bg-primary text-white border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1" style="letter-spacing: 1px; font-size: 0.8rem;">Docentes</h6>
                            <h2 class="mb-0 fw-bold">{{ $profesores->count() }}</h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fas fa-chalkboard-teacher"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card bg-success text-white border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-semibold mb-1" style="letter-spacing: 1px; font-size: 0.8rem;">Aulas Activas</h6>
                            <h2 class="mb-0 fw-bold">{{ $aulas->count() }}</h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fas fa-school"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.configuracion') }}" class="text-decoration-none">
                <div class="card bg-warning text-dark border-0 shadow-sm h-100 rounded-4 cv-card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1" style="letter-spacing: 1px; font-size: 0.8rem;">Portal Web</h6>
                                <h5 class="mb-0 fw-bold mt-2">Configurar</h5>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fas fa-globe"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('importar-alumnos') }}" class="text-decoration-none">
                <div class="card bg-danger text-white border-0 shadow-sm h-100 rounded-4 cv-card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1" style="letter-spacing: 1px; font-size: 0.8rem;">Herramientas</h6>
                                <h5 class="mb-0 fw-bold mt-2">Importar Datos</h5>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fas fa-file-excel"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-list me-2 text-primary"></i> Últimos Docentes Registrados</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre Completo</th>
                            <th>DNI</th>
                            <th>Usuario</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profesores->take(6) as $profe)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    {{ $profe->nombres }} {{ $profe->apellidos }}
                                </div>
                            </td>
                            <td>{{ $profe->dni ?? 'N/A' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $profe->codigo_usuario ?? 'N/A' }}</span></td>
                            <td class="text-muted small">{{ $profe->created_at ? $profe->created_at->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No hay docentes registrados en el sistema.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .cv-card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .cv-card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
@endsection