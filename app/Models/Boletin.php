<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boletin extends Model
{
    protected $table = 'boletines';

    protected $fillable = [
        'titulo',
        'mes_anio',
        'archivo_pdf',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];
}
