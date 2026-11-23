<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Tabla users: alumno de prueba con el que se llenan las demás tablas.
 */
class UsuarioSeeder extends Seeder
{
    public const EMAIL = 'prueba@shiftly.com';

    public const PASSWORD = 'Password123';

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => self::EMAIL],
            [
                'name' => 'Usuario de prueba',
                'apellido' => 'Shiftly',
                'carrera' => 'Sistemas y Software',
                'area_profesional' => 'Sistemas y Software',
                'password' => self::PASSWORD,
                'email_verified_at' => now(),
            ]
        )->forceFill([
            // Meta del día y mejor resultado con el micrófono (dashboard e insignias)
            'meta_diaria' => 30,
            'mejor_pronunciacion' => 82,
        ])->save();
    }

    public static function usuario(): User
    {
        return User::where('email', self::EMAIL)->firstOrFail();
    }
}
