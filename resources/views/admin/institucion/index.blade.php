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
                        <label class="form-label fw-semibold">Imágenes del Uniforme (Galería)</label>
                        <input type="file" name="uniforme_imagenes[]" class="form-control rounded-3 mb-2" accept="image/*" multiple>
                        <small class="text-muted d-block mb-3">Puedes seleccionar múltiples archivos de imagen para subir a la galería.</small>

                        @if($info->uniforme_imagenes && count($info->uniforme_imagenes) > 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small d-block mb-2">Imágenes de la galería actual (marca para eliminar):</label>
                                <div class="row g-2">
                                    @foreach($info->uniforme_imagenes as $img)
                                        <div class="col-6 col-sm-4 text-center">
                                            <div class="position-relative border rounded-3 p-1 bg-light h-100 d-flex flex-column justify-content-between">
                                                <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail rounded-3 mb-1" style="height: 80px; width: 100%; object-fit: cover;">
                                                <div class="form-check d-flex justify-content-center align-items-center mb-0">
                                                    <input class="form-check-input me-1" type="checkbox" name="eliminar_imagenes[]" value="{{ $img }}" id="del_{{ md5($img) }}">
                                                    <label class="form-check-label small text-danger fw-semibold" for="del_{{ md5($img) }}" style="font-size: 0.75rem;">Eliminar</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($info->uniforme_imagen)
                            <div class="border-top pt-3 mt-3">
                                <label class="form-label fw-semibold text-secondary small d-block mb-2">Imagen única antigua:</label>
                                <div class="row g-2">
                                    <div class="col-6 col-sm-4">
                                        <div class="position-relative border rounded-3 p-1 bg-light text-center h-100 d-flex flex-column justify-content-between">
                                            <img src="{{ asset('storage/' . $info->uniforme_imagen) }}" class="img-thumbnail rounded-3 mb-1" style="height: 80px; width: 100%; object-fit: cover;">
                                            <div class="form-check d-flex justify-content-center align-items-center mb-0">
                                                <input class="form-check-input me-1" type="checkbox" name="eliminar_imagen_single" value="1" id="del_single">
                                                <label class="form-check-label small text-danger fw-semibold" for="del_single" style="font-size: 0.75rem;">Eliminar antigua</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Esta imagen corresponde al formato anterior. Puedes eliminarla si ya subiste nuevas imágenes.</small>
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
                <div class="mb-4">
                    <label class="form-label fw-semibold">Descripción o Reseña General de Infraestructura</label>
                    <textarea name="infraestructura_descripcion" class="form-control rounded-3" rows="3"
                        placeholder="Describe de forma general las instalaciones: aulas, laboratorios, biblioteca, etc.">{{ old('infraestructura_descripcion', $info->infraestructura_descripcion) }}</textarea>
                    <small class="text-muted">Este texto aparecerá como introducción en la sección de infraestructura.</small>
                </div>

                <hr class="my-4 text-muted">

                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-images me-2"></i> Galerías de Fotos por Categoría</h6>
                
                <div class="row g-4">
                    @php
                        $categorias = [
                            'aulas' => [
                                'titulo' => 'Aulas Modernas',
                                'db_field' => 'infra_aulas_imagenes',
                                'file_name' => 'infra_aulas_imagenes[]',
                                'del_name' => 'eliminar_infra_aulas[]',
                                'color' => 'primary',
                                'icon' => 'chalkboard-teacher'
                            ],
                            'labs' => [
                                'titulo' => 'Laboratorios',
                                'db_field' => 'infra_labs_imagenes',
                                'file_name' => 'infra_labs_imagenes[]',
                                'del_name' => 'eliminar_infra_labs[]',
                                'color' => 'success',
                                'icon' => 'flask'
                            ],
                            'biblio' => [
                                'titulo' => 'Biblioteca',
                                'db_field' => 'infra_biblio_imagenes',
                                'file_name' => 'infra_biblio_imagenes[]',
                                'del_name' => 'eliminar_infra_biblio[]',
                                'color' => 'warning',
                                'icon' => 'book'
                            ],
                            'aip' => [
                                'titulo' => 'Aula de Innovación (AIP)',
                                'db_field' => 'infra_aip_imagenes',
                                'file_name' => 'infra_aip_imagenes[]',
                                'del_name' => 'eliminar_infra_aip[]',
                                'color' => 'danger',
                                'icon' => 'laptop-code'
                            ]
                        ];
                    @endphp

                    @foreach($categorias as $catKey => $cat)
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 bg-light h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-{{ $cat['color'] }} mb-2">
                                        <i class="fas fa-{{ $cat['icon'] }} me-2"></i>{{ $cat['titulo'] }}
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Subir fotos (múltiple):</label>
                                        <input type="file" name="{{ $cat['file_name'] }}" class="form-control form-control-sm rounded-3" accept="image/*" multiple>
                                    </div>

                                    @php $imagenes = $info->{$cat['db_field']} ?? []; @endphp
                                    @if(count($imagenes) > 0)
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary">Fotos guardadas (marca para eliminar):</label>
                                            <div class="row g-2">
                                                @foreach($imagenes as $img)
                                                    <div class="col-4 text-center">
                                                        <div class="position-relative border rounded-3 p-1 bg-white h-100 d-flex flex-column justify-content-between">
                                                            <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail rounded-3 mb-1" style="height: 60px; width: 100%; object-fit: cover;">
                                                            <div class="form-check d-flex justify-content-center align-items-center mb-0">
                                                                <input class="form-check-input me-1" type="checkbox" name="{{ $cat['del_name'] }}" value="{{ $img }}" id="del_{{ md5($img) }}">
                                                                <label class="form-check-label small text-danger fw-semibold" for="del_{{ md5($img) }}" style="font-size: 0.7rem;">Eliminar</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted small fst-italic mb-0">Sin imágenes subidas en esta categoría.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
