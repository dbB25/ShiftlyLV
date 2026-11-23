<?php

namespace App\Services;

use App\Models\ActividadDiaria;
use App\Models\Diagnostico;
use App\Models\LeccionPersonalizada;
use App\Models\ProgresoLeccion;
use App\Models\ProgresoRuta;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Traduce entre las tablas de progreso y el formato que usan los scripts
 * del navegador (shiftly-progreso.js, ruta-*.js y ejercicios-leccion.js).
 *
 * La base de datos es la fuente de verdad: cada página recibe el progreso
 * del usuario desde aquí y cada cambio se vuelve a guardar aquí.
 */
class ProgresoUsuario
{
    /** Carreras de rutas-catalogo.js; su clave en el navegador es "shiftly_progreso_<carrera>". */
    public const CARRERAS = ['sistemas', 'medicina', 'gastronomia', 'derecho', 'arquitectura', 'general'];

    /** Días de actividad que se envían al navegador (la racha solo necesita los recientes). */
    private const DIAS_ACTIVIDAD = 120;

    // ------------------------------------------------------------------
    // LECTURA: todo el progreso del usuario en el formato del navegador
    // ------------------------------------------------------------------
    public function paraNavegador(User $usuario): array
    {
        $rutas = ProgresoRuta::query()
            ->where('user_id', $usuario->id)
            ->with(['lecciones', 'errores'])
            ->get()
            ->keyBy('carrera');

        $personalizadas = LeccionPersonalizada::query()
            ->where('user_id', $usuario->id)
            ->orderBy('id')
            ->get()
            ->groupBy('carrera');

        $resultado = [];
        foreach (self::CARRERAS as $carrera) {
            $resultado[$carrera] = $this->rutaParaNavegador(
                $rutas->get($carrera),
                ($personalizadas->get($carrera) ?? collect())->map->paraNavegador()->values()->all()
            );
        }

        return [
            'rutas' => $resultado,
            'actividad' => $this->actividadParaNavegador($usuario),
            // Último examen diagnóstico (el que usan el dashboard y las recomendaciones)
            'diagnostico' => Diagnostico::query()
                ->where('user_id', $usuario->id)
                ->latest('realizado_en')
                ->value('resultado'),
        ];
    }

    private function rutaParaNavegador(?ProgresoRuta $ruta, array $personalizadas): array
    {
        $datos = [
            'completadas' => [],
            'personalizadas' => $personalizadas,
            'ejercicios' => (object) [],
            'errores' => [],
            'actualizado' => $ruta?->updated_at?->toIso8601String(),
        ];

        if (! $ruta) {
            return $datos;
        }

        $datos['completadas'] = $ruta->lecciones
            ->where('completada', true)
            ->sortBy('orden')
            ->pluck('leccion_clave')
            ->values()
            ->all();

        $ejercicios = [];
        foreach ($ruta->lecciones->whereNotNull('practicada_en') as $leccion) {
            $ejercicios[$leccion->leccion_clave] = [
                'aciertosPrimerIntento' => $leccion->aciertos_primer_intento,
                'total' => $leccion->total_ejercicios,
                'vidas' => $leccion->vidas,
                'fuente' => $leccion->fuente,
                'duracionSeg' => $leccion->duracion_seg,
                'fecha' => $leccion->practicada_en->toIso8601String(),
            ];
        }
        $datos['ejercicios'] = (object) $ejercicios;

        $datos['errores'] = $ruta->errores
            ->sortBy('id')
            ->map(fn ($error) => [
                'clave' => $error->clave,
                'leccionId' => $error->leccion_clave,
                'leccionTitulo' => $error->leccion_titulo,
                'ejercicio' => $error->ejercicio,
                'fecha' => $error->fallado_en?->toIso8601String(),
            ])
            ->values()
            ->all();

        if ($ruta->ubicacion_leccion) {
            $datos['ubicacion'] = [
                'numero' => $ruta->ubicacion_leccion,
                'fecha' => $ruta->ubicacion_fecha?->toIso8601String(),
            ];
        }

        return $datos;
    }

    private function actividadParaNavegador(User $usuario): array
    {
        $dias = ActividadDiaria::query()
            ->where('user_id', $usuario->id)
            ->where('fecha', '>=', now()->subDays(self::DIAS_ACTIVIDAD)->toDateString())
            ->orderBy('fecha')
            ->get()
            ->mapWithKeys(fn ($dia) => [
                $dia->fecha->toDateString() => ['xp' => $dia->xp, 'practicas' => $dia->practicas],
            ])
            ->all();

        return [
            'dias' => (object) $dias,
            'meta' => $usuario->meta_diaria ?? 30,
            'mejorPronunciacion' => $usuario->mejor_pronunciacion ?? 0,
        ];
    }

    // ------------------------------------------------------------------
    // ESCRITURA: estado completo de una ruta enviado por el navegador
    // ------------------------------------------------------------------
    public function guardarRuta(User $usuario, string $carrera, array $datos): ProgresoRuta
    {
        return DB::transaction(function () use ($usuario, $carrera, $datos) {
            $ruta = ProgresoRuta::firstOrCreate(['user_id' => $usuario->id, 'carrera' => $carrera]);

            $ubicacion = $datos['ubicacion'] ?? null;
            $ruta->fill([
                'ubicacion_leccion' => $ubicacion['numero'] ?? null,
                'ubicacion_fecha' => isset($ubicacion['fecha']) ? Carbon::parse($ubicacion['fecha']) : null,
            ]);
            $ruta->touch();

            $completadas = array_values(array_unique($datos['completadas'] ?? []));
            $ejercicios = $datos['ejercicios'] ?? [];
            $xpPorLeccion = $datos['xp'] ?? [];

            // Lecciones con avance: las completadas y las que ya se practicaron
            $claves = array_values(array_unique([...$completadas, ...array_keys($ejercicios)]));

            foreach ($claves as $clave) {
                $completada = in_array($clave, $completadas, true);
                $resultado = $ejercicios[$clave] ?? null;

                ProgresoLeccion::updateOrCreate(
                    ['progreso_ruta_id' => $ruta->id, 'leccion_clave' => $clave],
                    [
                        'completada' => $completada,
                        'orden' => $completada ? array_search($clave, $completadas, true) : null,
                        'xp' => $completada ? (int) ($xpPorLeccion[$clave] ?? 0) : 0,
                        'aciertos_primer_intento' => $resultado['aciertosPrimerIntento'] ?? null,
                        'total_ejercicios' => $resultado['total'] ?? null,
                        'vidas' => $resultado['vidas'] ?? null,
                        'duracion_seg' => $resultado['duracionSeg'] ?? null,
                        'fuente' => $resultado['fuente'] ?? null,
                        'practicada_en' => $resultado
                            ? Carbon::parse($resultado['fecha'] ?? now())
                            : null,
                    ]
                );
            }

            // Lo que ya no viene del navegador se borró (reiniciar ruta, lección eliminada)
            $ruta->lecciones()->whereNotIn('leccion_clave', $claves ?: [''])->delete();

            $this->guardarErrores($ruta, $datos['errores'] ?? []);

            return $ruta;
        });
    }

    private function guardarErrores(ProgresoRuta $ruta, array $errores): void
    {
        $hashes = [];
        foreach ($errores as $error) {
            $hash = sha1($error['clave']);
            $hashes[] = $hash;

            $ruta->errores()->updateOrCreate(
                ['clave_hash' => $hash],
                [
                    'clave' => $error['clave'],
                    'leccion_clave' => $error['leccionId'],
                    'leccion_titulo' => $error['leccionTitulo'] ?? null,
                    'ejercicio' => $error['ejercicio'],
                    'fallado_en' => isset($error['fecha']) ? Carbon::parse($error['fecha']) : now(),
                ]
            );
        }

        // Los errores que el alumno ya repasó bien desaparecen de la lista
        $ruta->errores()->whereNotIn('clave_hash', $hashes ?: [''])->delete();
    }

    // ------------------------------------------------------------------
    // ESCRITURA: racha, meta diaria y mejor pronunciación
    // ------------------------------------------------------------------
    public function guardarActividad(User $usuario, array $datos): void
    {
        DB::transaction(function () use ($usuario, $datos) {
            foreach ($datos['dias'] ?? [] as $fecha => $dia) {
                ActividadDiaria::updateOrCreate(
                    ['user_id' => $usuario->id, 'fecha' => $fecha],
                    ['xp' => (int) ($dia['xp'] ?? 0), 'practicas' => (int) ($dia['practicas'] ?? 0)]
                );
            }

            $usuario->forceFill([
                'meta_diaria' => $datos['meta'] ?? $usuario->meta_diaria,
                'mejor_pronunciacion' => max(
                    (int) $usuario->mejor_pronunciacion,
                    (int) ($datos['mejorPronunciacion'] ?? 0)
                ),
            ])->save();
        });
    }
}
