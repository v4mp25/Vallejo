<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas_psicologicas', function (Blueprint $table) {
            $table->enum('bimestre', ['B1', 'B2', 'B3', 'B4'])->default('B1')->after('motivo');
        });
    }

    public function down(): void
    {
        Schema::table('citas_psicologicas', function (Blueprint $table) {
            $table->dropColumn('bimestre');
        });
    }
};
