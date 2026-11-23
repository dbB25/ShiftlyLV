<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Una fila por usuario y carrera: es la ruta que el alumno va avanzando.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progreso_rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('carrera', 30);
            // Examen de ubicación: lección desde la que empezó el alumno
            $table->unsignedSmallInteger('ubicacion_leccion')->nullable();
            $table->timestamp('ubicacion_fecha')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'carrera']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_rutas');
    }
};
