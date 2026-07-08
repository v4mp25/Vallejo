<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institucion_info', function (Blueprint $table) {
            $table->id();

            // 2.1 Reseña Histórica
            $table->text('resena_historica')->nullable();

            // 2.2 Identidad Institucional
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->text('valores')->nullable();      // texto libre, ej: "Respeto, Honestidad, ..."
            $table->text('principios')->nullable();
            $table->string('lema')->nullable();

            // 2.3 Símbolos Institucionales
            $table->text('letra_himno')->nullable();
            $table->string('uniforme_descripcion')->nullable();
            $table->string('uniforme_imagen')->nullable(); // ruta en storage

            // 2.4 Infraestructura
            $table->text('infraestructura_descripcion')->nullable();

            // 2.5 Línea de Tiempo (JSON: [{anio, evento}])
            $table->json('linea_tiempo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institucion_info');
    }
};
