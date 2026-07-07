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
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('contenido');
            $table->date('fecha');
            $table->string('imagen');
            $table->timestamps();
        });

        Schema::create('comunicados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->date('fecha');
            $table->string('archivo_pdf');
            $table->timestamps();
        });

        Schema::create('agenda_escolar', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('lugar');
            $table->timestamps();
        });

        Schema::create('boletines', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('mes_anio');
            $table->string('archivo_pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletines');
        Schema::dropIfExists('agenda_escolar');
        Schema::dropIfExists('comunicados');
        Schema::dropIfExists('noticias');
    }
};
