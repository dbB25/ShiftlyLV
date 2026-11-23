<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * El avance del alumno (lecciones, ejercicios, errores, actividad,
 * práctica libre y diagnóstico) se guarda en la base de datos.
 */
class ProgresoTest extends TestCase
{
    use RefreshDatabase;

    private function rutaConAvance(): array
    {
        return [
            'completadas' => ['sistemas-01', 'sistemas-02'],
            'xp' => ['sistemas-01' => 10, 'sistemas-02' => 15],
            'ejercicios' => [
                'sistemas-02' => [
                    'aciertosPrimerIntento' => 7,
                    'total' => 8,
                    'vidas' => 4,
                    'fuente' => 'local',
                    'duracionSeg' => 95,
                    'fecha' => '2026-11-22T10:00:00Z',
                ],
            ],
            'errores' => [[
                'clave' => 'sistemas-02|opcion|The program ___ data',
                'leccionId' => 'sistemas-02',
                'leccionTitulo' => 'Entrada y salida de datos',
                'ejercicio' => ['tipo' => 'opcion', 'frase' => 'The program ___ data'],
                'fecha' => '2026-11-22T10:00:00Z',
            ]],
        ];
    }

    public function test_el_progreso_requiere_sesion(): void
    {
        $this->putJson('/progreso/rutas/sistemas', ['completadas' => []])->assertUnauthorized();
        $this->postJson('/lecciones-personalizadas', [])->assertUnauthorized();
    }

    public function test_guarda_y_devuelve_el_avance_de_una_ruta(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)
            ->putJson('/progreso/rutas/sistemas', $this->rutaConAvance())
            ->assertOk()
            ->assertJson(['lecciones_completadas' => 2, 'xp' => 25]);

        $this->assertDatabaseHas('progreso_lecciones', [
            'leccion_clave' => 'sistemas-02',
            'completada' => true,
            'xp' => 15,
            'aciertos_primer_intento' => 7,
            'vidas' => 4,
        ]);
        $this->assertDatabaseCount('errores_ejercicios', 1);

        $this->actingAs($usuario)->getJson('/progreso')
            ->assertOk()
            ->assertJsonPath('rutas.sistemas.completadas', ['sistemas-01', 'sistemas-02'])
            ->assertJsonPath('rutas.sistemas.ejercicios.sistemas-02.total', 8)
            ->assertJsonPath('rutas.sistemas.errores.0.leccionId', 'sistemas-02')
            ->assertJsonPath('rutas.medicina.completadas', []);
    }

    public function test_reiniciar_la_ruta_borra_su_avance(): void
    {
        $usuario = User::factory()->create();
        $this->actingAs($usuario)->putJson('/progreso/rutas/sistemas', $this->rutaConAvance())->assertOk();

        $this->actingAs($usuario)
            ->putJson('/progreso/rutas/sistemas', ['completadas' => [], 'ejercicios' => [], 'errores' => []])
            ->assertOk();

        $this->assertDatabaseCount('progreso_lecciones', 0);
        $this->assertDatabaseCount('errores_ejercicios', 0);
    }

    public function test_rechaza_carreras_y_lecciones_invalidas(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)->putJson('/progreso/rutas/astronauta', ['completadas' => []])->assertNotFound();
        $this->actingAs($usuario)
            ->putJson('/progreso/rutas/sistemas', ['completadas' => ['<script>']])
            ->assertUnprocessable();
    }

    public function test_guarda_la_actividad_diaria_y_la_meta(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)->putJson('/progreso/actividad', [
            'dias' => ['2026-11-21' => ['xp' => 20, 'practicas' => 2], '2026-11-22' => ['xp' => 35, 'practicas' => 3]],
            'meta' => 50,
            'mejorPronunciacion' => 88,
        ])->assertOk();

        $this->assertDatabaseCount('actividad_diaria', 2);
        $this->assertDatabaseHas('users', ['id' => $usuario->id, 'meta_diaria' => 50, 'mejor_pronunciacion' => 88]);

        $this->actingAs($usuario)
            ->putJson('/progreso/actividad', ['dias' => ['2026-02-30' => ['xp' => 1, 'practicas' => 1]]])
            ->assertUnprocessable();
    }

    public function test_guarda_la_practica_libre(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)
            ->putJson('/progreso/practica', ['unidad' => 'unidad-03-leccion-02', 'completados' => 4, 'total' => 8])
            ->assertOk()
            ->assertJson(['porcentaje' => 50]);

        $this->assertDatabaseHas('practicas_libres', ['user_id' => $usuario->id, 'completados' => 4, 'completado' => false]);
    }

    public function test_guarda_el_diagnostico_sin_duplicar_reenvios(): void
    {
        $usuario = User::factory()->create();
        $resultado = ['nivel' => 'B1', 'carrera' => 'Sistemas y Software', 'aciertos' => 6, 'total' => 10, 'revision' => [], 'fecha' => '2026-11-22T10:00:00Z'];

        $this->actingAs($usuario)->postJson('/progreso/diagnostico', ['resultado' => $resultado])->assertCreated();
        $this->actingAs($usuario)->postJson('/progreso/diagnostico', ['resultado' => $resultado])->assertCreated();

        $this->assertDatabaseCount('diagnosticos', 1);
        $this->actingAs($usuario)->getJson('/progreso')->assertJsonPath('diagnostico.nivel', 'B1');
    }

    public function test_crud_de_lecciones_personalizadas(): void
    {
        $usuario = User::factory()->create();
        $datos = [
            'carrera' => 'sistemas',
            'titulo' => 'Entrevista técnica',
            'descripcion' => 'Respuestas para una entrevista.',
            'vocab' => [['en' => 'deadline', 'es' => 'fecha límite']],
            'xp' => 30,
            'forma' => 'inicio',
            'icono' => 'bi-terminal',
        ];

        // CREATE
        $id = $this->actingAs($usuario)->postJson('/lecciones-personalizadas', $datos)
            ->assertCreated()
            ->assertJsonPath('leccion.titulo', 'Entrevista técnica')
            ->json('leccion.id');
        $numero = (int) str_replace('p-', '', $id);

        // READ
        $this->actingAs($usuario)->getJson('/lecciones-personalizadas?carrera=sistemas')
            ->assertOk()
            ->assertJsonCount(1, 'lecciones');

        // UPDATE
        $this->actingAs($usuario)
            ->putJson("/lecciones-personalizadas/{$numero}", ['titulo' => 'Entrevista editada'] + $datos)
            ->assertOk()
            ->assertJsonPath('leccion.titulo', 'Entrevista editada');

        // Su avance se borra junto con la lección
        $this->actingAs($usuario)->putJson('/progreso/rutas/sistemas', [
            'completadas' => ['sistemas-01', $id],
            'xp' => ['sistemas-01' => 10, $id => 30],
        ])->assertOk();

        // DELETE
        $this->actingAs($usuario)->deleteJson("/lecciones-personalizadas/{$numero}")->assertOk();

        $this->assertDatabaseCount('lecciones_personalizadas', 0);
        $this->assertDatabaseMissing('progreso_lecciones', ['leccion_clave' => $id]);
        $this->assertDatabaseHas('progreso_lecciones', ['leccion_clave' => 'sistemas-01']);
    }

    public function test_valida_las_lecciones_personalizadas_en_espanol(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)->postJson('/lecciones-personalizadas', [
            'carrera' => 'sistemas',
            'titulo' => 'ab',
            'vocab' => [],
            'xp' => 30,
            'forma' => 'inicio',
            'icono' => 'bi-terminal',
        ])->assertUnprocessable()->assertJsonValidationErrors([
            'titulo' => 'El título debe tener al menos 3 caracteres.',
        ]);
    }

    public function test_un_alumno_no_puede_tocar_lecciones_de_otro(): void
    {
        $duena = User::factory()->create();
        $otro = User::factory()->create();

        $leccion = $duena->leccionesPersonalizadas()->create([
            'carrera' => 'sistemas',
            'titulo' => 'Mi lección',
            'vocabulario' => [],
            'xp' => 20,
            'forma' => 'inicio',
            'icono' => 'bi-terminal',
        ]);

        $this->actingAs($otro)->deleteJson("/lecciones-personalizadas/{$leccion->id}")->assertForbidden();
        $this->actingAs($otro)->getJson('/lecciones-personalizadas')->assertJsonCount(0, 'lecciones');
        $this->assertDatabaseCount('lecciones_personalizadas', 1);
    }

    public function test_las_paginas_entregan_el_progreso_del_servidor(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)->get('/ruta-sistemas')
            ->assertOk()
            ->assertSee('window.SHIFTLY_SERVIDOR', false)
            ->assertSee('js/shiftly-servidor.js', false);
    }
}
