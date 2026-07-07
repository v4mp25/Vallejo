<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AliadoEstrategico extends Model
{
    protected $table = 'aliados_estrategicos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'logo',
        'enlace_web',
    ];
}
