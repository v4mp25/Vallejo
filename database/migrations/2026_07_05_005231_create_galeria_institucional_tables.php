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
        Schema::create('galeria_fotografias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('imagen');
            $table->timestamps();
        });

        Schema::create('galeria_videos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('url_video');
            $table->timestamps();
        });

        Schema::create('galeria_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->string('imagen');
            $table->timestamps();
        });

        Schema::create('galeria_actividades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->string('imagen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeria_actividades');
        Schema::dropIfExists('galeria_eventos');
        Schema::dropIfExists('galeria_videos');
        Schema::dropIfExists('galeria_fotografias');
    }
};
