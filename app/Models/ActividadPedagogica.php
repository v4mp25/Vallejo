<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadPedagogica extends Model
{
    protected $table = 'galeria_actividades';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'imagen',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}
