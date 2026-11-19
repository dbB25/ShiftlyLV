<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('leccion_id')
                ->constrained('lecciones')
                ->cascadeOnDelete();

            $table->decimal('puntaje', 5, 2)->default(0);
            $table->boolean('completado')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'leccion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progresos');
    }
};
