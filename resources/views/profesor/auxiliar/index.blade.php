@extends('layouts.profesor')

@section('title', 'Derivaciones al Auxiliar')
@section('page_title', 'Módulo de Auxiliares')
@section('page_subtitle', 'Deriva estudiantes a auxiliares para control conductual')

@section('content')
    <section class="mb-4">
        <div class="cv-card card-body p-4 shadow-sm border-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="cv-badge-primary mb-2 d-inline-block">Auxiliares</span>
                    <h2 class="cv-page-title mt-1 mb-1">Derivaciones de estudiantes</h2>
                    <p class="text-muted mb-0">Registra derivaciones a auxiliares de educación y haz seguimiento.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pestañas de Navegación --}}
    <ul class="nav nav-pills mb-4" id="auxiliarTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-semibold" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab" aria-controls="recent" aria-selected="true">
                <i class="fas fa-clipboard-list me-2"></i>Mis Derivaciones Recientes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold" id="new-deriv-tab" data-bs-toggle="tab" data-bs-target="#new-deriv" type="button" role="tab" aria-controls="new-deriv" aria-selected="false">
                <i class="fas fa-user-plus me-2"></i>Derivar Nuevo Alumno
            </button>
        </li>
    </ul>

    <div class="tab-content" id="auxiliarTabContent">
        {{-- Pestaña: Derivaciones Recientes (Historial agrupado por Bimestre) --}}
        <div class="tab-pane fade show active" id="recent" role="tabpanel" aria-labelledby="recent-tab">
            <div class="cv-card card-body p-4 shadow-sm border-0">
                <h5 class="fw-bold mb-3"><i class="fas fa-history me-2 cv-primary"></i>Historial de derivaciones por bimestres</h5>
                @if($derivaciones->isEmpty())
                    <p class="text-muted mb-0">Aún no registraste derivaciones a auxiliares.</p>
                @else
                    @php
                        $bimestres = [
                            'B1' => 'Bimestre I',
                            'B2' => 'Bimestre II',
                            'B3' => 'Bimestre III',
                            'B4' => 'Bimestre IV',
                        ];
                    @endphp

                    <div class="accordion" id="accordionDerivacionesBimestres">
                        @foreach($bimestres as $key => $label)
                            @php
                                $citasBim = $derivaciones->where('bimestre', $key);
                            @endphp
                            <div class="accordion-item border-0 shadow-sm mb-2 rounded-3 overflow-hidden">
                                <h2 class="accordion-header" id="headingDeriv{{ $key }}">
                                    <button class="accordion-button collapsed fw-bold bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDeriv{{ $key }}" aria-expanded="false" aria-controls="collapseDeriv{{ $key }}">
                                        <i class="fas fa-calendar-check me-2 text-primary"></i> {{ $label }}
                                        <span class="badge bg-secondary ms-2">{{ $citasBim->count() }} derivaciones</span>
                                    </button>
                                </h2>
                                <div id="collapseDeriv{{ $key }}" class="accordion-collapse collapse" aria-labelledby="headingDeriv{{ $key }}" data-bs-parent="#accordionDerivacionesBimestres">
                                    <div class="accordion-body bg-light p-2">
                                        @if($citasBim->isEmpty())
                                            <p class="text-muted small text-center my-3">No hay derivaciones registradas en este bimester.</p>
                                        @else
                                            <div class="table-responsive bg-white rounded-3 p-2 shadow-sm">
                                                <table class="table cv-table align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Alumno</th>
                                                            <th>Motivo</th>
                                                            <th>Estado</th>
                                                            <th>Fecha de cita / Citado</th>
                                                            <th>Auxiliar asignado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($citasBim as $cita)
                                                            @php
                                                                $alumno = $usuarios[$cita->alumno_id] ?? null;
                                                                $auxiliar = $cita->auxiliar_id ? ($usuarios[$cita->auxiliar_id] ?? null) : null;
                                                            @endphp
                                                            <tr>
                                                                <td class="fw-bold text-dark">{{ $alumno ? trim($alumno->apellidos.' '.$alumno->nombres) : '—' }}</td>
                                                                <td style="min-width: 320px;">{{ $cita->motivo }}</td>
                                                                <td>
                                                                    @if($cita->estado === 'pendiente')
                                                                        <span class="badge text-bg-warning">Pendiente</span>
                                                                    @elseif($cita->estado === 'citado')
                                                                        <span class="badge text-bg-info text-white">Citado</span>
                                                                    @elseif($cita->estado === 'atendida')
                                                                        <span class="badge text-bg-success">Atendida</span>
                                                                    @else
                                                                        <span class="badge text-bg-secondary">Cancelada</span>
                                                                    @endif
                                                                </td>
                                                                <td class="small text-muted">{{ $cita->fecha_cita ? \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y h:i A') : 'Sin asignar' }}</td>
                                                                <td>{{ $auxiliar ? trim($auxiliar->apellidos.' '.$auxiliar->nombres) : 'Sin asignar' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Pestaña: Derivar Nuevo Alumno --}}
        <div class="tab-pane fade" id="new-deriv" role="tabpanel" aria-labelledby="new-deriv-tab">
            <div class="cv-card card-body p-4 shadow-sm border-0">
                <h5 class="fw-bold mb-3"><i class="fas fa-graduation-cap me-2 cv-primary"></i>Selecciona un salón para buscar al alumno</h5>
                
                @if($cursos->isEmpty())
                    <p class="text-muted mb-0">No tienes salones asignados.</p>
                @else
                    <div class="accordion" id="accordionCursosAuxiliar">
                        @foreach($cursos as $index => $curso)
                            <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                                <h2 class="accordion-header" id="headingCurso{{ $curso->id }}">
                                    <button class="accordion-button collapsed fw-bold bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCurso{{ $curso->id }}" aria-expanded="false" aria-controls="collapseCurso{{ $curso->id }}">
                                        <div class="d-flex align-items-center justify-content-between w-100 pe-3 flex-wrap gap-2">
                                            <div>
                                                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                                                <strong>{{ $curso->curso->nombre ?? 'Materia' }}</strong>
                                            </div>
                                            <span class="badge bg-secondary px-3 py-2">Secundaria - Grado y Sección: {{ $curso->aula->grado ?? '' }} "{{ $curso->aula->seccion ?? '' }}"</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseCurso{{ $curso->id }}" class="accordion-collapse collapse" aria-labelledby="headingCurso{{ $curso->id }}" data-bs-parent="#accordionCursosAuxiliar">
                                    <div class="accordion-body bg-light p-3">
                                        @php
                                            $alumnos = $alumnosPorAula[$curso->aula_id] ?? collect();
                                        @endphp
                                        @if($alumnos->isEmpty())
                                            <p class="text-muted small mb-0 text-center py-3">No hay alumnos matriculados en este salón.</p>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table cv-table table-hover align-middle bg-white rounded-3 shadow-sm mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Apellidos y Nombres</th>
                                                            <th style="width:150px;" class="text-end">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($alumnos as $alumno)
                                                            <tr>
                                                                <td class="fw-bold text-dark">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</td>
                                                                <td class="text-end">
                                                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="abrirModalDerivacionAuxiliar({{ $alumno->id }}, '{{ addslashes($alumno->apellidos . ', ' . $alumno->nombres) }}')">
                                                                        <i class="fas fa-paper-plane me-1"></i> Derivar
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal de Derivación --}}
    <div class="modal fade" id="modalDerivarAlumnoAuxiliar" tabindex="-1" aria-labelledby="modalDerivarAlumnoAuxiliarLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('profesor.auxiliar.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="modalDerivarAlumnoAuxiliarLabel"><i class="fas fa-user-plus me-2"></i>Derivar alumno a Auxiliar</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="alumno_id" id="derivacion-alumno-id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alumno seleccionado</label>
                            <input type="text" id="derivacion-alumno-nombre" class="form-control fw-semibold" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label for="derivacion-auxiliar" class="form-label fw-bold">Auxiliar a cargo</label>
                            <select name="auxiliar_id" id="derivacion-auxiliar" class="form-select" required>
                                <option value="">Selecciona un auxiliar...</option>
                                @foreach($auxiliares as $aux)
                                    <option value="{{ $aux->id }}">{{ $aux->apellidos }}, {{ $aux->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="derivacion-bimestre" class="form-label fw-bold">Bimestre académico</label>
                            <select name="bimestre" id="derivacion-bimestre" class="form-select" required>
                                <option value="B1">Bimestre I</option>
                                <option value="B2">Bimestre II</option>
                                <option value="B3">Bimestre III</option>
                                <option value="B4">Bimestre IV</option>
                            </select>
                        </div>
                        <div>
                            <label for="motivo" class="form-label fw-bold">Motivo de derivación</label>
                            <textarea
                                name="motivo"
                                id="motivo"
                                rows="5"
                                class="form-control"
                                placeholder="Describe detalladamente el comportamiento o situación conductual..."
                                required
                            >{{ old('motivo') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-paper-plane me-1"></i> Guardar derivación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function abrirModalDerivacionAuxiliar(alumnoId, alumnoNombre) {
        document.getElementById('derivacion-alumno-id').value = alumnoId;
        document.getElementById('derivacion-alumno-nombre').value = alumnoNombre;
        document.getElementById('motivo').value = '';
        
        const modal = new bootstrap.Modal(document.getElementById('modalDerivarAlumnoAuxiliar'));
        modal.show();
    }
</script>
@endpush
