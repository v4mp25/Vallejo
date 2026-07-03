@extends('layouts.admin') {{-- O el layout maestro de admin que uses --}}

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 max-w-2xl mx-auto">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-cogs me-2"></i> Ajustes Generales del Portal Web</h5>
        </div>
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.configuracion.guardar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Frase Institucional del Topbar</label>
                    <input type="text" name="frase_topbar" class="form-control" value="{{ old('frase_topbar', $config->frase_topbar) }}" required>
                    <small class="text-muted">Esta frase se mostrará en la esquina superior derecha de la pantalla de inicio pública.</small>
                </div>
                <hr class="my-4">
<h5 class="mb-3 text-primary fw-bold"><i class="fas fa-share-alt me-2"></i> Redes Sociales y Ubicación</h5>
                <div class="mb-4">
    <label class="form-label fw-bold text-dark">Enlace de la página de Facebook</label>
    <input type="url" name="link_facebook" class="form-control" value="{{ old('link_facebook', $config->link_facebook) }}" placeholder="https://www.facebook.com/tu-colegio">
    <small class="text-muted">Pega la URL completa del perfil o página oficial de Facebook de la institución.</small>
</div>

<div class="mb-4">
    <label class="form-label fw-bold text-dark">Enlace de la página de YouTube</label>
    <input type="url" name="link_youtube" class="form-control" value="{{ old('link_youtube', $config->link_youtube) }}" placeholder="https://www.youtube.com/c/tu-colegio">
    <small class="text-muted">Pega la URL completa del canal oficial de YouTube de la institución.</small>
</div>

<div class="mb-4">
    <label class="form-label fw-bold text-dark">Enlace de la página de Instagram</label>
    <input type="url" name="link_instagram" class="form-control" value="{{ old('link_instagram', $config->link_instagram) }}" placeholder="https://www.instagram.com/tu-colegio">
    <small class="text-muted">Pega la URL completa del perfil oficial de Instagram de la institución.</small>
</div>

<div class="mb-4">
    <label class="form-label fw-bold text-dark">Texto de la Dirección</label>
    <input type="text" name="direccion_texto" class="form-control" value="{{ old('direccion_texto', $config->direccion_texto) }}" placeholder="Ej: Av. Universitaria s/n, Huánuco">
    <small class="text-muted">La dirección física corta que se imprimirá en el pie de página.</small>
</div>

<div class="mb-4">
    <label class="form-label fw-bold text-dark">Enlace de Google Maps (URL de redirección)</label>
    <textarea name="link_maps" class="form-control" rows="2" placeholder="Pega el enlace compartido desde Google Maps aquí...">{{ old('link_maps', $config->link_maps) }}</textarea>
    <small class="text-muted">Entra a Google Maps, busca el colegio, dale a "Compartir", copia el enlace y pégalo aquí.</small>
</div>
                <div class="mb-4">
    <label class="form-label fw-bold text-dark">Título de Bienvenida (Hero Banner)</label>
    <input type="text" name="hero_titulo" class="form-control" value="{{ old('hero_titulo', $config->hero_titulo) }}" placeholder="Ej: Formando el futuro con excelencia">
    <small class="text-muted">Este título aparecerá en letras grandes y blancas en la sección de presentación principal.</small>
</div>

<div class="mb-4">
    <label class="form-label fw-bold text-dark">Descripción o Reseña Corta del Hero</label>
    <textarea name="hero_subtitulo" class="form-control" rows="3" placeholder="Ingresa la descripción institucional completa aquí...">{{ old('hero_subtitulo', $config->hero_subtitulo) }}</textarea>
    <small class="text-muted">Párrafo descriptivo que se ubica debajo del título principal.</small>
</div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Foto Principal de Fondo (Hero Banner)</label>
                    <input type="file" name="banner_inicial" class="form-control" accept="image/*">
                    <small class="text-muted">Sube la fachada del colegio o una foto representativa. Tamaño recomendado: 1920x1080px.</small>
                    
                    @if($config->banner_inicial_url)
                        <div class="mt-3">
                            <span class="d-block small text-muted mb-1">Imagen actual:</span>
                            <img src="{{ asset('storage/' . $config->banner_inicial_url) }}" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                </div>
                <div class="mb-4">
    <label class="form-label fw-bold text-dark">Logo Oficial de la Institución</label>
    <input type="file" name="logo" class="form-control" accept="image/*">
    <small class="text-muted">Se mostrará en la barra de navegación principal. Formato recomendado: PNG transparente.</small>
    
    @if(isset($config) && $config->logo_url)
        <div class="mt-3">
            <span class="d-block small text-muted mb-1">Logo actual:</span>
            <img src="{{ asset('storage/' . $config->logo_url) }}" class="img-thumbnail" style="max-height: 80px; background-color: #f0f0f0;">
        </div>
    @endif
</div>

                <hr class="my-4">

                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow">
                    <i class="fas fa-save me-2"></i> Guardar Cambios
                </button>
            </form>

        </div>
    </div>
</div>
@endsection