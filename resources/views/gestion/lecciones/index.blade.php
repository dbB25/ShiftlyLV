@extends('layouts.app')

@section('title', 'Lecciones')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-muted mb-1">Panel de contenido</p>
            <h1 class="h2 fw-bold mb-0">Lecciones</h1>
        </div>
        <a href="{{ route('gestion.lecciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nueva lección
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('gestion.lecciones.index') }}" class="row g-2 align-items-end" role="search">
                <div class="col-12 col-md-6">
                    <label for="buscar" class="form-label small fw-semibold">Buscar</label>
                    <input type="search" id="buscar" name="buscar" value="{{ $buscar }}" class="form-control"
                        placeholder="Título de la lección" maxlength="100">
                </div>
                <div class="col-12 col-md-4">
                    <label for="area" class="form-label small fw-semibold">Área</label>
                    <select id="area" name="area" class="form-select">
                        <option value="">Todas las áreas</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @selected($areaId === $area->id)>{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill">Filtrar</button>
                    @if ($buscar !== '' || $areaId)
                        <a href="{{ route('gestion.lecciones.index') }}" class="btn btn-outline-secondary" title="Quitar filtros">
                            <i class="bi bi-x-lg" aria-hidden="true"></i><span class="visually-hidden">Quitar filtros</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">Lecciones guardadas</h2>
                <span class="badge text-bg-secondary">{{ $lecciones->total() }}</span>
            </div>

            @if ($lecciones->isEmpty())
                <p class="text-muted mb-0">
                    @if ($buscar !== '' || $areaId)
                        No hay lecciones que coincidan con la búsqueda.
                    @else
                        Todavía no hay lecciones. Crea la primera con el botón «Nueva lección».
                    @endif
                </p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Lección</th>
                                <th scope="col" class="d-none d-md-table-cell">Área</th>
                                <th scope="col" class="d-none d-sm-table-cell">Nivel</th>
                                <th scope="col" class="text-center d-none d-md-table-cell">Ejercicios</th>
                                <th scope="col" class="d-none d-sm-table-cell">Estado</th>
                                <th scope="col"><span class="visually-hidden">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lecciones as $leccion)
                                <tr>
                                    <td>
                                        <strong>{{ $leccion->titulo }}</strong>
                                        <div class="small text-muted">Orden {{ $leccion->orden }}</div>
                                        <div class="small text-muted d-md-none">{{ $leccion->area?->nombre }} · {{ $leccion->nivel }}</div>
                                    </td>
                                    <td class="small d-none d-md-table-cell">{{ $leccion->area?->nombre }}</td>
                                    <td class="d-none d-sm-table-cell"><span class="badge text-bg-light">{{ $leccion->nivel }}</span></td>
                                    <td class="text-center d-none d-md-table-cell">{{ $leccion->ejercicios_count }}</td>
                                    <td class="d-none d-sm-table-cell">
                                        @if ($leccion->publicada)
                                            <span class="badge text-bg-success">Publicada</span>
                                        @else
                                            <span class="badge text-bg-secondary">Borrador</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end acciones-tabla">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('gestion.lecciones.edit', $leccion) }}">Editar</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                data-accion="{{ route('gestion.lecciones.destroy', $leccion) }}"
                                                data-mensaje="¿Seguro que quieres eliminar la lección «{{ $leccion->titulo }}»?{{ match (true) { $leccion->ejercicios_count === 1 => ' También se eliminará su ejercicio.', $leccion->ejercicios_count > 1 => ' También se eliminarán sus ' . $leccion->ejercicios_count . ' ejercicios.', default => '' } }}{{ $leccion->progresos_count > 0 ? ' Tiene progreso de estudiantes, así que no se podrá eliminar.' : '' }}">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $lecciones->links() }}
            @endif
        </div>
    </div>

    @include('partials.confirmar-eliminar')
@endsection
