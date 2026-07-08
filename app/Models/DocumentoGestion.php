<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoGestion extends Model
{
    protected $table = 'documentos_gestion';

    protected $fillable = [
        'titulo',
        'archivo_pdf',
        'tipo',
    ];
}
