<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')
                ->constrained('areas')
                ->restrictOnDelete();

            $table->string('titulo', 150);
            $table->string('nivel', 2);
            $table->smallInteger('orden');
            $table->boolean('publicada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecciones');
    }
};
