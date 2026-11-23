<?php

namespace Database\Seeders;

use App\Models\ErrorEjercicio;
use Illuminate\Database\Seeder;

/**
 * Tabla errores_ejercicios: dos retos fallados de la lección 2 de Sistemas,
 * tomados de public/js/ejercicios-sistemas.js, para probar el modo repaso.
 */
class ErrorEjercicioSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = ProgresoRutaSeeder::ruta('sistemas');
        $titulo = 'Entrada y salida de datos';

        $ejercicios = [
            [
                'tipo' => 'opcion',
                'enunciado' => 'Selecciona la palabra técnica correcta',
                'frase' => 'The function receives two ___: width and height.',
                'opciones' => ['parameters', 'outputs', 'servers', 'commits'],
                'correcta' => 0,
                'explicacion' => 'Los parameters son las variables que declara la función para recibir datos.',
            ],
            [
                'tipo' => 'completar',
                'enunciado' => 'Completa la explicación',
                'antes' => 'When you call sum(2, 3), the values 2 and 3 are the',
                'despues' => 'of the function.',
                'respuestas' => ['arguments', 'argument'],
                'pista' => 'Valores reales enviados al llamar una función: «arg…».',
                'explicacion' => '2 y 3 son los arguments que recibe sum().',
            ],
        ];

        foreach ($ejercicios as $ejercicio) {
            // Misma firma que claveEjercicio() en ejercicios-leccion.js
            $firma = $ejercicio['frase'] ?? $ejercicio['antes'];
            $clave = "sistemas-02|{$ejercicio['tipo']}|{$firma}";

            ErrorEjercicio::updateOrCreate(
                ['progreso_ruta_id' => $ruta->id, 'clave_hash' => sha1($clave)],
                [
                    'leccion_clave' => 'sistemas-02',
                    'leccion_titulo' => $titulo,
                    'clave' => $clave,
                    'ejercicio' => $ejercicio,
                    'fallado_en' => now()->subDays(2)->setTime(18, 32),
                ]
            );
        }
    }
}
