<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Año académico por defecto para archivar la carga docente existente
     * (registros creados antes de que existiera esta columna).
     */
    private const ANIO_HISTORICO_POR_DEFECTO = '2026';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asignacions', function (Blueprint $table) {
            $table->string('año_academico', 10)->nullable()->after('curso_id');
        });

        // Archivamos la carga académica ya existente bajo el año histórico,
        // para no perder el registro de qué profesor dictó qué curso.
        DB::table('asignacions')
            ->whereNull('año_academico')
            ->update(['año_academico' => self::ANIO_HISTORICO_POR_DEFECTO]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignacions', function (Blueprint $table) {
            $table->dropColumn('año_academico');
        });
    }
};
