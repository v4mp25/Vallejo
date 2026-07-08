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
                            <form action="{{ route('admin.servicio-educativo.areas.guardar') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nombre de la Área</label>
                                    <input type="text" name="nombre" class="form-control rounded-3" placeholder="Ej: Matemática" required value="{{ old('nombre') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Clase de Icono (FontAwesome)</label>
                                    <input type="text" name="icono" class="form-control rounded-3" placeholder="Ej: fas fa-calculator" required value="{{ old('icono') }}">
                                    <small class="text-muted">Busca clases en FontAwesome (ej. `fas fa-language`, `fas fa-flask`).</small>
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
                                                <th style="width: 80px;">Icono</th>
                                                <th>Área</th>
                                                <th>Descripción</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($areas as $area)
                                                <tr>
                                                    <td>
                                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-primary" style="width: 45px; height: 45px; font-size: 1.5rem;">
                                                            <i class="{{ $area->icono }}"></i>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $area->nombre }}</span>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 text-muted small" style="max-height: 50px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $area->descripcion }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.servicio-educativo.areas.eliminar', $area->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este curso?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-3">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
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
                                                        <p class="text-muted mb-3 small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $p->descripcion }}</p>
                                                    </div>
                                                    <div class="text-end">
                                                        <form action="{{ route('admin.servicio-educativo.proyectos.eliminar', $p->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proyecto?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold">
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
@endsection
