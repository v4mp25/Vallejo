@extends('layouts.alumno')

@section('title', 'Acompañamiento por Auxiliar')
@section('page_title', 'Módulo de Auxiliares')
@section('page_subtitle', 'Consulta el estado y programación de tus citaciones conductuales')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                    <i class="fas fa-user-shield fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Atención por Auxiliar de Educación</h4>
                    <p class="text-muted mb-0">Aquí puedes revisar las citaciones y el material enviado por el auxiliar asignado.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE CITAS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-calendar-alt text-success me-2"></i>Mis citaciones programadas</h5>
            
            @if ($citas->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="far fa-calendar-times fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0 fw-semibold">No tienes citaciones registradas con auxiliares.</p>
                    <small>Sigue las normas de convivencia escolar del plantel.</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle cv-table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Auxiliar Asignado</th>
                                <th>Derivado por</th>
                                <th>Motivo / Situación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($citas as $cita)
                                @php
                                    $profesor = $usuarios[$cita->profesor_id] ?? null;
                                    $auxiliarU = $usuarios[$cita->auxiliar_id] ?? null;
                                @endphp
                                <tr>
                                    <td class="fw-bold text-success">
                                        @if ($cita->fecha_cita)
                                            <i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y h:i A') }}
                                        @else
                                            <span class="text-muted fw-normal">Por programar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($auxiliarU)
                                            <span class="fw-semibold text-dark">{{ $auxiliarU->nombres }} {{ $auxiliarU->apellidos }}</span>
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

    {{-- MATERIALES Y COMUNICADOS DEL AUXILIAR --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-folder-open text-success me-2"></i>Materiales y Comunicados del Auxiliar
            </h5>
        </div>
        <div class="card-body px-4 pb-4">
            @if ($materialesPorBimestre->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-30"></i>
                    <p class="mb-0 fw-semibold">No hay materiales o comunicados publicados para tu sección aún.</p>
                </div>
            @else
                <div class="accordion rounded-4 overflow-hidden border" id="accordionMatAux">
                    @foreach ($materialesPorBimestre as $bim => $mats)
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header" id="headingMatAux{{ Str::slug($bim) }}">
                                <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMatAux{{ Str::slug($bim) }}" aria-expanded="false">
                                    <i class="fas fa-bookmark text-success me-2"></i> {{ $bim }}
                                    <span class="badge bg-success rounded-pill ms-2 px-2 py-1" style="font-size: 0.72rem;">{{ $mats->count() }} recurso(s)</span>
                                </button>
                            </h2>
                            <div id="collapseMatAux{{ Str::slug($bim) }}" class="accordion-collapse collapse" aria-labelledby="headingMatAux{{ Str::slug($bim) }}" data-bs-parent="#accordionMatAux">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        @foreach ($mats as $mat)
                                            <div class="col-md-6">
                                                <div class="card border rounded-4 h-100 shadow-sm">
                                                    @if($mat->imagen)
                                                        <img src="{{ asset('storage/' . $mat->imagen) }}" class="card-img-top rounded-top-4" style="max-height: 180px; object-fit: cover;">
                                                    @endif
                                                    <div class="card-body">
                                                        <h6 class="fw-bold text-dark mb-1">{{ $mat->titulo }}</h6>
                                                        @if($mat->descripcion)
                                                            <p class="text-muted small mb-2">{{ Str::limit($mat->descripcion, 120) }}</p>
                                                        @endif
                                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                                            @if($mat->enlace)
                                                                <a href="{{ $mat->enlace }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                                                    <i class="fas fa-external-link-alt me-1"></i>Abrir enlace
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 small text-muted pt-0 px-3 pb-2">
                                                        <i class="fas fa-user-shield me-1"></i>
                                                        {{ $mat->auxiliar ? $mat->auxiliar->nombres . ' ' . $mat->auxiliar->apellidos : '—' }}
                                                        @if($mat->fecha_limite)
                                                            &nbsp;·&nbsp;<i class="far fa-clock me-1"></i>Hasta {{ \Carbon\Carbon::parse($mat->fecha_limite)->format('d/m/Y H:i') }}
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
