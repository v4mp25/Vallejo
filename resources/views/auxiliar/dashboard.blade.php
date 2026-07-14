<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Auxiliar — I.E. César Vallejo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }
        .navbar-brand {
            font-size: 1.35rem;
            letter-spacing: -0.3px;
        }
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }
        .accordion-button:not(.collapsed) {
            background-color: rgba(22, 163, 74, 0.05);
            color: #16a34a;
        }
        .btn-action {
            font-weight: 500;
            border-radius: 20px;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-xs">
        <div class="container">
            <span class="navbar-brand fw-bold text-success">
                <i class="fas fa-user-shield me-2"></i>Módulo de Auxiliares
            </span>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-success btn-sm btn-action px-3" data-bs-toggle="modal" data-bs-target="#modalPublicarMaterial">
                    <i class="fas fa-plus-circle me-1"></i>Publicar Nuevo Material/Comunicado
                </button>
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm btn-action px-3" type="submit">
                        <i class="fas fa-sign-out-alt me-1"></i>Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- BANNER DE BIENVENIDA --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white" style="width: 70px; height: 70px; font-size: 2.2rem;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div class="col">
                        <small class="text-uppercase fw-semibold tracking-wider opacity-75 d-block mb-1" style="font-size: 0.75rem;">Bienvenido al Portal</small>
                        <h3 class="fw-bold mb-0">Auxiliar: {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</h3>
                        <p class="mb-0 small opacity-90 mt-1"><i class="far fa-calendar-alt me-1"></i> Fecha: {{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TARJETAS DE RESUMEN --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card card-stats border-0 shadow-sm rounded-4 border-start border-4 border-warning h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Derivaciones Activas</small>
                            <h2 class="mb-0 text-warning fw-bold mt-1">{{ $resumen['pendientes'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-stats border-0 shadow-sm rounded-4 border-start border-4 border-success h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Alumnos Atendidos</small>
                            <h2 class="mb-0 text-success fw-bold mt-1">{{ $resumen['atendidas'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA DE PENDIENTES --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-clipboard-list text-success me-2"></i>Derivaciones y Citas Pendientes</h5>
            </div>
            <div class="card-body px-4 pb-4">
                @if ($pendientes->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-folder-open fs-1 mb-3 opacity-50"></i>
                        <p class="mb-0 fw-semibold">No hay derivaciones ni citas activas por atender.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Alumno</th>
                                    <th>Grado y Sección</th>
                                    <th>Bimestre</th>
                                    <th>Derivado por</th>
                                    <th>Motivo</th>
                                    <th>Fecha de cita</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-3" style="width: 250px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendientes as $cita)
                                    @php
                                        $alumno      = $usuarios[$cita->alumno_id] ?? null;
                                        $profesor    = $usuarios[$cita->profesor_id] ?? null;
                                        $aula        = $alumno?->matriculas?->first()?->aula;
                                        $gradoSeccion = $aula ? $aula->grado . '° ' . $aula->seccion : '—';
                                        $labelBimestre = $bimestreLabels[$cita->bimestre] ?? 'Bimestre ' . $cita->bimestre;
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-dark ps-3">{{ $alumno ? trim($alumno->apellidos.' '.$alumno->nombres) : '—' }}</td>
                                        <td><span class="badge bg-light text-dark border px-2 py-1 rounded-pill">{{ $gradoSeccion }}</span></td>
                                        <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill fw-semibold">{{ $labelBimestre }}</span></td>
                                        <td>{{ $profesor ? trim($profesor->apellidos.' '.$profesor->nombres) : '—' }}</td>
                                        <td style="min-width: 220px; max-width: 320px;" class="text-truncate">{{ $cita->motivo }}</td>
                                        <td>
                                            @if($cita->fecha_cita)
                                                <span class="fw-semibold text-success"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y h:i A') }}</span>
                                            @else
                                                <span class="text-muted"><i class="far fa-calendar-minus me-1"></i>Sin programar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cita->estado === 'citado')
                                                <span class="badge bg-info text-white rounded-pill px-3 py-1 fw-semibold">Citado</span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-semibold">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-action {{ $cita->fecha_cita ? 'btn-outline-success' : 'btn-success' }} px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalAsignarCita{{ $cita->id }}"
                                                >
                                                    @if($cita->fecha_cita)
                                                        <i class="fas fa-calendar-alt me-1"></i>Reprogramar
                                                    @else
                                                        <i class="fas fa-calendar-plus me-1"></i>Programar
                                                    @endif
                                                </button>

                                                @if($cita->fecha_cita)
                                                    <form method="POST" action="{{ route('auxiliar.citas.atendida', $cita) }}" class="d-inline" onsubmit="return confirm('¿Marcar al alumno como atendido?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success btn-action px-3">
                                                            <i class="fas fa-check-circle me-1"></i>Atendido
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- MODAL ASIGNAR CITA --}}
                                    <div class="modal fade" id="modalAsignarCita{{ $cita->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <form method="POST" action="{{ route('auxiliar.citas.asignar', $cita) }}">
                                                    @csrf
                                                    <div class="modal-header bg-success text-white border-bottom-0 pb-0">
                                                        <h5 class="modal-title fw-bold">
                                                            @if($cita->fecha_cita)
                                                                <i class="fas fa-calendar-alt me-2"></i>Reprogramar Cita
                                                            @else
                                                                <i class="fas fa-calendar-plus me-2"></i>Programar Cita
                                                            @endif
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body py-4">
                                                        <p class="mb-2"><strong>Alumno:</strong> {{ $alumno ? trim($alumno->apellidos.' '.$alumno->nombres) : '—' }}</p>
                                                        <p class="text-muted small mb-3"><strong>Motivo de derivación:</strong> {{ $cita->motivo }}</p>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold" for="fecha_cita_{{ $cita->id }}">Fecha y hora de atención</label>
                                                            <input
                                                                id="fecha_cita_{{ $cita->id }}"
                                                                type="datetime-local"
                                                                name="fecha_cita"
                                                                class="form-control rounded-3"
                                                                value="{{ $cita->fecha_cita ? \Carbon\Carbon::parse($cita->fecha_cita)->format('Y-m-d\TH:i') : '' }}"
                                                                required
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light border-top-0 pt-0">
                                                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Guardar Cita</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- HISTORIAL DE ATENCIONES POR BIMESTRE --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-history text-success me-2"></i> Historial de Alumnos Atendidos por Bimestre
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                @if ($atendidasPorBimestre->isEmpty())
                    <p class="text-muted mb-0 py-4 text-center fst-italic">No hay registro de alumnos atendidos aún.</p>
                @else
                    <div class="accordion rounded-4 overflow-hidden border" id="accordionHistorial">
                        @foreach ($atendidasPorBimestre as $bim => $citasAtendidas)
                            @php
                                $label = $bimestreLabels[$bim] ?? 'Bimestre ' . $bim;
                            @endphp
                            <div class="accordion-item border-0 border-bottom">
                                <h2 class="accordion-header" id="headingAux{{ $bim }}">
                                    <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAux{{ $bim }}" aria-expanded="false" aria-controls="collapseAux{{ $bim }}">
                                        <i class="fas fa-folder text-success me-2"></i> {{ $label }}
                                        <span class="badge bg-success rounded-pill ms-2 px-2 py-1 text-xs fw-semibold">{{ $citasAtendidas->count() }} @choice('atención|atenciones', $citasAtendidas->count())</span>
                                    </button>
                                </h2>
                                <div id="collapseAux{{ $bim }}" class="accordion-collapse collapse" aria-labelledby="headingAux{{ $bim }}" data-bs-parent="#accordionHistorial">
                                    <div class="accordion-body p-0">
                                        <div class="table-responsive">
                                            <table class="table align-middle mb-0 table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="ps-4">Alumno</th>
                                                        <th>Grado y Sección</th>
                                                        <th>Fecha de Atención</th>
                                                        <th>Derivado por</th>
                                                        <th class="pe-4">Motivo / Caso</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($citasAtendidas as $cita)
                                                        @php
                                                            $alumno   = $usuarios[$cita->alumno_id] ?? null;
                                                            $profesor = $usuarios[$cita->profesor_id] ?? null;
                                                            $aula     = $alumno?->matriculas?->first()?->aula;
                                                            $gradoSeccion = $aula ? $aula->grado . '° ' . $aula->seccion : '—';
                                                        @endphp
                                                        <tr>
                                                            <td class="ps-4 fw-bold text-dark">{{ $alumno ? trim($alumno->apellidos.' '.$alumno->nombres) : '—' }}</td>
                                                            <td><span class="badge bg-light text-dark border px-2 py-1 rounded-pill">{{ $gradoSeccion }}</span></td>
                                                            <td>
                                                                <span class="fw-semibold text-success">
                                                                    <i class="far fa-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y h:i A') }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $profesor ? trim($profesor->apellidos.' '.$profesor->nombres) : '—' }}</td>
                                                            <td class="pe-4 text-muted small" style="max-width: 350px;">{{ $cita->motivo }}</td>
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
                @endif
            </div>
        </div>

        {{-- HISTORIAL DE MATERIALES PUBLICADOS POR EL AUXILIAR --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-folder-open text-success me-2"></i> Mis Materiales y Comunicados Publicados
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                @if ($materialesPorBimestreDoc->isEmpty())
                    <p class="text-muted mb-0 py-4 text-center fst-italic">No has publicado ningún material o comunicado aún.</p>
                @else
                    <div class="accordion rounded-4 overflow-hidden border" id="accordionMaterialesDoc">
                        @foreach ($materialesPorBimestreDoc as $bim => $materialesDoc)
                            <div class="accordion-item border-0 border-bottom">
                                <h2 class="accordion-header" id="headingDocAux{{ Str::slug($bim) }}">
                                    <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDocAux{{ Str::slug($bim) }}" aria-expanded="false" aria-controls="collapseDocAux{{ Str::slug($bim) }}">
                                        <i class="fas fa-bookmark text-success me-2"></i> {{ $bim }}
                                        <span class="badge bg-success rounded-pill ms-2 px-2 py-1 text-xs fw-semibold">{{ $materialesDoc->count() }} recurso(s)</span>
                                    </button>
                                </h2>
                                <div id="collapseDocAux{{ Str::slug($bim) }}" class="accordion-collapse collapse" aria-labelledby="headingDocAux{{ Str::slug($bim) }}" data-bs-parent="#accordionMaterialesDoc">
                                    <div class="accordion-body p-0">
                                        <div class="table-responsive">
                                            <table class="table align-middle mb-0 table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="ps-4">Título</th>
                                                        <th>Enlace</th>
                                                        <th>Imagen</th>
                                                        <th>Fecha Límite</th>
                                                        <th>Destinatarios (Aulas)</th>
                                                        <th class="text-end pe-4" style="width: 120px;">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($materialesDoc as $mat)
                                                        <tr>
                                                            <td class="ps-4">
                                                                <span class="fw-bold text-dark d-block">{{ $mat->titulo }}</span>
                                                                <small class="text-muted">{{ Str::limit($mat->descripcion, 60) }}</small>
                                                            </td>
                                                            <td>
                                                                @if($mat->enlace)
                                                                    <a href="{{ $mat->enlace }}" target="_blank" class="badge bg-light text-success border text-decoration-none"><i class="fas fa-link me-1"></i>Link</a>
                                                                @else
                                                                    <span class="text-muted small">—</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($mat->imagen)
                                                                    <a href="{{ asset('storage/' . $mat->imagen) }}" target="_blank">
                                                                        <img src="{{ asset('storage/' . $mat->imagen) }}" class="img-thumbnail" style="max-height: 40px; object-fit: cover;">
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted small">—</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($mat->fecha_limite)
                                                                    @if(\Carbon\Carbon::parse($mat->fecha_limite)->isPast())
                                                                        <span class="text-danger fw-semibold" title="Deshabilitado por fecha límite"><i class="fas fa-ban me-1"></i>Vencido ({{ \Carbon\Carbon::parse($mat->fecha_limite)->format('d/m H:i') }})</span>
                                                                    @else
                                                                        <span class="text-success fw-semibold"><i class="far fa-clock me-1"></i>Hasta {{ \Carbon\Carbon::parse($mat->fecha_limite)->format('d/m/Y H:i') }}</span>
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted small">Sin límite</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @foreach($mat->aulas as $a)
                                                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded" style="font-size: 0.7rem;">{{ $a->grado }}° "{{ $a->seccion }}"</span>
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                            <td class="text-end pe-4">
                                                                <form action="{{ route('auxiliar.materiales.eliminar', $mat->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este recurso?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm border-0">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
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
                @endif
            </div>
        </div>
    </main>

    {{-- MODAL PUBLICAR NUEVO MATERIAL/COMUNICADO --}}
    <div class="modal fade" id="modalPublicarMaterial" tabindex="-1" aria-labelledby="modalPublicarMaterialLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" action="{{ route('auxiliar.materiales.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modalPublicarMaterialLabel">
                            <i class="fas fa-paper-plane me-2"></i>Publicar Nuevo Material / Comunicado
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Título del Material o Comunicado</label>
                            <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ej: Normas de Convivencia, Actividad de Tutoría..." required value="{{ old('titulo') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Descripción (Opcional)</label>
                            <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Describe brevemente el contenido de este material...">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Enlace / Link (Google Forms, Drive, etc.) (Opcional)</label>
                            <input type="url" name="enlace" class="form-control rounded-3" placeholder="Ej: https://docs.google.com/..." value="{{ old('enlace') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Imagen Adjunta (Opcional)</label>
                            <input type="file" name="imagen" class="form-control rounded-3" accept="image/*">
                            <small class="text-muted">Formatos: JPG, PNG, WEBP (máx: 2MB).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Fecha y Hora Límite de Visualización (Opcional)</label>
                            <input type="datetime-local" name="fecha_limite" class="form-control rounded-3" value="{{ old('fecha_limite') }}">
                            <small class="text-muted">Si se define, el recurso se deshabilitará automáticamente para los alumnos pasada esta fecha.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Bimestre</label>
                            <select name="bimestre" class="form-select rounded-3" required>
                                <option value="" disabled selected>Selecciona bimestre...</option>
                                <option value="I Bimestre" {{ old('bimestre') === 'I Bimestre' ? 'selected' : '' }}>I Bimestre</option>
                                <option value="II Bimestre" {{ old('bimestre') === 'II Bimestre' ? 'selected' : '' }}>II Bimestre</option>
                                <option value="III Bimestre" {{ old('bimestre') === 'III Bimestre' ? 'selected' : '' }}>III Bimestre</option>
                                <option value="IV Bimestre" {{ old('bimestre') === 'IV Bimestre' ? 'selected' : '' }}>IV Bimestre</option>
                            </select>
                        </div>

                        {{-- Seleccionar Aulas --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold text-secondary small mb-0">Seleccionar Salones Destinatarios</label>
                                <button type="button" id="btn-select-all-aulas-aux" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-check-double me-1"></i>Seleccionar todos los salones
                                </button>
                            </div>
                            <div class="p-3 border rounded-3 bg-light">
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
                                                <input type="checkbox" class="btn-check check-aula-aux" name="aulas[]" value="{{ $aula->id }}" id="aula_aux_{{ $aula->id }}">
                                                <label class="btn btn-outline-success btn-sm rounded-pill px-3" for="aula_aux_{{ $aula->id }}">
                                                    {{ $aula->grado }}° "{{ $aula->seccion }}"
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Publicar Material</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Seleccionar/Deseleccionar todas las aulas
        const btnSelectAll = document.getElementById('btn-select-all-aulas-aux');
        if (btnSelectAll) {
            let allChecked = false;
            btnSelectAll.addEventListener('click', function() {
                allChecked = !allChecked;
                document.querySelectorAll('.check-aula-aux').forEach(checkbox => {
                    checkbox.checked = allChecked;
                });

                if (allChecked) {
                    btnSelectAll.innerHTML = '<i class="fas fa-times-circle me-1"></i>Desmarcar todos';
                    btnSelectAll.classList.replace('btn-outline-secondary', 'btn-outline-danger');
                } else {
                    btnSelectAll.innerHTML = '<i class="fas fa-check-double me-1"></i>Seleccionar todos los salones';
                    btnSelectAll.classList.replace('btn-outline-danger', 'btn-outline-secondary');
                }
            });
        }
    });
    </script>
</body>
</html>
