@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-trophy me-2 text-primary"></i> Logros y Reconocimientos
        </h2>
        <span class="text-muted small">Capítulo 7 — Premiaciones e Hitos de la I.E.</span>
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
        {{-- Card 1: Registro de Logros --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i> Registrar Nuevo Logro
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('admin.logros.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Título del Logro</label>
                            <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Campeones Regionales de Vóley" required value="{{ old('titulo') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Categoría</label>
                            <select name="categoria" class="form-select rounded-3" required>
                                <option value="" disabled selected>Selecciona una categoría...</option>
                                <option value="Académicos" {{ old('categoria') == 'Académicos' ? 'selected' : '' }}>Académicos</option>
                                <option value="Deportivos" {{ old('categoria') == 'Deportivos' ? 'selected' : '' }}>Deportivos</option>
                                <option value="Artísticos" {{ old('categoria') == 'Artísticos' ? 'selected' : '' }}>Artísticos</option>
                                <option value="Científicos" {{ old('categoria') == 'Científicos' ? 'selected' : '' }}>Científicos</option>
                                <option value="Institucionales" {{ old('categoria') == 'Institucionales' ? 'selected' : '' }}>Institucionales</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Fecha del Logro</label>
                            <input type="date" name="fecha" class="form-control rounded-3" value="{{ old('fecha') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Descripción / Resumen</label>
                            <textarea name="descripcion" class="form-control rounded-3" rows="4" placeholder="Describe brevemente el logro, participantes o importancia del premio...">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Fotografía del Recuerdo</label>
                            <input type="file" name="imagen" class="form-control rounded-3" accept="image/*" required>
                            <small class="text-muted">Formatos: JPG, PNG, WEBP. Máx: 3MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm mt-2">
                            <i class="fas fa-save me-1"></i> Guardar Logro
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card 2: Historial --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-history text-success me-2"></i> Historial de Logros Registrados
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    @if(count($logros) > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 100px;">Imagen</th>
                                        <th>Título</th>
                                        <th>Categoría</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logros as $l)
                                        <tr>
                                            <td>
                                                <div class="rounded border overflow-hidden d-flex align-items-center justify-content-center" style="width: 70px; height: 50px;">
                                                    <img src="{{ asset('storage/' . $l->imagen) }}" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="Logro">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $l->titulo }}</div>
                                                <small class="text-muted text-truncate d-block" style="max-width: 250px;">{{ $l->descripcion }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-primary border rounded-pill px-3 py-2 fw-semibold">{{ $l->categoria }}</span>
                                            </td>
                                            <td>
                                                <span class="small text-muted">{{ $l->fecha ? $l->fecha->format('d/m/Y') : 'N/A' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.logros.eliminar', $l->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este logro o reconocimiento?')">
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
                            <i class="fas fa-award fs-1 mb-3 opacity-50 text-warning"></i>
                            <p class="mb-0 fw-semibold">No hay logros registrados aún.</p>
                            <small class="text-muted">Completa el formulario de la izquierda para registrar la primera premiación.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
