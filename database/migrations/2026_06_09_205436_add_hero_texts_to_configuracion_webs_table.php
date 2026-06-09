<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_webs', function (Blueprint $table) {
            $table->string('hero_titulo')->nullable()->after('frase_topbar');
            $table->text('hero_subtitulo')->nullable()->after('hero_titulo');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_webs', function (Blueprint $table) {
            $table->dropColumn(['hero_titulo', 'hero_subtitulo']);
        });
    }
};