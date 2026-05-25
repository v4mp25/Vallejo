<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Importante no olvidar esta línea

return new class extends Migration
{
    public function up(): void
    {
        // Esto le dice a MySQL que actualice la lista de roles permitidos
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin', 'director', 'profesor', 'padre', 'alumno') NOT NULL DEFAULT 'alumno'");
    }

    public function down(): void
    {
        // Si nos arrepentimos, regresamos a como estaba antes
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin', 'profesor', 'alumno') NOT NULL DEFAULT 'alumno'");
    }
};