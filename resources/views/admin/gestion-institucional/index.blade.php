@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-folder-open me-2 text-primary"></i> Gestión Institucional
        </h2>
        <span class="text-muted small">Capítulo 3 — Gestión Escolar</span>
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
        {{-- Card 1: 3.1 Personal Institucional --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-users me-2 text-primary"></i> 3.1 Personal Institucional
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-user-plus me-1"></i> Agregar Nuevo Personal</h6>
                            <form action="{{ route('admin.gestion-institucional.personal.guardar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nombres</label>
                                    <input type="text" name="nombres" class="form-control rounded-3" placeholder="Ej: Juan" required value="{{ old('nombres') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Apellidos</label>
                                    <input type="text" name="apellidos" class="form-control rounded-3" placeholder="Ej: Pérez" required value="{{ old('apellidos') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Cargo</label>
                                    <input type="text" name="cargo" class="form-control rounded-3" placeholder="Ej: Subdirector académico" required value="{{ old('cargo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Categoría</label>
                                    <select name="categoria" class="form-select rounded-3" required>
                                        <option value="" disabled selected>Selecciona una categoría...</option>
                                        <option value="directivo" {{ old('categoria') === 'directivo' ? 'selected' : '' }}>Directivo</option>
                                        <option value="docente" {{ old('categoria') === 'docente' ? 'selected' : '' }}>Docente</option>
                                        <option value="administrativo" {{ old('categoria') === 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Foto (Opcional)</label>
                                    <input type="file" name="foto" class="form-control rounded-3" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Agregar Personal
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-list me-1"></i> Lista de Personal Registrado</h6>
                            @if(count($personal) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Foto</th>
                                                <th>Nombres y Apellidos</th>
                                                <th>Cargo</th>
                                                <th>Categoría</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($personal as $p)
                                                <tr>
                                                    <td>
                                                        @if($p->foto)
                                                            <img src="{{ asset('storage/' . $p->foto) }}" class="rounded-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle border d-flex align-items-center justify-content-center bg-light text-muted" style="width: 45px; height: 45px;">
                                                                <i class="fas fa-user-tie"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $p->nombres }} {{ $p->apellidos }}</span>
                                                    </td>
                                                    <td>{{ $p->cargo }}</td>
                                                    <td>
                                                        @if($p->categoria === 'directivo')
                                                            <span class="badge bg-primary">Directivo</span>
                                                        @elseif($p->categoria === 'docente')
                                                            <span class="badge bg-success">Docente</span>
                                                        @else
                                                            <span class="badge bg-secondary">Administrativo</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.gestion-institucional.personal.eliminar', $p->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este miembro del personal?')">
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
                                    <i class="fas fa-user-slash fs-2 mb-2 opacity-50"></i>
                                    <p class="mb-0">No hay personal registrado todavía.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario unificado para Organigrama y Órganos de Participación (CONEI y APAFA) --}}
        <div class="col-12">
            <form action="{{ route('admin.gestion-institucional.guardar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Card 2: 3.2 Organigrama --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fas fa-sitemap me-2 text-success"></i> 3.2 Organigrama
                        </h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subir imagen del Organigrama</label>
                                <input type="file" name="organigrama_imagen" class="form-control rounded-3" accept="image/*">
                                <small class="text-muted d-block mt-2">Formatos permitidos: JPG, PNG, WEBP. Tamaño máx: 2MB.</small>
                            </div>
                            <div class="col-md-6">
                                @if($info->organigrama_imagen)
                                    <div class="p-2 border rounded-3 bg-light text-center">
                                        <small class="text-muted d-block mb-1">Imagen actual:</small>
                                        <img src="{{ asset('storage/' . $info->organigrama_imagen) }}" class="img-thumbnail rounded-3 mb-2" style="max-height: 120px;">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input me-2" type="checkbox" name="eliminar_organigrama" value="1" id="del_orga">
                                            <label class="form-check-label text-danger small fw-semibold" for="del_orga">Eliminar imagen actual</label>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted small fst-italic mb-0 text-center py-3 bg-light rounded-3 border border-dashed">Sin organigrama cargado actualmente.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: 3.3 Órganos de Participación (CONEI y APAFA) --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fas fa-handshake me-2 text-warning"></i> 3.3 Órganos de Participación
                        </h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Descripción del CONEI</label>
                                <textarea name="conei_descripcion" class="form-control rounded-3" rows="6" placeholder="Describe los representantes y las funciones del Consejo Educativo Institucional (CONEI)...">{{ old('conei_descripcion', $info->conei_descripcion) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Descripción de la APAFA</label>
                                <textarea name="apafa_descripcion" class="form-control rounded-3" rows="6" placeholder="Describe la junta directiva y el rol de la Asociación de Padres de Familia (APAFA)...">{{ old('apafa_descripcion', $info->apafa_descripcion) }}</textarea>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Guardar Organigrama y Órganos
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Card 4: 3.4 Instrumentos de Gestión --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-file-pdf me-2 text-danger"></i> 3.4 Instrumentos de Gestión
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-file-upload me-1"></i> Subir Documento Oficial</h6>
                            <form action="{{ route('admin.gestion-institucional.documentos.guardar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Documento</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: PEI 2026 - 2030" required value="{{ old('titulo') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Tipo de Instrumento</label>
                                    <select name="tipo" class="form-select rounded-3" required>
                                        <option value="" disabled selected>Selecciona un tipo...</option>
                                        <option value="PEI" {{ old('tipo') === 'PEI' ? 'selected' : '' }}>Proyecto Educativo Institucional (PEI)</option>
                                        <option value="PAT" {{ old('tipo') === 'PAT' ? 'selected' : '' }}>Plan Anual de Trabajo (PAT)</option>
                                        <option value="PCI" {{ old('tipo') === 'PCI' ? 'selected' : '' }}>Proyecto Curricular de la Institución (PCI)</option>
                                        <option value="RI" {{ old('tipo') === 'RI' ? 'selected' : '' }}>Reglamento Interno (RI)</option>
                                        <option value="IGA" {{ old('tipo') === 'IGA' ? 'selected' : '' }}>Instrumentos de Gestión Ambiental (IGA)</option>
                                        <option value="OTROS" {{ old('tipo') === 'OTROS' ? 'selected' : '' }}>Otros Documentos</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Archivo PDF</label>
                                    <input type="file" name="archivo_pdf" class="form-control rounded-3" accept=".pdf" required>
                                    <small class="text-muted d-block mt-1">Solo se admiten archivos .pdf hasta 10MB.</small>
                                </div>
                                <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-upload me-1"></i> Subir Documento
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-folder-open me-1"></i> Documentos Registrados</h6>
                            @if(count($documentos) > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título</th>
                                                <th>Tipo</th>
                                                <th class="text-center">PDF</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($documentos as $d)
                                                <tr>
                                                    <td class="fw-semibold text-dark">{{ $d->titulo }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary opacity-75">{{ $d->tipo }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ asset('storage/' . $d->archivo_pdf) }}" target="_blank" class="btn btn-light btn-sm rounded-circle text-danger border" title="Ver / Descargar PDF">
                                                            <i class="fas fa-file-pdf fs-5"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ route('admin.gestion-institucional.documentos.eliminar', $d->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este documento?')">
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
                                    <i class="fas fa-file-excel fs-2 mb-2 opacity-50"></i>
                                    <p class="mb-0">No hay documentos de gestión subidos todavía.</p>
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
