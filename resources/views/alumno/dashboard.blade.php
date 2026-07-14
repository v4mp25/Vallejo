@extends('layouts.alumno')

@section('title', 'Mi panel')
@section('page_title', 'Mi panel')
@section('page_subtitle', 'Tu espacio de aprendizaje siempre activo')

@section('content')
    @php
        $today = \Carbon\Carbon::now();
        $bimestres = [
            'B1' => 'I Bimestre',
            'B2' => 'II Bimestre',
            'B3' => 'III Bimestre',
            'B4' => 'IV Bimestre',
        ];
    @endphp

    <div class="row g-4">
        <div class="col-12">
            <div class="cv-card card-body p-4 p-md-5">
                <div class="d-flex align-items-start justify-content-between flex-column flex-md-row gap-3">
                    <div class="flex-grow-1">
                        <i class="fas fa-user-graduate fa-3x cv-primary mb-3"></i>
                        <h2 class="h4 fw-bold">Bienvenido al portal del alumno</h2>
                        <p class="text-muted mb-0">
                            @if($aula)
                                Aula: <strong>{{ $aula->grado }}° "{{ $aula->seccion }}"</strong> · {{ $aula->nivel }} · Turno {{ $aula->turno }}
                            @else
                                Aún no tienes una matrícula/aula asignada. Contacta a la secretaría para más información.
                            @endif
                        </p>
                    </div>
                    <div class="text-start text-md-end">
                        <p class="mb-0 text-secondary" style="font-size:.85rem;">Hoy es {{ $today->isoFormat('D [de] MMMM') }}.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- LIBRETA DE NOTAS DINÁMICA --}}
        <div class="col-12">
            <div class="cv-card card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 p-3 d-flex align-items-center justify-content-center" style="background: rgba(1,72,164,.1);">
                        <i class="fas fa-book-open cv-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Libreta de Calificaciones</h5>
                        <small class="text-muted">Notas finales registradas por bimestre en cada curso</small>
                    </div>
                </div>

                @if($libreta->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25 cv-primary"></i>
                        <p class="mb-0">Aún no hay cursos o notas registradas para tu aula.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 cv-table">
                            <thead>
                                <tr>
                                    <th class="ps-3 py-3">Curso</th>
                                    <th class="py-3 text-center">I Bimestre</th>
                                    <th class="py-3 text-center">II Bimestre</th>
                                    <th class="py-3 text-center">III Bimestre</th>
                                    <th class="py-3 text-center">IV Bimestre</th>
                                    <th class="py-3 text-center pe-3">Promedio Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($libreta as $fila)
                                    <tr class="border-bottom">
                                        <td class="ps-3 py-3">
                                            <span class="fw-bold text-dark d-block">{{ $fila['curso']->nombre }}</span>
                                            <small class="text-muted">
                                                @if($fila['profesor'])
                                                    Docente: {{ $fila['profesor']->nombres }} {{ $fila['profesor']->apellidos }}
                                                    · Cel: {{ $fila['profesor']->celular ?: 'Obteniendo datos...' }}
                                                @else
                                                    Docente: Obteniendo datos...
                                                @endif
                                            </small>
                                        </td>
                                        @foreach(['B1','B2','B3','B4'] as $periodo)
                                            <td class="py-3 text-center">
                                                @if($fila['notas'][$periodo] !== null && $fila['notas'][$periodo] !== '')
                                                    <span class="badge rounded-pill px-3 py-2 fw-semibold bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                        {{ $fila['notas'][$periodo] }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="py-3 text-center pe-3">
                                            @if($fila['promedio'] !== null)
                                                <span class="badge rounded-pill px-3 py-2 fw-bold bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                    {{ $fila['promedio'] }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
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

        {{-- TRACKER DE TAREAS ACADÉMICAS --}}
        <div class="col-12">
            <div class="cv-card card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 p-3 d-flex align-items-center justify-content-center" style="background: rgba(1,72,164,.1);">
                        <i class="fas fa-tasks cv-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Monitoreo de Tareas Académicas</h5>
                        <small class="text-muted">Cuánto has entregado y qué tienes pendiente, curso por curso</small>
                    </div>
                </div>

                @if($tareasPorCurso->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-clipboard-check fa-3x mb-3 opacity-25 cv-primary"></i>
                        <p class="mb-0">No hay tareas publicadas todavía para tus cursos.</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($tareasPorCurso as $item)
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-4 p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-dark">{{ $item['curso']->nombre }}</span>
                                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                            {{ $item['total'] }} tarea(s)
                                        </span>
                                    </div>

                                    @if($item['total'] > 0)
                                        <div class="progress rounded-pill mb-2" style="height: 10px;">
                                            <div class="progress-bar bg-success rounded-pill" role="progressbar"
                                                 style="width: {{ $item['porcentaje'] }}%;"
                                                 aria-valuenow="{{ $item['porcentaje'] }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span class="text-success fw-semibold">
                                                <i class="fas fa-check-circle me-1"></i>{{ $item['enviadas'] }} enviada(s)
                                            </span>
                                            <span class="{{ $item['pendientes'] > 0 ? 'text-danger' : 'text-muted' }} fw-semibold">
                                                <i class="fas fa-hourglass-half me-1"></i>{{ $item['pendientes'] }} pendiente(s)
                                            </span>
                                        </div>
                                    @else
                                        <p class="text-muted small mb-0">Sin tareas publicadas por el momento.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
