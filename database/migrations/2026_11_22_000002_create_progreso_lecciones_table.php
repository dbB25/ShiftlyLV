<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Avance del alumno en cada lección de una ruta: si la completó, el XP que
 * ganó y el resultado de su última ronda de ejercicios.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progreso_lecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progreso_ruta_id')->constrained('progreso_rutas')->cascadeOnDelete();
            // ID de la lección del catálogo ("sistemas-01") o de una personalizada ("p-12")
            $table->string('leccion_clave', 60);
            $table->boolean('completada')->default(false);
            $table->unsignedSmallInteger('orden')->nullable();
            $table->unsignedInteger('xp')->default(0);

            // Resultado de la última ronda de ejercicios de la lección
            $table->unsignedSmallInteger('aciertos_primer_intento')->nullable();
            $table->unsignedSmallInteger('total_ejercicios')->nullable();
            $table->unsignedTinyInteger('vidas')->nullable();
            $table->unsignedInteger('duracion_seg')->nullable();
            $table->string('fuente', 20)->nullable();
            $table->timestamp('practicada_en')->nullable();
            $table->timestamps();

            $table->unique(['progreso_ruta_id', 'leccion_clave']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_lecciones');
    }
};
