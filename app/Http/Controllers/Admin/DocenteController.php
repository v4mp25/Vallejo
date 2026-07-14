<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    /**
     * Display a listing of teachers/staff.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Flat list for "Todos los Docentes" (Directory) tab - now contains all staff
        $queryTodos = User::with(['asignaciones', 'aulasTutoria'])
            ->whereIn('rol', ['profesor', 'psicologo', 'auxiliar', 'secretaria', 'directivo', 'director', 'administrativo'])
            ->latest();
            
        if ($search) {
            $queryTodos->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('codigo_usuario', 'like', "%{$search}%");
            });
        }
        $todosDocentes = $queryTodos->get();

        // 1b. Colecciones por rol para las pestañas específicas
        $buildRolQuery = function($roles) use ($search) {
            $roles = (array) $roles;
            $q = User::whereIn('rol', $roles)->orderBy('apellidos')->orderBy('nombres');
            if ($search) {
                $q->where(function($sq) use ($search) {
                    $sq->where('nombres', 'like', "%{$search}%")
                       ->orWhere('apellidos', 'like', "%{$search}%")
                       ->orWhere('codigo_usuario', 'like', "%{$search}%");
                });
            }
            return $q->get();
        };
        $psicologos     = $buildRolQuery('psicologo');
        $auxiliares     = $buildRolQuery('auxiliar');
        $administrativos = $buildRolQuery('administrativo');
        // El rol "director" se agrupa junto con "directivo" en la pestaña de Directivos
        $directivos     = $buildRolQuery(['directivo', 'director']);

        // 2. Fetch assignments to group teachers by Turno (Shift) and Course (Only profesor role)
        $queryAsignaciones = Asignacion::with(['profesor.asignaciones', 'profesor.aulasTutoria', 'aula', 'curso'])
            ->whereHas('profesor', function ($q) use ($search) {
                $q->where('rol', 'profesor');
                if ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where('nombres', 'like', "%{$search}%")
                          ->orWhere('apellidos', 'like', "%{$search}%")
                          ->orWhere('codigo_usuario', 'like', "%{$search}%");
                    });
                }
            });

        $asignaciones = $queryAsignaciones->get();

        // Restructure assignments: Turno -> Curso -> Profesor -> Aulas
        $grouped = [
            'Mañana' => [],
            'Tarde' => []
        ];

        foreach ($asignaciones as $asig) {
            if (!$asig->profesor || !$asig->aula || !$asig->curso) {
                continue;
            }

            $turno = $asig->aula->turno;
            $turnoKey = (strcasecmp($turno, 'Mañana') === 0 || strcasecmp($turno, 'manana') === 0) ? 'Mañana' : 'Tarde';
            
            $cursoNombre = $asig->curso->nombre;
            $profesorId = $asig->profesor_id;

            if (!isset($grouped[$turnoKey][$cursoNombre])) {
                $grouped[$turnoKey][$cursoNombre] = [];
            }

            if (!isset($grouped[$turnoKey][$cursoNombre][$profesorId])) {
                $grouped[$turnoKey][$cursoNombre][$profesorId] = [
                    'profesor' => $asig->profesor,
                    'aulas' => []
                ];
            }

            $existingAulaIds = array_map(function ($aula) {
                return $aula->id;
            }, $grouped[$turnoKey][$cursoNombre][$profesorId]['aulas']);

            if (!in_array($asig->aula->id, $existingAulaIds)) {
                $grouped[$turnoKey][$cursoNombre][$profesorId]['aulas'][] = $asig->aula;
            }
        }

        $allAulas = \App\Models\Aula::orderBy('turno')->orderBy('grado')->orderBy('seccion')->get();
        $cursos = \App\Models\Curso::orderBy('nombre')->get();

        return view('admin.docentes.index', compact(
            'todosDocentes', 'grouped', 'search', 'allAulas', 'cursos',
            'psicologos', 'auxiliares', 'administrativos', 'directivos'
        ));
    }

    /**
     * Store a newly created teacher/staff member in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'codigo_usuario' => 'required|string|max:100|unique:users,codigo_usuario',
            'celular' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:profesor,psicologo,auxiliar,secretaria,directivo,director,administrativo',
            'cursos' => 'nullable|array',
            'cursos.*' => 'required|exists:cursos,id',
            'aulas' => 'nullable|array',
            'aulas.*' => 'nullable|array',
            'aulas.*.*' => 'exists:aulas,id',
            'tutor_aulas' => 'nullable|array',
            'tutor_aulas.*' => 'exists:aulas,id',
        ], [
            'codigo_usuario.unique' => 'El código de usuario (DNI) ya está registrado en el sistema.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        \DB::transaction(function () use ($request) {
            $rol = $request->input('rol');

            $docente = User::create([
                'nombres' => $request->input('nombres'),
                'apellidos' => $request->input('apellidos'),
                'codigo_usuario' => $request->input('codigo_usuario'),
                'celular' => $request->input('celular'),
                'fecha_nacimiento' => $request->input('fecha_nacimiento'),
                'rol' => $rol,
                'password' => Hash::make($request->input('password')),
                'estado' => true,
            ]);

            // Only assign load and tutoring if role is profesor
            if ($rol === 'profesor') {
                $cursosInput = $request->input('cursos', []);
                $aulasInput = $request->input('aulas', []);

                foreach ($cursosInput as $index => $cursoId) {
                    $aulaIds = $aulasInput[$index] ?? [];
                    if ($cursoId && !empty($aulaIds)) {
                        foreach ($aulaIds as $aulaId) {
                            Asignacion::updateOrCreate(
                                [
                                    'aula_id' => $aulaId,
                                    'curso_id' => $cursoId,
                                    'año_academico' => \App\Models\ConfiguracionWeb::anioAcademicoActual(),
                                ],
                                [
                                    'profesor_id' => $docente->id,
                                ]
                            );
                        }
                    }
                }

                $tutorAulas = $request->input('tutor_aulas', []);
                if (!empty($tutorAulas)) {
                    \App\Models\Aula::whereIn('id', $tutorAulas)->update(['tutor_id' => $docente->id]);
                }
            }
        });

        return redirect()
            ->route('admin.docentes.index')
            ->with('success', '¡Miembro del personal registrado con éxito!');
    }

    /**
     * Update the specified teacher/staff in storage.
     */
    public function update(Request $request, $id)
    {
        $docente = User::whereIn('rol', ['profesor', 'psicologo', 'auxiliar', 'secretaria', 'directivo', 'director', 'administrativo'])->findOrFail($id);

        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'codigo_usuario' => 'required|string|max:100|unique:users,codigo_usuario,' . $id,
            'celular' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'rol' => 'required|in:profesor,psicologo,auxiliar,secretaria,directivo,director,administrativo',
            'cursos' => 'nullable|array',
            'cursos.*' => 'required|exists:cursos,id',
            'aulas' => 'nullable|array',
            'aulas.*' => 'nullable|array',
            'aulas.*.*' => 'exists:aulas,id',
            'tutor_aulas' => 'nullable|array',
            'tutor_aulas.*' => 'exists:aulas,id',
        ], [
            'codigo_usuario.unique' => 'El código de usuario (DNI) ya está registrado en el sistema.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        \DB::transaction(function () use ($request, $docente) {
            $rol = $request->input('rol');

            $data = [
                'nombres' => $request->input('nombres'),
                'apellidos' => $request->input('apellidos'),
                'codigo_usuario' => $request->input('codigo_usuario'),
                'celular' => $request->input('celular'),
                'fecha_nacimiento' => $request->input('fecha_nacimiento'),
                'rol' => $rol,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->input('password'));
            }

            $docente->update($data);

            // Clean up existing assignments (SOLO del año académico vigente: las de años
            // anteriores se conservan como historial y no deben borrarse, ya que notas.asignacion_id
            // tiene onDelete('cascade') y las eliminaría en cascada).
            Asignacion::where('profesor_id', $docente->id)
                ->where('año_academico', \App\Models\ConfiguracionWeb::anioAcademicoActual())
                ->delete();
            \App\Models\Aula::where('tutor_id', $docente->id)->update(['tutor_id' => null]);

            // Re-assign academic load and tutoring if updated role is profesor
            if ($rol === 'profesor') {
                $cursosInput = $request->input('cursos', []);
                $aulasInput = $request->input('aulas', []);

                foreach ($cursosInput as $index => $cursoId) {
                    $aulaIds = $aulasInput[$index] ?? [];
                    if ($cursoId && !empty($aulaIds)) {
                        foreach ($aulaIds as $aulaId) {
                            Asignacion::updateOrCreate(
                                [
                                    'aula_id' => $aulaId,
                                    'curso_id' => $cursoId,
                                    'año_academico' => \App\Models\ConfiguracionWeb::anioAcademicoActual(),
                                ],
                                [
                                    'profesor_id' => $docente->id,
                                ]
                            );
                        }
                    }
                }

                $tutorAulas = $request->input('tutor_aulas', []);
                if (!empty($tutorAulas)) {
                    \App\Models\Aula::whereIn('id', $tutorAulas)->update(['tutor_id' => $docente->id]);
                }
            }
        });

        return redirect()
            ->route('admin.docentes.index')
            ->with('success', '¡Información del personal actualizada con éxito!');
    }

    /**
     * Toggle status.
     */
    public function toggleStatus($id)
    {
        $docente = User::whereIn('rol', ['profesor', 'psicologo', 'auxiliar', 'secretaria', 'directivo', 'director', 'administrativo'])->findOrFail($id);
        $docente->estado = !$docente->estado;
        $docente->save();

        $mensaje = $docente->estado ? '¡Personal activado!' : '¡Personal desactivado!';
        
        return redirect()
            ->route('admin.docentes.index')
            ->with('success', $mensaje);
    }

    /**
     * Remove the specified teacher/staff.
     */
    public function destroy($id)
    {
        $docente = User::whereIn('rol', ['profesor', 'psicologo', 'auxiliar', 'secretaria', 'directivo', 'director', 'administrativo'])->findOrFail($id);

        \DB::transaction(function () use ($id, $docente) {
            \App\Models\Aula::where('tutor_id', $id)->update(['tutor_id' => null]);
            Asignacion::where('profesor_id', $id)->delete();
            $docente->delete();
        });

        return redirect()
            ->route('admin.docentes.index')
            ->with('success', 'Personal eliminado correctamente.');
    }

    /**
     * Store a newly created course.
     */
    public function storeCurso(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:cursos,nombre',
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.unique' => 'El curso ya está registrado en el sistema.',
        ]);

        \App\Models\Curso::create([
            'nombre' => $request->input('nombre'),
        ]);

        return redirect()
            ->route('admin.docentes.index')
            ->with('success', 'Curso creado con éxito.');
    }

    /**
     * Delete the specified course after checking assignments.
     */
    public function destroyCurso($id)
    {
        $curso = \App\Models\Curso::findOrFail($id);

        $count = Asignacion::where('curso_id', $id)->count();

        if ($count > 0) {
            return redirect()
                ->route('admin.docentes.index')
                ->with('error', 'No se puede eliminar el curso porque hay docentes asignados a él.');
        }

        $curso->delete();

        return redirect()
            ->route('admin.docentes.index')
            ->with('success', 'Curso eliminado correctamente.');
    }
}
