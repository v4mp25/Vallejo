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
    Schema::create('citas_psicologicas', function (Blueprint $table) {
        $table->id();
        
        // Conexiones con la tabla users (alumno, profe que avisa, y psicólogo)
        $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('profesor_id')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('psicologo_id')->nullable()->constrained('users')->onDelete('set null');
        
        $table->text('motivo');
        // Queda nullable porque cuando el profe deriva, él no sabe cuándo será la cita
        $table->dateTime('fecha_cita')->nullable(); 
        $table->string('estado')->default('pendiente'); // pendiente, atendida, cancelada
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cita_psicologicas');
    }
};
