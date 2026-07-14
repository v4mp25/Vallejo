<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materiales_psicologia', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('enlace');
            $table->dateTime('fecha_limite')->nullable()->after('imagen');
        });
    }

    public function down(): void
    {
        Schema::table('materiales_psicologia', function (Blueprint $table) {
            $table->dropColumn(['imagen', 'fecha_limite']);
        });
    }
};
