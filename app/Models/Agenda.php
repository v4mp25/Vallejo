<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agenda_escolar';

    protected $fillable = [
        'titulo',
        'fecha_inicio',
        'fecha_fin',
        'lugar',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'fecha_limite' => 'date',
    ];
}
