<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GestionInstitucional extends Model
{
    protected $table = 'gestion_institucional';

    protected $fillable = [
        'conei_descripcion',
        'apafa_descripcion',
        'organigrama_imagen',
    ];
}
