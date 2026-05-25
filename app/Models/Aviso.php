<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    protected $fillable = [
        'titulo', 'contenido', 'imagen_path', 'publicado_at', 'creado_por'
    ];
}