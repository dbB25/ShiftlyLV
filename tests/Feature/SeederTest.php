<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UsuarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Los seeders llenan todas las tablas y se pueden correr varias veces.
 */
class SeederTest extends TestCase
{
    use RefreshDatabase;

    private const FILAS_POR_TABLA = [
        'users' => 1,
        'areas' => 6,
        'lecciones' => 6,
        'terminos' => 6,
        'ejercicios' => 6,
        'progresos' => 6,
        'progreso_rutas' => 3,
        'progreso_lecciones' => 5,
        'errores_ejercicios' => 2,
        'actividad_diaria' => 4,
        'lecciones_personalizadas' => 3,
        'practicas_libres' => 1,
        'diagnosticos' => 2,
    ];

    public function test_llena_cada_tabla_sin_duplicar_al_repetirse(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        foreach (self::FILAS_POR_TABLA as $tabla => $filas) {
            $this->assertDatabaseCount($tabla, $filas);
        }
    }

    public function test_el_alumno_de_prueba_puede_entrar_y_ve_su_progreso(): void
    {
        $this->seed(DatabaseSeeder::class);

        $usuario = User::where('email', UsuarioSeeder::EMAIL)->firstOrFail();
        $this->assertTrue(Hash::check(UsuarioSeeder::PASSWORD, $usuario->password));

        $this->post('/login', ['email' => UsuarioSeeder::EMAIL, 'password' => UsuarioSeeder::PASSWORD])
            ->assertRedirect('/dashboard');

        $this->getJson('/progreso')
            ->assertOk()
            ->assertJsonPath('rutas.sistemas.completadas', ['sistemas-01', 'sistemas-02', 'sistemas-03'])
            ->assertJsonCount(2, 'rutas.sistemas.personalizadas')
            ->assertJsonCount(2, 'rutas.sistemas.errores')
            ->assertJsonPath('diagnostico.nivel', 'B1')
            ->assertJsonPath('actividad.meta', 30);
    }
}
