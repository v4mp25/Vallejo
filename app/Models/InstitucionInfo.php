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
        'uniforme_imagenes',
        'infraestructura_descripcion',
        'infra_aulas_imagenes',
        'infra_labs_imagenes',
        'infra_biblio_imagenes',
        'infra_aip_imagenes',
        'linea_tiempo',
    ];

    protected $casts = [
        'linea_tiempo' => 'array',
        'uniforme_imagenes' => 'array',
        'infra_aulas_imagenes' => 'array',
        'infra_labs_imagenes' => 'array',
        'infra_biblio_imagenes' => 'array',
        'infra_aip_imagenes' => 'array',
    ];
}
