<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Verificación de Correo</title>

    <link rel="icon" href="data:,">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Exclusivos de Verificación -->
    <link rel="stylesheet" href="{{ asset('css/verificacion-correo.css') }}?v=1">
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
         CONTENIDO PRINCIPAL: VERIFICACIÓN OTP 3D
         ========================================== -->
    <main class="auth-wrapper py-5 px-3 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="verify-card-3d p-4 p-md-5 text-center">

            <!-- Icono Destacado 3D -->
            <div class="mail-badge-3d">
                <i class="bi bi-envelope-check-fill"></i>
            </div>

            <!-- Encabezado -->
            <div class="mb-4">
                <span class="badge rounded-pill px-3 py-2 fw-extrabold text-uppercase mb-2"
                      style="background-color: var(--purple-soft); color: var(--purple-main); letter-spacing: 0.8px;">
                    <i class="bi bi-shield-check me-1" style="color: var(--orange-bright);"></i> VERIFICACIÓN REQUERIDA
                </span>

                <h1 class="h2 fw-extrabold mb-2" style="color: var(--purple-dark);">Revisa tu Correo</h1>

                <p class="small text-muted mb-3 mx-auto" style="max-width: 390px;">
                    Hemos enviado un código de verificación de 4 dígitos a tu correo electrónico registrado:
                </p>

                <!-- Correo detectado dinámicamente desde sessionStorage -->
                <div class="target-email-badge" id="correoDestinoBadge">
                    <i class="bi bi-envelope-at-fill"></i>
                    <span id="correoDestinoTexto">tu correo registrado</span>
                </div>
            </div>

            <!-- Barra de Progreso Gamificada -->
            <div class="mb-4 text-start">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted">Progreso del código:</span>
                    <span id="textoProgreso" class="small fw-extrabold" style="color: var(--purple-main);">0%</span>
                </div>
                <div class="progress progress-shiftly">
                    <div id="barraProgreso" class="progress-bar progress-bar-shiftly" role="progressbar"
                         style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            <!-- Contenedor para Mensajes Dinámicos -->
            <div id="mensajeVerificacion" class="alert d-none text-start mb-3 rounded-4 py-2 px-3 small fw-bold" role="alert"></div>

            <!-- Formulario de Verificación (Redirige a /new-password) -->
            <form id="formularioVerificacion" data-redirect="{{ url('/new-password') }}" novalidate>
                <!-- Inputs del Código de 4 Dígitos -->
                <div class="d-flex justify-content-center gap-2 gap-sm-3 mb-4" id="grupoInputsOtp">
                    <input type="text" inputmode="numeric" maxlength="1" class="form-control codigo otp-input-3d" aria-label="Primer dígito" autocomplete="one-time-code">
                    <input type="text" inputmode="numeric" maxlength="1" class="form-control codigo otp-input-3d" aria-label="Segundo dígito">
                    <input type="text" inputmode="numeric" maxlength="1" class="form-control codigo otp-input-3d" aria-label="Tercer dígito">
                    <input type="text" inputmode="numeric" maxlength="1" class="form-control codigo otp-input-3d" aria-label="Cuarto dígito">
                </div>

                <button type="submit" id="btnVerificar" class="btn btn-3d-orange w-100 py-3 fs-6">
                    <span>Verificar Cuenta</span>
                    <i class="bi bi-check2-circle fs-5"></i>
                </button>
            </form>

            <!-- Reenviar Código con Temporizador Interactivo -->
            <div class="mt-4 pt-3 border-top" style="border-color: var(--border-purple) !important;">
                <p class="small text-muted mb-1">
                    ¿No recibiste el correo?
                    <a href="#" id="reenviarCodigo" class="fw-extrabold text-decoration-none ms-1" style="color: var(--orange-bright);">
                        Reenviar código <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </p>
                <span id="contadorReenvio" class="small text-muted d-none" style="font-size: 0.78rem;"></span>
            </div>

        </div>
    </main>

    <!-- ==========================================
         FOOTER MORADO CORPORATIVO
         ========================================== -->
    <footer class="footer-shiftly py-4 mt-auto">
        <div class="container-xl d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="26" height="26">
                <span class="fw-bold small">Shiftly</span>
            </div>
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Lógico de Verificación -->
    <script src="{{ asset('js/verificacion-correo.js') }}"></script>
</body>

</html>