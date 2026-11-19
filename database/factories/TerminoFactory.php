<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerminoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'area_id' => Area::factory(),
            'termino' => fake()->unique()->word(),
            'traduccion' => fake()->word(),
            'ejemplo' => fake()->sentence(),
        ];
    }
}
