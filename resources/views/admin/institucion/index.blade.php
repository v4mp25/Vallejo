@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-school me-2 text-primary"></i> Nuestra Institución
        </h2>
        <span class="text-muted small">Capítulo 2 — Información Institucional</span>
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

    <form action="{{ route('admin.institucion.guardar') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 2.1 Reseña Histórica --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-book-open me-2 text-primary"></i> 2.1 Reseña Histórica
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                <textarea name="resena_historica" class="form-control rounded-3" rows="6"
                    placeholder="Escribe aquí la historia de la institución...">{{ old('resena_historica', $info->resena_historica) }}</textarea>
            </div>
        </div>

        {{-- 2.2 Identidad Institucional --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-star me-2 text-warning"></i> 2.2 Identidad Institucional
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Lema Institucional</label>
                    <input type="text" name="lema" class="form-control rounded-3"
                        placeholder='Ej: "Formamos líderes con corazón vallejiano"'
                        value="{{ old('lema', $info->lema) }}">
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Misión</label>
                        <textarea name="mision" class="form-control rounded-3" rows="5"
                            placeholder="Escribe la misión...">{{ old('mision', $info->mision) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Visión</label>
                        <textarea name="vision" class="form-control rounded-3" rows="5"
                            placeholder="Escribe la visión...">{{ old('vision', $info->vision) }}</textarea>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Valores</label>
                        <textarea name="valores" class="form-control rounded-3" rows="4"
                            placeholder="Ej: Respeto, Honestidad, Responsabilidad...">{{ old('valores', $info->valores) }}</textarea>
                        <small class="text-muted">Separados por coma o uno por línea.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Principios</label>
                        <textarea name="principios" class="form-control rounded-3" rows="4"
                            placeholder="Principios institucionales...">{{ old('principios', $info->principios) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2.3 Símbolos Institucionales --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-music me-2 text-success"></i> 2.3 Símbolos Institucionales
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Letra del Himno</label>
                    <textarea name="letra_himno" class="form-control rounded-3" rows="8"
                        placeholder="Pega aquí la letra completa del himno...">{{ old('letra_himno', $info->letra_himno) }}</textarea>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Descripción del Uniforme</label>
                        <textarea name="uniforme_descripcion" class="form-control rounded-3" rows="3"
                            placeholder="Describe el uniforme oficial...">{{ old('uniforme_descripcion', $info->uniforme_descripcion) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Imagen del Uniforme</label>
                        <input type="file" name="uniforme_imagen" class="form-control rounded-3" accept="image/*">
                        @if($info->uniforme_imagen)
                            <div class="mt-2">
                                <small class="text-muted">Imagen actual:</small><br>
                                <img src="{{ asset('storage/' . $info->uniforme_imagen) }}"
                                    class="img-thumbnail mt-1 rounded-3" style="max-height:120px">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 2.4 Infraestructura --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-building me-2 text-danger"></i> 2.4 Infraestructura
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                <textarea name="infraestructura_descripcion" class="form-control rounded-3" rows="5"
                    placeholder="Describe las instalaciones: aulas, laboratorios, biblioteca, etc.">{{ old('infraestructura_descripcion', $info->infraestructura_descripcion) }}</textarea>
                <small class="text-muted">Puedes describir cada ambiente o listarlo por líneas.</small>
            </div>
        </div>

        {{-- 2.5 Línea de Tiempo --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-clock me-2 text-info"></i> 2.5 Línea de Tiempo
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div id="lineaTiempo">
                    @php $eventos = $info->linea_tiempo ?? []; @endphp
                    @if(count($eventos) > 0)
                        @foreach($eventos as $ev)
                        <div class="row g-2 mb-2 linea-item align-items-center">
                            <div class="col-md-2">
                                <input type="text" name="lt_anio[]" class="form-control rounded-3" placeholder="Año" value="{{ $ev['anio'] ?? '' }}">
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="lt_evento[]" class="form-control rounded-3" placeholder="Descripción del evento" value="{{ $ev['evento'] ?? '' }}">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger rounded-3 w-100" onclick="this.closest('.linea-item').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="row g-2 mb-2 linea-item align-items-center">
                            <div class="col-md-2">
                                <input type="text" name="lt_anio[]" class="form-control rounded-3" placeholder="Año">
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="lt_evento[]" class="form-control rounded-3" placeholder="Descripción del evento">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger rounded-3 w-100" onclick="this.closest('.linea-item').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-outline-primary rounded-pill btn-sm mt-3" id="btnAgregarEvento">
                    <i class="fas fa-plus me-1"></i> Agregar evento
                </button>
            </div>
        </div>

        {{-- Botón guardar --}}
        <div class="d-flex justify-content-end mb-5">
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                <i class="fas fa-save me-2"></i> Guardar todo
            </button>
        </div>

    </form>
</div>

<script>
document.getElementById('btnAgregarEvento').addEventListener('click', function () {
    const container = document.getElementById('lineaTiempo');
    const div = document.createElement('div');
    div.className = 'row g-2 mb-2 linea-item align-items-center';
    div.innerHTML = `
        <div class="col-md-2"><input type="text" name="lt_anio[]" class="form-control rounded-3" placeholder="Año"></div>
        <div class="col-md-9"><input type="text" name="lt_evento[]" class="form-control rounded-3" placeholder="Descripción del evento"></div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger rounded-3 w-100" onclick="this.closest('.linea-item').remove()">
                <i class="fas fa-trash"></i>
            </button>
        </div>`;
    container.appendChild(div);
});
</script>
@endsection
