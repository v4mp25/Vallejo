@extends('layouts.profesor')

@section('title', 'Derivaciones a Psicología')
@section('page_title', 'Módulo de Psicología')
@section('page_subtitle', 'Deriva estudiantes para acompañamiento psicológico')

@section('content')
    <section class="mb-4">
        <div class="cv-card card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="cv-badge-primary">Psicología</span>
                    <h2 class="cv-page-title mt-2 mb-1">Derivaciones de estudiantes</h2>
                    <p class="text-muted mb-0">Registra derivaciones y haz seguimiento al estado de cada caso.</p>
                </div>
                <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalDerivarAlumno">
                    <i class="fas fa-user-plus me-1"></i> Derivar alumno
                </button>
            </div>
        </div>
    </section>

    <section>
        <div class="cv-card card-body p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-clipboard-list me-2 cv-primary"></i>Mis derivaciones recientes</h5>
            @if($derivaciones->isEmpty())
                <p class="text-muted mb-0">Aún no registraste derivaciones.</p>
            @else
                <div class="table-responsive">
                    <table class="table cv-table align-middle">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Fecha de cita</th>
                                <th>Psicólogo asignado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($derivaciones as $cita)
                                @php
                                    $alumno = $usuarios[$cita->alumno_id] ?? null;
                                    $psicologo = $cita->psicologo_id ? ($usuarios[$cita->psicologo_id] ?? null) : null;
                                @endphp
                                <tr>
                                    <td>{{ $alumno ? trim($alumno->apellidos.' '.$alumno->nombres) : '—' }}</td>
                                    <td style="min-width: 320px;">{{ $cita->motivo }}</td>
                                    <td>
                                        @if($cita->estado === 'pendiente')
                                            <span class="badge text-bg-warning">Pendiente</span>
                                        @elseif($cita->estado === 'atendida')
                                            <span class="badge text-bg-success">Atendida</span>
                                        @else
                                            <span class="badge text-bg-secondary">Cancelada</span>
                                        @endif
                                    </td>
                                    <td>{{ $cita->fecha_cita ? \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y h:i A') : 'Sin asignar' }}</td>
                                    <td>{{ $psicologo ? trim($psicologo->apellidos.' '.$psicologo->nombres) : 'Sin asignar' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    <div class="modal fade" id="modalDerivarAlumno" tabindex="-1" aria-labelledby="modalDerivarAlumnoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('profesor.psicologia.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDerivarAlumnoLabel">Derivar alumno a Psicología</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="alumno_id" class="form-label fw-semibold">Alumno</label>
                            <select name="alumno_id" id="alumno_id" class="form-select" required>
                                <option value="">Selecciona un alumno</option>
                                @foreach($alumnos as $alumno)
                                    <option value="{{ $alumno->id }}" @selected(old('alumno_id') == $alumno->id)>
                                        {{ $alumno->apellidos }}, {{ $alumno->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="motivo" class="form-label fw-semibold">Motivo de derivación</label>
                            <textarea
                                name="motivo"
                                id="motivo"
                                rows="4"
                                class="form-control"
                                placeholder="Describe la situación observada en el estudiante..."
                                required
                            >{{ old('motivo') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Guardar derivación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

