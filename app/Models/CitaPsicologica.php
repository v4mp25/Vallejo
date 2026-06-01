<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaPsicologica extends Model
{
    use HasFactory;

    // Forzamos el nombre de la tabla por si Laravel intenta pluralizarlo en inglés
    protected $table = 'citas_psicologicas';

    protected $fillable = [
        'alumno_id',
        'profesor_id',
        'psicologo_id',
        'motivo',
        'fecha_cita',
        'estado'
    ];
}