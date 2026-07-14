@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-graduation-cap me-2 text-primary"></i> Servicio Educativo
        </h2>
        <span class="text-muted small">Capítulo 4 — Oferta y Enfoque Pedagógico</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        {{-- Card 1: Textos Descriptivos --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-file-alt me-2 text-primary"></i> 4.2 Propuesta Pedagógica (Textos Generales)
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('admin.servicio-educativo.guardar') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nivel Secundaria</label>
                                <textarea name="nivel_secundaria" class="form-control rounded-3" rows="4" placeholder="Describe el nivel secundaria (primero a quinto grado)...">{{ old('nivel_secundaria', $info->nivel_secundaria) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Enfoque por Competencias</label>
                                <textarea name="enfoque_competencias" class="form-control rounded-3" rows="4" placeholder="Describe el enfoque por competencias (CNEB)...">{{ old('enfoque_competencias', $info->enfoque_competencias) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Innovación Pedagógica (AIP)</label>
                                <textarea name="innovacion_pedagogica" class="form-control rounded-3" rows="4" placeholder="Describe las metodologías activas y tecnología aplicada...">{{ old('innovacion_pedagogica', $info->innovacion_pedagogica) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tutoría y Orientación Educativa</label>
                                <textarea name="tutoria_orientacion" class="form-control rounded-3" rows="4" placeholder="Describe el acompañamiento emocional y de tutoría...">{{ old('tutoria_orientacion', $info->tutoria_orientacion) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Educación Inclusiva</label>
                                <textarea name="educacion_inclusiva" class="form-control rounded-3" rows="4" placeholder="Describe la atención a las necesidades educativas especiales (NEE)...">{{ old('educacion_inclusiva', $info->educacion_inclusiva) }}</textarea>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Guardar Textos Pedagógicos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card 2: Áreas Curriculares --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-book me-2 text-success"></i> 4.1 Áreas Curriculares
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Agregar Nueva Área (Curso)</h6>
                            <form action="{{ route('admin.servicio-educativo.areas.guardar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nombre de la Área</label>
                                    <input type="text" name="nombre" class="form-control rounded-3" placeholder="Ej: Matemática" required value="{{ old('nombre') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Imagen del Área (Icono chico)</label>
                                    <input type="file" name="imagen" class="form-control rounded-3" accept="image/*" required>
                                    <small class="text-muted">Sube una imagen pequeña (máx: 2MB).</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Descripción del Área</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="4" placeholder="Describe brevemente el plan del curso..." required>{{ old('descripcion') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Agregar Área
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-list me-1"></i> Áreas Curriculares Registradas</h6>
                            @if(count($areas) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 80px;">Imagen</th>
                                                <th>Área</th>
                                                <th>Descripción</th>
                                                <th class="text-center" style="width: 130px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($areas as $area)
                                                <tr>
                                                    <td>
                                                        @if($area->imagen)
                                                            <img src="{{ asset('storage/' . $area->imagen) }}" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;" alt="Área">
                                                        @else
                                                            <div class="rounded bg-light border d-flex align-items-center justify-content-center text-secondary" style="width: 45px; height: 45px;">
                                                                <i class="fas fa-book"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $area->nombre }}</span>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 text-muted small" style="max-height: 50px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $area->descripcion }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-3 btn-edit-area" 
                                                                    data-id="{{ $area->id }}"
                                                                    data-nombre="{{ $area->nombre }}"
                                                                    data-descripcion="{{ $area->descripcion }}"
                                                                    data-imagen="{{ $area->imagen ? asset('storage/' . $area->imagen) : '' }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('admin.servicio-educativo.areas.eliminar', $area->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este curso?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted bg-light rounded-3">
                                    <i class="fas fa-book-open fs-2 mb-2 opacity-50"></i>
                                    <p class="mb-0">No hay áreas curriculares registradas aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Proyectos Institucionales --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-rocket me-2 text-danger"></i> 4.3 Proyectos Bandera Institucionales
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus-circle me-1"></i> Registrar Nuevo Proyecto</h6>
                            <form action="{{ route('admin.servicio-educativo.proyectos.guardar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Proyecto</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Feria de Ciencias Eureka" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Descripción</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="4" placeholder="Describe brevemente el alcance, impacto u objetivos del proyecto..." required>{{ old('descripcion') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Imagen del Proyecto</label>
                                    <input type="file" name="imagen" class="form-control rounded-3" accept="image/*" required>
                                    <small class="text-muted">Formatos: JPG, PNG, WEBP. Máx: 3MB.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Enlace (Link redirigible - Opcional)</label>
                                    <input type="url" name="link" class="form-control rounded-3" placeholder="Ej: https://eureka.org" value="{{ old('link') }}">
                                </div>
                                <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Proyecto
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-images me-1"></i> Galería de Proyectos Bandera</h6>
                            @if(count($proyectos) > 0)
                                <div class="row g-3">
                                    @foreach($proyectos as $p)
                                        <div class="col-md-6 col-xxl-4">
                                            <div class="card border rounded-3 overflow-hidden h-100 shadow-xs">
                                                <img src="{{ asset('storage/' . $p->imagen) }}" class="card-img-top" style="height: 140px; object-fit: cover;" alt="Proyecto">
                                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1">{{ $p->titulo }}</h6>
                                                        <p class="text-muted mb-2 small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $p->descripcion }}</p>
                                                        @if($p->link)
                                                            <a href="{{ $p->link }}" target="_blank" class="d-block text-truncate small text-primary mb-3" title="{{ $p->link }}">
                                                                <i class="fas fa-external-link-alt me-1"></i> {{ $p->link }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3 btn-edit-proyecto"
                                                                data-id="{{ $p->id }}"
                                                                data-titulo="{{ $p->titulo }}"
                                                                data-descripcion="{{ $p->descripcion }}"
                                                                data-link="{{ $p->link }}"
                                                                data-imagen="{{ asset('storage/' . $p->imagen) }}">
                                                            <i class="fas fa-edit me-1"></i> Editar
                                                        </button>
                                                        <form action="{{ route('admin.servicio-educativo.proyectos.eliminar', $p->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proyecto?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3">
                                                                <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-muted bg-light rounded-3">
                                    <i class="fas fa-folder-open fs-2 mb-2 opacity-50"></i>
                                    <p class="mb-0">No hay proyectos bandera registrados aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDITAR ÁREA CURRICULAR --}}
<div class="modal fade" id="editAreaModal" tabindex="-1" aria-labelledby="editAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editAreaModalLabel">Editar Área Curricular</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-area" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nombre del Área</label>
                        <input type="text" name="nombre" id="edit-area-nombre" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Imagen actual del Área</label>
                        <div class="mb-2">
                            <img id="edit-area-img-prev" src="" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                        </div>
                        <label class="form-label small fw-semibold">Cambiar Imagen (Opcional)</label>
                        <input type="file" name="imagen" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">Sube una nueva imagen chica para actualizar (máx: 2MB).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Descripción del Área</label>
                        <textarea name="descripcion" id="edit-area-descripcion" class="form-control rounded-3" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-pill fw-bold px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR PROYECTO INSTITUCIONAL --}}
<div class="modal fade" id="editProyectoModal" tabindex="-1" aria-labelledby="editProyectoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editProyectoModalLabel">Editar Proyecto Institucional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-proyecto" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Título del Proyecto</label>
                        <input type="text" name="titulo" id="edit-proyecto-titulo" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Descripción</label>
                        <textarea name="descripcion" id="edit-proyecto-descripcion" class="form-control rounded-3" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Imagen actual del Proyecto</label>
                        <div class="mb-2">
                            <img id="edit-proyecto-img-prev" src="" class="rounded border w-100" style="max-height: 140px; object-fit: cover;">
                        </div>
                        <label class="form-label small fw-semibold">Cambiar Imagen (Opcional)</label>
                        <input type="file" name="imagen" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">Sube una nueva imagen para actualizar (máx: 3MB).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Enlace (Link redirigible - Opcional)</label>
                        <input type="url" name="link" id="edit-proyecto-link" class="form-control rounded-3" placeholder="Ej: https://eureka.org">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill fw-bold px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // EDIT AREA
    const editAreaModal = new bootstrap.Modal(document.getElementById('editAreaModal'));
    const formEditArea = document.getElementById('form-edit-area');
    const inputAreaNombre = document.getElementById('edit-area-nombre');
    const textAreaDesc = document.getElementById('edit-area-descripcion');
    const imgAreaPrev = document.getElementById('edit-area-img-prev');

    document.querySelectorAll('.btn-edit-area').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const desc = this.getAttribute('data-descripcion');
            const img = this.getAttribute('data-imagen');

            // Populate form
            formEditArea.action = `/admin/servicio-educativo/areas/${id}`;
            inputAreaNombre.value = nombre;
            textAreaDesc.value = desc;
            if (img) {
                imgAreaPrev.src = img;
                imgAreaPrev.style.display = 'block';
            } else {
                imgAreaPrev.style.display = 'none';
            }

            editAreaModal.show();
        });
    });

    // EDIT PROYECTO
    const editProyectoModal = new bootstrap.Modal(document.getElementById('editProyectoModal'));
    const formEditProyecto = document.getElementById('form-edit-proyecto');
    const inputProyectoTitulo = document.getElementById('edit-proyecto-titulo');
    const textProyectoDesc = document.getElementById('edit-proyecto-descripcion');
    const inputProyectoLink = document.getElementById('edit-proyecto-link');
    const imgProyectoPrev = document.getElementById('edit-proyecto-img-prev');

    document.querySelectorAll('.btn-edit-proyecto').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const titulo = this.getAttribute('data-titulo');
            const desc = this.getAttribute('data-descripcion');
            const link = this.getAttribute('data-link');
            const img = this.getAttribute('data-imagen');

            // Populate form
            formEditProyecto.action = `/admin/servicio-educativo/proyectos/${id}`;
            inputProyectoTitulo.value = titulo;
            textProyectoDesc.value = desc;
            inputProyectoLink.value = link || '';
            if (img) {
                imgProyectoPrev.src = img;
                imgProyectoPrev.style.display = 'block';
            } else {
                imgProyectoPrev.style.display = 'none';
            }

            editProyectoModal.show();
        });
    });
});
</script>
@endsection
