<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Resultados del examen diagnóstico de cada alumno (historial de intentos).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nivel', 10);
            $table->string('carrera', 80)->nullable();
            $table->unsignedSmallInteger('aciertos');
            $table->unsignedSmallInteger('total');
            // Resultado completo (incluye la revisión pregunta por pregunta)
            $table->json('resultado');
            $table->timestamp('realizado_en');
            $table->timestamps();

            $table->unique(['user_id', 'realizado_en']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
