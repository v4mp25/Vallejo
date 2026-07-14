<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'contenido',
        'fecha',
        'imagen',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_limite' => 'date',
    ];
}
