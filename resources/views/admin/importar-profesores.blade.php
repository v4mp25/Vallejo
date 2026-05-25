@extends('layouts.admin')

@section('title', 'Importar Profesores')

@section('page_title', 'Importar Profesores')
@section('page_subtitle', 'Carga masiva desde archivo Excel')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="cv-card card">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.importar-profesores.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="cv-upload-zone mb-4">
                            <i class="fas fa-file-excel d-block"></i>
                            <h5 class="fw-bold mb-2">Sube tu archivo Excel</h5>
                            <p class="text-muted small mb-3">
                                Formatos admitidos: .xlsx, .xls, .csv (máx. 10 MB)
                            </p>
                            <input type="file"
                                   class="form-control @error('archivo') is-invalid @enderror"
                                   name="archivo"
                                   id="archivo"
                                   accept=".xlsx,.xls,.csv"
                                   required>
                            @error('archivo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-light border small mb-4">
                            <i class="fas fa-info-circle cv-primary me-1"></i>
                            El archivo debe incluir columnas acordadas con tu backend (DNI, nombres, correo, etc.).
                            Las aulas se crearán automáticamente según tu lógica de importación.
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-upload me-2"></i> Cargar archivo
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Volver al dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
