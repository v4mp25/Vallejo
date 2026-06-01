<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Psicología — I.E. César Vallejo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <span class="navbar-brand fw-bold text-primary">
                <i class="fas fa-brain me-2"></i>Módulo de Psicología
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-secondary btn-sm" type="submit">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar sesión
                </button>
            </form>
        </div>
    </nav>

    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted d-block">Pendientes</small>
                        <h3 class="mb-0 text-warning">{{ $resumen['pendientes'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted d-block">Atendidas</small>
                        <h3 class="mb-0 text-success">{{ $resumen['atendidas'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted d-block">Canceladas</small>
                        <h3 class="mb-0 text-secondary">{{ $resumen['canceladas'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0">Derivaciones pendientes</h5>
            </div>
            <div class="card-body">
                @if ($pendientes->isEmpty())
                    <p class="text-muted mb-0">No hay derivaciones pendientes por atender.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Alumno</th>
                                    <th>Profesor que deriva</th>
                                    <th>Motivo</th>
                                    <th>Fecha de cita</th>
                                    <th class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendientes as $cita)
                                    @php
                                        $alumno = $usuarios[$cita->alumno_id] ?? null;
                                        $profesor = $usuarios[$cita->profesor_id] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $alumno ? trim($alumno->apellidos.' '.$alumno->nombres) : '—' }}</td>
                                        <td>{{ $profesor ? trim($profesor->apellidos.' '.$profesor->nombres) : '—' }}</td>
                                        <td style="min-width: 320px;">{{ $cita->motivo }}</td>
                                        <td>{{ $cita->fecha_cita ? \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y h:i A') : 'Sin programar' }}</td>
                                        <td class="text-end">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAsignarCita{{ $cita->id }}"
                                            >
                                                <i class="fas fa-calendar-plus me-1"></i>Asignar cita
                                            </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalAsignarCita{{ $cita->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('psicologo.citas.asignar', $cita) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Asignar cita psicológica</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2"><strong>Alumno:</strong> {{ $alumno ? trim($alumno->apellidos.' '.$alumno->nombres) : '—' }}</p>
                                                        <p class="text-muted small">{{ $cita->motivo }}</p>
                                                        <label class="form-label fw-semibold" for="fecha_cita_{{ $cita->id }}">Fecha y hora de atención</label>
                                                        <input
                                                            id="fecha_cita_{{ $cita->id }}"
                                                            type="datetime-local"
                                                            name="fecha_cita"
                                                            class="form-control"
                                                            value="{{ $cita->fecha_cita ? \Carbon\Carbon::parse($cita->fecha_cita)->format('Y-m-d\TH:i') : '' }}"
                                                            required
                                                        >
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar cita</button>
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
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

