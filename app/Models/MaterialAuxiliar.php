<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialAuxiliar extends Model
{
    use HasFactory;

    protected $table = 'materiales_auxiliar';

    protected $fillable = [
        'auxiliar_id',
        'titulo',
        'descripcion',
        'enlace',
        'imagen',
        'fecha_limite',
        'bimestre',
    ];

    public function auxiliar()
    {
        return $this->belongsTo(User::class, 'auxiliar_id');
    }

    public function aulas()
    {
        return $this->belongsToMany(Aula::class, 'aula_material_auxiliar', 'material_auxiliar_id', 'aula_id');
    }
}
