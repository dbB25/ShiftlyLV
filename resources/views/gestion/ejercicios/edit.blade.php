@extends('layouts.app')

@section('title', 'Editar ejercicio')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="{{ route('gestion.ejercicios.index') }}" class="small text-decoration-none">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Volver a ejercicios
            </a>
            <h1 class="h2 fw-bold mt-2 mb-4">Editar ejercicio</h1>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('gestion.ejercicios.update', $ejercicio) }}">
                        @csrf
                        @method('PUT')

                        @include('gestion.ejercicios._form')

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            <a href="{{ route('gestion.ejercicios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
