<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Agrega el valor 'egresado' a la columna `rol` de `users`.
     *
     * Esta columna ha tenido una historia irregular en el proyecto: la
     * migración original la crea como ENUM, pero en la base de datos real
     * ya se le han agregado roles (psicologo, auxiliar, secretaria,
     * administrativo, directivo, director) que nunca estuvieron en ningún
     * ENUM migrado — lo que indica que en algún momento la columna fue
     * convertida a VARCHAR fuera de las migraciones (o el ENUM real no
     * coincide con lo que asumían las migraciones).
     *
     * Por eso esta migración primero verifica el tipo REAL de la columna:
     *   - Si es ENUM, agrega 'egresado' preservando todos los valores
     *     existentes (sin asumir una lista fija).
     *   - Si NO es ENUM (ej. VARCHAR), no hace falta alterar nada: cualquier
     *     texto, incluido 'egresado', ya cabe sin problema.
     */
    public function up(): void
    {
        $columna = DB::selectOne("SHOW COLUMNS FROM users WHERE Field = 'rol'");

        if (!$columna) {
            throw new \RuntimeException("No se encontró la columna users.rol; aborta la migración por seguridad.");
        }

        if (!preg_match("/^enum\((.*)\)$/i", $columna->Type, $coincidencias)) {
            // La columna no es ENUM (ej. varchar/string): no requiere alteración,
            // ya acepta cualquier valor de texto incluido 'egresado'.
            return;
        }

        $this->agregarValorAlEnum($coincidencias[1], 'egresado');
    }

    /**
     * Reverse the migrations.
     *
     * No se elimina 'egresado' del ENUM al revertir: si ya existen alumnos
     * marcados como egresados, quitar el valor rompería esos registros.
     * Esta migración se considera de solo avance (no reversible de forma segura).
     */
    public function down(): void
    {
        // Intencionalmente vacío. Ver comentario de la clase.
    }

    private function agregarValorAlEnum(string $definicionEnum, string $nuevoValor): void
    {
        $valoresActuales = array_map(
            fn (string $valor) => trim(trim($valor), "'"),
            str_getcsv($definicionEnum, ',', "'")
        );

        if (!in_array($nuevoValor, $valoresActuales, true)) {
            $valoresActuales[] = $nuevoValor;
        }

        $listaEnum = implode(',', array_map(
            fn (string $valor) => "'" . str_replace("'", "''", $valor) . "'",
            $valoresActuales
        ));

        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM({$listaEnum}) NOT NULL DEFAULT 'alumno'");
    }
};
