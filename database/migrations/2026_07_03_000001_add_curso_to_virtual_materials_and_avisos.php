<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('virtual_materials', function (Blueprint $table) {
            $table->foreignId('curso_id')->nullable()->after('user_id')->constrained('cursos')->nullOnDelete();
        });

        Schema::table('avisos', function (Blueprint $table) {
            if (! Schema::hasColumn('avisos', 'curso_id')) {
                $table->foreignId('curso_id')->nullable()->after('creado_por')->constrained('cursos')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('virtual_materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('curso_id');
        });

        Schema::table('avisos', function (Blueprint $table) {
            if (Schema::hasColumn('avisos', 'curso_id')) {
                $table->dropConstrainedForeignId('curso_id');
            }
        });
    }
};
