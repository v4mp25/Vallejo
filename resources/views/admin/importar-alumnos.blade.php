@extends('layouts.admin')

@section('title', 'Importar Alumnos')

@section('page_title', 'Importar Alumnos')
@section('page_subtitle', 'Carga masiva desde archivo CSV (Nómina Total)')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="cv-card card">
                <div class="card-body p-4 p-md-5">
                    
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('importar-alumnos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="cv-upload-zone mb-4 text-center">
                            <i class="fas fa-users d-block mb-2 cv-primary" style="font-size: 3rem;"></i>
                            <h5 class="fw-bold mb-2">Sube tu archivo CSV de Alumnos</h5>
                            <p class="text-muted small mb-3">
                                Formato admitido: .csv (Nómina Total limpia)
                            </p>
                            <input type="file"
                                   class="form-control mx-auto @error('archivo') is-invalid @enderror"
                                   name="archivo"
                                   id="archivo"
                                   accept=".csv,.xlsx"
                                   style="max-width: 400px;"
                                   required>
                            @error('archivo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-light border small mb-4">
                            <i class="fas fa-info-circle cv-primary me-1"></i>
                            El archivo debe contener las columnas: <strong>nombres, apellido_paterno, apellido_materno, dni, fecha_nacimiento, grado, seccion</strong>. Las filas sin DNI serán ignoradas por el sistema.
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-center">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-upload me-2"></i> Iniciar Matrícula
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