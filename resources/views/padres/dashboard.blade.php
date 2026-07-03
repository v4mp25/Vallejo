@extends('layouts.alumno')

@section('title', 'Panel de Padres')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h2 class="h4 fw-bold mb-1">Bienvenido, {{ auth()->user()->nombre_completo }}</h2>
                            <p class="text-muted mb-0">Aquí encontrarás avisos, notas y acceso rápido a tu cuenta.</p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            <i class="fas fa-user-shield me-2"></i> Acceso padre de familia
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning-subtle text-warning rounded-circle p-2 me-2">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Avisos e información</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-semibold mb-1">Entrega de libretas</h6>
                                    <p class="text-muted small mb-0">Se informará por este canal el cronograma del primer bimestre.</p>
                                </div>
                                <span class="badge bg-danger-subtle text-danger">Importante</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-semibold mb-1">Reunión de apoderados</h6>
                                    <p class="text-muted small mb-0">Próxima reunión programada para el viernes a las 6:00 p.m.</p>
                                </div>
                                <span class="badge bg-primary-subtle text-primary">Programado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle p-2 me-2">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Resumen académico</h5>
                    </div>
                    <div class="text-center py-4">
                        <i class="fas fa-book-open fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Consulta tus notas y seguimiento académico en un solo lugar.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success-subtle text-success rounded-circle p-2 me-2">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Notas recientes</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Periodo</th>
                                    <th>Nota</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Comunicación</td>
                                    <td>Primer bimestre</td>
                                    <td>16</td>
                                    <td><span class="badge bg-success-subtle text-success">Bueno</span></td>
                                </tr>
                                <tr>
                                    <td>Matemática</td>
                                    <td>Primer bimestre</td>
                                    <td>14</td>
                                    <td><span class="badge bg-warning-subtle text-warning">En proceso</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-secondary-subtle text-secondary rounded-circle p-2 me-2">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Mi cuenta</h5>
                    </div>
                    <form method="POST" action="{{ route('api.padres.cuenta') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Usuario</label>
                            <input type="text" name="codigo_usuario" class="form-control" value="{{ auth()->user()->codigo_usuario }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="recibir_avisos_email" value="1" {{ auth()->user()->padre?->recibir_avisos_email ? 'checked' : '' }}>
                            <label class="form-check-label">Recibir avisos por correo</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-save me-2"></i>Guardar cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
