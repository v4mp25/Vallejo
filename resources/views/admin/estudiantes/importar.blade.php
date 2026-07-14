@extends('layouts.admin')

@section('title', 'Importar Alumnos')
@section('page_title', 'Importar Alumnos')
@section('page_subtitle', 'Carga masiva de alumnos y matrícula desde un archivo Excel')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Se encontraron {{ count(session('import_errors')) }} fila(s) con errores:
                </h6>
                <ul class="mb-0 small" style="max-height: 220px; overflow-y: auto;">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="cv-card card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-file-excel fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">1. Descarga la plantilla de ejemplo</h5>
                        <p class="text-muted mb-3">
                            Usa este archivo base para asegurarte de que las cabeceras y el formato sean exactamente los que el sistema espera.
                        </p>
                        <a href="{{ route('admin.estudiantes.importar.plantilla') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                            <i class="fas fa-download me-2"></i> Descargar Plantilla de Ejemplo
                        </a>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-upload fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1">2. Sube el archivo completo</h5>
                        <p class="text-muted mb-3">
                            El sistema creará (o reutilizará) el usuario de cada alumno, lo ubicará en su salón y generará su matrícula automáticamente.
                        </p>

                        <form action="{{ route('admin.estudiantes.importar.procesar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="cv-upload-zone mb-3 text-center">
                                <i class="fas fa-file-import d-block mb-2 cv-primary" style="font-size: 2.5rem;"></i>
                                <input type="file"
                                       class="form-control mx-auto @error('archivo_excel') is-invalid @enderror"
                                       name="archivo_excel"
                                       id="archivo_excel"
                                       accept=".xlsx,.xls"
                                       style="max-width: 400px;"
                                       required>
                                @error('archivo_excel')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <p class="text-muted small mt-2 mb-0">Formatos admitidos: .xlsx, .xls (máx. 10 MB)</p>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> Subir Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="cv-card card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle cv-primary me-2"></i>Instrucciones de llenado</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-3">
                        <thead class="table-light">
                            <tr>
                                <th>Columna</th>
                                <th>Descripción</th>
                                <th>Ejemplo</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr><td><code>nombres</code></td><td>Nombres del alumno</td><td>Juan Carlos</td></tr>
                            <tr><td><code>apellidos</code></td><td>Apellidos completos del alumno</td><td>Quispe Ramos</td></tr>
                            <tr><td><code>dni</code></td><td>DNI o código; será su usuario y contraseña inicial</td><td>75841203</td></tr>
                            <tr><td><code>grado</code></td><td>Grado tal como está registrado en el aula (ej. 1, 2, 3...)</td><td>3</td></tr>
                            <tr><td><code>seccion</code></td><td>Sección del aula</td><td>A</td></tr>
                            <tr><td><code>turno</code></td><td>Turno del aula (Mañana / Tarde)</td><td>Mañana</td></tr>
                            <tr><td><code>año_academico</code></td><td>Año académico de la matrícula</td><td>2026</td></tr>
                            <tr><td><code>celular_apoderado</code></td><td>Celular de contacto del apoderado (opcional)</td><td>987654321</td></tr>
                        </tbody>
                    </table>
                </div>
                <ul class="text-muted small mb-0 ps-3">
                    <li>El <strong>grado, sección y turno</strong> deben corresponder exactamente a un aula ya registrada en el sistema; si no existe, la fila se marcará con error y no se importará.</li>
                    <li>Si el alumno (DNI) ya existe, no se duplicará: solo se generará/actualizará su matrícula.</li>
                    <li>Las filas con errores no detienen la importación de las demás — al finalizar verás el detalle de cada una.</li>
                </ul>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Volver al dashboard
            </a>
        </div>
    </div>
</div>
@endsection
