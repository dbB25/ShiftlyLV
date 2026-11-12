<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Recuperar Contraseña</title>

    <link rel="icon" href="data:,">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Propios -->
    <link rel="stylesheet" href="{{ asset('css/recuperar-password.css') }}?v=1">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- ==========================================
         NAVBAR MORADO CORPORATIVO
         ========================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-shiftly sticky-top py-3">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-extrabold fs-3 text-decoration-none text-white" href="{{ url('/') }}" style="color: #FFFFFF !important;">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="40" height="40">
                <span style="color: #FFFFFF !important;">Shiftly</span>
            </a>

            <div class="d-flex align-items-center gap-2 ms-auto">
                <a href="{{ url('/') }}" class="nav-link nav-link-app d-none d-sm-inline-block">Inicio</a>
                <a href="{{ url('/login') }}" class="btn btn-3d-glass fs-6">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         CONTENIDO PRINCIPAL
         ========================================== -->
    <main class="auth-wrapper py-5 px-3 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="recovery-card-3d p-4 p-md-5 text-center">

            <div class="key-badge-3d">
                <i class="bi bi-key-fill"></i>
            </div>

            <div class="mb-4">
                <span class="badge rounded-pill px-3 py-2 fw-extrabold text-uppercase mb-2"
                      style="background-color: var(--purple-soft); color: var(--purple-main); letter-spacing: 0.8px;">
                    <i class="bi bi-shield-lock-fill me-1" style="color: var(--orange-bright);"></i> ACCESO A TU CUENTA
                </span>

                <h1 class="h2 fw-extrabold mb-2" style="color: var(--purple-dark);">
                    ¿Olvidaste tu contraseña?
                </h1>

                <p class="small text-muted mb-0 mx-auto" style="max-width: 390px;">
                    Ingresa tu correo electrónico registrado y te enviaremos un enlace seguro para restablecer tu acceso.
                </p>
            </div>

            <!-- ALERTA DINÁMICA DE ERRORES -->
            <div id="mensajeRecuperacion" class="alert d-none text-start rounded-4 py-2 px-3 small fw-bold" role="alert"></div>

            @if (session('status'))
                <div class="alert alert-success text-start rounded-4 py-2 px-3 small fw-bold" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger text-start rounded-4 py-2 px-3 small fw-bold" role="alert">
                    @foreach ($errors->all() as $error)
                        <p class="mb-1">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Formulario de Recuperación -->
            <form id="formRecuperar" action="{{ route('password.email') }}" method="POST" novalidate>
                @csrf

                <div class="mb-4 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="email" class="form-label small fw-extrabold mb-0" style="color: var(--purple-dark);">
                            Correo Electrónico
                        </label>
                        <span id="estadoCuentaBadge" class="small fw-bold text-muted" style="font-size: 0.75rem;"></span>
                    </div>

                    <div class="input-group-shiftly">
                        <i class="bi bi-envelope-fill icon-input"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input-auth"
                            placeholder="ejemplo@correo.com"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required>
                    </div>
                </div>

                <button type="submit" id="btnEnviarEnlace" class="btn btn-3d-orange w-100 py-3 fs-6">
                    <span>Enviar Enlace</span>
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>

            <div class="mt-4 pt-3 border-top" style="border-color: var(--border-purple) !important;">
                <p class="small text-muted mb-0">
                    ¿Recordaste tu contraseña?
                    <a href="{{ url('/login') }}" class="fw-extrabold text-decoration-none ms-1" style="color: var(--orange-bright);">
                        Iniciar Sesión <i class="bi bi-arrow-right-short"></i>
                    </a>
                </p>
            </div>

        </div>
    </main>

    <!-- ==========================================
         VENTANA EMERGENTE (MODAL DE CONFIRMACIÓN)
         ========================================== -->
    <div class="modal fade" id="modalConfirmacionCorreo" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-shiftly-content text-center p-4">
                <div class="modal-body p-2">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 74px; height: 74px; background-color: #DCFCE7; color: #15803D; font-size: 34px;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5 class="h4 fw-extrabold mb-2" id="modalLabel" style="color: var(--purple-dark);">¡Enlace Enviado!</h5>
                    <p class="text-muted small mb-3">
                        Hemos enviado las instrucciones de recuperación a la dirección de correo:
                    </p>
                    <div class="p-3 mb-4 email-pill-box text-break fw-extrabold" id="textoCorreoEnviado">
                        --
                    </div>
                    <button type="button" id="btnAceptarModal" class="btn btn-3d-orange w-100 py-3">
                        <span>Aceptar y Continuar</span>
                        <i class="bi bi-arrow-right-circle-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         FOOTER MORADO CORPORATIVO
         ========================================== -->
    <footer class="footer-shiftly py-4 mt-auto">
        <div class="container-xl d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="26" height="26">
                <span class="fw-bold small">Shiftly</span>
            </div>
            <p class="mb-0 text-white-50 small">
                &copy; 2026 Shiftly. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS & Script de Recuperación -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/recuperacion-password.js') }}"></script>

</body>

</html>