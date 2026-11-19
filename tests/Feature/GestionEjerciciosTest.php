<?php

namespace Tests\Feature;

use App\Models\Ejercicio;
use App\Models\Leccion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CRUD de ejercicios en /gestion/ejercicios.
 */
class GestionEjerciciosTest extends TestCase
{
    use RefreshDatabase;

    private Leccion $leccion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        $this->leccion = Leccion::factory()->create();
    }

    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'leccion_id' => $this->leccion->id,
            'titulo' => 'Saludo formal',
            'tipo' => 'opcion_multiple',
            'pregunta' => 'How do you greet your manager?',
            'opciones' => "Good morning\nWhat's up\nYo",
            'respuesta_correcta' => 'Good morning',
            'retroalimentacion' => 'En el trabajo se usa un saludo formal.',
        ], $cambios);
    }

    public function test_el_listado_pagina_busca_y_filtra_por_leccion(): void
    {
        Ejercicio::factory()->count(11)->for($this->leccion)->create();
        $otraLeccion = Leccion::factory()->create();
        Ejercicio::factory()->for($otraLeccion)->create(['titulo' => 'Pedir la cuenta']);

        $this->get('/gestion/ejercicios')
            ->assertOk()
            ->assertViewHas('ejercicios', fn ($ejercicios) => $ejercicios->total() === 12 && $ejercicios->count() === 10);

        $this->get('/gestion/ejercicios?buscar=cuenta')
            ->assertViewHas('ejercicios', fn ($ejercicios) => $ejercicios->total() === 1);

        $this->get("/gestion/ejercicios?leccion={$otraLeccion->id}")
            ->assertViewHas('ejercicios', fn ($ejercicios) => $ejercicios->total() === 1);
    }

    public function test_crea_un_ejercicio_con_sus_opciones(): void
    {
        $this->post('/gestion/ejercicios', $this->datosValidos())
            ->assertRedirect('/gestion/ejercicios')
            ->assertSessionHas('success');

        $ejercicio = Ejercicio::firstWhere('titulo', 'Saludo formal');

        $this->assertSame($this->leccion->id, $ejercicio->leccion_id);
        $this->assertSame(['Good morning', "What's up", 'Yo'], $ejercicio->opciones);
    }

    public function test_exige_leccion_y_que_la_respuesta_este_entre_las_opciones(): void
    {
        $this->post('/gestion/ejercicios', $this->datosValidos(['leccion_id' => '']))
            ->assertSessionHasErrors(['leccion_id' => 'Selecciona la lección a la que pertenece el ejercicio.']);

        $this->post('/gestion/ejercicios', $this->datosValidos(['respuesta_correcta' => 'Hello']))
            ->assertSessionHasErrors(['opciones' => 'Agrega al menos dos opciones e incluye entre ellas la respuesta correcta.']);
    }

    public function test_conserva_los_datos_escritos_cuando_hay_errores(): void
    {
        $this->from('/gestion/ejercicios/create')
            ->post('/gestion/ejercicios', $this->datosValidos(['leccion_id' => '']))
            ->assertRedirect('/gestion/ejercicios/create');

        $this->get('/gestion/ejercicios/create')
            ->assertSee('value="Saludo formal"', false)
            ->assertSee('Selecciona la lección a la que pertenece el ejercicio.');
    }

    public function test_edita_un_ejercicio_sin_chocar_con_su_propio_titulo(): void
    {
        $ejercicio = Ejercicio::factory()->for($this->leccion)->create(['titulo' => 'Saludo formal']);

        $this->get("/gestion/ejercicios/{$ejercicio->id}/edit")
            ->assertOk()
            ->assertSee('value="Saludo formal"', false);

        $this->put("/gestion/ejercicios/{$ejercicio->id}", $this->datosValidos(['pregunta' => 'Pregunta nueva']))
            ->assertRedirect('/gestion/ejercicios')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('ejercicios', ['id' => $ejercicio->id, 'pregunta' => 'Pregunta nueva']);
    }

    public function test_elimina_un_ejercicio(): void
    {
        $ejercicio = Ejercicio::factory()->for($this->leccion)->create();

        $this->delete("/gestion/ejercicios/{$ejercicio->id}")
            ->assertRedirect('/gestion/ejercicios')
            ->assertSessionHas('success');

        $this->assertModelMissing($ejercicio);
    }
}
