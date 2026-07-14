<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $fillable = ['grado', 'seccion', 'nivel', 'turno', 'tutor_id'];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }

    /** Helper: nombre legible del aula, ej: "3° A" */
    public function getNombreAttribute(): string
    {
        return $this->grado . '° ' . $this->seccion;
    }

    public function materialesPsicologia()
    {
        return $this->belongsToMany(MaterialPsicologia::class, 'aula_material_psicologia', 'aula_id', 'material_psicologia_id');
    }

    public function materialesAuxiliar()
    {
        return $this->belongsToMany(MaterialAuxiliar::class, 'aula_material_auxiliar', 'aula_id', 'material_auxiliar_id');
    }
}
