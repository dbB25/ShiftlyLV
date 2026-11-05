<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Recuperar Contraseña</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body
    style="background-color: #DAE0F2; font-family: sans-serif; margin: 0; min-height: 100vh; display: flex; flex-direction: column;">

    <header class="bg-white shadow-sm sticky-top py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-3 text-decoration-none"
                href="{{ url('/') }}" style="color: #111344;">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="40" height="40">
                <span>Shiftly</span>
            </a>
        </div>
    </header>

    <main class="py-5 flex-grow-1 d-flex align-items-center">
        <div class="container" style="max-width: 500px;">
            <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 text-center" style="background-color: #FFFFFF;">

                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 70px; height: 70px; background-color: #EFE9E7; color: #52154E; font-size: 32px;">
                    <i class="bi bi-key-fill"></i>
                </div>

                <div class="mb-4">
                    <p class="text-uppercase fw-bold small mb-1" style="color: #52154E; letter-spacing: 1px;">
                        ACCESO A TU CUENTA
                    </p>

                    <h1 class="h3 fw-bold mb-2" style="color: #111344;">
                        ¿Olvidaste tu contraseña?
                    </h1>

                    <p class="small text-muted mb-0">
                        Ingresa tu correo electrónico registrado para enviarte las instrucciones de recuperación.
                    </p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success text-start" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger text-start" role="alert">
                        @foreach ($errors->all() as $error)
                            <p class="mb-1">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form id="formRecuperar" action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="mb-4 text-start">
                        <label for="email" class="form-label small fw-bold" style="color: #111344;">
                            Correo Electrónico
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-color: #CBD5E1;">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>

                            <input type="email" id="email" name="email" class="form-control border-start-0"
                                style="border-color: #CBD5E1;" placeholder="ejemplo@correo.com"
                                value="{{ old('email') }}" autocomplete="email" required>
                        </div>
                    </div>

                    <button type="submit" class="btn text-white rounded-pill w-100 py-2 fw-semibold"
                        style="background-color: #52154E; border-color: #52154E;">
                        Enviar Enlace
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">
                        ¿Recordaste tu contraseña?
                        <a href="{{ url('/login') }}" class="fw-bold text-decoration-none" style="color: #52154E;">
                            Iniciar Sesión
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-white border-top py-3 mt-auto text-center">
        <p class="mb-0 text-muted small">
            &copy; 2026 Shiftly. Todos los derechos reservados.
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
