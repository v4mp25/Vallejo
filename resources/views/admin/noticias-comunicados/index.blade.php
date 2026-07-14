@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-bullhorn me-2 text-primary"></i> Noticias y Comunicados
        </h2>
        <span class="text-muted small">Capítulo 9 — Portal Informativo Escolar</span>
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
        {{-- Card 1: Noticias --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-newspaper text-primary me-2"></i> 9.1 Noticias del Portal
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Publicar Noticia</h6>
                            <form action="{{ route('admin.noticias-comunicados.noticias.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título de la Noticia</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Éxito en el simulacro nacional" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha de Publicación</label>
                                    <input type="date" name="fecha" class="form-control rounded-3" required value="{{ old('fecha') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Contenido del Artículo</label>
                                    <textarea name="contenido" class="form-control rounded-3" rows="3" placeholder="Escribe el cuerpo completo de la noticia aquí..." required>{{ old('contenido') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Imagen Principal</label>
                                    <input type="file" name="imagen" class="form-control rounded-3" accept="image/*" required>
                                    <small class="text-muted">Max: 3MB. Formatos: JPG, PNG, WEBP.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha Límite (Opcional, para expirar)</label>
                                    <input type="date" name="fecha_limite" class="form-control rounded-3" value="{{ old('fecha_limite') }}">
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-upload me-1"></i> Publicar Noticia
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-list me-1"></i> Noticias Publicadas</h6>
                            @if(count($noticias) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 80px;">Foto</th>
                                                <th>Título y Fechas</th>
                                                <th>Resumen</th>
                                                <th class="text-center" style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($noticias as $noticia)
                                                @php
                                                    $isExpired = $noticia->fecha_limite && $noticia->fecha_limite->isPast();
                                                @endphp
                                                <tr class="{{ $isExpired ? 'opacity-50' : '' }}">
                                                    <td>
                                                        <img src="{{ asset('storage/' . $noticia->imagen) }}" class="rounded" style="width: 60px; height: 45px; object-fit: cover;" alt="Noticia">
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $noticia->titulo }}</div>
                                                        <small class="text-muted d-block"><i class="far fa-calendar-alt me-1"></i>{{ $noticia->fecha->format('d/m/Y') }}</small>
                                                        @if($noticia->fecha_limite)
                                                            <small class="d-block mt-1 {{ $isExpired ? 'text-danger fw-bold' : 'text-muted' }}">
                                                                <i class="fas fa-clock me-1"></i>Vence: {{ $noticia->fecha_limite->format('d/m/Y') }}
                                                                @if($isExpired)
                                                                    <span class="badge bg-danger ms-1">Expirado</span>
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="small text-muted text-truncate d-block" style="max-width: 250px;">{{ $noticia->contenido }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.noticias-comunicados.noticias.eliminar', $noticia->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta noticia?')">
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
                                    <i class="fas fa-newspaper fs-2 mb-2 opacity-50 text-secondary"></i>
                                    <p class="mb-0">No hay noticias registradas aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Comunicados --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-file-pdf text-success me-2"></i> 9.2 Comunicados Oficiales
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Cargar Comunicado</h6>
                            <form action="{{ route('admin.noticias-comunicados.comunicados.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Comunicado</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Comunicado N°015 - Matrículas 2026" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha del Comunicado</label>
                                    <input type="date" name="fecha" class="form-control rounded-3" required value="{{ old('fecha') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Contenido (Texto, Opcional)</label>
                                    <textarea name="contenido" class="form-control rounded-3" rows="3" placeholder="Detalle informativo en texto si no subes un PDF..." >{{ old('contenido') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Archivo PDF (Opcional)</label>
                                    <input type="file" name="archivo_pdf" class="form-control rounded-3" accept="application/pdf">
                                    <small class="text-muted">Solo formato PDF. Máx: 10MB.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha Límite (Opcional, para expirar)</label>
                                    <input type="date" name="fecha_limite" class="form-control rounded-3" value="{{ old('fecha_limite') }}">
                                </div>
                                <button type="submit" class="btn btn-success rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Publicar Comunicado
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-file-invoice me-1"></i> Historial de Comunicados</h6>
                            @if(count($comunicados) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título y Contenido</th>
                                                <th>Fechas</th>
                                                <th>Documento</th>
                                                <th class="text-center" style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comunicados as $com)
                                                @php
                                                    $isExpired = $com->fecha_limite && $com->fecha_limite->isPast();
                                                @endphp
                                                <tr class="{{ $isExpired ? 'opacity-50' : '' }}">
                                                    <td>
                                                        <span class="fw-bold text-dark">
                                                            @if($com->archivo_pdf)
                                                                <i class="fas fa-file-pdf text-danger me-1"></i>
                                                            @else
                                                                <i class="fas fa-file-alt text-primary me-1"></i>
                                                            @endif
                                                            {{ $com->titulo }}
                                                        </span>
                                                        @if($com->contenido)
                                                            <div class="small text-muted text-truncate" style="max-width: 300px;">{{ $com->contenido }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="small text-muted d-block">{{ $com->fecha->format('d/m/Y') }}</span>
                                                        @if($com->fecha_limite)
                                                            <small class="d-block mt-1 {{ $isExpired ? 'text-danger fw-bold' : 'text-muted' }}">
                                                                <i class="fas fa-clock me-1"></i>Vence: {{ $com->fecha_limite->format('d/m/Y') }}
                                                                @if($isExpired)
                                                                    <span class="badge bg-danger ms-1">Expirado</span>
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($com->archivo_pdf)
                                                            <a href="{{ asset('storage/' . $com->archivo_pdf) }}" target="_blank" class="btn btn-outline-primary btn-xs rounded-3 px-3">
                                                                <i class="fas fa-eye me-1"></i> Ver PDF
                                                            </a>
                                                        @else
                                                            <span class="badge bg-light text-muted border">Texto Libre</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.noticias-comunicados.comunicados.eliminar', $com->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este comunicado?')">
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
                                    <i class="fas fa-file-pdf fs-2 mb-2 opacity-50 text-success"></i>
                                    <p class="mb-0">No hay comunicados cargados aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Agenda Institucional --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-calendar-alt text-warning me-2"></i> 9.3 Agenda Escolar de Actividades
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Calendarizar Actividad</h6>
                            <form action="{{ route('admin.noticias-comunicados.agenda.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Evento</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Entrega de Libreta de Notas" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha de Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control rounded-3" required value="{{ old('fecha_inicio') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha de Cierre</label>
                                    <input type="date" name="fecha_fin" class="form-control rounded-3" required value="{{ old('fecha_fin') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Lugar / Plataforma</label>
                                    <input type="text" name="lugar" class="form-control rounded-3" placeholder="Ej: Aula Magna / Virtual Zoom" required value="{{ old('lugar') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha Límite (Opcional, para expirar)</label>
                                    <input type="date" name="fecha_limite" class="form-control rounded-3" value="{{ old('fecha_limite') }}">
                                </div>
                                <button type="submit" class="btn btn-warning rounded-pill w-100 fw-bold text-dark shadow-sm">
                                    <i class="fas fa-save me-1"></i> Programar Actividad
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-calendar-check me-1"></i> Eventos Calendarizados</h6>
                            @if(count($agenda) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título</th>
                                                <th>Fechas y Vencimiento</th>
                                                <th>Lugar</th>
                                                <th class="text-center" style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($agenda as $act)
                                                @php
                                                    $isExpired = $act->fecha_limite && $act->fecha_limite->isPast();
                                                @endphp
                                                <tr class="{{ $isExpired ? 'opacity-50' : '' }}">
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $act->titulo }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="small text-muted d-block">
                                                            @if($act->fecha_inicio->equalTo($act->fecha_fin))
                                                                {{ $act->fecha_inicio->format('d/m/Y') }}
                                                            @else
                                                                Del {{ $act->fecha_inicio->format('d/m/Y') }} al {{ $act->fecha_fin->format('d/m/Y') }}
                                                            @endif
                                                        </span>
                                                        @if($act->fecha_limite)
                                                            <small class="d-block mt-1 {{ $isExpired ? 'text-danger fw-bold' : 'text-muted' }}">
                                                                <i class="fas fa-clock me-1"></i>Vence: {{ $act->fecha_limite->format('d/m/Y') }}
                                                                @if($isExpired)
                                                                    <span class="badge bg-danger ms-1">Expirado</span>
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-primary border rounded-pill px-3 py-1 fw-semibold">{{ $act->lugar }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.noticias-comunicados.agenda.eliminar', $act->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento de la agenda?')">
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
                                    <i class="fas fa-calendar-times fs-2 mb-2 opacity-50 text-warning"></i>
                                    <p class="mb-0">No hay actividades programadas en la agenda aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Boletines --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-book-open text-danger me-2"></i> 9.4 Boletines Informativos Mensuales
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Publicar Boletín</h6>
                            <form action="{{ route('admin.noticias-comunicados.boletines.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Boletín</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Boletín Ecológico de Fiestas" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Mes y Año</label>
                                    <input type="text" name="mes_anio" class="form-control rounded-3" placeholder="Ej: Julio 2026" required value="{{ old('mes_anio') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Archivo PDF</label>
                                    <input type="file" name="archivo_pdf" class="form-control rounded-3" accept="application/pdf" required>
                                    <small class="text-muted">Solo formato PDF. Máx: 10MB.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha Límite (Opcional, para expirar)</label>
                                    <input type="date" name="fecha_limite" class="form-control rounded-3" value="{{ old('fecha_limite') }}">
                                </div>
                                <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Boletín
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-book-reader me-1"></i> Boletines Publicados</h6>
                            @if(count($boletines) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título</th>
                                                <th>Mes / Año y Vencimiento</th>
                                                <th>Documento</th>
                                                <th class="text-center" style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($boletines as $bol)
                                                @php
                                                    $isExpired = $bol->fecha_limite && $bol->fecha_limite->isPast();
                                                @endphp
                                                <tr class="{{ $isExpired ? 'opacity-50' : '' }}">
                                                    <td>
                                                        <span class="fw-bold text-dark"><i class="fas fa-file-pdf text-danger me-1"></i>{{ $bol->titulo }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="small text-muted d-block">{{ $bol->mes_anio }}</span>
                                                        @if($bol->fecha_limite)
                                                            <small class="d-block mt-1 {{ $isExpired ? 'text-danger fw-bold' : 'text-muted' }}">
                                                                <i class="fas fa-clock me-1"></i>Vence: {{ $bol->fecha_limite->format('d/m/Y') }}
                                                                @if($isExpired)
                                                                    <span class="badge bg-danger ms-1">Expirado</span>
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $bol->archivo_pdf) }}" target="_blank" class="btn btn-outline-primary btn-xs rounded-3 px-3">
                                                            <i class="fas fa-eye me-1"></i> Ver PDF
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.noticias-comunicados.boletines.eliminar', $bol->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este boletín?')">
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
                                    <i class="fas fa-book-open fs-2 mb-2 opacity-50 text-danger"></i>
                                    <p class="mb-0">No hay boletines publicados aún.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 5: Próximas Actividades --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-calendar-day text-danger me-2"></i> 9.5 Próximas Actividades
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Publicar Actividad</h6>
                            <form action="{{ route('admin.noticias-comunicados.actividades-proximas.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título de la Actividad</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Día del Campesino" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha del Evento</label>
                                    <input type="date" name="fecha" class="form-control rounded-3" required value="{{ old('fecha') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Detalle / Descripción</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Ej: Actuación cívico-escolar especial..." required>{{ old('descripcion') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Fecha Límite (Opcional, para expirar)</label>
                                    <input type="date" name="fecha_limite" class="form-control rounded-3" value="{{ old('fecha_limite') }}">
                                </div>
                                <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Actividad
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-calendar-check me-1"></i> Actividades Registradas</h6>
                            @if(count($actividadesProximas) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título</th>
                                                <th>Fecha del Evento</th>
                                                <th>Detalle</th>
                                                <th>Vencimiento</th>
                                                <th class="text-center" style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($actividadesProximas as $act)
                                                @php
                                                    $isExpired = $act->fecha_limite && $act->fecha_limite->isPast();
                                                @endphp
                                                <tr class="{{ $isExpired ? 'opacity-50' : '' }}">
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $act->titulo }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="small text-muted">{{ $act->fecha->format('d/m/Y') }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="small text-muted">{{ $act->descripcion }}</span>
                                                    </td>
                                                    <td>
                                                        @if($act->fecha_limite)
                                                            <span class="small {{ $isExpired ? 'text-danger fw-bold' : 'text-muted' }}">
                                                                {{ $act->fecha_limite->format('d/m/Y') }}
                                                                @if($isExpired)
                                                                    <span class="badge bg-danger ms-1">Expirado</span>
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.noticias-comunicados.actividades-proximas.eliminar', $act->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta actividad?')">
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
                                    <i class="fas fa-calendar-times fs-2 mb-2 opacity-50 text-danger"></i>
                                    <p class="mb-0">No hay actividades programadas aún.</p>
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
