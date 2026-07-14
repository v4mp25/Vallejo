<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comunidad_textos', function (Blueprint $table) {
            $table->renameColumn('cronograma_notas_pdf', 'cronograma_notes_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('comunidad_textos', function (Blueprint $table) {
            $table->renameColumn('cronograma_notes_pdf', 'cronograma_notas_pdf');
        });
    }
};
