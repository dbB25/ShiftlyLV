<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEjercicioRequest;
use App\Http\Requests\UpdateEjercicioRequest;
use App\Models\Ejercicio;
use App\Models\Leccion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EjercicioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $leccionId = $request->integer('leccion') ?: null;

        $ejercicios = Ejercicio::with('leccion.area')
            ->buscar($buscar)
            ->when($leccionId, fn ($consulta) => $consulta->where('leccion_id', $leccionId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('gestion.ejercicios.index', [
            'ejercicios' => $ejercicios,
            'lecciones' => Leccion::orderBy('titulo')->get(),
            'buscar' => $buscar,
            'leccionId' => $leccionId,
        ]);
    }

    public function create()
    {
        return view('gestion.ejercicios.create', [
            'ejercicio' => new Ejercicio(['tipo' => 'completar']),
            'lecciones' => Leccion::orderBy('titulo')->get(),
        ]);
    }

    public function store(StoreEjercicioRequest $request)
    {
        Ejercicio::create($request->datosEjercicio());

        return redirect()->route('gestion.ejercicios.index')
            ->with('success', 'El ejercicio se creó correctamente.');
    }

    public function edit(Ejercicio $ejercicio)
    {
        return view('gestion.ejercicios.edit', [
            'ejercicio' => $ejercicio,
            'lecciones' => Leccion::orderBy('titulo')->get(),
        ]);
    }

    public function update(UpdateEjercicioRequest $request, Ejercicio $ejercicio)
    {
        $ejercicio->update($request->datosEjercicio());

        return redirect()->route('gestion.ejercicios.index')
            ->with('success', 'El ejercicio se actualizó correctamente.');
    }

    public function destroy(Ejercicio $ejercicio)
    {
        try {
            $ejercicio->delete();
        } catch (QueryException) {
            return redirect()->route('gestion.ejercicios.index')
                ->with('error', 'No se pudo eliminar el ejercicio. Inténtalo de nuevo.');
        }

        return redirect()->route('gestion.ejercicios.index')
            ->with('success', 'El ejercicio se eliminó correctamente.');
    }
}
