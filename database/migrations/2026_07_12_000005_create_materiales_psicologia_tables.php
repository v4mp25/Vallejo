<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiales_psicologia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('psicologo_id');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('enlace')->nullable();
            $table->string('bimestre'); // 'I Bimestre', 'II Bimestre', 'III Bimestre', 'IV Bimestre'
            $table->timestamps();

            $table->foreign('psicologo_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('aula_material_psicologia', function (Blueprint $table) {
            $table->unsignedBigInteger('aula_id');
            $table->unsignedBigInteger('material_psicologia_id');

            $table->foreign('aula_id')->references('id')->on('aulas')->onDelete('cascade');
            $table->foreign('material_psicologia_id')->references('id')->on('materiales_psicologia')->onDelete('cascade');

            $table->primary(['aula_id', 'material_psicologia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aula_material_psicologia');
        Schema::dropIfExists('materiales_psicologia');
    }
};
