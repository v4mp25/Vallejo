<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioEducativo extends Model
{
    protected $table = 'servicio_educativo';

    protected $fillable = [
        'nivel_secundaria',
        'enfoque_competencias',
        'innovacion_pedagogica',
        'tutoria_orientacion',
        'educacion_inclusiva',
    ];
}
