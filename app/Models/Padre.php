<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Padre extends Model
{
    protected $fillable = [
        'user_id', 'estudiante_id', 'recibir_avisos_email'
    ];
}