<?php

namespace Database\Seeders;

use App\Models\LeccionPersonalizada;
use Illuminate\Database\Seeder;

/**
 * Tabla lecciones_personalizadas: lecciones que el alumno de prueba creó
 * al final de sus árboles. Las formas e íconos son válidos para cada ruta.
 */
class LeccionPersonalizadaSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = UsuarioSeeder::usuario();

        $lecciones = [
            [
                'carrera' => 'sistemas',
                'titulo' => 'Entrevista técnica en inglés',
                'descripcion' => 'Practica respuestas para una entrevista de desarrollador junior.',
                'vocabulario' => [
                    ['en' => 'deadline', 'es' => 'fecha límite'],
                    ['en' => 'teamwork', 'es' => 'trabajo en equipo'],
                    ['en' => 'code review', 'es' => 'revisión de código'],
                ],
                'xp' => 30,
                'forma' => 'decision',
                'icono' => 'bi-code-slash',
            ],
            [
                'carrera' => 'sistemas',
                'titulo' => 'Reportar un bug',
                'descripcion' => 'Describe un error: qué pasó, qué esperabas y cómo reproducirlo.',
                'vocabulario' => [
                    ['en' => 'bug', 'es' => 'error'],
                    ['en' => 'steps to reproduce', 'es' => 'pasos para reproducir'],
                ],
                'xp' => 20,
                'forma' => 'proceso',
                'icono' => 'bi-bug',
            ],
            [
                'carrera' => 'medicina',
                'titulo' => 'Explicar una receta al paciente',
                'descripcion' => 'Indica dosis, horario y efectos secundarios con palabras sencillas.',
                'vocabulario' => [
                    ['en' => 'dose', 'es' => 'dosis'],
                    ['en' => 'side effects', 'es' => 'efectos secundarios'],
                    ['en' => 'twice a day', 'es' => 'dos veces al día'],
                ],
                'xp' => 20,
                'forma' => 'capsula',
                'icono' => 'bi-prescription2',
            ],
        ];

        foreach ($lecciones as $datos) {
            LeccionPersonalizada::updateOrCreate(
                ['user_id' => $usuario->id, 'carrera' => $datos['carrera'], 'titulo' => $datos['titulo']],
                $datos
            );
        }
    }
}
