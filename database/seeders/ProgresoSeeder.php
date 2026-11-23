<?php

namespace Database\Seeders;

use App\Models\Leccion;
use App\Models\Progreso;
use Illuminate\Database\Seeder;

/**
 * Tabla progresos: el alumno de prueba completó la lección de cada área
 * del panel de contenido (por eso el panel no deja borrarlas).
 */
class ProgresoSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = UsuarioSeeder::usuario();

        foreach (Leccion::all() as $leccion) {
            Progreso::firstOrCreate(
                ['user_id' => $usuario->id, 'leccion_id' => $leccion->id],
                ['puntaje' => 100, 'completado' => true]
            );
        }
    }
}
