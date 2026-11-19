@extends('layouts.app')

@section('title', 'Ejercicios')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-muted mb-1">Panel de contenido</p>
            <h1 class="h2 fw-bold mb-0">Ejercicios</h1>
        </div>
        <a href="{{ route('gestion.ejercicios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuevo ejercicio
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('gestion.ejercicios.index') }}" class="row g-2 align-items-end" role="search">
                <div class="col-12 col-md-6">
                    <label for="buscar" class="form-label small fw-semibold">Buscar</label>
                    <input type="search" id="buscar" name="buscar" value="{{ $buscar }}" class="form-control"
                        placeholder="Título o pregunta" maxlength="100">
                </div>
                <div class="col-12 col-md-4">
                    <label for="leccion" class="form-label small fw-semibold">Lección</label>
                    <select id="leccion" name="leccion" class="form-select">
                        <option value="">Todas las lecciones</option>
                        @foreach ($lecciones as $leccion)
                            <option value="{{ $leccion->id }}" @selected($leccionId === $leccion->id)>{{ $leccion->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill">Filtrar</button>
                    @if ($buscar !== '' || $leccionId)
                        <a href="{{ route('gestion.ejercicios.index') }}" class="btn btn-outline-secondary" title="Quitar filtros">
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
                <h2 class="h5 fw-bold mb-0">Ejercicios guardados</h2>
                <span class="badge text-bg-secondary">{{ $ejercicios->total() }}</span>
            </div>

            @if ($ejercicios->isEmpty())
                <p class="text-muted mb-0">
                    @if ($buscar !== '' || $leccionId)
                        No hay ejercicios que coincidan con la búsqueda.
                    @else
                        Todavía no hay ejercicios. Crea el primero con el botón «Nuevo ejercicio».
                    @endif
                </p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Ejercicio</th>
                                <th scope="col" class="d-none d-md-table-cell">Lección</th>
                                <th scope="col" class="d-none d-sm-table-cell">Tipo</th>
                                <th scope="col"><span class="visually-hidden">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ejercicios as $ejercicio)
                                <tr>
                                    <td>
                                        <strong>{{ $ejercicio->titulo }}</strong>
                                        <div class="small text-muted">{{ \Illuminate\Support\Str::limit($ejercicio->pregunta, 80) }}</div>
                                        <div class="small text-muted d-md-none">{{ $ejercicio->leccion?->titulo }}</div>
                                    </td>
                                    <td class="small d-none d-md-table-cell">
                                        {{ $ejercicio->leccion?->titulo ?? 'Sin lección' }}
                                        <div class="text-muted">{{ $ejercicio->leccion?->area?->nombre }}</div>
                                    </td>
                                    <td class="d-none d-sm-table-cell"><span class="badge text-bg-light">{{ str_replace('_', ' ', $ejercicio->tipo) }}</span></td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end acciones-tabla">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('gestion.ejercicios.edit', $ejercicio) }}">Editar</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                data-accion="{{ route('gestion.ejercicios.destroy', $ejercicio) }}"
                                                data-mensaje="¿Seguro que quieres eliminar el ejercicio «{{ $ejercicio->titulo }}»? Esta acción no se puede deshacer.">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $ejercicios->links() }}
            @endif
        </div>
    </div>

    @include('partials.confirmar-eliminar')
@endsection
