<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganoDocumento extends Model
{
    protected $table = 'organo_documentos';

    protected $fillable = [
        'organo',
        'titulo',
        'archivo_pdf',
        'link',
    ];
}
