@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-users me-2 text-primary"></i> Comunidad Educativa
        </h2>
        <span class="text-muted small">Capítulo 6 — Grupos y Aliados de la Institución</span>
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
        {{-- Card 1: Textos Descriptivos y Documentos --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-file-alt text-primary me-2"></i> Información General y Documentos Oficiales
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('admin.comunidad-educativa.guardar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Estudiantes</label>
                                <textarea name="estudiantes_texto" class="form-control rounded-3" rows="5" placeholder="Descripción o mensaje dirigido a los estudiantes de la institución...">{{ old('estudiantes_texto', $info->estudiantes_texto) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Padres de Familia</label>
                                <textarea name="padres_texto" class="form-control rounded-3" rows="5" placeholder="Mensaje, normas o información relevante para los padres de familia...">{{ old('padres_texto', $info->padres_texto) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Exalumnos / Egresados</label>
                                <textarea name="exalumnos_texto" class="form-control rounded-3" rows="5" placeholder="Mensaje o convocatoria para la comunidad de exalumnos...">{{ old('exalumnos_texto', $info->exalumnos_texto) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold text-dark small"><i class="fas fa-file-pdf text-danger me-1"></i> Reglamento Escolar (PDF)</label>
                                    <input type="file" name="reglamento_pdf" class="form-control rounded-3" accept=".pdf">
                                    <small class="text-muted d-block mt-1">Sube el reglamento general en formato PDF (Max 10MB).</small>
                                    @if($info->reglamento_pdf)
                                        <div class="mt-2 small d-flex align-items-center">
                                            <i class="fas fa-check text-success me-1"></i> 
                                            <span class="text-secondary me-2">Archivo actual cargado:</span>
                                            <a href="{{ asset('storage/' . $info->reglamento_pdf) }}" target="_blank" class="fw-semibold text-primary text-decoration-none">
                                                <i class="fas fa-external-link-alt small"></i> Ver Reglamento
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold text-dark small"><i class="fas fa-file-pdf text-danger me-1"></i> Cronograma de Notas (PDF)</label>
                                    <input type="file" name="cronograma_notes_pdf" class="form-control rounded-3" accept=".pdf">
                                    <small class="text-muted d-block mt-1">Sube el cronograma escolar de evaluaciones en formato PDF (Max 10MB).</small>
                                    @if($info->cronograma_notes_pdf)
                                        <div class="mt-2 small d-flex align-items-center">
                                            <i class="fas fa-check text-success me-1"></i> 
                                            <span class="text-secondary me-2">Archivo actual cargado:</span>
                                            <a href="{{ asset('storage/' . $info->cronograma_notes_pdf) }}" target="_blank" class="fw-semibold text-primary text-decoration-none">
                                                <i class="fas fa-external-link-alt small"></i> Ver Cronograma
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Guardar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card 2: Aliados Estratégicos --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-handshake text-success me-2"></i> Aliados Estratégicos
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus me-1"></i> Agregar Nuevo Aliado</h6>
                            <form action="{{ route('admin.comunidad-educativa.aliados.guardar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nombre de la Institución</label>
                                    <input type="text" name="nombre" class="form-control rounded-3" placeholder="Ej: Universidad Nacional de Trujillo" required value="{{ old('nombre') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Enlace Web (Opcional)</label>
                                    <input type="url" name="enlace_web" class="form-control rounded-3" placeholder="Ej: https://www.unitru.edu.pe" value="{{ old('enlace_web') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Descripción / Convenio (Opcional)</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Describe brevemente el tipo de convenio o alianza comercial...">{{ old('descripcion') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Logo de la Institución</label>
                                    <input type="file" name="logo" class="form-control rounded-3" accept="image/*" required>
                                    <small class="text-muted">Formatos: JPG, PNG, WEBP. Máx: 2MB.</small>
                                </div>
                                <button type="submit" class="btn btn-success rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Registrar Aliado
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-list me-1"></i> Aliados Estratégicos Registrados</h6>
                            @if(count($aliados) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 100px;">Logo</th>
                                                <th>Institución</th>
                                                <th>Detalles / Convenio</th>
                                                <th>Enlace</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($aliados as $aliado)
                                                <tr>
                                                    <td>
                                                        <div class="rounded border p-1 bg-light d-flex align-items-center justify-content-center" style="width: 70px; height: 50px;">
                                                            <img src="{{ asset('storage/' . $aliado->logo) }}" class="img-fluid" style="max-height: 40px; object-fit: contain;" alt="Logo">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $aliado->nombre }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted small">{{ $aliado->descripcion ?? 'Sin descripción.' }}</span>
                                                    </td>
                                                    <td>
                                                        @if($aliado->enlace_web)
                                                            <a href="{{ $aliado->enlace_web }}" target="_blank" class="badge bg-light text-primary border text-decoration-none py-2 px-3 rounded-pill fw-semibold">
                                                                <i class="fas fa-link me-1"></i> Ir a Web
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">No tiene enlace</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.comunidad-educativa.aliados.eliminar', $aliado->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este aliado estratégico?')">
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
                                    <i class="fas fa-handshake fs-2 mb-2 opacity-50 text-success"></i>
                                    <p class="mb-0">No hay aliados estratégicos registrados aún.</p>
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
