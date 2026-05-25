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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            
            // 1. ¿A qué alumno se le pasa lista?
            $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
            
            // 2. ¿En qué clase/curso/aula exacta? (Usamos tu asignación)
            $table->foreignId('asignacion_id')->constrained('asignacions')->onDelete('cascade');
            
            // 3. La fecha del día de la clase
            $table->date('fecha');
            
            // 4. El estado de la asistencia (Ej: 'asistio', 'falta', 'tardanza', 'justificado')
            $table->string('estado');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};