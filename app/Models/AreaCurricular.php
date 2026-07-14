<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaCurricular extends Model
{
    protected $table = 'areas_curriculares';

    protected $fillable = [
        'nombre',
        'imagen',
        'descripcion',
    ];
}
