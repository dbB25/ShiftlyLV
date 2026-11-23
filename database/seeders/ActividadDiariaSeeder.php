<?php

namespace Database\Seeders;

use App\Models\ActividadDiaria;
use Illuminate\Database\Seeder;

/**
 * Tabla actividad_diaria: 4 días seguidos de práctica hasta hoy
 * (racha de 4 días). Las fechas son relativas al día en que se corre el seeder.
 */
class ActividadDiariaSeeder extends Seeder
{
    /**
     * La racha se calcula con la fecha local del navegador del alumno, no con la
     * del servidor (UTC). Por la noche en México, UTC ya va en el día siguiente.
     */
    private const ZONA_ALUMNOS = 'America/Mexico_City';

    public function run(): void
    {
        $usuario = UsuarioSeeder::usuario();
        $hoy = now(self::ZONA_ALUMNOS);

        // hace (días) => [xp, prácticas]
        $dias = [
            3 => [10, 1],
            2 => [15, 2],
            1 => [30, 2],
            0 => [10, 1],
        ];

        foreach ($dias as $hace => [$xp, $practicas]) {
            ActividadDiaria::updateOrCreate(
                ['user_id' => $usuario->id, 'fecha' => $hoy->copy()->subDays($hace)->toDateString()],
                ['xp' => $xp, 'practicas' => $practicas]
            );
        }
    }
}
