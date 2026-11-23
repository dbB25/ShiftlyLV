<?php

namespace Database\Seeders;

use App\Models\ProgresoRuta;
use Illuminate\Database\Seeder;

/**
 * Tabla progreso_rutas: el alumno de prueba empezó tres rutas.
 */
class ProgresoRutaSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = UsuarioSeeder::usuario();

        foreach (['sistemas', 'medicina', 'general'] as $carrera) {
            ProgresoRuta::firstOrCreate(['user_id' => $usuario->id, 'carrera' => $carrera]);
        }
    }

    public static function ruta(string $carrera): ProgresoRuta
    {
        return ProgresoRuta::query()
            ->where('user_id', UsuarioSeeder::usuario()->id)
            ->where('carrera', $carrera)
            ->firstOrFail();
    }
}
