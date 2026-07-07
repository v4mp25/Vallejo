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
        Schema::create('gestion_institucional', function (Blueprint $table) {
            $table->id();
            $table->text('conei_descripcion')->nullable();
            $table->text('apafa_descripcion')->nullable();
            $table->string('organigrama_imagen')->nullable();
            $table->timestamps();
        });

        Schema::create('personal_institucional', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('cargo');
            $table->string('categoria'); // directivo, docente, administrativo
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('documentos_gestion', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('archivo_pdf');
            $table->string('tipo'); // PEI, PAT, PCI, RI, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_gestion');
        Schema::dropIfExists('personal_institucional');
        Schema::dropIfExists('gestion_institucional');
    }
};
