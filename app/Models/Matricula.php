<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = [
        'alumno_id',
        'aula_id',
        'año_academico',
        'estado',
        'celular_apoderado',
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}
