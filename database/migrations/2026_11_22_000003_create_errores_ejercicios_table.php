<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ejercicios que el alumno falló, para el modo de repaso.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('errores_ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progreso_ruta_id')->constrained('progreso_rutas')->cascadeOnDelete();
            $table->string('leccion_clave', 60);
            $table->string('leccion_titulo', 200)->nullable();
            // Firma del ejercicio (lección|tipo|texto) y su hash para el índice único
            $table->text('clave');
            $table->char('clave_hash', 40);
            $table->json('ejercicio');
            $table->timestamp('fallado_en')->nullable();
            $table->timestamps();

            $table->unique(['progreso_ruta_id', 'clave_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('errores_ejercicios');
    }
};
