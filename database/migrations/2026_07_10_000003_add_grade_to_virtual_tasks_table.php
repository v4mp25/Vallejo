<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('virtual_tasks', function (Blueprint $table) {
            $table->string('grade', 10)->nullable()->after('submission_text');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_tasks', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
    }
};
