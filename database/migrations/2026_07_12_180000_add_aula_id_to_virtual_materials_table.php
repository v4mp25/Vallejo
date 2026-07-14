<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Agrega `aula_id` a `virtual_materials`.
     *
     * Bug que corrige: el material/tareas del aula virtual solo se filtraba
     * por `curso_id`, así que un profesor que dicta el mismo curso en más de
     * un salón (ej. Educación Física en 4°A y 2°A) veía el mismo material
     * "cruzado" entre ambos salones.
     *
     * Backfill de datos existentes: para cada material ya publicado,
     * intentamos deducir su aula buscando en `asignacions` (profesor_id +
     * curso_id). Si el profesor dicta ese curso en un único salón, se lo
     * asignamos automáticamente. Si lo dicta en más de un salón, no podemos
     * adivinar cuál era el correcto, así que se deja en null (y ese material
     * seguirá viéndose en todos esos salones hasta que el profesor lo
     * revise/reasigne manualmente).
     */
    public function up(): void
    {
        Schema::table('virtual_materials', function (Blueprint $table) {
            $table->foreignId('aula_id')->nullable()->after('curso_id')->constrained('aulas')->nullOnDelete();
        });

        $materiales = DB::table('virtual_materials')->whereNotNull('curso_id')->get(['id', 'user_id', 'curso_id']);

        foreach ($materiales as $material) {
            $aulasPosibles = DB::table('asignacions')
                ->where('profesor_id', $material->user_id)
                ->where('curso_id', $material->curso_id)
                ->pluck('aula_id')
                ->unique();

            if ($aulasPosibles->count() === 1) {
                DB::table('virtual_materials')
                    ->where('id', $material->id)
                    ->update(['aula_id' => $aulasPosibles->first()]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('aula_id');
        });
    }
};
