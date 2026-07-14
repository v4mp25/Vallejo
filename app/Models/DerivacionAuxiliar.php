<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DerivacionAuxiliar extends Model
{
    use HasFactory;

    protected $table = 'derivaciones_auxiliares';

    protected $fillable = [
        'alumno_id',
        'profesor_id',
        'auxiliar_id',
        'motivo',
        'bimestre',
        'fecha_cita',
        'estado'
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function auxiliar()
    {
        return $this->belongsTo(User::class, 'auxiliar_id');
    }
}
