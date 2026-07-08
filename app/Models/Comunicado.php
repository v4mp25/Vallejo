<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    protected $table = 'comunicados';

    protected $fillable = [
        'titulo',
        'fecha',
        'archivo_pdf',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}
