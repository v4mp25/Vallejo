<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_webs', function (Blueprint $table) {
            $table->string('link_facebook')->nullable()->after('correo_contacto');
            $table->text('link_maps')->nullable()->after('link_facebook');
            $table->string('direccion_texto')->nullable()->after('link_maps');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_webs', function (Blueprint $table) {
            $table->dropColumn(['link_facebook', 'link_maps', 'direccion_texto']);
        });
    }
};