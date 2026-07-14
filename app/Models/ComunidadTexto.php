<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComunidadTexto extends Model
{
    protected $table = 'comunidad_textos';

    protected $fillable = [
        'estudiantes_texto',
        'padres_texto',
        'exalumnos_texto',
        'reglamento_pdf',
        'cronograma_notes_pdf',
    ];
}
