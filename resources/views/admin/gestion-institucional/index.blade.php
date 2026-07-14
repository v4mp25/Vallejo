@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-university me-2 text-primary"></i> Gestión Institucional
        </h2>
        <span class="text-muted small">Capítulo 3 — Organización y Personal</span>
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
                        <i class="fas fa-users-cog me-2 text-primary"></i> 3.1 Personal Institucional (Directivos y Administrativos)
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
                                                        @else
                                                            <span class="badge bg-secondary">Administrativo</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-3 btn-edit-personal"
                                                                    data-id="{{ $p->id }}"
                                                                    data-nombres="{{ $p->nombres }}"
                                                                    data-apellidos="{{ $p->apellidos }}"
                                                                    data-cargo="{{ $p->cargo }}"
                                                                    data-categoria="{{ $p->categoria }}"
                                                                    data-foto="{{ $p->foto ? asset('storage/' . $p->foto) : '' }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('admin.gestion-institucional.personal.eliminar', $p->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este miembro del personal?')">
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
                                    <i class="fas fa-user-slash fs-2 mb-2 opacity-50"></i>
                                    <p class="mb-0">No hay personal registrado todavía.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario unificado para Organigrama y Órganos de Participación --}}
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
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Descripción del CONEI</label>
                                <textarea name="conei_descripcion" class="form-control rounded-3" rows="5" placeholder="Describe los representantes y las funciones del Consejo Educativo Institucional (CONEI)...">{{ old('conei_descripcion', $info->conei_descripcion) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Descripción de la APAFA</label>
                                <textarea name="apafa_descripcion" class="form-control rounded-3" rows="5" placeholder="Describe la junta directiva y el rol de la Asociación de Padres de Familia (APAFA)...">{{ old('apafa_descripcion', $info->apafa_descripcion) }}</textarea>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Guardar Organigrama y Descripciones
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Card 3.5: Carga de Archivos/Enlaces para CONEI/APAFA (Subir N) --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-folder-plus me-2 text-warning"></i> Documentos y Enlaces para Órganos de Participación (CONEI / APAFA)
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-lg-5 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus-circle me-1"></i> Subir Documento o Enlace</h6>
                            <form action="{{ route('admin.gestion-institucional.organos.documento.guardar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Órgano de Destino</label>
                                    <select name="organo" class="form-select rounded-3" required>
                                        <option value="" disabled selected>Selecciona órgano...</option>
                                        <option value="conei">CONEI</option>
                                        <option value="apafa">APAFA</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Título del Documento o Enlace</label>
                                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Reglamento Interno de APAFA" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Archivo PDF (Opcional)</label>
                                    <input type="file" name="archivo_pdf" class="form-control rounded-3" accept=".pdf">
                                    <small class="text-muted">Adjuntar archivo en formato PDF (máx: 10MB).</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Enlace Web / URL (Opcional)</label>
                                    <input type="url" name="link" class="form-control rounded-3" placeholder="Ej: https://site.com/reuniones">
                                    <small class="text-muted">Dirección web redirigible.</small>
                                </div>
                                <button type="submit" class="btn btn-warning text-dark rounded-pill w-100 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> Registrar Documento/Enlace
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-7">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-folder-open me-1"></i> Archivos y Enlaces Registrados</h6>
                            <div class="row g-3">
                                <div class="col-md-6 border-end">
                                    <h7 class="fw-bold text-primary mb-2 d-block"><i class="fas fa-gavel me-1"></i> CONEI</h7>
                                    @if(count($coneiDocs) > 0)
                                        <ul class="list-group list-group-flush small">
                                            @foreach($coneiDocs as $doc)
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <div class="text-truncate" style="max-width: 80%;">
                                                        <span class="fw-semibold text-dark d-block text-truncate" title="{{ $doc->titulo }}">{{ $doc->titulo }}</span>
                                                        <div class="mt-1">
                                                            @if($doc->archivo_pdf)
                                                                <a href="{{ asset('storage/' . $doc->archivo_pdf) }}" target="_blank" class="badge bg-danger text-decoration-none me-1"><i class="fas fa-file-pdf"></i> PDF</a>
                                                            @endif
                                                            @if($doc->link)
                                                                <a href="{{ $doc->link }}" target="_blank" class="badge bg-primary text-decoration-none"><i class="fas fa-link"></i> Enlace</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('admin.gestion-institucional.organos.documento.eliminar', $doc->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este documento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm border-0">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted small fst-italic py-2">No hay archivos ni enlaces registrados.</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h7 class="fw-bold text-warning mb-2 d-block"><i class="fas fa-handshake me-1"></i> APAFA</h7>
                                    @if(count($apafaDocs) > 0)
                                        <ul class="list-group list-group-flush small">
                                            @foreach($apafaDocs as $doc)
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <div class="text-truncate" style="max-width: 80%;">
                                                        <span class="fw-semibold text-dark d-block text-truncate" title="{{ $doc->titulo }}">{{ $doc->titulo }}</span>
                                                        <div class="mt-1">
                                                            @if($doc->archivo_pdf)
                                                                <a href="{{ asset('storage/' . $doc->archivo_pdf) }}" target="_blank" class="badge bg-danger text-decoration-none me-1"><i class="fas fa-file-pdf"></i> PDF</a>
                                                            @endif
                                                            @if($doc->link)
                                                                <a href="{{ $doc->link }}" target="_blank" class="badge bg-primary text-decoration-none"><i class="fas fa-link"></i> Enlace</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('admin.gestion-institucional.organos.documento.eliminar', $doc->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este documento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm border-0">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted small fst-italic py-2">No hay archivos ni enlaces registrados.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

{{-- MODAL EDITAR PERSONAL --}}
<div class="modal fade" id="editPersonalModal" tabindex="-1" aria-labelledby="editPersonalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editPersonalModalLabel">Editar Miembro de Personal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-personal" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nombres</label>
                        <input type="text" name="nombres" id="edit-pers-nombres" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Apellidos</label>
                        <input type="text" name="apellidos" id="edit-pers-apellidos" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Cargo</label>
                        <input type="text" name="cargo" id="edit-pers-cargo" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Categoría</label>
                        <select name="categoria" id="edit-pers-categoria" class="form-select rounded-3" required>
                            <option value="directivo">Directivo</option>
                            <option value="administrativo">Administrativo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Foto actual</label>
                        <div class="mb-2 text-center">
                            <img id="edit-pers-foto-prev" src="" class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <label class="form-label small fw-semibold">Cambiar Foto (Opcional)</label>
                        <input type="file" name="foto" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">Formatos: JPG, PNG, WEBP (máx: 2MB).</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // EDIT PERSONAL
    const editPersonalModal = new bootstrap.Modal(document.getElementById('editPersonalModal'));
    const formEditPersonal = document.getElementById('form-edit-personal');
    const inputPersNombres = document.getElementById('edit-pers-nombres');
    const inputPersApellidos = document.getElementById('edit-pers-apellidos');
    const inputPersCargo = document.getElementById('edit-pers-cargo');
    const selectPersCategoria = document.getElementById('edit-pers-categoria');
    const imgPersFotoPrev = document.getElementById('edit-pers-foto-prev');

    document.querySelectorAll('.btn-edit-personal').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombres = this.getAttribute('data-nombres');
            const apellidos = this.getAttribute('data-apellidos');
            const cargo = this.getAttribute('data-cargo');
            const categoria = this.getAttribute('data-categoria');
            const foto = this.getAttribute('data-foto');

            // Populate form
            formEditPersonal.action = `/admin/gestion-institucional/personal/${id}`;
            inputPersNombres.value = nombres;
            inputPersApellidos.value = apellidos;
            inputPersCargo.value = cargo;
            selectPersCategoria.value = categoria;

            if (foto) {
                imgPersFotoPrev.src = foto;
                imgPersFotoPrev.style.display = 'inline-block';
            } else {
                imgPersFotoPrev.style.display = 'none';
            }

            editPersonalModal.show();
        });
    });
});
</script>
@endsection
