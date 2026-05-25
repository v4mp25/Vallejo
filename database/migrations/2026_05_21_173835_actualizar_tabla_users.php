<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
         $table->boolean('estado')->default(true)->after('password');
        });

      DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin', 'director', 'profesor', 'padre', 'alumno') NOT NULL DEFAULT 'alumno'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin', 'profesor', 'alumno') NOT NULL DEFAULT 'alumno'");
    }
};