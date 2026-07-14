<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $fillable = ['profesor_id', 'aula_id', 'curso_id', 'año_academico'];

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
