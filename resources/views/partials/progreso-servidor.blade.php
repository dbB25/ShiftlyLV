{{--
    Progreso del alumno desde la base de datos.
    Se incluye en cada página que usa el progreso, ANTES de rutas-catalogo.js
    y shiftly-progreso.js, para que los scripts lean los datos del servidor.
--}}
@auth
    @php
        $shiftlyServidor = [
            'csrf' => csrf_token(),
            'usuario' => [
                'email' => mb_strtolower(auth()->user()->email),
                'nombre' => auth()->user()->name,
                'carrera' => auth()->user()->carrera,
            ],
            'urls' => [
                'rutas' => url('/progreso/rutas'),
                'actividad' => route('progreso.actividad'),
                'practica' => route('progreso.practica'),
                'diagnostico' => route('progreso.diagnostico'),
                'lecciones' => route('lecciones-personalizadas.index'),
            ],
            'progreso' => app(\App\Services\ProgresoUsuario::class)->paraNavegador(auth()->user()),
        ];
    @endphp
    <script>
        window.SHIFTLY_SERVIDOR = @json($shiftlyServidor);
    </script>
    <script src="{{ asset('js/shiftly-servidor.js') }}?v=1"></script>
@endauth
