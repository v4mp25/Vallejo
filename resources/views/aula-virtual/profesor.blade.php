@extends('layouts.profesor')

@section('title', 'Aula Virtual')
@section('page_title', 'Aula Virtual')
@section('page_subtitle', 'Gestiona recursos, tareas y entregas')

@section('content')
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
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1">Aula Virtual</h4>
                        <p class="text-muted mb-0">Organiza recursos y tareas por curso y sección.</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary">{{ $selectedCourseName ?? 'Selecciona un curso' }}</span>
                        @if ($selectedSection)
                            <span class="badge bg-secondary">{{ $selectedSection }}</span>
                        @endif
                    </div>
                </div>

                @if (! isset($selectedAssignmentId))
                    <div class="alert alert-info mb-0">
                        Selecciona un curso en el menú izquierdo para ver recursos y tareas.
                    </div>
                @else
                    {{-- Acordeón de Bimestres --}}
                    @php
                        $bimestres = [
                            'B1' => 'Bimestre I',
                            'B2' => 'Bimestre II',
                            'B3' => 'Bimestre III',
                            'B4' => 'Bimestre IV',
                        ];
                    @endphp

                    <div class="accordion mt-3" id="accordionBimestres">
                        @foreach($bimestres as $key => $label)
                            @php
                                $mats = $materials->where('bimestre', $key);
                            @endphp
                            <div class="accordion-item border-0 shadow-sm mb-2 rounded-3 overflow-hidden">
                                <h2 class="accordion-header" id="heading{{ $key }}">
                                    <button class="accordion-button collapsed fw-bold bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                        <i class="fas fa-bookmark me-2 text-primary"></i> {{ $label }} 
                                        <span class="badge bg-secondary ms-2">{{ $mats->count() }} publicaciones</span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionBimestres">
                                    <div class="accordion-body p-2 bg-light">
                                        @if ($mats->isEmpty())
                                            <p class="text-muted small text-center my-3">No hay materiales ni tareas publicados en este bimestre.</p>
                                        @else
                                            <div class="list-group list-group-flush">
                                                @foreach ($mats as $material)
                                                    <div class="list-group-item bg-transparent border-0 py-3 mb-2 bg-white rounded-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h6 class="mb-0 fw-bold text-dark">{{ $material->title }}</h6>
                                                                @if ($material->classification === 'tarea')
                                                                    <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Tarea</span>
                                                                @else
                                                                    <span class="badge bg-info text-white" style="font-size:0.7rem;">Material</span>
                                                                @endif
                                                            </div>
                                                            <p class="mb-1 text-muted small text-truncate" style="max-width: 450px;">{{ $material->description ?? 'Sin descripción' }}</p>
                                                            <div class="d-flex align-items-center gap-2" style="font-size:0.75rem;">
                                                                <span class="text-secondary"><i class="fas fa-file-alt me-1"></i>{{ ucfirst($material->resource_type) }}</span>
                                                                @if($material->due_date)
                                                                    <span class="text-muted">· <i class="fas fa-clock me-1"></i>Límite: {{ $material->due_date->format('d/m/Y') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <a href="{{ route('aula-virtual.show', $material) }}" class="btn btn-sm btn-outline-secondary rounded-pill" title="Ver detalle">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if($material->classification === 'tarea')
                                                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill" 
                                                                        onclick="abrirModalEntregas({{ $material->id }}, '{{ addslashes($material->title) }}')" title="Ver entregas">
                                                                    <i class="fas fa-users-cog"></i> Entregas ({{ $tasks->where('material_id', $material->id)->count() }})
                                                                </button>
                                                            @endif
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" 
                                                                    onclick="abrirModalEditar({{ json_encode($material) }})" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('aula-virtual.destroy', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta publicación?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
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
                    <h5 class="mb-3 fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i>Publicar material o tarea</h5>
                    <form action="{{ route('aula-virtual.material.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="curso_id" value="{{ $selectedCourseId ?? '' }}">
                        <input type="hidden" name="aula_id" value="{{ $selectedAulaId ?? '' }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text" name="title" class="form-control" placeholder="Ej: Lectura N° 1 o Tarea Final" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Instrucciones breves..."></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bimestre académico</label>
                                <select name="bimestre" class="form-select" required>
                                    <option value="B1">Bimestre I</option>
                                    <option value="B2">Bimestre II</option>
                                    <option value="B3">Bimestre III</option>
                                    <option value="B4">Bimestre IV</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Clasificación de publicación</label>
                                <select name="classification" id="select-classification" class="form-select" required>
                                    <option value="material">Material de Estudio (Solo Lectura)</option>
                                    <option value="tarea">Tarea Académica (Permite entregas)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipo de recurso</label>
                                <select name="resource_type" class="form-select">
                                    <option value="file">Archivo</option>
                                    <option value="video">Video</option>
                                    <option value="link">Enlace</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha límite de entrega (Solo para Tareas)</label>
                                <input type="date" name="due_date" id="input-due_date" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subir archivo</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Enlace externo (Ej: YouTube, Drive)</label>
                                <input type="url" name="external_url" class="form-control" placeholder="https://...">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill mt-3 px-4">
                            <i class="fas fa-paper-plane me-1"></i> Publicar recurso
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Editar Material -->
<div class="modal fade" id="modalEditarMaterial" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="form-editar-material" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Editar publicación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título</label>
                        <input type="text" name="title" id="edit-title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bimestre académico</label>
                            <select name="bimestre" id="edit-bimestre" class="form-select" required>
                                <option value="B1">Bimestre I</option>
                                <option value="B2">Bimestre II</option>
                                <option value="B3">Bimestre III</option>
                                <option value="B4">Bimestre IV</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Clasificación</label>
                            <select name="classification" id="edit-classification" class="form-select" required>
                                <option value="material">Material de Estudio (Solo Lectura)</option>
                                <option value="tarea">Tarea Académica (Permite entregas)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de recurso</label>
                            <select name="resource_type" id="edit-resource_type" class="form-select" required>
                                <option value="file">Archivo</option>
                                <option value="video">Video</option>
                                <option value="link">Enlace</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha límite de entrega</label>
                            <input type="date" name="due_date" id="edit-due_date" class="form-control">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Reemplazar archivo (opcional)</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Enlace externo</label>
                            <input type="url" name="external_url" id="edit-external_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Actualizar publicación</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ver Entregas -->
<div class="modal fade" id="modalVerEntregas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-users-cog me-2"></i>Entregas de alumnos: <span id="nombre-tarea-entregas"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">Listado de entregas y notas de los alumnos.</span>
                    <a id="btn-exportar-entregas" href="#" class="btn btn-sm btn-success rounded-pill px-3 hover-lift">
                        <i class="fas fa-file-excel me-2"></i> Descargar Notas (Excel)
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table cv-table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Alumno</th>
                                <th>Título de entrega</th>
                                <th>Respuesta / Comentario</th>
                                <th>Archivo adjunto</th>
                                <th>Fecha de envío</th>
                                <th style="width:120px;">Nota</th>
                            </tr>
                        </thead>
                        <tbody id="lista-entregas-tbody">
                            <!-- Entregas dinámicas cargadas por JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const allDeliveries = @json($tasks);

    // Toggle due_date input on classification change (Publish Form)
    document.getElementById('select-classification')?.addEventListener('change', function() {
        const dueDateInput = document.getElementById('input-due_date');
        if (this.value === 'material') {
            dueDateInput.value = '';
            dueDateInput.disabled = true;
        } else {
            dueDateInput.disabled = false;
        }
    });

    // Toggle due_date input on classification change (Edit Form)
    document.getElementById('edit-classification')?.addEventListener('change', function() {
        const dueDateInput = document.getElementById('edit-due_date');
        if (this.value === 'material') {
            dueDateInput.value = '';
            dueDateInput.disabled = true;
        } else {
            dueDateInput.disabled = false;
        }
    });

    function abrirModalEntregas(materialId, materialTitle) {
        document.getElementById('nombre-tarea-entregas').textContent = materialTitle;
        
        // Actualizar el enlace del botón de exportación de notas
        const selectedAssignmentId = "{{ $selectedAssignmentId ?? '' }}";
        const exportBtn = document.getElementById('btn-exportar-entregas');
        if (exportBtn) {
            exportBtn.href = `/profesor/aula-virtual/tarea/${materialId}/exportar-notas/${selectedAssignmentId}`;
        }

        const tbody = document.getElementById('lista-entregas-tbody');
        tbody.innerHTML = '';
        
        const deliveries = allDeliveries.filter(d => d.material_id == materialId);
        
        if (deliveries.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">Ningún alumno ha enviado su entrega para esta tarea aún.</td></tr>`;
        } else {
            deliveries.forEach(d => {
                const fileLink = d.submission_file_path 
                    ? `<a href="/storage/${d.submission_file_path}" class="btn btn-sm btn-outline-success rounded-pill" target="_blank"><i class="fas fa-download me-1"></i>Descargar</a>`
                    : `<span class="text-muted small">No adjuntó archivo</span>`;
                
                const formatFecha = new Date(d.created_at).toLocaleDateString('es-ES', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                tbody.innerHTML += `
                    <tr>
                        <td class="fw-bold">${d.user.nombres} ${d.user.apellidos}</td>
                        <td>${d.title}</td>
                        <td><p class="mb-0 small text-wrap" style="max-width:300px;">${d.submission_text || '—'}</p></td>
                        <td>${fileLink}</td>
                        <td class="text-muted small">${formatFecha}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <input type="text" value="${d.grade || ''}" class="form-control form-control-sm text-center fw-bold" id="grade-input-${d.id}" style="width:60px;" placeholder="—" maxlength="10">
                                <button type="button" class="btn btn-sm btn-success rounded-circle p-1 d-flex align-items-center justify-content-center" onclick="guardarNota(${d.id})" style="width:28px; height:28px;" title="Guardar nota">
                                    <i class="fas fa-check" style="font-size:0.75rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }
        
        const modal = new bootstrap.Modal(document.getElementById('modalVerEntregas'));
        modal.show();
    }

    function abrirModalEditar(material) {
        const form = document.getElementById('form-editar-material');
        form.action = `/profesor/aula-virtual/${material.id}`;
        
        document.getElementById('edit-title').value = material.title;
        document.getElementById('edit-description').value = material.description || '';
        document.getElementById('edit-bimestre').value = material.bimestre || 'B1';
        document.getElementById('edit-classification').value = material.classification || 'material';
        document.getElementById('edit-resource_type').value = material.resource_type || 'file';
        
        const dueDateInput = document.getElementById('edit-due_date');
        if (material.classification === 'material') {
            dueDateInput.disabled = true;
            dueDateInput.value = '';
        } else {
            dueDateInput.disabled = false;
            if (material.due_date) {
                // Format date YYYY-MM-DD
                const d = new Date(material.due_date);
                const month = '' + (d.getMonth() + 1);
                const day = '' + d.getDate();
                const year = d.getFullYear();
                const formatted = [year, month.padStart(2, '0'), day.padStart(2, '0')].join('-');
                dueDateInput.value = formatted;
            } else {
                dueDateInput.value = '';
            }
        }
        
        document.getElementById('edit-external_url').value = material.external_url || '';
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditarMaterial'));
        modal.show();
    }

    function guardarNota(taskId) {
        const gradeValue = document.getElementById(`grade-input-${taskId}`).value;
        const btn = document.querySelector(`button[onclick="guardarNota(${taskId})"]`);
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size:0.75rem;"></i>`;

        fetch(`/profesor/aula-virtual/entregas/${taskId}/calificar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ grade: gradeValue })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalContent;

            if (data.success) {
                // Update local array in memory
                const del = allDeliveries.find(d => d.id == taskId);
                if (del) {
                    del.grade = data.grade;
                }
                
                // Success styling feedback
                const input = document.getElementById(`grade-input-${taskId}`);
                input.classList.add('is-valid');
                setTimeout(() => input.classList.remove('is-valid'), 2000);
            } else {
                alert('Ocurrió un error al guardar la nota: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            console.error(err);
            alert('Error al conectar con el servidor.');
        });
    }
</script>
@endpush
