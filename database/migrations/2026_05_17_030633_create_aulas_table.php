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
    Schema::create('aulas', function (Blueprint $table) {
        $table->id();
        $table->string('grado');  // Ej: "1", "2", "3"
        $table->string('seccion'); // Ej: "A", "B"
        $table->string('nivel')->nullable(); // Primaria o Secundaria (puede ser vacío por ahora)
        $table->string('turno')->nullable(); // Mañana o Tarde
        
        // Llave foránea para el tutor (conecta con la tabla users)
        $table->foreignId('tutor_id')->nullable()->constrained('users')->onDelete('set null');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
