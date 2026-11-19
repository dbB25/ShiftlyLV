<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leccion_id')
                ->constrained('lecciones')
                ->cascadeOnDelete();

            $table->string('titulo', 150);
            $table->string('tipo', 30);
            $table->text('pregunta');
            $table->jsonb('opciones')->nullable();
            $table->text('respuesta_correcta')->nullable();
            $table->text('retroalimentacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicios');
    }
};
