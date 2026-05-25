<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $fillable = [
        'alumno_id',
        'asignacion_id',
        'periodo',
        'criterio',
        'calificacion',
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }
}
