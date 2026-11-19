<?php

namespace Database\Factories;

use App\Models\Leccion;
use Illuminate\Database\Eloquent\Factories\Factory;

class EjercicioFactory extends Factory
{
    public function definition(): array
    {
        $opciones = [fake()->word(), fake()->word(), fake()->word(), fake()->word()];

        return [
            'leccion_id' => Leccion::factory(),
            'titulo' => fake()->sentence(3),
            'tipo' => 'opcion_multiple',
            'pregunta' => fake()->sentence() . '?',
            'opciones' => $opciones,
            'respuesta_correcta' => $opciones[0],
            'retroalimentacion' => fake()->sentence(),
        ];
    }
}
