<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeccionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'area_id' => Area::factory(),
            'titulo' => fake()->sentence(4),
            'nivel' => fake()->randomElement(['A1', 'A2', 'B1', 'B2', 'C1', 'C2']),
            'orden' => fake()->numberBetween(1, 20),
            'publicada' => fake()->boolean(),
        ];
    }
}
