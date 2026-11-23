<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeccionPersonalizadaRequest;
use App\Models\LeccionPersonalizada;
use App\Models\ProgresoLeccion;
use App\Services\ProgresoUsuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * CRUD de lecciones personalizadas: cada alumno crea, edita y elimina
 * sus propias lecciones al final del árbol de su carrera.
 */
class LeccionPersonalizadaController extends Controller
{
    // READ: GET /lecciones-personalizadas?carrera=sistemas
    public function index(Request $request): JsonResponse
    {
        $request->validate(['carrera' => ['nullable', Rule::in(ProgresoUsuario::CARRERAS)]]);

        $lecciones = $request->user()->leccionesPersonalizadas()
            ->when($request->filled('carrera'), fn ($q) => $q->where('carrera', $request->input('carrera')))
            ->orderBy('id')
            ->get()
            ->map->paraNavegador();

        return response()->json(['lecciones' => $lecciones]);
    }

    // CREATE: POST /lecciones-personalizadas
    public function store(LeccionPersonalizadaRequest $request): JsonResponse
    {
        $leccion = $request->user()->leccionesPersonalizadas()->create($this->datos($request) + [
            'carrera' => $request->validated('carrera'),
        ]);

        return response()->json(['leccion' => $leccion->paraNavegador()], 201);
    }

    // UPDATE: PUT /lecciones-personalizadas/{leccion}
    public function update(LeccionPersonalizadaRequest $request, LeccionPersonalizada $leccion): JsonResponse
    {
        $this->autorizar($request, $leccion);

        $leccion->update($this->datos($request));

        return response()->json(['leccion' => $leccion->paraNavegador()]);
    }

    // DELETE: DELETE /lecciones-personalizadas/{leccion}
    public function destroy(Request $request, LeccionPersonalizada $leccion): JsonResponse
    {
        $this->autorizar($request, $leccion);

        DB::transaction(function () use ($request, $leccion) {
            // También se borra el avance que el alumno tenía en esa lección
            ProgresoLeccion::query()
                ->where('leccion_clave', $leccion->claveArbol())
                ->whereHas('ruta', fn ($q) => $q->where('user_id', $request->user()->id))
                ->delete();

            $leccion->delete();
        });

        return response()->json(['eliminada' => true]);
    }

    private function datos(LeccionPersonalizadaRequest $request): array
    {
        $datos = $request->validated();

        return [
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'] ?: null,
            'vocabulario' => array_map(
                fn ($v) => ['en' => trim($v['en']), 'es' => trim($v['es'])],
                $datos['vocab']
            ),
            'xp' => $datos['xp'],
            'forma' => $datos['forma'],
            'icono' => $datos['icono'],
        ];
    }

    // Cada alumno solo puede tocar sus propias lecciones
    private function autorizar(Request $request, LeccionPersonalizada $leccion): void
    {
        abort_unless($leccion->user_id === $request->user()->id, 403);
    }
}
