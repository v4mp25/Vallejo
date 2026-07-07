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
        Schema::table('institucion_info', function (Blueprint $table) {
            $table->json('infra_aulas_imagenes')->nullable()->after('infraestructura_descripcion');
            $table->json('infra_labs_imagenes')->nullable()->after('infra_aulas_imagenes');
            $table->json('infra_biblio_imagenes')->nullable()->after('infra_labs_imagenes');
            $table->json('infra_aip_imagenes')->nullable()->after('infra_biblio_imagenes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institucion_info', function (Blueprint $table) {
            $table->dropColumn([
                'infra_aulas_imagenes',
                'infra_labs_imagenes',
                'infra_biblio_imagenes',
                'infra_aip_imagenes'
            ]);
        });
    }
};
