@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-images me-2 text-primary"></i> Galería Institucional
        </h2>
        <span class="text-muted small">Capítulo 8 — Momentos, Videos, Eventos y Actividades</span>
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
        {{-- Card 1: Fotografías --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-camera text-primary me-2"></i> 8.1 Fotografías de Momentos
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Subir Fotografía</h6>
                            <form action="{{ route('admin.galeria-institucional.fotos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título / Leyenda</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Aniversario del Colegio" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Archivo de Imagen</label>
                                    <input type="file" name="imagen" class="form-control rounded-3" accept="image/*" required>
                                    <small class="text-muted">Max: 3MB. Formatos: JPG, PNG, WEBP.</small>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-upload me-1"></i> Agregar Foto
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-th me-1"></i> Fotos de Recuerdos Actuales</h6>
                            @if(count($fotos) > 0)
                                <div class="row g-3">
                                    @foreach($fotos as $foto)
                                        <div class="col-md-4 col-xl-3">
                                            <div class="card border rounded-3 overflow-hidden h-100 position-relative shadow-xs">
                                                <img src="{{ asset('storage/' . $foto->imagen) }}" class="card-img-top" style="height: 100px; object-fit: cover;" alt="Foto">
                                                <div class="card-body p-2 d-flex flex-column justify-content-between">
                                                    <span class="text-dark small fw-semibold text-truncate d-block mb-2" title="{{ $foto->titulo }}">{{ $foto->titulo }}</span>
                                                    <form action="{{ route('admin.galeria-institucional.fotos.eliminar', $foto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta fotografía?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-xs w-100 rounded-3 py-1">
                                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-muted bg-light rounded-3">
                                    <i class="fas fa-image fs-2 mb-2 opacity-50 text-secondary"></i>
                                    <p class="mb-0">No hay fotos registradas aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Videos --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-video text-success me-2"></i> 8.2 Videos de YouTube
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Registrar Nuevo Video</h6>
                            <form action="{{ route('admin.galeria-institucional.videos.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Video</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Himno de la I.E. César Vallejo" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Enlace Web (YouTube)</label>
                                    <input type="url" name="url_video" class="form-control rounded-3" placeholder="Ej: https://www.youtube.com/watch?v=..." required value="{{ old('url_video') }}">
                                </div>
                                <button type="submit" class="btn btn-success rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Video
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-list me-1"></i> Historial de Videos</h6>
                            @if(count($videos) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título</th>
                                                <th>Enlace YouTube</th>
                                                <th class="text-center" style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($videos as $video)
                                                <tr>
                                                    <td>
                                                        <span class="fw-bold text-dark"><i class="fab fa-youtube text-danger me-1"></i>{{ $video->titulo }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $video->url_video }}" target="_blank" class="small text-decoration-none text-primary">{{ $video->url_video }}</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.galeria-institucional.videos.eliminar', $video->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este video?')">
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
                                    <i class="fab fa-youtube fs-2 mb-2 opacity-50 text-danger"></i>
                                    <p class="mb-0">No hay videos registrados aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Eventos --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-calendar-alt text-warning me-2"></i> 8.3 Eventos Cívicos e Institucionales
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Registrar Evento</h6>
                            <form action="{{ route('admin.galeria-institucional.eventos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Evento</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Desfile Escolar por Fiestas Patrias" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha del Evento</label>
                                    <input type="date" name="fecha" class="form-control rounded-3" required value="{{ old('fecha') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Descripción del Evento</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Describe brevemente de qué trató el evento escolar...">{{ old('descripcion') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Imagen del Evento</label>
                                    <input type="file" name="imagen" class="form-control rounded-3" accept="image/*" required>
                                    <small class="text-muted">Max: 3MB. Formatos: JPG, PNG, WEBP.</small>
                                </div>
                                <button type="submit" class="btn btn-warning rounded-pill w-100 fw-bold text-dark shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Evento
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-th-large me-1"></i> Eventos Registrados</h6>
                            @if(count($eventos) > 0)
                                <div class="row g-3">
                                    @foreach($eventos as $evento)
                                        <div class="col-md-6 col-xl-4">
                                            <div class="card border rounded-3 overflow-hidden h-100 shadow-xs">
                                                <img src="{{ asset('storage/' . $evento->imagen) }}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Evento">
                                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill small fw-semibold">{{ $evento->fecha->format('d/m/Y') }}</span>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $evento->titulo }}">{{ $evento->titulo }}</h6>
                                                        <p class="text-muted small mb-3 text-truncate">{{ $evento->descripcion }}</p>
                                                    </div>
                                                    <form action="{{ route('admin.galeria-institucional.eventos.eliminar', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold">
                                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-muted bg-light rounded-3">
                                    <i class="fas fa-calendar fs-2 mb-2 opacity-50 text-warning"></i>
                                    <p class="mb-0">No hay eventos cívicos registrados aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Actividades Pedagógicas --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-chalkboard-teacher text-danger me-2"></i> 8.4 Actividades Pedagógicas y Académicas
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Registrar Actividad</h6>
                            <form action="{{ route('admin.galeria-institucional.actividades.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título de la Actividad</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Feria Escolar de Ciencias y Tecnología" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha de la Actividad</label>
                                    <input type="date" name="fecha" class="form-control rounded-3" required value="{{ old('fecha') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Descripción</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Describe brevemente de qué trató la actividad pedagógica...">{{ old('descripcion') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Imagen ilustrativa</label>
                                    <input type="file" name="imagen" class="form-control rounded-3" accept="image/*" required>
                                    <small class="text-muted">Max: 3MB. Formatos: JPG, PNG, WEBP.</small>
                                </div>
                                <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Actividad
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-th-large me-1"></i> Actividades Registradas</h6>
                            @if(count($actividades) > 0)
                                <div class="row g-3">
                                    @foreach($actividades as $act)
                                        <div class="col-md-6 col-xl-4">
                                            <div class="card border rounded-3 overflow-hidden h-100 shadow-xs">
                                                <img src="{{ asset('storage/' . $act->imagen) }}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Actividad">
                                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill small fw-semibold">{{ $act->fecha->format('d/m/Y') }}</span>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $act->titulo }}">{{ $act->titulo }}</h6>
                                                        <p class="text-muted small mb-3 text-truncate">{{ $act->descripcion }}</p>
                                                    </div>
                                                    <form action="{{ route('admin.galeria-institucional.actividades.eliminar', $act->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta actividad?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold">
                                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-muted bg-light rounded-3">
                                    <i class="fas fa-chalkboard-teacher fs-2 mb-2 opacity-50 text-danger"></i>
                                    <p class="mb-0">No hay actividades pedagógicas registradas aún.</p>
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
