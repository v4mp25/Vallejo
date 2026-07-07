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
        if (Schema::hasTable('aulas') && ! Schema::hasColumn('aulas', 'tutor_id')) {
            Schema::table('aulas', function (Blueprint $table) {
                // Agregamos la columna tutor_id. Le ponemos nullable() por si 
                // algún salón momentáneamente se queda sin tutor.
                $table->unsignedBigInteger('tutor_id')->nullable()->after('turno');
            });
        }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aulas', function (Blueprint $table) {
            if (Schema::hasColumn('aulas', 'tutor_id')) {
                $table->dropColumn('tutor_id');
            }
