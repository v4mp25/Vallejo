@extends('layouts.profesor')

@section('title', 'Notas de tutorados — ' . $aulaTutoria->grado . '° ' . $aulaTutoria->seccion)

@section('content')
    {{-- Breadcrumb --}}
    <a href="{{ route('profesor.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
        <i class="fas fa-arrow-left me-1"></i> Volver a mi panel
    </a>

    {{-- Encabezado del salón --}}
    <div class="cv-tutoria-banner mb-4">
        <div>
            <h3 class="h4 mb-1">
                <i class="fas fa-users me-2"></i>
                Notas de tutorados — Salón {{ $aulaTutoria->grado }}° "{{ $aulaTutoria->seccion }}"
            </h3>
            <p class="mb-0 opacity-90">
                Vista consolidada de <strong>todos los cursos</strong> de tus tutorados
                · {{ $alumnos->count() }} alumno{{ $alumnos->count() !== 1 ? 's' : '' }}
            </p>
        </div>
    </div>

    @if ($alumnos->isEmpty())
        <div class="cv-card card-body text-center py-5">
            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">No hay alumnos matriculados en tu salón de tutoría.</p>
        </div>
    @elseif ($asignaciones->isEmpty())
        <div class="cv-card card-body text-center py-5">
            <i class="fas fa-book fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">No hay cursos asignados a este salón.</p>
        </div>
    @else
        <div class="cv-card card-body p-3">
            <div class="table-responsive">
                <table class="table cv-table table-bordered table-sm align-middle mb-0" style="font-size:.85rem;">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle" style="min-width:180px;">Estudiante</th>
                            @foreach ($asignaciones as $asig)
                                <th colspan="4" class="text-center">
                                    {{ $asig->curso->nombre ?? 'Curso' }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($asignaciones as $asig)
                                <th class="text-center" style="width:40px; font-size:.7rem;">B1</th>
                                <th class="text-center" style="width:40px; font-size:.7rem;">B2</th>
                                <th class="text-center" style="width:40px; font-size:.7rem;">B3</th>
                                <th class="text-center" style="width:40px; font-size:.7rem;">B4</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alumnos as $alumno)
                            <tr>
                                <td>
                                    <strong>{{ $alumno->apellidos }}</strong>,
                                    {{ $alumno->nombres }}
                                </td>
                                @foreach ($asignaciones as $asig)
                                    @foreach (['B1', 'B2', 'B3', 'B4'] as $periodo)
                                        @php
                                            $nota = $notasOrganizadas[$alumno->id][$asig->id][$periodo] ?? null;
                                        @endphp
                                        <td class="text-center {{ $nota !== null && is_numeric($nota) && $nota < 11 ? 'text-danger fw-bold' : '' }}">
                                            {{ $nota ?? '—' }}
                                        </td>
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-muted small mt-3">
            <i class="fas fa-info-circle me-1"></i>
            Las notas en <span class="text-danger fw-bold">rojo</span> están por debajo de 11.
            Esta vista es de <strong>solo lectura</strong> — las notas se registran desde cada curso.
        </p>
    @endif
@endsection

@push('styles')
<style>
    .table-bordered th, .table-bordered td {
        border-color: #e8ecf0;
    }
    .table thead th {
        background: var(--cv-light-bg);
        position: sticky;
        top: 0;
        z-index: 1;
    }
</style>
@endpush
