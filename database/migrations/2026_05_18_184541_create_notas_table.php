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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            
            // 1. ¿De qué alumno es la nota?
            $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
            
            // 2. ¿En qué clase exacta? (Esto enlaza el curso, el profe y el aula de golpe)
            $table->foreignId('asignacion_id')->constrained('asignacions')->onDelete('cascade');
            
            // 3. El periodo (Ej: 'Trimestre 1', 'Trimestre 2')
            $table->string('periodo');
            
            // 4. ¿De qué es la nota? (Ej: 'Examen Parcial', 'Práctica 1', 'Cuaderno')
            $table->string('criterio');
            
            // 5. La nota final. Le ponemos string para que aguante un "18" o un "AD"
            $table->string('calificacion');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
