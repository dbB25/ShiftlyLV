<?php

namespace Database\Seeders;

use App\Models\Diagnostico;
use Illuminate\Database\Seeder;

/**
 * Tabla diagnosticos: dos intentos del examen; el más reciente (B1) es el que
 * usan el dashboard, /resultado y las recomendaciones.
 * Los textos de cada nivel son los de public/js/diagnostico.js.
 */
class DiagnosticoSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = UsuarioSeeder::usuario();

        $intentos = [
            [
                'hace' => 7,
                'aciertos' => 1,
                'nivel' => 'A2',
                'nombre' => 'Básico (A2)',
                'descripcion' => 'Puedes comunicarte en tareas sencillas y comprender instrucciones frecuentes dentro de tu área profesional.',
                'recomendacion' => 'Refuerza los tiempos verbales simples (pasado y futuro) y practica el glosario inicial de tu carrera.',
            ],
            [
                'hace' => 3,
                'aciertos' => 2,
                'nivel' => 'B1',
                'nombre' => 'Intermedio (B1)',
                'descripcion' => 'Comprendes las ideas principales de manuales claros y puedes desenvolverte en situaciones laborales habituales.',
                'recomendacion' => 'Avanza hacia las lecciones de comunicación operativa, reportes técnicos y comprensión auditiva.',
            ],
        ];

        foreach ($intentos as $intento) {
            $fecha = now()->subDays($intento['hace'])->setTime(17, 0);
            $resultado = [
                'nivel' => $intento['nivel'],
                'nombre' => $intento['nombre'],
                'descripcion' => $intento['descripcion'],
                'recomendacion' => $intento['recomendacion'],
                'carrera' => 'Sistemas y Software',
                'aciertos' => $intento['aciertos'],
                'total' => 4,
                'revision' => [],
                'fecha' => $fecha->toIso8601String(),
            ];

            Diagnostico::updateOrCreate(
                ['user_id' => $usuario->id, 'realizado_en' => $fecha],
                [
                    'nivel' => $intento['nivel'],
                    'carrera' => 'Sistemas y Software',
                    'aciertos' => $intento['aciertos'],
                    'total' => 4,
                    'resultado' => $resultado,
                ]
            );
        }
    }
}
