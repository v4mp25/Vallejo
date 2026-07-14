@extends('layouts.admin')

@section('title', 'Cierre de Año Académico')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Tarjeta de Cabecera y Alerta --}}
            <div class="card border-0 shadow-sm mb-4 overflow-hidden rounded-3">
                <div class="card-header bg-danger text-white py-3 border-0">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Cierre y Apertura de Año Académico</h4>
                </div>
                <div class="card-body p-4 bg-white">
                    <div class="alert alert-warning border-0 d-flex gap-3 mb-4 rounded-3">
                        <div class="fs-1 text-warning">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Este proceso NO elimina información</h5>
                            <p class="mb-0 small text-dark opacity-90">
                                A diferencia de un "reseteo", este proceso <strong>conserva todo el historial</strong> de notas, asistencias y matrículas de años anteriores.
                                Prepara el sistema para el año <strong>{{ $anioSugerido }}</strong> promoviendo alumnos y liberando la carga docente para ser reasignada en marzo.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Año académico vigente</span>
                                <span class="fs-4 fw-bold text-dark">{{ $anioActual }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Matrículas activas a promover</span>
                                <span class="fs-4 fw-bold text-dark">{{ $totalMatriculasActivas }}</span>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-3">¿Qué ocurrirá al ejecutar el cierre de año?</h5>
                    <ul class="list-group list-group-flush mb-4 rounded-3 border">
                        <li class="list-group-item d-flex align-items-center justify-content-between p-3 bg-light">
                            <div>
                                <strong class="text-success">Notas, asistencias y matrículas anteriores</strong>
                                <br><small class="text-muted">Se conservan intactas como historial. Nunca se eliminan.</small>
                            </div>
                            <span class="badge bg-success rounded-pill px-3 py-2 text-uppercase font-monospace" style="font-size:0.75rem;">Conservar</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                            <div>
                                <strong class="text-dark">Alumnos de 1° a 4° de secundaria</strong>
                                <br><small class="text-muted">Se promueven automáticamente al siguiente grado, conservando su sección y turno.</small>
                            </div>
                            <span class="badge bg-primary rounded-pill px-3 py-2 text-uppercase font-monospace" style="font-size:0.75rem;">Promover</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                            <div>
                                <strong class="text-dark">Alumnos de 5° de secundaria</strong>
                                <br><small class="text-muted">Pasan a estado "egresado". No se les genera una matrícula nueva.</small>
                            </div>
                            <span class="badge bg-secondary rounded-pill px-3 py-2 text-uppercase font-monospace" style="font-size:0.75rem;">Egresar</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                            <div>
                                <strong class="text-dark">Tutorías de aula</strong>
                                <br><small class="text-muted">Se liberan todos los tutores asignados (quedan sin tutor) para reasignarse en marzo.</small>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 text-uppercase font-monospace" style="font-size:0.75rem;">Reiniciar</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                            <div>
                                <strong class="text-dark">Carga académica de docentes ({{ $anioActual }})</strong>
                                <br><small class="text-muted">Se archiva bajo el año {{ $anioActual }}. La carga del año {{ $anioSugerido }} queda en blanco hasta que se reasigne.</small>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 text-uppercase font-monospace" style="font-size:0.75rem;">Reiniciar</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                            <div>
                                <strong class="text-dark">Año académico global del sistema</strong>
                                <br><small class="text-muted">Pasa de {{ $anioActual }} a {{ $anioSugerido }}. Los dashboards empezarán a reflejar el año nuevo.</small>
                            </div>
                            <span class="badge bg-info text-white rounded-pill px-3 py-2 text-uppercase font-monospace" style="font-size:0.75rem;">Actualizar</span>
                        </li>
                    </ul>

                    <div class="alert alert-light border small mb-4">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        Si algún alumno de 1°-4° no tiene un aula de destino disponible (ej. no existe "3° B" turno Tarde), el proceso completo se cancelará automáticamente
                        <strong>sin aplicar ningún cambio</strong> y se te indicará exactamente qué aula falta crear antes de reintentar.
                    </div>

                    {{-- Formulario de Doble Verificación --}}
                    <form action="{{ route('admin.cierre-ano.procesar') }}" method="POST" id="form-cierre-anio"
                          onsubmit="return confirm('¿Estás COMPLETAMENTE seguro? Se promoverá a todos los alumnos activos y se reiniciará la carga docente para el nuevo año.');">
                        @csrf

                        <div class="mb-3">
                            <label for="nuevo_anio" class="form-label fw-bold text-dark">Nuevo año académico</label>
                            <input
                                type="number"
                                id="nuevo_anio"
                                name="nuevo_anio"
                                class="form-control @error('nuevo_anio') is-invalid @enderror"
                                value="{{ old('nuevo_anio', $anioSugerido) }}"
                                min="{{ (int) $anioActual + 1 }}"
                                max="{{ (int) $anioActual + 5 }}"
                                required
                            >
                            @error('nuevo_anio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 bg-light p-3 rounded-3 border">
                            <label for="verification_input" class="form-label fw-bold text-dark mb-2">
                                Para confirmar, escribe exactamente la frase de seguridad en mayúsculas:
                            </label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white text-danger border-end-0"><i class="fas fa-lock"></i></span>
                                <input
                                    type="text"
                                    id="verification_input"
                                    name="confirmacion"
                                    class="form-control border-start-0 font-monospace text-center fw-bold"
                                    placeholder="{{ $fraseConfirmacion }}"
                                    autocomplete="off"
                                    required
                                >
                            </div>
                            <small class="text-muted d-block">
                                El botón se habilitará únicamente cuando el texto coincida exactamente con
                                <code class="fw-bold text-danger">{{ $fraseConfirmacion }}</code>.
                            </small>
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="fas fa-times me-1"></i> Cancelar y Salir
                            </a>
                            <button
                                type="submit"
                                id="reset_btn"
                                class="btn btn-danger rounded-pill px-4 py-2 fw-bold"
                                disabled
                            >
                                <i class="fas fa-forward me-1"></i> Ejecutar Cierre de Año
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('verification_input');
        var btn = document.getElementById('reset_btn');
        var phrase = @json($fraseConfirmacion);

        input.addEventListener('input', function () {
            if (input.value === phrase) {
                btn.removeAttribute('disabled');
            } else {
                btn.setAttribute('disabled', 'true');
            }
        });
    });
</script>
@endpush
