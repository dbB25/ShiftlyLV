<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

/**
 * Tabla areas: las 6 áreas profesionales.
 */
class AreaSeeder extends Seeder
{
    public const AREAS = [
        ['nombre' => 'Sistemas y Software', 'slug' => 'sistemas-software'],
        ['nombre' => 'Medicina y Salud', 'slug' => 'medicina-salud'],
        ['nombre' => 'Gastronomía', 'slug' => 'gastronomia'],
        ['nombre' => 'Derecho Corporativo', 'slug' => 'derecho-corporativo'],
        ['nombre' => 'Arquitectura', 'slug' => 'arquitectura'],
        ['nombre' => 'General', 'slug' => 'general'],
    ];

    public function run(): void
    {
        foreach (self::AREAS as $datos) {
            Area::firstOrCreate(
                ['slug' => $datos['slug']],
                [
                    'nombre' => $datos['nombre'],
                    'descripcion' => 'Área de aprendizaje de '.$datos['nombre'],
                    'activo' => true,
                ]
            );
        }
    }
}
