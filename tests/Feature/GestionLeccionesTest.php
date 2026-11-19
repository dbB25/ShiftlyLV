<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Ejercicio;
use App\Models\Leccion;
use App\Models\Progreso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CRUD de lecciones en /gestion/lecciones.
 */
class GestionLeccionesTest extends TestCase
{
    use RefreshDatabase;

    private Area $area;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        $this->area = Area::factory()->create();
    }

    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'area_id' => $this->area->id,
            'titulo' => 'Entrevista de trabajo',
            'nivel' => 'B1',
            'orden' => 2,
            'publicada' => '1',
        ], $cambios);
    }

    public function test_un_invitado_no_puede_entrar_al_panel(): void
    {
        auth()->logout();

        $this->get('/gestion/lecciones')->assertRedirect('/login');
    }

    public function test_el_listado_pagina_y_busca(): void
    {
        Leccion::factory()->count(12)->for($this->area)->create();
        Leccion::factory()->for($this->area)->create(['titulo' => 'Vocabulario de cocina']);

        $this->get('/gestion/lecciones')
            ->assertOk()
            ->assertViewHas('lecciones', fn ($lecciones) => $lecciones->total() === 13 && $lecciones->count() === 10);

        $this->get('/gestion/lecciones?buscar=cocina')
            ->assertOk()
            ->assertSee('Vocabulario de cocina')
            ->assertViewHas('lecciones', fn ($lecciones) => $lecciones->total() === 1);
    }

    public function test_crea_una_leccion(): void
    {
        $this->post('/gestion/lecciones', $this->datosValidos())
            ->assertRedirect('/gestion/lecciones')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('lecciones', [
            'titulo' => 'Entrevista de trabajo',
            'area_id' => $this->area->id,
            'publicada' => true,
        ]);
    }

    public function test_valida_los_datos_con_mensajes_en_espanol(): void
    {
        $this->from('/gestion/lecciones/create')
            ->post('/gestion/lecciones', ['titulo' => '', 'nivel' => 'Z9', 'orden' => 0])
            ->assertRedirect('/gestion/lecciones/create')
            ->assertSessionHasErrors([
                'area_id' => 'Selecciona el área de la lección.',
                'titulo' => 'Escribe el título de la lección.',
                'nivel' => 'El nivel debe ser A1, A2, B1, B2, C1 o C2.',
                'orden' => 'El orden debe ser al menos 1.',
            ]);
    }

    public function test_no_permite_titulos_repetidos_en_la_misma_area(): void
    {
        Leccion::factory()->for($this->area)->create(['titulo' => 'Entrevista de trabajo']);

        $this->post('/gestion/lecciones', $this->datosValidos())
            ->assertSessionHasErrors(['titulo' => 'Ya existe una lección con ese título en el área seleccionada.']);
    }

    public function test_al_editar_el_titulo_unico_ignora_la_leccion_actual(): void
    {
        $leccion = Leccion::factory()->for($this->area)->create(['titulo' => 'Entrevista de trabajo']);

        $this->get("/gestion/lecciones/{$leccion->id}/edit")
            ->assertOk()
            ->assertSee('value="Entrevista de trabajo"', false);

        $this->put("/gestion/lecciones/{$leccion->id}", $this->datosValidos(['orden' => 5, 'publicada' => null]))
            ->assertRedirect('/gestion/lecciones')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('lecciones', ['id' => $leccion->id, 'orden' => 5, 'publicada' => false]);
    }

    public function test_elimina_la_leccion_y_sus_ejercicios(): void
    {
        $leccion = Leccion::factory()->for($this->area)->create();
        Ejercicio::factory()->count(2)->for($leccion)->create();

        $this->delete("/gestion/lecciones/{$leccion->id}")
            ->assertRedirect('/gestion/lecciones')
            ->assertSessionHas('success');

        $this->assertModelMissing($leccion);
        $this->assertDatabaseCount('ejercicios', 0);
    }

    public function test_no_elimina_una_leccion_con_progreso_de_estudiantes(): void
    {
        $leccion = Leccion::factory()->for($this->area)->create();
        Progreso::factory()->for($leccion)->create();

        $this->delete("/gestion/lecciones/{$leccion->id}")
            ->assertRedirect('/gestion/lecciones')
            ->assertSessionHas('error');

        $this->assertModelExists($leccion);
    }
}
