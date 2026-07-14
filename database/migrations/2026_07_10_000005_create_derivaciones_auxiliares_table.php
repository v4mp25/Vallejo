<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('derivaciones_auxiliares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('profesor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('auxiliar_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('motivo');
            $table->enum('bimestre', ['B1', 'B2', 'B3', 'B4'])->default('B1');
            $table->dateTime('fecha_cita')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, citado, atendida, cancelada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('derivaciones_auxiliares');
    }
};
