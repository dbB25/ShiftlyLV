<?php

namespace Database\Seeders;

use App\Models\PracticaLibre;
use Illuminate\Database\Seeder;

/**
 * Tabla practicas_libres: avance a la mitad en la práctica libre de /ejercicios.
 */
class PracticaLibreSeeder extends Seeder
{
    public function run(): void
    {
        PracticaLibre::updateOrCreate(
            ['user_id' => UsuarioSeeder::usuario()->id, 'unidad' => 'unidad-03-leccion-02'],
            ['completados' => 4, 'total' => 8, 'porcentaje' => 50, 'completado' => false]
        );
    }
}
