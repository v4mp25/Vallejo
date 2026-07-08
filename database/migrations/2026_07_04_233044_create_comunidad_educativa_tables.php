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
        Schema::create('comunidad_textos', function (Blueprint $table) {
            $table->id();
            $table->text('estudiantes_texto')->nullable();
            $table->text('padres_texto')->nullable();
            $table->text('exalumnos_texto')->nullable();
            $table->string('reglamento_pdf')->nullable();
            $table->string('cronograma_notas_pdf')->nullable();
            $table->timestamps();
        });

        Schema::create('aliados_estrategicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('logo');
            $table->string('enlace_web')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aliados_estrategicos');
        Schema::dropIfExists('comunidad_textos');
    }
};
