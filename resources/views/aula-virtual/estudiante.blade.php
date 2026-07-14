@extends('layouts.alumno')

@section('title', 'Aula Virtual')
@section('page_title', 'Aula Virtual')
@section('page_subtitle', 'Selecciona un curso para comenzar')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Mis cursos</h5>
                    @if ($courses->isEmpty())
                        <p class="text-muted">No tienes cursos asignados aún.</p>
                    @else
                        <div class="list-group">
                            @foreach ($courses as $curso)
                                <a href="?asignacion_id={{ $curso->id }}" class="list-group-item list-group-item-action {{ isset($selectedAssignmentId) && $selectedAssignmentId == $curso->id ? 'active' : '' }}">
                                    <strong>{{ $curso->curso->nombre ?? 'Curso' }}</strong>
                                    <div class="small text-muted">Sección {{ $curso->aula->seccion ?? '—' }} · {{ $curso->aula->grado ?? '—' }}</div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-lg-9">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <div class="cv-card p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h4 class="mb-1">Aula Virtual</h4>
                                <p class="text-muted mb-0">Visualiza los recursos y entregas del curso seleccionado.</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">{{ $selectedCourseName ?? 'Sin curso seleccionado' }}</span>
                                @if ($selectedSection)
                                    <span class="badge bg-secondary">{{ $selectedSection }}</span>
                                @endif
                            </div>
                        </div>

                        @if (! isset($selectedAssignmentId))
                            <div class="alert alert-info mb-0">
                                Selecciona un curso a la izquierda para ver tus recursos disponibles.
                            </div>
                        @else
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="cv-status-card">
                                        <div class="icon bg-primary bg-opacity-15 text-primary mb-3">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <div class="h5 mb-1">{{ $materials->count() }}</div>
                                        <div class="text-muted">Recursos</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="cv-status-card">
                                        <div class="icon bg-success bg-opacity-15 text-success mb-3">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                        <div class="h5 mb-1">{{ $avisos->count() }}</div>
                                        <div class="text-muted">Avisos</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="cv-status-card">
                                        <div class="icon bg-warning bg-opacity-15 text-warning mb-3">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="h5 mb-1">
                                            @php
                                                $tareasAbiertas = $materials->where('classification', 'tarea')
                                                    ->filter(fn($m) => !$m->due_date || now()->startOfDay()->lte($m->due_date))
                                                    ->count();
                                            @endphp
                                            {{ $tareasAbiertas }}
                                        </div>
                                        <div class="text-muted">Tareas Abiertas</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="cv-inst-card h-100">
                        <i class="fas fa-bullhorn"></i>
                        <h5 class="mt-3 mb-2">Avisos del curso</h5>
                        <p class="small text-muted mb-3">Revisa los mensajes más recientes de tu profesor.</p>
                        @if ($avisos->isEmpty())
                            <p class="text-muted small mb-0">No hay avisos nuevos.</p>
                        @else
                            <ul class="list-unstyled mb-0">
                                @foreach ($avisos->take(3) as $aviso)
                                    <li class="mb-2">
                                        <strong>{{ Str::limit($aviso->titulo, 28) }}</strong>
                                        <div class="small text-muted">{{ Str::limit($aviso->contenido, 68) }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold"><i class="fas fa-book-open me-2 text-primary"></i>Recursos disponibles</h5>
                    @if (! isset($selectedAssignmentId))
                        <div class="alert alert-info mb-0">
                            Selecciona un curso a la izquierda para comenzar.
                        </div>
                    @elseif ($materials->isEmpty())
                        <div class="alert alert-warning mb-0">
                            No hay recursos publicados todavía para este curso.
                        </div>
                    @else
                        {{-- Acordeones por Bimestres --}}
                        @php
                            $bimestres = [
                                'B1' => 'Bimestre I',
                                'B2' => 'Bimestre II',
                                'B3' => 'Bimestre III',
                                'B4' => 'Bimestre IV',
                            ];
                        @endphp

                        <div class="accordion" id="accordionBimestresEstudiantes">
                            @foreach($bimestres as $key => $label)
                                @php
                                    $mats = $materials->where('bimestre', $key);
                                @endphp
                                <div class="accordion-item border-0 shadow-sm mb-2 rounded-3 overflow-hidden">
                                    <h2 class="accordion-header" id="heading{{ $key }}">
                                        <button class="accordion-button collapsed fw-bold bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                            <i class="fas fa-folder me-2 text-primary"></i> {{ $label }} 
                                            <span class="badge bg-secondary ms-2">{{ $mats->count() }} recursos</span>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionBimestresEstudiantes">
                                        <div class="accordion-body p-2 bg-light">
                                            @if ($mats->isEmpty())
                                                <p class="text-muted small text-center my-3">No hay materiales publicados para este bimestre.</p>
                                            @else
                                                <div class="list-group list-group-flush">
                                                    @foreach ($mats as $material)
                                                        @php
                                                            $cerrado = $material->due_date && now()->startOfDay()->gt($material->due_date);
                                                            $miEntrega = $tasks->where('material_id', $material->id)->where('user_id', Auth::id())->first();
                                                        @endphp
                                                        <a href="{{ route('aula-virtual.show', $material) }}" class="list-group-item list-group-item-action bg-white rounded-3 shadow-sm mb-2 py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                            <div>
                                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                                    <h6 class="mb-0 fw-bold text-dark">{{ $material->title }}</h6>
                                                                    @if ($material->classification === 'tarea')
                                                                        @if ($miEntrega)
                                                                            @if ($miEntrega->grade)
                                                                                <span class="badge bg-success" style="font-size:0.7rem;">Nota: {{ $miEntrega->grade }}</span>
                                                                            @else
                                                                                <span class="badge bg-secondary text-white" style="font-size:0.7rem;">Entregada</span>
                                                                            @endif
                                                                        @else
                                                                            @if ($cerrado)
                                                                                <span class="badge bg-danger" style="font-size:0.7rem;">Entrega Cerrada</span>
                                                                            @else
                                                                                <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Pendiente</span>
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        <span class="badge bg-info text-white" style="font-size:0.7rem;">Material</span>
                                                                    @endif
                                                                </div>
                                                                <p class="mb-1 text-muted small text-truncate" style="max-width:450px;">{{ $material->description ?? 'Sin descripción' }}</p>
                                                                <div class="d-flex align-items-center gap-2" style="font-size:0.75rem;">
                                                                    <span class="text-secondary"><i class="fas fa-file-alt me-1"></i>{{ ucfirst($material->resource_type) }}</span>
                                                                    @if($material->due_date)
                                                                        <span class="text-muted">· <i class="fas fa-calendar-alt me-1"></i>Límite: {{ $material->due_date->format('d/m/Y') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="text-muted small text-end">
                                                                <div>Por {{ $material->user->nombres ?? 'Docente' }}</div>
                                                            </div>
                                                        </a>
                                                    @endforeach
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

            @if (isset($selectedAssignmentId))
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold"><i class="fas fa-upload me-2 text-success"></i>Subir tarea</h5>
                        
                        @php
                            $tareasEntregables = $materials->where('classification', 'tarea')
                                ->filter(fn($m) => !$m->due_date || now()->startOfDay()->lte($m->due_date));
                        @endphp

                        @if ($tareasEntregables->isEmpty())
                            <div class="alert alert-warning mb-0 text-center py-4">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="mb-0 fw-semibold text-dark">No hay tareas pendientes o abiertas para entregar en este curso.</p>
                                <small class="text-muted">Las tareas del bimestre se encuentran cerradas o no se han publicado aún.</small>
                            </div>
                        @else
                            <form action="{{ route('aula-virtual.tarea.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="curso_id" value="{{ $selectedCourseId ?? '' }}">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Selecciona la Tarea</label>
                                    <select name="material_id" class="form-select" required>
                                        <option value="">Seleccione una tarea abierta...</option>
                                        @foreach ($tareasEntregables as $material)
                                            <option value="{{ $material->id }}">{{ $material->title }} (Límite: {{ $material->due_date ? $material->due_date->format('d/m/Y') : 'Sin fecha' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Título de la entrega</label>
                                    <input type="text" name="title" class="form-control" placeholder="Ej: Mi entrega de tarea" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Respuesta / Comentarios</label>
                                    <textarea name="submission_text" class="form-control" rows="4" placeholder="Comentario adicional para el docente..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Archivo adjunto (.pdf, .docx, .zip, etc.)</label>
                                    <input type="file" name="submission_file" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-success rounded-pill px-4">
                                    <i class="fas fa-paper-plane me-1"></i> Enviar tarea
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
