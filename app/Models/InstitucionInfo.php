<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitucionInfo extends Model
{
    protected $table = 'institucion_info';

    protected $fillable = [
        'resena_historica',
        'mision',
        'vision',
        'valores',
        'principios',
        'lema',
        'letra_himno',
        'uniforme_descripcion',
        'uniforme_imagen',
        'infraestructura_descripcion',
        'linea_tiempo',
    ];

    protected $casts = [
        'linea_tiempo' => 'array',
    ];
}
