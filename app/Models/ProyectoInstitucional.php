<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoInstitucional extends Model
{
    protected $table = 'proyectos_institucionales';

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
    ];
}
