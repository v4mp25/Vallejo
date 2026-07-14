@extends('layouts.profesor')

@section('title', $asignacion->curso->nombre . ' — ' . $asignacion->aula->grado . '° ' . $asignacion->aula->seccion)

@section('content')
    {{-- Breadcrumb --}}
    <a href="{{ route('profesor.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
        <i class="fas fa-arrow-left me-1"></i> Volver a mis cursos
    </a>

    {{-- Encabezado del curso --}}
    <div class="cv-card card-body p-4 mb-4">
        <span class="cv-badge-primary">Curso</span>
        <h2 class="cv-page-title mt-2 mb-1">{{ $asignacion->curso->nombre }}</h2>
        <p class="text-muted mb-0">
            <i class="fas fa-school me-1"></i>
            Salón {{ $asignacion->aula->grado }}° "{{ $asignacion->aula->seccion }}"
            @if ($asignacion->aula->turno)
                · {{ $asignacion->aula->turno }}
            @endif
            · <strong>{{ $alumnos->count() }}</strong> alumno{{ $alumnos->count() !== 1 ? 's' : '' }}
        </p>
    </div>

    {{-- ===================== SECCIÓN NOTAS ===================== --}}
    <div class="cv-card card-body p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="fas fa-edit me-2 cv-primary"></i> Registrar / Editar notas
                </h5>
                <p class="text-muted small mb-0">
                    Ingresa las notas directamente en la tabla o utiliza un archivo Excel para actualizar de forma masiva.
                </p>
            </div>
            @if (!$alumnos->isEmpty())
                <div class="d-flex gap-2">
                    <a href="{{ route('profesor.notas.exportar', $asignacion->id) }}" 
                       class="btn btn-outline-success rounded-pill btn-sm d-flex align-items-center px-3 hover-lift">
                        <i class="fas fa-file-excel me-2"></i> Descargar Plantilla
                    </a>
                    <button type="button" 
                            class="btn btn-success rounded-pill btn-sm d-flex align-items-center px-3 hover-lift" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalImportarNotas">
                        <i class="fas fa-file-upload me-2"></i> Subir Excel
                    </button>
                </div>
            @endif
        </div>

        @if (session('import_errors'))
            <div class="alert alert-warning border-warning border-opacity-25 rounded-3 mb-4 shadow-sm">
                <h6 class="fw-bold text-warning-emphasis mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i> Algunos registros no pudieron ser importados:
                </h6>
                <ul class="mb-0 small text-warning-emphasis ps-3" style="max-height: 200px; overflow-y: auto;">
                    @foreach (session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profesor.notas.guardar', $asignacion->id) }}" method="POST">
            @csrf

            @if ($alumnos->isEmpty())
                <div class="text-center py-4 text-muted">
                    <p class="mb-0">No hay alumnos matriculados.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table cv-table tabla-notas-editable align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th class="text-center">Bim. I</th>
                                <th class="text-center">Bim. II</th>
                                <th class="text-center">Bim. III</th>
                                <th class="text-center">Bim. IV</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alumnos as $i => $alumno)
                                @php
                                    $notasAlumno = $notas[$alumno->id] ?? collect();
                                    $b1 = $notasAlumno->firstWhere('periodo', 'B1')->calificacion ?? '';
                                    $b2 = $notasAlumno->firstWhere('periodo', 'B2')->calificacion ?? '';
                                    $b3 = $notasAlumno->firstWhere('periodo', 'B3')->calificacion ?? '';
                                    $b4 = $notasAlumno->firstWhere('periodo', 'B4')->calificacion ?? '';
                                @endphp
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <strong>{{ $alumno->apellidos }}</strong>,
                                        {{ $alumno->nombres }}
                                    </td>
                                    @foreach (['B1', 'B2', 'B3', 'B4'] as $periodo)
                                        <td class="text-center" style="width:12%">
                                            <input type="text"
                                                   class="form-control form-control-sm text-center nota-input"
                                                   name="notas[{{ $alumno->id }}][{{ $periodo }}][calificacion]"
                                                   value="{{ ${'b' . substr($periodo, 1)} }}"
                                                   maxlength="5"
                                                   placeholder="—"
                                                   style="max-width:70px; margin:0 auto;">
                                            <input type="hidden"
                                                   name="notas[{{ $alumno->id }}][{{ $periodo }}][periodo]"
                                                   value="{{ $periodo }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill mt-2">
                    <i class="fas fa-save me-1"></i> Guardar notas
                </button>
            @endif
        </form>
    </div>

    {{-- Modal para importar notas --}}
    @if (!$alumnos->isEmpty())
        <div class="modal fade" id="modalImportarNotas" tabindex="-1" aria-labelledby="modalImportarNotasLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modalImportarNotasLabel">
                            <i class="fas fa-file-excel text-success me-2"></i> Importar Notas desde Excel
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('profesor.notas.importar', $asignacion->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-4">
                            <div class="alert alert-info border-info border-opacity-25 rounded-3 mb-3 small">
                                <i class="fas fa-info-circle me-1"></i>
                                Para asegurar una correcta importación, descarga la plantilla y complétala sin alterar la estructura (las columnas `ID_Estudiante` y `DNI` se usan para identificar a los alumnos).
                            </div>
                            
                            <div class="mb-3">
                                <label for="archivo_excel" class="form-label fw-semibold small">Selecciona el archivo Excel (.xlsx, .xls)</label>
                                <input class="form-control rounded-3" type="file" id="archivo_excel" name="archivo_excel" accept=".xlsx, .xls" required>
                            </div>
                            
                            <div class="text-muted small">
                                <i class="fas fa-spell-check me-1"></i>
                                Las notas cualitativas en minúsculas (a, b, c, d, ad) se convertirán automáticamente a mayúsculas.
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                <i class="fas fa-upload me-1"></i> Cargar Notas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
    .nota-input {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        transition: border-color .2s;
    }
    .nota-input:focus {
        border-color: var(--cv-primary);
        box-shadow: 0 0 0 .15rem rgba(var(--cv-primary-rgb), .2);
    }
    .hover-lift {
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush