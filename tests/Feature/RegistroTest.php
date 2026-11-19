<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_un_usuario_con_su_carrera(): void
    {
        $this->post('/registro', [
            'nombre' => 'Ana',
            'carrera' => 'Medicina y Salud',
            'email' => 'ana@example.com',
            'password' => 'Secreta123',
        ])->assertRedirect('/login')->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['email' => 'ana@example.com', 'carrera' => 'Medicina y Salud']);
    }

    public function test_rechaza_correos_repetidos_y_carreras_invalidas(): void
    {
        User::factory()->create(['email' => 'ana@example.com']);

        $this->post('/registro', [
            'nombre' => 'Ana',
            'carrera' => 'Astronauta',
            'email' => 'ana@example.com',
            'password' => 'Secreta123',
        ])->assertSessionHasErrors([
            'carrera' => 'Selecciona una carrera válida.',
            'email' => 'Este correo ya está registrado.',
        ]);
    }
}
