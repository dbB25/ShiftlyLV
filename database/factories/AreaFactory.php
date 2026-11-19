<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'Sistemas y Software',
                'Medicina y Salud',
                'Gastronomía',
                'Derecho Corporativo',
                'Arquitectura',
                'General',
            ]),
            'slug' => fake()->unique()->slug(2),
            'descripcion' => fake()->sentence(),
            'activo' => true,
        ];
    }
}
