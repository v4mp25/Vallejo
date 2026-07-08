<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'galeria_eventos';

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
