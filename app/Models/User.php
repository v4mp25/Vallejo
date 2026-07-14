<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombres', 'apellidos', 'codigo_usuario',
        'celular', 'fecha_nacimiento', 'rol', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

   
  
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'profesor_id');
    }


    public function aulasTutoria()
    {
        return $this->hasMany(Aula::class, 'tutor_id');
    }

   
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'alumno_id');
    }

  
    public function notas()
    {
        return $this->hasMany(Nota::class, 'alumno_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'alumno_id');
    }

    public function virtualMaterials()
    {
        return $this->hasMany(VirtualMaterial::class, 'user_id');
    }
}
