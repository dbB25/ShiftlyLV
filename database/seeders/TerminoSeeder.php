<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Termino;
use Illuminate\Database\Seeder;

/**
 * Tabla terminos: un término de glosario por área.
 */
class TerminoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Area::all() as $area) {
            Termino::firstOrCreate(
                ['area_id' => $area->id, 'termino' => 'Vocabulary'],
                ['traduccion' => 'Vocabulario', 'ejemplo' => 'Learn new vocabulary every day.']
            );
        }
    }
}
