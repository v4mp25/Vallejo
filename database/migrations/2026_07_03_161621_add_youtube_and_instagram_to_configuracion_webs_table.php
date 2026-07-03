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
            $table->string('link_youtube')->nullable()->after('link_facebook');
            $table->string('link_instagram')->nullable()->after('link_youtube');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracion_webs', function (Blueprint $table) {
            $table->dropColumn(['link_youtube', 'link_instagram']);
        });
    }
};
