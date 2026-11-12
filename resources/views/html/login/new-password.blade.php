<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Crear Nueva Contraseña</title>

    <link rel="icon" href="data:,">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Exclusivos de Nueva Contraseña -->
    <link rel="stylesheet" href="{{ asset('css/new-password.css') }}?v=1">
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
         CONTENIDO PRINCIPAL: NUEVA CONTRASEÑA 3D
         ========================================== -->
    <main class="auth-wrapper py-5 px-3 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="password-card-3d p-4 p-md-5 text-center">

            <!-- Icono Destacado 3D -->
            <div class="shield-badge-3d">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <!-- Encabezado -->
            <div class="mb-4">
                <span class="badge rounded-pill px-3 py-2 fw-extrabold text-uppercase mb-2"
                      style="background-color: var(--purple-soft); color: var(--purple-main); letter-spacing: 0.8px;">
                    <i class="bi bi-key-fill me-1" style="color: var(--orange-bright);"></i> SEGURIDAD DE LA CUENTA
                </span>

                <h1 class="h2 fw-extrabold mb-2" style="color: var(--purple-dark);">Crea tu Nueva Contraseña</h1>

                <p class="small text-muted mb-3 mx-auto" style="max-width: 400px;">
                    Ingresa tu contraseña actual y escribe la nueva clave para actualizar el acceso a tu cuenta.
                </p>

                <!-- Cuenta detectada desde recuperación -->
                <div class="account-pill-badge d-none" id="cuentaRecuperadaBadge">
                    <i class="bi bi-person-check-fill"></i>
                    <span id="cuentaRecuperadaTexto"></span>
                </div>
            </div>

            <!-- Contenedor para Mensajes Dinámicos -->
            <div id="mensajeCambio" class="alert d-none text-start mb-3 rounded-4 py-2 px-3 small fw-bold" role="alert"></div>

            <!-- Formulario de Cambio de Contraseña -->
            <form id="formCambiarPassword" data-redirect="{{ url('/login') }}" novalidate>

                <!-- Contraseña Actual -->
                <div class="mb-3 text-start">
                    <label for="passActual" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                        Contraseña Actual
                    </label>
                    <div class="input-group-shiftly">
                        <i class="bi bi-lock-fill icon-input"></i>
                        <input type="password" id="passActual" class="input-auth" placeholder="••••••••" autocomplete="current-password" required>
                        <button class="btn-eye-toggle toggle-pass" type="button" data-target="passActual" aria-label="Mostrar contraseña actual">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Nueva Contraseña + Medidor -->
                <div class="mb-3 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="passNueva" class="form-label small fw-extrabold mb-0" style="color: var(--purple-dark);">
                            Nueva Contraseña
                        </label>
                        <span id="newPassStrengthText" class="small fw-bold text-muted" style="font-size: 0.75rem;">
                            Mínimo 6 caracteres
                        </span>
                    </div>
                    <div class="input-group-shiftly">
                        <i class="bi bi-key-fill icon-input"></i>
                        <input type="password" id="passNueva" class="input-auth" placeholder="Mínimo 6 caracteres" autocomplete="new-password" required>
                        <button class="btn-eye-toggle toggle-pass" type="button" data-target="passNueva" aria-label="Mostrar nueva contraseña">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="strength-meter-bar">
                        <div id="newPassStrengthBar" class="strength-meter-fill"></div>
                    </div>
                </div>

                <!-- Confirmar Nueva Contraseña -->
                <div class="mb-4 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="passConfirmar" class="form-label small fw-extrabold mb-0" style="color: var(--purple-dark);">
                            Confirmar Nueva Contraseña
                        </label>
                        <span id="matchStatusText" class="small fw-bold" style="font-size: 0.75rem;"></span>
                    </div>
                    <div class="input-group-shiftly">
                        <i class="bi bi-check2-circle icon-input"></i>
                        <input type="password" id="passConfirmar" class="input-auth" placeholder="Repite tu nueva contraseña" autocomplete="new-password" required>
                        <button class="btn-eye-toggle toggle-pass" type="button" data-target="passConfirmar" aria-label="Mostrar confirmación">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btnCrearPassword" class="btn btn-3d-orange w-100 py-3 fs-6">
                    <span>Crear Nueva Contraseña</span>
                    <i class="bi bi-shield-check fs-5"></i>
                </button>
            </form>

            <!-- Cancelar y regresar al Login -->
            <div class="mt-4 pt-3 border-top" style="border-color: var(--border-purple) !important;">
                <p class="small text-muted mb-0">
                    ¿Cancelar proceso?
                    <a href="{{ url('/login') }}" class="fw-extrabold text-decoration-none ms-1" style="color: var(--orange-bright);">
                        Volver al Login <i class="bi bi-arrow-right-short"></i>
                    </a>
                </p>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Exclusivo de Nueva Contraseña -->
    <script src="{{ asset('js/new-password.js') }}"></script>
</body>

</html>