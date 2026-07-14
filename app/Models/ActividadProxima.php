<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadProxima extends Model
{
    protected $table = 'actividades_proximas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_limite' => 'date',
    ];
}
