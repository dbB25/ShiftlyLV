<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * XP y prácticas por día: alimenta la racha, la meta diaria y las insignias.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_diaria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha');
            $table->unsignedInteger('xp')->default(0);
            $table->unsignedInteger('practicas')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'fecha']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('meta_diaria')->default(30);
            $table->unsignedTinyInteger('mejor_pronunciacion')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['meta_diaria', 'mejor_pronunciacion']);
        });

        Schema::dropIfExists('actividad_diaria');
    }
};
