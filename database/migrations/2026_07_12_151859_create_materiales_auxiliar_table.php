<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiales_auxiliar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auxiliar_id')->constrained('users')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('enlace')->nullable();
            $table->string('imagen')->nullable();
            $table->dateTime('fecha_limite')->nullable();
            $table->enum('bimestre', ['I Bimestre', 'II Bimestre', 'III Bimestre', 'IV Bimestre']);
            $table->timestamps();
        });

        Schema::create('aula_material_auxiliar', function (Blueprint $table) {
            $table->foreignId('aula_id')->constrained('aulas')->cascadeOnDelete();
            $table->foreignId('material_auxiliar_id')->constrained('materiales_auxiliar')->cascadeOnDelete();
            $table->primary(['aula_id', 'material_auxiliar_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aula_material_auxiliar');
        Schema::dropIfExists('materiales_auxiliar');
    }
};
