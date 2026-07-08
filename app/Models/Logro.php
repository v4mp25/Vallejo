<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    protected $table = 'logros';

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'fecha',
        'imagen',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}
