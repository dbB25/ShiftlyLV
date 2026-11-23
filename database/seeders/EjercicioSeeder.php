<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\Leccion;
use Illuminate\Database\Seeder;

/**
 * Tabla ejercicios: un ejercicio de opción múltiple por lección.
 */
class EjercicioSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Leccion::all() as $leccion) {
            Ejercicio::firstOrCreate(
                ['leccion_id' => $leccion->id, 'pregunta' => 'What does Hello mean?'],
                [
                    'titulo' => 'Saludo básico',
                    'tipo' => 'opcion_multiple',
                    'opciones' => ['Hola', 'Adiós', 'Gracias', 'Por favor'],
                    'respuesta_correcta' => 'Hola',
                    'retroalimentacion' => 'Hello significa Hola.',
                ]
            );
        }
    }
}
