@extends('layouts.admin')

@section('title', 'Gestión de Docentes')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="fas fa-id-card me-2 text-primary"></i> Gestión de Personal</h2>
            <p class="text-muted mb-0">Organiza y administra a los docentes, psicólogos, auxiliares y administrativos de la institución.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createDocenteModal">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Personal
            </button>
            <button type="button" class="btn btn-outline-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGestionCursos">
                <i class="fas fa-book me-2"></i> Gestionar Cursos
            </button>
        </div>
    </div>

    <!-- Mensajes de sesión de éxito o error -->
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Mostrar errores de validación si existen -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Por favor corrige los siguientes errores:</h6>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de resumen -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold mb-1 small" style="letter-spacing: 1px;">Total Personal</h6>
                        <h3 class="mb-0 fw-bold">{{ $todosDocentes->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 me-3">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold mb-1 small" style="letter-spacing: 1px;">Activos</h6>
                        <h3 class="mb-0 fw-bold">{{ $todosDocentes->where('estado', 1)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-4 p-3 me-3">
                        <i class="fas fa-user-slash fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold mb-1 small" style="letter-spacing: 1px;">Inactivos</h6>
                        <h3 class="mb-0 fw-bold">{{ $todosDocentes->where('estado', 0)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buscador global -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.docentes.index') }}" method="GET" class="row g-2 justify-content-between align-items-center">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group rounded-pill overflow-hidden border">
                        <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-0 ps-2" placeholder="Buscar por nombre o DNI..." value="{{ $search }}">
                        @if($search)
                            <a href="{{ route('admin.docentes.index') }}" class="btn btn-light border-0 d-flex align-items-center justify-content-center px-3"><i class="fas fa-times text-muted"></i></a>
                        @endif
                        <button type="submit" class="btn btn-primary px-4 border-0">Buscar</button>
                    </div>
                </div>
                <div class="col-md-auto text-end">
                    <span class="text-muted small">Resultados de búsqueda: {{ $todosDocentes->count() }} miembros de personal</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Pestañas Principales (Nav-Pills) -->
    <ul class="nav nav-pills flex-wrap gap-2 p-2 bg-light rounded-4 mb-4 border" id="docentesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-semibold px-3 py-2" id="manana-tab" data-bs-toggle="tab" data-bs-target="#manana-content" type="button" role="tab">
                <i class="fas fa-sun me-2 text-warning"></i> Turno Mañana
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold px-3 py-2" id="tarde-tab" data-bs-toggle="tab" data-bs-target="#tarde-content" type="button" role="tab">
                <i class="fas fa-cloud-sun me-2 text-info"></i> Turno Tarde
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold px-3 py-2" id="directorio-tab" data-bs-toggle="tab" data-bs-target="#directorio-content" type="button" role="tab">
                <i class="fas fa-address-book me-2"></i> Directorio General
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold px-3 py-2" id="psicologos-tab" data-bs-toggle="tab" data-bs-target="#psicologos-content" type="button" role="tab">
                <i class="fas fa-brain me-2 text-purple" style="color:#7c3aed;"></i> Psicólogos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold px-3 py-2" id="auxiliares-tab" data-bs-toggle="tab" data-bs-target="#auxiliares-content" type="button" role="tab">
                <i class="fas fa-user-shield me-2 text-success"></i> Auxiliares
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold px-3 py-2" id="administrativos-tab" data-bs-toggle="tab" data-bs-target="#administrativos-content" type="button" role="tab">
                <i class="fas fa-briefcase me-2 text-secondary"></i> Administrativos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold px-3 py-2" id="directivos-tab" data-bs-toggle="tab" data-bs-target="#directivos-content" type="button" role="tab">
                <i class="fas fa-star me-2 text-warning"></i> Directivos
            </button>
        </li>
    </ul>

    <!-- Contenido de las Pestañas -->
    <div class="tab-content" id="docentesTabsContent">
        
        <!-- PESTAÑA: TURNO MAÑANA -->
        <div class="tab-pane fade show active" id="manana-content" role="tabpanel" aria-labelledby="manana-tab">
            @php
                $cursosManana = $grouped['Mañana'] ?? [];
            @endphp
            @if(count($cursosManana) > 0)
                <div class="accordion border-0" id="accordionManana">
                    @foreach($cursosManana as $cursoNombre => $profesoresAsig)
                        @php
                            $loopId = Str::slug($cursoNombre) . '-manana';
                        @endphp
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="heading-{{ $loopId }}">
                                <button class="accordion-button collapsed fw-bold text-dark bg-light py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loopId }}" aria-expanded="false" aria-controls="collapse-{{ $loopId }}">
                                    <div class="d-flex align-items-center justify-content-between w-100 pe-3">
                                        <div>
                                            <i class="fas fa-book-open me-2 text-primary"></i> 
                                            <span>{{ $cursoNombre }}</span>
                                        </div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1.5 small">
                                            {{ count($profesoresAsig) }} Docente(s)
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse-{{ $loopId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $loopId }}" data-bs-parent="#accordionManana">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4 py-3 border-0">Docente</th>
                                                    <th class="py-3 border-0">DNI / Código (Usuario)</th>
                                                    <th class="py-3 border-0">Aulas (Grado / Sección)</th>
                                                    <th class="pe-4 py-3 border-0 text-center" style="width: 150px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($profesoresAsig as $profId => $info)
                                                    @php
                                                        $docente = $info['profesor'];
                                                        $aulas = $info['aulas'];
                                                    @endphp
                                                    <tr class="border-bottom">
                                                        <td class="ps-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 38px; height: 38px;">
                                                                    <i class="fas fa-user-tie"></i>
                                                                </div>
                                                                <div>
                                                                    <span class="fw-bold text-dark d-block">{{ $docente->nombres }} {{ $docente->apellidos }}</span>
                                                                    <span class="text-muted small">{{ $docente->celular ?? 'Sin celular' }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">{{ $docente->codigo_usuario }}</span>
                                                        </td>
                                                        <td>
                                                            @foreach($aulas as $aula)
                                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1.5 mb-1 d-inline-block fw-semibold">
                                                                    {{ $aula->nombre }} <span class="text-muted text-capitalize font-normal" style="font-weight: normal; font-size: 0.75rem;">({{ $aula->nivel }})</span>
                                                                </span>
                                                            @endforeach
                                                        </td>
                                                        <td class="pe-4 py-3 text-center">
                                                            <div class="d-flex gap-2 justify-content-center">
                                                                <button type="button" 
                                                                        class="btn btn-outline-primary btn-sm rounded-3 px-3" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#editDocenteModal"
                                                                        data-id="{{ $docente->id }}"
                                                                        data-nombres="{{ $docente->nombres }}"
                                                                        data-apellidos="{{ $docente->apellidos }}"
                                                                        data-codigo="{{ $docente->codigo_usuario }}"
                                                                        data-celular="{{ $docente->celular }}"
                                                                        data-nacimiento="{{ $docente->fecha_nacimiento }}"
                                                                        data-carga="{{ json_encode($docente->asignaciones->groupBy('curso_id')->map(fn($asigs) => $asigs->pluck('aula_id'))->toArray()) }}"
                                                                        data-tutorias="{{ json_encode($docente->aulasTutoria->pluck('id')->toArray()) }}">
                                                                    <i class="fas fa-edit me-1"></i> Editar
                                                                </button>
                                                                <form action="{{ route('admin.docentes.eliminar', $docente->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este docente? Esta acción también borrará toda su carga académica asignada y lo removerá de sus tutorías.')" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3" title="Eliminar docente">
                                                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-5 bg-white rounded-4 shadow-sm border border-light">
                    <i class="fas fa-sun fa-3x mb-3 text-warning opacity-55"></i>
                    <h5 class="fw-semibold text-dark">No hay registros</h5>
                    <p class="mb-0">No se encontraron docentes con cursos asignados para el turno de la mañana.</p>
                </div>
            @endif
        </div>

        <!-- PESTAÑA: TURNO TARDE -->
        <div class="tab-pane fade" id="tarde-content" role="tabpanel" aria-labelledby="tarde-tab">
            @php
                $cursosTarde = $grouped['Tarde'] ?? [];
            @endphp
            @if(count($cursosTarde) > 0)
                <div class="accordion border-0" id="accordionTarde">
                    @foreach($cursosTarde as $cursoNombre => $profesoresAsig)
                        @php
                            $loopId = Str::slug($cursoNombre) . '-tarde';
                        @endphp
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="heading-{{ $loopId }}">
                                <button class="accordion-button collapsed fw-bold text-dark bg-light py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loopId }}" aria-expanded="false" aria-controls="collapse-{{ $loopId }}">
                                    <div class="d-flex align-items-center justify-content-between w-100 pe-3">
                                        <div>
                                            <i class="fas fa-book-open me-2 text-info"></i> 
                                            <span>{{ $cursoNombre }}</span>
                                        </div>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-1.5 small">
                                            {{ count($profesoresAsig) }} Docente(s)
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse-{{ $loopId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $loopId }}" data-bs-parent="#accordionTarde">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4 py-3 border-0">Docente</th>
                                                    <th class="py-3 border-0">DNI / Código (Usuario)</th>
                                                    <th class="py-3 border-0">Aulas (Grado / Sección)</th>
                                                    <th class="pe-4 py-3 border-0 text-center" style="width: 150px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($profesoresAsig as $profId => $info)
                                                    @php
                                                        $docente = $info['profesor'];
                                                        $aulas = $info['aulas'];
                                                    @endphp
                                                    <tr class="border-bottom">
                                                        <td class="ps-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="bg-info bg-opacity-10 text-info rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 38px; height: 38px;">
                                                                    <i class="fas fa-user-tie"></i>
                                                                </div>
                                                                <div>
                                                                    <span class="fw-bold text-dark d-block">{{ $docente->nombres }} {{ $docente->apellidos }}</span>
                                                                    <span class="text-muted small">{{ $docente->celular ?? 'Sin celular' }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">{{ $docente->codigo_usuario }}</span>
                                                        </td>
                                                        <td>
                                                            @foreach($aulas as $aula)
                                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-1.5 mb-1 d-inline-block fw-semibold">
                                                                    {{ $aula->nombre }} <span class="text-muted text-capitalize font-normal" style="font-weight: normal; font-size: 0.75rem;">({{ $aula->nivel }})</span>
                                                                </span>
                                                            @endforeach
                                                        </td>
                                                        <td class="pe-4 py-3 text-center">
                                                            <div class="d-flex gap-2 justify-content-center">
                                                                <button type="button" 
                                                                        class="btn btn-outline-primary btn-sm rounded-3 px-3" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#editDocenteModal"
                                                                        data-id="{{ $docente->id }}"
                                                                        data-nombres="{{ $docente->nombres }}"
                                                                        data-apellidos="{{ $docente->apellidos }}"
                                                                        data-codigo="{{ $docente->codigo_usuario }}"
                                                                        data-celular="{{ $docente->celular }}"
                                                                        data-nacimiento="{{ $docente->fecha_nacimiento }}"
                                                                        data-carga="{{ json_encode($docente->asignaciones->groupBy('curso_id')->map(fn($asigs) => $asigs->pluck('aula_id'))->toArray()) }}"
                                                                        data-tutorias="{{ json_encode($docente->aulasTutoria->pluck('id')->toArray()) }}">
                                                                    <i class="fas fa-edit me-1"></i> Editar
                                                                </button>
                                                                <form action="{{ route('admin.docentes.eliminar', $docente->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este docente? Esta acción también borrará toda su carga académica asignada y lo removerá de sus tutorías.')" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3" title="Eliminar docente">
                                                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-5 bg-white rounded-4 shadow-sm border border-light">
                    <i class="fas fa-cloud-sun fa-3x mb-3 text-info opacity-55"></i>
                    <h5 class="fw-semibold text-dark">No hay registros</h5>
                    <p class="mb-0">No se encontraron docentes con cursos asignados para el turno de la tarde.</p>
                </div>
            @endif
        </div>

        <!-- PESTAÑA: DIRECTORIO GENERAL (CRUD COMPLETO) -->
        <div class="tab-pane fade" id="directorio-content" role="tabpanel" aria-labelledby="directorio-tab">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light border-0">
                                <tr>
                                    <th class="ps-4 py-3 border-0 rounded-start-4">Miembro del Personal</th>
                                    <th class="py-3 border-0">Código / DNI (Usuario)</th>
                                    <th class="py-3 border-0">Celular</th>
                                    <th class="py-3 border-0">Fecha de Nacimiento</th>
                                    <th class="py-3 border-0">Estado</th>
                                    <th class="pe-4 py-3 border-0 rounded-end-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todosDocentes as $docente)
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3 fw-medium text-dark">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $docente->nombres }} {{ $docente->apellidos }}</div>
                                                <span class="text-muted small">
                                                    @if($docente->rol === 'profesor')
                                                        Rol: Docente
                                                    @elseif($docente->rol === 'psicologo')
                                                        Rol: Psicólogo
                                                    @elseif($docente->rol === 'auxiliar')
                                                        Rol: Auxiliar
                                                    @elseif($docente->rol === 'secretaria')
                                                        Rol: Secretaria
                                                    @elseif($docente->rol === 'directivo')
                                                        Rol: Directivo
                                                    @elseif($docente->rol === 'director')
                                                        Rol: Director
                                                    @elseif($docente->rol === 'administrativo')
                                                        Rol: Administrativo
                                                    @else
                                                        Rol: {{ ucfirst($docente->rol) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">{{ $docente->codigo_usuario }}</span>
                                    </td>
                                    <td class="py-3 text-muted">{{ $docente->celular ?? 'No registrado' }}</td>
                                    <td class="py-3 text-muted">
                                        {{ $docente->fecha_nacimiento ? \Carbon\Carbon::parse($docente->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}
                                    </td>
                                    <td class="py-3">
                                        <form action="{{ route('admin.docentes.toggle-status', $docente->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm border-0 p-0" title="Haga clic para alternar estado">
                                                @if($docente->estado)
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold">
                                                        <i class="fas fa-check-circle me-1"></i> Activo
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-semibold">
                                                        <i class="fas fa-times-circle me-1"></i> Inactivo
                                                    </span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="pe-4 py-3 text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <!-- Botón Editar -->
                                            <button type="button" 
                                                    class="btn btn-outline-primary btn-sm rounded-3 px-2.5 py-1.5" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editDocenteModal"
                                                    data-id="{{ $docente->id }}"
                                                    data-nombres="{{ $docente->nombres }}"
                                                    data-apellidos="{{ $docente->apellidos }}"
                                                    data-codigo="{{ $docente->codigo_usuario }}"
                                                    data-celular="{{ $docente->celular }}"
                                                    data-nacimiento="{{ $docente->fecha_nacimiento }}"
                                                    data-rol="{{ $docente->rol }}"
                                                    data-carga="{{ json_encode($docente->asignaciones->groupBy('curso_id')->map(fn($asigs) => $asigs->pluck('aula_id'))->toArray()) }}"
                                                    data-tutorias="{{ json_encode($docente->aulasTutoria->pluck('id')->toArray()) }}"
                                                    title="Editar información">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Botón Eliminar -->
                                            <form action="{{ route('admin.docentes.eliminar', $docente->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a esta persona? Esta acción también borrará toda su carga académica (si es docente) y sus relaciones en el sistema.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-2.5 py-1.5" title="Eliminar miembro del personal">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                        <p class="mb-0">No se encontraron miembros de personal registrados en el sistema.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- PESTAÑA: PSICÓLOGOS --}}
        <div class="tab-pane fade" id="psicologos-content" role="tabpanel" aria-labelledby="psicologos-tab">
            @include('admin.docentes._partials.tab_rol', [
                'personas'   => $psicologos,
                'rolNombre'  => 'Psicólogo',
                'iconColor'  => '#7c3aed',
                'iconClass'  => 'fa-brain',
                'emptyMsg'   => 'No hay psicólogos registrados en el sistema.',
            ])
        </div>

        {{-- PESTAÑA: AUXILIARES --}}
        <div class="tab-pane fade" id="auxiliares-content" role="tabpanel" aria-labelledby="auxiliares-tab">
            @include('admin.docentes._partials.tab_rol', [
                'personas'   => $auxiliares,
                'rolNombre'  => 'Auxiliar',
                'iconColor'  => '#16a34a',
                'iconClass'  => 'fa-user-shield',
                'emptyMsg'   => 'No hay auxiliares registrados en el sistema.',
            ])
        </div>

        {{-- PESTAÑA: ADMINISTRATIVOS --}}
        <div class="tab-pane fade" id="administrativos-content" role="tabpanel" aria-labelledby="administrativos-tab">
            @include('admin.docentes._partials.tab_rol', [
                'personas'   => $administrativos,
                'rolNombre'  => 'Administrativo',
                'iconColor'  => '#6b7280',
                'iconClass'  => 'fa-briefcase',
                'emptyMsg'   => 'No hay personal administrativo registrado en el sistema.',
            ])
        </div>

        {{-- PESTAÑA: DIRECTIVOS --}}
        <div class="tab-pane fade" id="directivos-content" role="tabpanel" aria-labelledby="directivos-tab">
            @include('admin.docentes._partials.tab_rol', [
                'personas'   => $directivos,
                'rolNombre'  => 'Directivo',
                'iconColor'  => '#d97706',
                'iconClass'  => 'fa-star',
                'emptyMsg'   => 'No hay directivos registrados en el sistema.',
            ])
        </div>

    </div>
</div>

<!-- Modal: Crear Docente -->
<div class="modal fade" id="createDocenteModal" tabindex="-1" aria-labelledby="createDocenteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 bg-light rounded-top-4 p-4 pb-3">
                <h5 class="modal-title fw-bold text-dark" id="createDocenteModalLabel">
                    <i class="fas fa-plus-circle text-primary me-2"></i> Registrar Nuevo Miembro del Personal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.docentes.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-3" style="max-height: 70vh; overflow-y: auto;">
                    
                    <!-- SECCIÓN A: DATOS PERSONALES -->
                    <div class="border-bottom pb-4 mb-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user-edit me-2"></i> Sección A - Datos Personales</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label small fw-semibold text-secondary">Nombres</label>
                                <input type="text" name="nombres" id="nombres" class="form-control rounded-3" placeholder="Ej: Juan Carlos" required value="{{ old('nombres') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label small fw-semibold text-secondary">Apellidos</label>
                                <input type="text" name="apellidos" id="apellidos" class="form-control rounded-3" placeholder="Ej: Quispe Ramos" required value="{{ old('apellidos') }}">
                            </div>
                            <div class="col-12">
                                <label for="codigo_usuario" class="form-label small fw-semibold text-secondary">DNI o Código de Usuario (Login)</label>
                                <input type="text" name="codigo_usuario" id="codigo_usuario" class="form-control rounded-3" placeholder="Ej: 45781296" required value="{{ old('codigo_usuario') }}">
                                <div class="form-text small text-muted">Este DNI servirá como su nombre de usuario para iniciar sesión.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="celular" class="form-label small fw-semibold text-secondary">Celular (Opcional)</label>
                                <input type="text" name="celular" id="celular" class="form-control rounded-3" placeholder="Ej: 987654321" value="{{ old('celular') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label small fw-semibold text-secondary">Fecha de Nacimiento (Opcional)</label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control rounded-3" value="{{ old('fecha_nacimiento') }}">
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label small fw-semibold text-secondary">Contraseña Temporal</label>
                                <input type="password" name="password" id="password" class="form-control rounded-3" placeholder="Mínimo 6 caracteres" required>
                            </div>
                            <div class="col-12">
                                <label for="create_rol" class="form-label small fw-bold text-secondary">Rol / Categoría de Cuenta</label>
                                <select name="rol" id="create_rol" class="form-select rounded-3 select-rol-type" required>
                                    <option value="profesor" selected>Docente</option>
                                    <option value="psicologo">Psicólogo</option>
                                    <option value="auxiliar">Auxiliar</option>
                                    <option value="secretaria">Secretaria</option>
                                    <option value="directivo">Directivo</option>
                                    <option value="director">Director</option>
                                    <option value="administrativo">Administrativo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN B Y C: CARGA ACADÉMICA Y TUTORÍA (Solo para Docentes) -->
                    <div class="cargo-profesor-only" style="display: block;">
                        <!-- SECCIÓN B: CARGA ACADÉMICA -->
                        <div class="border-bottom pb-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-book me-2"></i> Sección B - Carga Académica (Opcional)</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-add-carga">
                                    <i class="fas fa-plus me-1"></i> Asignar Curso
                                </button>
                            </div>
                            
                            <div id="carga-academica-container">
                                <!-- Aquí se insertan las filas dinámicas de carga -->
                            </div>
                        </div>

                        <!-- SECCIÓN C: TUTORÍA -->
                        <div class="mb-2">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-users-cog me-2"></i> Sección C - Tutoría (Opcional)</h6>
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-secondary mb-2">¿Será tutor de algún salón? (Marque todos los que apliquen)</label>
                                    <div class="p-3 border rounded bg-white">
                                        @php
                                            $aulasPorTurnoT = $allAulas->groupBy('turno');
                                        @endphp
                                        @foreach($aulasPorTurnoT as $turno => $aulasGrupo)
                                            <div class="mb-3">
                                                <span class="text-secondary small fw-bold d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                                    @if(strcasecmp($turno, 'Mañana') === 0 || strcasecmp($turno, 'manana') === 0)
                                                        ☀️ Turno Mañana
                                                    @else
                                                        🌙 Turno Tarde
                                                    @endif
                                                </span>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($aulasGrupo as $aula)
                                                        <input type="checkbox" class="btn-check" name="tutor_aulas[]" value="{{ $aula->id }}" id="tutor_aula_{{ $aula->id }}">
                                                        <label class="btn btn-outline-primary btn-sm rounded-pill px-3 transition-all" for="tutor_aula_{{ $aula->id }}">
                                                            {{ $aula->grado }}° "{{ $aula->seccion }}"
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Guardar Personal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template para carga académica dinámica -->
<template id="carga-row-template">
    <div class="carga-row border border-light-subtle rounded-3 p-3 mb-3 bg-light position-relative shadow-sm hover-lift" style="transition: transform .2s ease, box-shadow .2s ease;">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-carga-btn" style="font-size: 0.75rem;" title="Eliminar asignación"></button>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label small fw-bold text-secondary mb-1">Curso</label>
                <select name="cursos[__INDEX__]" class="form-select rounded-3" required>
                    <option value="" disabled selected>Seleccione un curso</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold text-secondary mb-2">Aulas donde dictará este curso (Marque todas las que apliquen)</label>
                <div class="p-3 border rounded bg-white">
                    @php
                        $aulasPorTurno = $allAulas->groupBy('turno');
                    @endphp
                    @foreach($aulasPorTurno as $turno => $aulasGrupo)
                        <div class="mb-3">
                            <span class="text-secondary small fw-bold d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                @if(strcasecmp($turno, 'Mañana') === 0 || strcasecmp($turno, 'manana') === 0)
                                    ☀️ Turno Mañana
                                @else
                                    🌙 Turno Tarde
                                @endif
                            </span>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($aulasGrupo as $aula)
                                    <input type="checkbox" class="btn-check" name="aulas[__INDEX__][]" value="{{ $aula->id }}" id="aula_{{ $aula->id }}___INDEX__">
                                    <label class="btn btn-outline-primary btn-sm rounded-pill px-3 transition-all" for="aula_{{ $aula->id }}___INDEX__">
                                        {{ $aula->grado }}° "{{ $aula->seccion }}"
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Modal: Editar Docente -->
<div class="modal fade" id="editDocenteModal" tabindex="-1" aria-labelledby="editDocenteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 bg-light rounded-top-4 p-4 pb-3">
                <h5 class="modal-title fw-bold text-dark" id="editDocenteModalLabel">
                    <i class="fas fa-edit text-primary me-2"></i> Editar Información del Miembro de Personal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.docentes.update', 'replace_id') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 pt-3" style="max-height: 70vh; overflow-y: auto;">
                    
                    <!-- SECCIÓN A: DATOS PERSONALES -->
                    <div class="border-bottom pb-4 mb-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user-edit me-2"></i> Sección A - Datos Personales</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_nombres" class="form-label small fw-semibold text-secondary">Nombres</label>
                                <input type="text" name="nombres" id="edit_nombres" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_apellidos" class="form-label small fw-semibold text-secondary">Apellidos</label>
                                <input type="text" name="apellidos" id="edit_apellidos" class="form-control rounded-3" required>
                            </div>
                            <div class="col-12">
                                <label for="edit_codigo_usuario" class="form-label small fw-semibold text-secondary">DNI o Código de Usuario</label>
                                <input type="text" name="codigo_usuario" id="edit_codigo_usuario" class="form-control rounded-3" required>
                                <div class="form-text small text-muted">Este DNI servirá como su nombre de usuario para iniciar sesión.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_celular" class="form-label small fw-semibold text-secondary">Celular (Opcional)</label>
                                <input type="text" name="edit_celular" id="edit_celular" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_fecha_nacimiento" class="form-label small fw-semibold text-secondary">Fecha de Nacimiento (Opcional)</label>
                                <input type="date" name="fecha_nacimiento" id="edit_fecha_nacimiento" class="form-control rounded-3">
                            </div>
                            <div class="col-12">
                                <label for="edit_password" class="form-label small fw-semibold text-secondary">Nueva Contraseña (Opcional)</label>
                                <input type="password" name="password" id="edit_password" class="form-control rounded-3" placeholder="Dejar en blanco para mantener la actual">
                                <div class="form-text small text-muted">Ingresa una nueva clave solo si deseas cambiarla. Mínimo 6 caracteres.</div>
                            </div>
                            <div class="col-12">
                                <label for="edit_rol" class="form-label small fw-bold text-secondary">Rol / Categoría de Cuenta</label>
                                <select name="rol" id="edit_rol" class="form-select rounded-3 select-rol-type" required>
                                    <option value="profesor">Docente</option>
                                    <option value="psicologo">Psicólogo</option>
                                    <option value="auxiliar">Auxiliar</option>
                                    <option value="secretaria">Secretaria</option>
                                    <option value="directivo">Directivo</option>
                                    <option value="director">Director</option>
                                    <option value="administrativo">Administrativo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN B Y C: CARGA ACADÉMICA Y TUTORÍA (Solo para Docentes) -->
                    <div class="cargo-profesor-only" id="edit_profesor_fields" style="display: block;">
                        <!-- SECCIÓN B: CARGA ACADÉMICA -->
                        <div class="border-bottom pb-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-book me-2"></i> Sección B - Carga Académica (Opcional)</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-add-carga-edit">
                                    <i class="fas fa-plus me-1"></i> Asignar Curso
                                </button>
                            </div>
                            
                            <div id="edit-carga-academica-container">
                                <!-- Aquí se insertan las filas dinámicas de carga -->
                            </div>
                        </div>

                        <!-- SECCIÓN C: TUTORÍA -->
                        <div class="mb-2">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-users-cog me-2"></i> Sección C - Tutoría (Opcional)</h6>
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-secondary mb-2">¿Será tutor de algún salón? (Marque todos los que apliquen)</label>
                                    <div class="p-3 border rounded bg-white">
                                        @php
                                            $aulasPorTurnoEdit = $allAulas->groupBy('turno');
                                        @endphp
                                        @foreach($aulasPorTurnoEdit as $turno => $aulasGrupo)
                                            <div class="mb-3">
                                                <span class="text-secondary small fw-bold d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                                    @if(strcasecmp($turno, 'Mañana') === 0 || strcasecmp($turno, 'manana') === 0)
                                                        ☀️ Turno Mañana
                                                    @else
                                                        🌙 Turno Tarde
                                                    @endif
                                                </span>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($aulasGrupo as $aula)
                                                        <input type="checkbox" class="btn-check tutor-checkbox-edit" name="tutor_aulas[]" value="{{ $aula->id }}" id="tutor_aula_edit_{{ $aula->id }}">
                                                        <label class="btn btn-outline-primary btn-sm rounded-pill px-3 transition-all" for="tutor_aula_edit_{{ $aula->id }}">
                                                            {{ $aula->grado }}° "{{ $aula->seccion }}"
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template para carga académica dinámica en edición -->
<template id="edit-carga-row-template">
    <div class="carga-row border border-light-subtle rounded-3 p-3 mb-3 bg-light position-relative shadow-sm hover-lift" style="transition: transform .2s ease, box-shadow .2s ease;">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-carga-btn" style="font-size: 0.75rem;" title="Eliminar asignación"></button>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label small fw-bold text-secondary mb-1">Curso</label>
                <select name="cursos[__INDEX__]" class="form-select rounded-3 edit-curso-select" required>
                    <option value="" disabled selected>Seleccione un curso</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold text-secondary mb-2">Aulas donde dictará este curso (Marque todas las que apliquen)</label>
                <div class="p-3 border rounded bg-white">
                    @foreach($aulasPorTurnoEdit as $turno => $aulasGrupo)
                        <div class="mb-3">
                            <span class="text-secondary small fw-bold d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                @if(strcasecmp($turno, 'Mañana') === 0 || strcasecmp($turno, 'manana') === 0)
                                    ☀️ Turno Mañana
                                @else
                                    🌙 Turno Tarde
                                @endif
                            </span>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($aulasGrupo as $aula)
                                    <input type="checkbox" class="btn-check edit-aula-checkbox" name="aulas[__INDEX__][]" value="{{ $aula->id }}" id="aula_edit_{{ $aula->id }}___INDEX__">
                                    <label class="btn btn-outline-primary btn-sm rounded-pill px-3 transition-all" for="aula_edit_{{ $aula->id }}___INDEX__">
                                        {{ $aula->grado }}° "{{ $aula->seccion }}"
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</template>

{{-- MODAL GESTIONAR CURSOS --}}
<div class="modal fade" id="modalGestionCursos" tabindex="-1" aria-labelledby="modalGestionCursosLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalGestionCursosLabel">
                    <i class="fas fa-book me-2"></i>Gestionar Cursos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body py-4">
                {{-- Formulario para crear un nuevo curso --}}
                <div class="card border border-dashed rounded-3 p-3 mb-4 bg-light">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-primary me-1"></i> Agregar Nuevo Curso</h6>
                    <form action="{{ route('admin.cursos.store') }}" method="POST">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label class="form-label small fw-bold text-secondary">Nombre del Curso</label>
                                <input type="text" name="nombre" class="form-control rounded-3" placeholder="Ej: Álgebra, Química, Historia..." required value="{{ old('nombre') }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm">
                                    <i class="fas fa-plus me-1"></i> Agregar Curso
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabla o lista iterando los cursos actuales --}}
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-list text-primary me-1"></i> Cursos Registrados</h6>
                <div class="table-responsive border rounded-3 bg-white" style="max-height: 300px; overflow-y: auto;">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3 py-2.5">Nombre del Curso</th>
                                <th class="text-end pe-3 py-2.5" style="width: 120px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($cursos) > 0)
                                @foreach($cursos as $c)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark">{{ $c->nombre }}</td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('admin.cursos.eliminar', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este curso?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm border-0" title="Eliminar Curso">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted fst-italic">No hay cursos registrados en el sistema.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #docentesTabs .nav-link {
        color: #4b5563;
        transition: all 0.25s ease;
    }
    #docentesTabs .nav-link.active {
        background-color: var(--bs-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.15) !important;
    }
    #docentesTabs .nav-link.active i {
        color: #ffffff !important;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f8fafc !important;
        color: var(--bs-primary) !important;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.125) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lógica de carga académica dinámica en creación
        let cargaIndex = 0;
        const container = document.getElementById('carga-academica-container');
        const template = document.getElementById('carga-row-template');

        if (container && template) {
            const templateHtml = template.innerHTML;

            document.getElementById('btn-add-carga').addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.innerHTML = templateHtml.replace(/__INDEX__/g, cargaIndex);
                container.appendChild(newRow.firstElementChild);
                cargaIndex++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-carga-btn') || e.target.closest('.remove-carga-btn')) {
                    const row = e.target.closest('.carga-row');
                    if (row) row.remove();
                }
            });
        }

        // Toggle Carga Académica based on role select
        document.querySelectorAll('.select-rol-type').forEach(select => {
            select.addEventListener('change', function() {
                const modal = this.closest('.modal');
                const container = modal.querySelector('.cargo-profesor-only');
                if (container) {
                    if (this.value === 'profesor') {
                        container.style.display = 'block';
                    } else {
                        container.style.display = 'none';
                    }
                }
            });
            // Initial trigger
            select.dispatchEvent(new Event('change'));
        });

        // Lógica del Modal de Edición
        const editModal = document.getElementById('editDocenteModal');
        if (editModal) {
            let editCargaIndex = 0;
            const editContainer = document.getElementById('edit-carga-academica-container');
            const editTemplate = document.getElementById('edit-carga-row-template');

            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombres = button.getAttribute('data-nombres');
                const apellidos = button.getAttribute('data-apellidos');
                const codigo = button.getAttribute('data-codigo');
                const celular = button.getAttribute('data-celular');
                const nacimiento = button.getAttribute('data-nacimiento');
                const rol = button.getAttribute('data-rol') || 'profesor';
                const cargaJson = button.getAttribute('data-carga');
                const tutoriasJson = button.getAttribute('data-tutorias');

                const form = editModal.querySelector('form');
                const actionUrl = "{{ route('admin.docentes.update', ':id') }}".replace(':id', id);
                form.setAttribute('action', actionUrl);

                editModal.querySelector('#edit_nombres').value = nombres || '';
                editModal.querySelector('#edit_apellidos').value = apellidos || '';
                editModal.querySelector('#edit_codigo_usuario').value = codigo || '';
                editModal.querySelector('#edit_celular').value = celular || '';
                editModal.querySelector('#edit_fecha_nacimiento').value = nacimiento || '';
                editModal.querySelector('#edit_password').value = '';

                const selectRol = editModal.querySelector('#edit_rol');
                if (selectRol) {
                    selectRol.value = rol;
                    selectRol.dispatchEvent(new Event('change'));
                }

                // 1. Limpiar contenedor de carga académica en edición
                if (editContainer) {
                    editContainer.innerHTML = '';
                }
                editCargaIndex = 0;

                // 2. Desmarcar todos los checkboxes de tutoría
                editModal.querySelectorAll('.tutor-checkbox-edit').forEach(chk => {
                    chk.checked = false;
                });

                // 3. Poblar Tutorías
                if (tutoriasJson) {
                    const tutorias = JSON.parse(tutoriasJson);
                    tutorias.forEach(aulaId => {
                        const chk = editModal.querySelector(`#tutor_aula_edit_${aulaId}`);
                        if (chk) chk.checked = true;
                    });
                }

                // 4. Poblar Carga Académica
                if (cargaJson && editTemplate && editContainer) {
                    const cargas = JSON.parse(cargaJson);
                    const templateHtml = editTemplate.innerHTML;

                    Object.keys(cargas).forEach(cursoId => {
                        const aulaIds = cargas[cursoId];

                        // Añadir fila
                        const newRow = document.createElement('div');
                        newRow.innerHTML = templateHtml.replace(/__INDEX__/g, editCargaIndex);
                        editContainer.appendChild(newRow.firstElementChild);

                        // Obtener fila insertada
                        const insertedRow = editContainer.lastElementChild;

                        // Seleccionar curso
                        const selectCurso = insertedRow.querySelector('.edit-curso-select');
                        if (selectCurso) selectCurso.value = cursoId;

                        // Marcar aulas
                        aulaIds.forEach(aulaId => {
                            const chk = insertedRow.querySelector(`#aula_edit_${aulaId}_${editCargaIndex}`);
                            if (chk) chk.checked = true;
                        });

                        editCargaIndex++;
                    });
                }
            });

            // Agregar curso en modal editar
            if (editTemplate && editContainer) {
                const templateHtml = editTemplate.innerHTML;
                document.getElementById('btn-add-carga-edit').addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.innerHTML = templateHtml.replace(/__INDEX__/g, editCargaIndex);
                    editContainer.appendChild(newRow.firstElementChild);
                    editCargaIndex++;
                });
            }

            if (editContainer) {
                editContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-carga-btn') || e.target.closest('.remove-carga-btn')) {
                        const row = e.target.closest('.carga-row');
                        if (row) row.remove();
                    }
                });
            }
        }
    });
</script>
@endpush
