<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('areas_curriculares', function (Blueprint $table) {
            $table->dropColumn('icono');
            $table->string('imagen')->nullable()->after('nombre');
        });

        Schema::table('proyectos_institucionales', function (Blueprint $table) {
            $table->string('link')->nullable()->after('imagen');
        });
    }

    public function down(): void
    {
        Schema::table('proyectos_institucionales', function (Blueprint $table) {
            $table->dropColumn('link');
        });

        Schema::table('areas_curriculares', function (Blueprint $table) {
            $table->dropColumn('imagen');
            $table->string('icono')->after('nombre');
        });
    }
};
