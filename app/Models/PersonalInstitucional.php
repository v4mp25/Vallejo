<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalInstitucional extends Model
{
    protected $table = 'personal_institucional';

    protected $fillable = [
        'nombres',
        'apellidos',
        'cargo',
        'categoria',
        'foto',
    ];
}
