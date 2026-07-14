{{-- Partial reutilizable para mostrar listado de personal por rol --}}
{{-- Vars esperadas: $personas, $rolNombre, $iconColor, $iconClass, $emptyMsg --}}
@if($personas->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex align-items-center gap-3">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" 
                 style="background: {{ $iconColor }}22; width:40px; height:40px;">
                <i class="fas {{ $iconClass }}" style="color:{{ $iconColor }};"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-dark">{{ $personas->count() }} {{ $rolNombre }}(s) registrado(s)</h6>
                <small class="text-muted">Puedes editar o eliminar desde las acciones a la derecha</small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 border-0">Nombre Completo</th>
                            <th class="py-3 border-0">DNI / Código</th>
                            <th class="py-3 border-0">Celular</th>
                            <th class="py-3 border-0">Estado</th>
                            <th class="pe-4 py-3 border-0 text-center" style="width:160px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personas as $persona)
                        <tr class="border-bottom">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" 
                                         style="width:38px; height:38px; background: {{ $iconColor }}22;">
                                        <i class="fas {{ $iconClass }}" style="color:{{ $iconColor }};"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $persona->nombres }} {{ $persona->apellidos }}</span>
                                        <small class="text-muted">{{ $persona->rol === 'director' ? 'Director' : $rolNombre }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">
                                    {{ $persona->codigo_usuario }}
                                </span>
                            </td>
                            <td class="py-3 text-muted">{{ $persona->celular ?? 'No registrado' }}</td>
                            <td class="py-3">
                                <form action="{{ route('admin.docentes.toggle-status', $persona->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm border-0 p-0" title="Clic para cambiar estado">
                                        @if($persona->estado)
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
                                            class="btn btn-outline-primary btn-sm rounded-3 px-2 py-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editDocenteModal"
                                            data-id="{{ $persona->id }}"
                                            data-nombres="{{ $persona->nombres }}"
                                            data-apellidos="{{ $persona->apellidos }}"
                                            data-codigo="{{ $persona->codigo_usuario }}"
                                            data-celular="{{ $persona->celular }}"
                                            data-nacimiento="{{ $persona->fecha_nacimiento }}"
                                            data-rol="{{ $persona->rol }}"
                                            data-carga="{{ json_encode([]) }}"
                                            data-tutorias="{{ json_encode([]) }}"
                                            title="Editar información">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('admin.docentes.eliminar', $persona->id) }}" method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar a este miembro del personal? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
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
@else
    <div class="text-center text-muted py-5 bg-white rounded-4 shadow-sm border border-light">
        <i class="fas {{ $iconClass }} fa-3x mb-3 opacity-25" style="color:{{ $iconColor }};"></i>
        <h5 class="fw-semibold text-dark">Sin registros</h5>
        <p class="mb-0">{{ $emptyMsg }}</p>
    </div>
@endif
