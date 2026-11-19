<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Area;
use App\Models\Leccion;
use App\Models\Termino;
use App\Models\Ejercicio;
use App\Models\Progreso;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o recuperar usuario de prueba
        $usuario = User::firstOrCreate(
            ['email' => 'prueba@shiftly.com'],
            [
                'name' => 'Usuario de prueba',
                'apellido' => 'Shiftly',
                'carrera' => 'Sistemas y Software',
                'password' => bcrypt('Password123'),
            ]
        );

        // Definir las áreas de aprendizaje
        $areas = [
            [
                'nombre' => 'Sistemas y Software',
                'slug' => 'sistemas-software',
            ],
            [
                'nombre' => 'Medicina y Salud',
                'slug' => 'medicina-salud',
            ],
            [
                'nombre' => 'Gastronomía',
                'slug' => 'gastronomia',
            ],
            [
                'nombre' => 'Derecho Corporativo',
                'slug' => 'derecho-corporativo',
            ],
            [
                'nombre' => 'Arquitectura',
                'slug' => 'arquitectura',
            ],
            [
                'nombre' => 'General',
                'slug' => 'general',
            ],
        ];

        foreach ($areas as $datos) {

            // Crear o recuperar el área
            $area = Area::firstOrCreate(
                ['slug' => $datos['slug']],
                [
                    'nombre' => $datos['nombre'],
                    'descripcion' => 'Área de aprendizaje de ' . $datos['nombre'],
                    'activo' => true,
                ]
            );

            // Crear o recuperar una lección
            $leccion = Leccion::firstOrCreate(
                [
                    'area_id' => $area->id,
                    'titulo' => 'Introducción a ' . $area->nombre,
                ],
                [
                    'nivel' => 'A1',
                    'orden' => 1,
                    'publicada' => true,
                ]
            );

            // Crear o recuperar un término del glosario
            Termino::firstOrCreate(
                [
                    'area_id' => $area->id,
                    'termino' => 'Vocabulary',
                ],
                [
                    'traduccion' => 'Vocabulario',
                    'ejemplo' => 'Learn new vocabulary every day.',
                ]
            );

            // Crear o recuperar un ejercicio
            Ejercicio::firstOrCreate(
                [
                    'leccion_id' => $leccion->id,
                    'pregunta' => 'What does Hello mean?',
                ],
                [
                    'tipo' => 'opcion_multiple',
                    'opciones' => [
                        'A' => 'Hola',
                        'B' => 'Adiós',
                        'C' => 'Gracias',
                        'D' => 'Por favor',
                    ],
                    'respuesta' => 'A',
                ]
            );

            // Crear o recuperar el progreso del usuario
            Progreso::firstOrCreate(
                [
                    'user_id' => $usuario->id,
                    'leccion_id' => $leccion->id,
                ],
                [
                    'puntaje' => 100,
                    'completado' => true,
                ]
            );
        }
    }
}
