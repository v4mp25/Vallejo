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
            //
        });
    }
};
