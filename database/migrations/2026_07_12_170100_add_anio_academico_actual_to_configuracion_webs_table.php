<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ANIO_ACTUAL_POR_DEFECTO = '2026';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('configuracion_webs', function (Blueprint $table) {
            // Año académico "vigente" del sistema. Los dashboards y reportes
            // deben usar este valor como referencia del año en curso.
            $table->string('anio_academico_actual', 10)
                ->default(self::ANIO_ACTUAL_POR_DEFECTO)
                ->after('correo_contacto');
        });

        // Si ya existe una fila de configuración (singleton), aseguramos que
        // tenga un valor explícito en vez de depender solo del default de columna.
        DB::table('configuracion_webs')->update([
            'anio_academico_actual' => self::ANIO_ACTUAL_POR_DEFECTO,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracion_webs', function (Blueprint $table) {
            $table->dropColumn('anio_academico_actual');
        });
    }
};
