<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Cambiar Contraseña</title>

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
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div class="mb-4">
                    <p class="text-uppercase fw-bold small mb-1" style="color: #52154E; letter-spacing: 1px;">
                        SEGURIDAD DE LA CUENTA
                    </p>

                    <h1 class="h3 fw-bold mb-2" style="color: #111344;">
                        Crea tu Nueva Contraseña
                    </h1>

                    <p class="small text-muted mb-0">
                        Ingresa tu nueva contraseña y confírmala para recuperar el acceso a tu cuenta.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger text-start">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success text-start">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div class="mb-3 text-start">
                        <label for="passNueva" class="form-label small fw-bold" style="color: #111344;">
                            Nueva Contraseña
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-color: #CBD5E1;">
                                <i class="bi bi-key text-muted"></i>
                            </span>

                            <input type="password" id="passNueva" name="password" class="form-control border-start-0"
                                style="border-color: #CBD5E1;" placeholder="Mínimo 8 caracteres" minlength="8" required
                                autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mb-4 text-start">
                        <label for="passConfirmar" class="form-label small fw-bold" style="color: #111344;">
                            Confirmar Nueva Contraseña
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-color: #CBD5E1;">
                                <i class="bi bi-check2-circle text-muted"></i>
                            </span>

                            <input type="password" id="passConfirmar" name="password_confirmation"
                                class="form-control border-start-0" style="border-color: #CBD5E1;"
                                placeholder="Repite tu nueva contraseña" minlength="8" required
                                autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn text-white rounded-pill w-100 py-2 fw-semibold"
                        style="background-color: #52154E; border-color: #52154E;">
                        Crear Nueva Contraseña
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">
                        ¿Cancelar proceso?
                        <a href="{{ url('/login') }}" class="fw-bold text-decoration-none" style="color: #52154E;">
                            Volver al Login
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-white border-top py-3 mt-auto text-center">
        <p class="mb-0 text-muted small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
