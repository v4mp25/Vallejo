<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionWeb extends Model
{
    // Forzamos el nombre exacto de la tabla
    protected $table = 'configuracion_webs';

    protected $fillable = [
        'frase_topbar',
        'banner_inicial_url',
        'telefono_contacto',
        'correo_contacto'
    ];
}