<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Leccion;
use Illuminate\Database\Seeder;

/**
 * Tabla lecciones: una lección de introducción por área (panel de contenido).
 */
class LeccionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Area::all() as $area) {
            Leccion::firstOrCreate(
                ['area_id' => $area->id, 'titulo' => 'Introducción a '.$area->nombre],
                ['nivel' => 'A1', 'orden' => 1, 'publicada' => true]
            );
        }
    }
}
