<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            // Año académico al que corresponde la matrícula (ej: "2026")
            $table->string('año_academico', 10)->nullable()->after('aula_id');

            // Estado de la matrícula: activo, inactivo, retirado, trasladado, etc.
            $table->string('estado', 20)->default('activo')->after('año_academico');

            // Teléfono de contacto del apoderado/padre de familia registrado en la importación
            $table->string('celular_apoderado', 20)->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn(['año_academico', 'estado', 'celular_apoderado']);
        });
    }
};
