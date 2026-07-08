@extends('layouts.profesor')

@section('title', $asignacion->curso->nombre . ' — ' . $asignacion->aula->grado . '° ' . $asignacion->aula->seccion)

@section('content')
    {{-- Breadcrumb --}}
    <a href="{{ route('profesor.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
        <i class="fas fa-arrow-left me-1"></i> Volver a mis cursos
    </a>

    {{-- Encabezado del curso --}}
    <div class="cv-card card-body p-4 mb-4">
        <span class="cv-badge-primary">Curso</span>
        <h2 class="cv-page-title mt-2 mb-1">{{ $asignacion->curso->nombre }}</h2>
        <p class="text-muted mb-0">
            <i class="fas fa-school me-1"></i>
            Salón {{ $asignacion->aula->grado }}° "{{ $asignacion->aula->seccion }}"
            @if ($asignacion->aula->turno)
                · {{ $asignacion->aula->turno }}
            @endif
            · <strong>{{ $alumnos->count() }}</strong> alumno{{ $alumnos->count() !== 1 ? 's' : '' }}
        </p>
    </div>

    {{-- Tabs: Asistencia | Notas --}}
    <ul class="nav nav-tabs mb-4" id="tabCurso" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-asistencia" data-bs-toggle="tab"
                    data-bs-target="#pane-asistencia" type="button" role="tab">
                <i class="fas fa-clipboard-check me-1"></i> Asistencia
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-notas" data-bs-toggle="tab"
                    data-bs-target="#pane-notas" type="button" role="tab">
                <i class="fas fa-edit me-1"></i> Notas
            </button>
        </li>
    </ul>

    <div class="tab-content" id="tabCursoContent">

        {{-- ===================== TAB ASISTENCIA ===================== --}}
        <div class="tab-pane fade show active" id="pane-asistencia" role="tabpanel">
            <div class="cv-card card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-clipboard-check me-2 cv-primary"></i> Registrar asistencia
                </h5>

                <form action="{{ route('profesor.asistencia.guardar', $asignacion->id) }}" method="POST">
                    @csrf
                    <div class="mb-3" style="max-width:220px;">
                        <label for="fecha" class="form-label fw-semibold">Fecha</label>
                        <input type="date" name="fecha" id="fecha"
                               class="form-control" value="{{ today()->toDateString() }}" required>
                    </div>

                    @if ($alumnos->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-user-slash fa-2x mb-2"></i>
                            <p class="mb-0">No hay alumnos matriculados en esta aula.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table cv-table align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th>Estudiante</th>
                                        <th class="text-center" style="width:15%">
                                            <i class="fas fa-check text-success"></i> Presente
                                        </th>
                                        <th class="text-center" style="width:15%">
                                            <i class="fas fa-clock text-warning"></i> Tardanza
                                        </th>
                                        <th class="text-center" style="width:15%">
                                            <i class="fas fa-times text-danger"></i> Falta
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alumnos as $i => $alumno)
                                        @php
                                            $estadoActual = $asistenciaHoy[$alumno->id]->estado ?? 'presente';
                                        @endphp
                                        <tr>
                                            <td class="text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <strong>{{ $alumno->apellidos }}</strong>,
                                                {{ $alumno->nombres }}
                                            </td>
                                            <td class="text-center">
                                                <input type="radio"
                                                       name="asistencias[{{ $alumno->id }}][estado]"
                                                       value="presente"
                                                       class="form-check-input"
                                                       {{ $estadoActual === 'presente' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio"
                                                       name="asistencias[{{ $alumno->id }}][estado]"
                                                       value="tardanza"
                                                       class="form-check-input"
                                                       {{ $estadoActual === 'tardanza' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio"
                                                       name="asistencias[{{ $alumno->id }}][estado]"
                                                       value="falta"
                                                       class="form-check-input"
                                                       {{ $estadoActual === 'falta' ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill mt-2">
                            <i class="fas fa-save me-1"></i> Guardar asistencia
                        </button>
                    @endif
                </form>
            </div>
        </div>

        {{-- ===================== TAB NOTAS ===================== --}}
        <div class="tab-pane fade" id="pane-notas" role="tabpanel">
            <div class="cv-card card-body p-4">
                <h5 class="fw-bold mb-1">
                    <i class="fas fa-edit me-2 cv-primary"></i> Registrar / Editar notas
                </h5>
                <p class="text-muted small mb-3">
                    Ingresa las notas directamente en la tabla. Las notas se guardan por bimestre.
                </p>

                <form action="{{ route('profesor.notas.guardar', $asignacion->id) }}" method="POST">
                    @csrf

                    @if ($alumnos->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <p class="mb-0">No hay alumnos matriculados.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table cv-table tabla-notas-editable align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Estudiante</th>
                                        <th class="text-center">Bim. I</th>
                                        <th class="text-center">Bim. II</th>
                                        <th class="text-center">Bim. III</th>
                                        <th class="text-center">Bim. IV</th>
                                        <th class="text-center">Promedio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alumnos as $i => $alumno)
                                        @php
                                            $notasAlumno = $notas[$alumno->id] ?? collect();
                                            $b1 = $notasAlumno->firstWhere('periodo', 'B1')->calificacion ?? '';
                                            $b2 = $notasAlumno->firstWhere('periodo', 'B2')->calificacion ?? '';
                                            $b3 = $notasAlumno->firstWhere('periodo', 'B3')->calificacion ?? '';
                                            $b4 = $notasAlumno->firstWhere('periodo', 'B4')->calificacion ?? '';

                                            // Calcular promedio de las notas numéricas
                                            $validas = collect([$b1, $b2, $b3, $b4])->filter(fn($v) => is_numeric($v));
                                            $promedio = $validas->count() > 0 ? round($validas->avg(), 1) : '—';
                                        @endphp
                                        <tr>
                                            <td class="text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <strong>{{ $alumno->apellidos }}</strong>,
                                                {{ $alumno->nombres }}
                                            </td>
                                            @foreach (['B1', 'B2', 'B3', 'B4'] as $periodo)
                                                <td class="text-center" style="width:12%">
                                                    <input type="text"
                                                           class="form-control form-control-sm text-center nota-input"
                                                           name="notas[{{ $alumno->id }}][{{ $periodo }}][calificacion]"
                                                           value="{{ ${'b' . substr($periodo, 1)} }}"
                                                           maxlength="5"
                                                           placeholder="—"
                                                           style="max-width:70px; margin:0 auto;">
                                                    <input type="hidden"
                                                           name="notas[{{ $alumno->id }}][{{ $periodo }}][periodo]"
                                                           value="{{ $periodo }}">
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <span class="badge {{ is_numeric($promedio) && $promedio >= 11 ? 'bg-success' : (is_numeric($promedio) ? 'bg-danger' : 'bg-secondary') }}">
                                                    {{ $promedio }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill mt-2">
                            <i class="fas fa-save me-1"></i> Guardar notas
                        </button>
                    @endif
                </form>
            </div>
        </div>

    </div>
@endsection

@push('styles')
<style>
    .nota-input {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        transition: border-color .2s;
    }
    .nota-input:focus {
        border-color: var(--cv-primary);
        box-shadow: 0 0 0 .15rem rgba(var(--cv-primary-rgb), .2);
    }
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #555;
    }
    .nav-tabs .nav-link.active {
        color: var(--cv-primary);
        border-bottom: 2px solid var(--cv-primary);
    }
</style>
@endpush
