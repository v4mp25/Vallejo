<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_webs', function (Blueprint $table) {
            $table->id();
            // Campos dinámicos para la página de inicio
            $table->string('frase_topbar')->default('Formamos líderes con corazón vallejiano');
            $table->string('banner_inicial_url')->nullable(); // Guardará la ruta de la foto/video principal
            
            // Campos de contacto por si también quieren cambiarlos en el futuro
            $table->string('telefono_contacto')->default('+51 927 736 128');
            $table->string('correo_contacto')->default('contacto@cesarvallejo.edu.pe');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_webs');
    }
};