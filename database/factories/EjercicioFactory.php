<?php

namespace Database\Factories;

use App\Models\Leccion;
use Illuminate\Database\Eloquent\Factories\Factory;

class EjercicioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'leccion_id' => Leccion::factory(),
            'tipo' => 'opcion_multiple',
            'pregunta' => fake()->sentence() . '?',
            'opciones' => [
                'A' => fake()->word(),
                'B' => fake()->word(),
                'C' => fake()->word(),
                'D' => fake()->word(),
            ],
            'respuesta' => 'A',
        ];
    }
}
