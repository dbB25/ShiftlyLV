<?php

namespace Database\Seeders;

use App\Models\ProgresoLeccion;
use Illuminate\Database\Seeder;

/**
 * Tabla progreso_lecciones: lecciones completadas, XP y resultados de ejercicios.
 * Los IDs y el XP coinciden con public/js/rutas-catalogo.js.
 *
 * Total: 45 XP en Sistemas (3 lecciones), 10 XP en Medicina (1 lección)
 * y una práctica sin terminar en General.
 */
class ProgresoLeccionSeeder extends Seeder
{
    public function run(): void
    {
        $lecciones = [
            // carrera, clave, completada, xp, aciertos, total, vidas, segundos, hace (días)
            ['sistemas', 'sistemas-01', true, 10, 6, 6, 5, 80, 3],
            ['sistemas', 'sistemas-02', true, 15, 5, 6, 4, 120, 2],
            ['sistemas', 'sistemas-03', true, 20, 6, 6, 5, 95, 1],
            ['medicina', 'medicina-01', true, 10, 4, 6, 3, 140, 1],
            ['general', 'general-01', false, 0, 3, 6, 0, 110, 0],
        ];

        $orden = [];
        foreach ($lecciones as [$carrera, $clave, $completada, $xp, $aciertos, $total, $vidas, $segundos, $hace]) {
            $ruta = ProgresoRutaSeeder::ruta($carrera);

            ProgresoLeccion::updateOrCreate(
                ['progreso_ruta_id' => $ruta->id, 'leccion_clave' => $clave],
                [
                    'completada' => $completada,
                    'orden' => $completada ? ($orden[$carrera] = ($orden[$carrera] ?? -1) + 1) : null,
                    'xp' => $xp,
                    'aciertos_primer_intento' => $aciertos,
                    'total_ejercicios' => $total,
                    'vidas' => $vidas,
                    'duracion_seg' => $segundos,
                    'fuente' => 'local',
                    'practicada_en' => now()->subDays($hace)->setTime(18, 30),
                ]
            );
        }
    }
}
