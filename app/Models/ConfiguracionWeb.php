<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionWeb extends Model
{
    // Forzamos el nombre exacto de la tabla
    protected $table = 'configuracion_webs';

    protected $fillable = [
        'frase_topbar',
        'banner_inicial_url',
        'telefono_contacto',
        'correo_contacto',
        'anio_academico_actual',
    ];

    /**
     * Devuelve el año académico vigente del sistema. Si aún no existe una
     * fila de configuración, recurre al año calendario actual como respaldo.
     */
    public static function anioAcademicoActual(): string
    {
        return static::query()->value('anio_academico_actual') ?? (string) now()->year;
    }
}