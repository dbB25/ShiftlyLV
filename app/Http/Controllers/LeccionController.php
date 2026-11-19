<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeccionRequest;
use App\Http\Requests\UpdateLeccionRequest;
use App\Models\Area;
use App\Models\Leccion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LeccionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $areaId = $request->integer('area') ?: null;

        $lecciones = Leccion::with('area')
            ->withCount(['ejercicios', 'progresos'])
            ->buscar($buscar)
            ->when($areaId, fn ($consulta) => $consulta->where('area_id', $areaId))
            ->orderBy('area_id')
            ->orderBy('orden')
            ->paginate(10)
            ->withQueryString();

        return view('gestion.lecciones.index', [
            'lecciones' => $lecciones,
            'areas' => Area::orderBy('nombre')->get(),
            'buscar' => $buscar,
            'areaId' => $areaId,
        ]);
    }

    public function create()
    {
        return view('gestion.lecciones.create', [
            'leccion' => new Leccion(['nivel' => 'A1', 'orden' => 1, 'publicada' => false]),
            'areas' => Area::orderBy('nombre')->get(),
        ]);
    }

    public function store(StoreLeccionRequest $request)
    {
        Leccion::create($request->validated());

        return redirect()->route('gestion.lecciones.index')
            ->with('success', 'La lección se creó correctamente.');
    }

    public function edit(Leccion $leccion)
    {
        return view('gestion.lecciones.edit', [
            'leccion' => $leccion,
            'areas' => Area::orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdateLeccionRequest $request, Leccion $leccion)
    {
        $leccion->update($request->validated());

        return redirect()->route('gestion.lecciones.index')
            ->with('success', 'La lección se actualizó correctamente.');
    }

    /**
     * Una lección con progreso de estudiantes no se borra, para no perder
     * su historial. Si solo tiene ejercicios, se borran junto con ella
     * (la llave foránea usa cascadeOnDelete).
     */
    public function destroy(Leccion $leccion)
    {
        $progresos = $leccion->progresos()->count();

        if ($progresos > 0) {
            return redirect()->route('gestion.lecciones.index')
                ->with('error', "No se puede eliminar «{$leccion->titulo}» porque {$progresos} "
                    . ($progresos === 1 ? 'estudiante tiene' : 'estudiantes tienen')
                    . ' progreso registrado en ella. Puedes despublicarla en su lugar.');
        }

        try {
            $leccion->delete();
        } catch (QueryException) {
            return redirect()->route('gestion.lecciones.index')
                ->with('error', 'No se pudo eliminar la lección porque tiene registros relacionados.');
        }

        return redirect()->route('gestion.lecciones.index')
            ->with('success', 'La lección y sus ejercicios se eliminaron correctamente.');
    }
}
