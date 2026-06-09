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
    Schema::table('configuracion_webs', function (Blueprint $table) {
        $table->string('logo_url')->nullable()->after('banner_inicial_url');
    });
}

public function down(): void
{
    Schema::table('configuracion_webs', function (Blueprint $table) {
        $table->dropColumn('logo_url');
    });
}
};
