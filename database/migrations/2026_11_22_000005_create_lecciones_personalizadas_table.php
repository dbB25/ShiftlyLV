<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lecciones que crea cada alumno al final del árbol de su carrera (CRUD del usuario).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecciones_personalizadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('carrera', 30);
            $table->string('titulo', 60);
            $table->string('descripcion', 240)->nullable();
            // Términos clave: [{ "en": "...", "es": "..." }]
            $table->json('vocabulario');
            $table->unsignedSmallInteger('xp')->default(20);
            $table->string('forma', 40);
            $table->string('icono', 60);
            $table->timestamps();

            $table->index(['user_id', 'carrera']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecciones_personalizadas');
    }
};
