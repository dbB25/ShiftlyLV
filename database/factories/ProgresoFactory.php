<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Leccion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgresoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'leccion_id' => Leccion::factory(),
            'puntaje' => fake()->randomFloat(2, 0, 100),
            'completado' => fake()->boolean(),
        ];
    }
}
