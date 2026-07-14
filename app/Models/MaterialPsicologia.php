<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPsicologia extends Model
{
    use HasFactory;

    protected $table = 'materiales_psicologia';

    protected $fillable = [
        'psicologo_id',
        'titulo',
        'descripcion',
        'enlace',
        'imagen',
        'fecha_limite',
        'bimestre',
    ];

    public function psicologo()
    {
        return $this->belongsTo(User::class, 'psicologo_id');
    }

    public function aulas()
    {
        return $this->belongsToMany(Aula::class, 'aula_material_psicologia', 'material_psicologia_id', 'aula_id');
    }
}
