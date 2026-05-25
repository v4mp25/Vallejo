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
        Schema::create('padres', function (Blueprint $table) {
            $table->id();
            // El ID del padre (que será un usuario con rol 'padre')
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // El ID del hijo (que será un usuario con rol 'alumno')
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            
            // El famoso botón de "Recibir avisos por Gmail"
            $table->boolean('recibir_avisos_email')->default(false);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padres');
    }
};
