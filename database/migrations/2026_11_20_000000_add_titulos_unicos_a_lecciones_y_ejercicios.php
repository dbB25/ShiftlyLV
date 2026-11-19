<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Evita títulos repetidos: una lección no puede repetirse dentro de su área
 * y un ejercicio no puede repetirse dentro de su lección.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lecciones', function (Blueprint $table) {
            $table->unique(['area_id', 'titulo']);
        });

        Schema::table('ejercicios', function (Blueprint $table) {
            $table->unique(['leccion_id', 'titulo']);
        });
    }

    public function down(): void
    {
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->dropUnique(['leccion_id', 'titulo']);
        });

        Schema::table('lecciones', function (Blueprint $table) {
            $table->dropUnique(['area_id', 'titulo']);
        });
    }
};
