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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            
            // Conectamos con el alumno (que está en la tabla users)
            $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
            
            // Conectamos con el salón (tabla aulas)
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
