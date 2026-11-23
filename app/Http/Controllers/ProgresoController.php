<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosticoRequest;
use App\Http\Requests\GuardarActividadRequest;
use App\Http\Requests\GuardarRutaRequest;
use App\Http\Requests\PracticaLibreRequest;
use App\Models\Diagnostico;
use App\Models\PracticaLibre;
use App\Services\ProgresoUsuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Guarda en la base de datos el avance del alumno: lecciones, ejercicios,
 * errores para repaso, actividad diaria, práctica libre y diagnóstico.
 */
class ProgresoController extends Controller
{
    public function __construct(private ProgresoUsuario $progreso)
    {
    }

    // GET /progreso: todo el progreso del usuario (lo mismo que recibe cada página)
    public function mostrar(Request $request): JsonResponse
    {
        return response()->json($this->progreso->paraNavegador($request->user()));
    }

    // PUT /progreso/rutas/{carrera}
    public function guardarRuta(GuardarRutaRequest $request, string $carrera): JsonResponse
    {
        abort_unless(in_array($carrera, ProgresoUsuario::CARRERAS, true), 404);

        $ruta = $this->progreso->guardarRuta($request->user(), $carrera, $request->validated());

        return response()->json([
            'guardado' => true,
            'carrera' => $carrera,
            'lecciones_completadas' => $ruta->lecciones()->where('completada', true)->count(),
            'xp' => (int) $ruta->lecciones()->sum('xp'),
        ]);
    }

    // PUT /progreso/actividad
    public function guardarActividad(GuardarActividadRequest $request): JsonResponse
    {
        $this->progreso->guardarActividad($request->user(), $request->validated());

        return response()->json(['guardado' => true]);
    }

    // PUT /progreso/practica
    public function guardarPractica(PracticaLibreRequest $request): JsonResponse
    {
        $datos = $request->validated();
        $porcentaje = $datos['total'] ? (int) round($datos['completados'] / $datos['total'] * 100) : 0;

        $practica = PracticaLibre::updateOrCreate(
            ['user_id' => $request->user()->id, 'unidad' => $datos['unidad']],
            [
                'completados' => $datos['completados'],
                'total' => $datos['total'],
                'porcentaje' => $porcentaje,
                'completado' => $datos['total'] > 0 && $datos['completados'] === $datos['total'],
            ]
        );

        return response()->json(['guardado' => true, 'porcentaje' => $practica->porcentaje]);
    }

    // POST /progreso/diagnostico (si se reenvía el mismo intento, no se duplica)
    public function guardarDiagnostico(DiagnosticoRequest $request): JsonResponse
    {
        $resultado = $request->input('resultado');

        $diagnostico = Diagnostico::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'realizado_en' => Carbon::parse($resultado['fecha']),
            ],
            [
                'nivel' => $resultado['nivel'],
                'carrera' => $resultado['carrera'] ?? null,
                'aciertos' => $resultado['aciertos'],
                'total' => $resultado['total'],
                'resultado' => $resultado,
            ]
        );

        return response()->json(['guardado' => true, 'id' => $diagnostico->id], 201);
    }
}
