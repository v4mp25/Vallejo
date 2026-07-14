<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('virtual_materials', function (Blueprint $table) {
            $table->enum('bimestre', ['B1', 'B2', 'B3', 'B4'])->default('B1')->after('curso_id');
            $table->enum('classification', ['material', 'tarea'])->default('material')->after('bimestre');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_materials', function (Blueprint $table) {
            $table->dropColumn(['bimestre', 'classification']);
        });
    }
};
