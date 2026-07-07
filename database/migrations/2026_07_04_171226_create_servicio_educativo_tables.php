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
        Schema::create('servicio_educativo', function (Blueprint $table) {
            $table->id();
            $table->text('nivel_secundaria')->nullable();
            $table->text('enfoque_competencias')->nullable();
            $table->text('innovacion_pedagogica')->nullable();
            $table->text('tutoria_orientacion')->nullable();
            $table->text('educacion_inclusiva')->nullable();
            $table->timestamps();
        });

        Schema::create('areas_curriculares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('icono');
            $table->text('descripcion');
            $table->timestamps();
        });

        Schema::create('proyectos_institucionales', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('imagen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos_institucionales');
        Schema::dropIfExists('areas_curriculares');
        Schema::dropIfExists('servicio_educativo');
    }
};
