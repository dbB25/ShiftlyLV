<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terminos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')
                ->constrained('areas')
                ->cascadeOnDelete();

            $table->string('termino', 120);
            $table->string('traduccion', 255);
            $table->text('ejemplo')->nullable();
            $table->timestamps();

            $table->unique(['area_id', 'termino']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terminos');
    }
};
