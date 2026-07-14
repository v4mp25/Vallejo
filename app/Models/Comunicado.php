<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    protected $table = 'comunicados';

    protected $fillable = [
        'titulo',
        'contenido',
        'fecha',
        'archivo_pdf',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_limite' => 'date',
    ];
}
