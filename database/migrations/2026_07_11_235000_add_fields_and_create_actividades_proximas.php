<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add fields to noticias
        Schema::table('noticias', function (Blueprint $table) {
            $table->date('fecha_limite')->nullable()->after('imagen');
        });

        // 2. Add fields and modify columns of comunicados
        Schema::table('comunicados', function (Blueprint $table) {
            $table->string('archivo_pdf')->nullable()->change();
            $table->text('contenido')->nullable()->after('titulo');
            $table->date('fecha_limite')->nullable()->after('archivo_pdf');
        });

        // 3. Add fields to agenda_escolar
        Schema::table('agenda_escolar', function (Blueprint $table) {
            $table->date('fecha_limite')->nullable()->after('lugar');
        });

        // 4. Add fields to boletines
        Schema::table('boletines', function (Blueprint $table) {
            $table->date('fecha_limite')->nullable()->after('archivo_pdf');
        });

        // 5. Create actividades_proximas table
        Schema::create('actividades_proximas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->date('fecha_limite')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades_proximas');

        Schema::table('boletines', function (Blueprint $table) {
            $table->dropColumn('fecha_limite');
        });

        Schema::table('agenda_escolar', function (Blueprint $table) {
            $table->dropColumn('fecha_limite');
        });

        Schema::table('comunicados', function (Blueprint $table) {
            $table->dropColumn(['contenido', 'fecha_limite']);
            $table->string('archivo_pdf')->nullable(false)->change();
        });

        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn('fecha_limite');
        });
    }
};
