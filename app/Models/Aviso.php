<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    protected $fillable = [
        'titulo', 'contenido', 'imagen_path', 'publicado_at', 'creado_por', 'curso_id'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}