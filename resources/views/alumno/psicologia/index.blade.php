@extends('layouts.alumno')

@section('title', 'Acompañamiento Psicológico')
@section('page_title', 'Módulo de Psicología')
@section('page_subtitle', 'Consulta el estado y programación de tus citas psicológicas')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                    <i class="fas fa-brain fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Acompañamiento Psicológico</h4>
                    <p class="text-muted mb-0">Aquí puedes revisar las citas programadas con el psicólogo de la institución.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-calendar-alt text-primary me-2"></i>Mis citas programadas</h5>
            
            @if ($citas->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="far fa-calendar-times fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0 fw-semibold">No tienes citas psicológicas registradas.</p>
                    <small>Si necesitas apoyo u orientación, consúltalo con tu tutor.</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle cv-table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Psicólogo Asignado</th>
                                <th>Derivado por</th>
                                <th>Motivo / Situación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($citas as $cita)
                                @php
                                    $profesor = $usuarios[$cita->profesor_id] ?? null;
                                    $psicologo = $usuarios[$cita->psicologo_id] ?? null;
                                @endphp
                                <tr>
                                    <td class="fw-bold text-primary">
                                        @if ($cita->fecha_cita)
                                            <i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y h:i A') }}
                                        @else
                                            <span class="text-muted fw-normal italic">Por programar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($psicologo)
                                            <span class="fw-semibold text-dark">{{ $psicologo->nombres }} {{ $psicologo->apellidos }}</span>
                                        @else
                                            <span class="text-muted">Por asignar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($profesor)
                                            <span class="text-secondary small">{{ $profesor->nombres }} {{ $profesor->apellidos }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 300px;" class="text-truncate" title="{{ $cita->motivo }}">
                                        {{ $cita->motivo }}
                                    </td>
                                    <td>
                                        @if ($cita->estado === 'pendiente')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pendiente</span>
                                        @elseif ($cita->estado === 'citado')
                                            <span class="badge bg-info text-white px-3 py-2 rounded-pill">Citado</span>
                                        @elseif ($cita->estado === 'atendida')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Atendida</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">Cancelada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- NUEVA SECCIÓN: MATERIAL Y EVALUACIONES PSICOLÓGICAS --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-folder-open text-primary me-2"></i>Material y Evaluaciones Psicológicas</h5>
            
            @if ($materialesPorBimestre->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="far fa-folder-open fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0 fw-semibold">No se han publicado materiales o evaluaciones para tu aula todavía.</p>
                </div>
            @else
                <div class="accordion rounded-4 overflow-hidden border" id="accordionMaterialesPsico">
                    @foreach ($materialesPorBimestre as $bimestre => $materiales)
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header" id="heading{{ Str::slug($bimestre) }}">
                                <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($bimestre) }}" aria-expanded="false" aria-controls="collapse{{ Str::slug($bimestre) }}">
                                    <i class="fas fa-bookmark text-primary me-2"></i>{{ $bimestre }}
                                    <span class="badge bg-primary rounded-pill ms-2 px-2.5 py-1 text-xs fw-semibold">{{ $materiales->count() }} recurso(s)</span>
                                </button>
                            </h2>
                            <div id="collapse{{ Str::slug($bimestre) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ Str::slug($bimestre) }}" data-bs-parent="#accordionMaterialesPsico">
                                <div class="accordion-body p-4">
                                    <div class="row g-3">
                                        @foreach ($materiales as $material)
                                            <div class="col-md-6">
                                                <div class="card border rounded-3 h-100 shadow-xs">
                                                    <div class="card-body p-3 d-flex flex-column">
                                                        <h6 class="fw-bold text-dark mb-1"><i class="fas fa-file-alt text-primary me-1"></i>{{ $material->titulo }}</h6>
                                                        <span class="text-muted small mb-2 d-block"><i class="far fa-user me-1"></i>Psicólogo: {{ $material->psicologo->nombres }} {{ $material->psicologo->apellidos }}</span>
                                                        
                                                        @if($material->imagen)
                                                            <div class="mb-3 text-center">
                                                                <img src="{{ asset('storage/' . $material->imagen) }}" class="img-fluid rounded-3 border" style="max-height: 180px; object-fit: contain;">
                                                            </div>
                                                        @endif

                                                        @if($material->descripcion)
                                                            <p class="text-secondary small mb-3 flex-grow-1">{{ $material->descripcion }}</p>
                                                        @else
                                                            <p class="text-secondary small mb-3 flex-grow-1 fst-italic">Sin descripción.</p>
                                                        @endif

                                                        @if($material->enlace)
                                                            <a href="{{ $material->enlace }}" target="_blank" class="btn btn-primary btn-sm rounded-pill mt-auto fw-bold text-center py-2 align-self-start px-4">
                                                                <i class="fas fa-external-link-alt me-1.5"></i>Abrir Recurso / Responder Test
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
