<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#52154E">
    <title>@yield('title', 'Panel de contenido') | Shiftly</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
    @stack('styles')
</head>

<body class="gestion-body">
    <nav class="navbar navbar-expand-lg navbar-dark gestion-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('dashboard') }}">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="" width="32" height="32">
                Shiftly
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#menuGestion" aria-controls="menuGestion" aria-expanded="false"
                aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuGestion">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('gestion.lecciones.*')) active @endif"
                            href="{{ route('gestion.lecciones.index') }}">Lecciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('gestion.ejercicios.*')) active @endif"
                            href="{{ route('gestion.ejercicios.index') }}">Ejercicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ejercicios') }}">Practicar</a>
                    </li>
                </ul>

                @auth
                    <form method="POST" action="{{ route('logout') }}" class="d-flex">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>Cerrar sesión
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4 py-lg-5">
        @include('partials.alertas')

        @yield('content')
    </main>

    <footer class="gestion-footer py-3">
        <div class="container small text-center">Shiftly · Panel de contenido</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
