<?php

namespace App\Http\Controllers;

use App\Http\Requests\EjercicioRequest;
use App\Models\Ejercicio;

class EjercicioController extends Controller
{
    public function index()
    {
        return view('html.dashboard.gestion-ejercicios', [
            'ejercicios' => Ejercicio::latest()->get(),
            'ejercicio' => null,
        ]);
    }

    public function create()
    {
        return view('html.dashboard.gestion-ejercicios', [
            'ejercicios' => Ejercicio::latest()->get(),
            'ejercicio' => new Ejercicio(['tipo' => 'completar']),
        ]);
    }

    public function store(EjercicioRequest $request)
    {
        $datos = $this->prepararDatos($request);

        Ejercicio::create($datos);

        return redirect()->route('gestion.ejercicios.index')
            ->with('success', 'El ejercicio se creó correctamente.');
    }

    public function edit(Ejercicio $ejercicio)
    {
        return view('html.dashboard.gestion-ejercicios', [
            'ejercicios' => Ejercicio::latest()->get(),
            'ejercicio' => $ejercicio,
        ]);
    }

    public function update(
        EjercicioRequest $request,
        Ejercicio $ejercicio
    ) {
        $datos = $this->prepararDatos($request);

        $ejercicio->update($datos);

        return redirect()->route('gestion.ejercicios.index')
            ->with('success', 'El ejercicio se actualizó correctamente.');
    }

    public function destroy(Ejercicio $ejercicio)
    {
        $ejercicio->delete();

        return redirect()->route('gestion.ejercicios.index')
            ->with('success', 'El ejercicio se eliminó correctamente.');
    }

    private function prepararDatos(EjercicioRequest $request): array
    {
        $datos = $request->validated();

        $opciones = preg_split(
            '/\R/u',
            $datos['opciones'] ?? ''
        ) ?: [];

        $opciones = array_values(array_filter(
            array_map('trim', $opciones),
            fn ($opcion) => $opcion !== ''
        ));

        $datos['opciones'] = $opciones ?: null;
        $datos['respuesta_correcta'] =
            $datos['respuesta_correcta'] ?? null;
        $datos['retroalimentacion'] =
            $datos['retroalimentacion'] ?? null;

        return $datos;
    }
}
