<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Iniciar Sesión</title>

    <!-- Previene el error 404 del favicon -->
    <link rel="icon" href="data:,">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Exclusivos de Login -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v=1">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- ==========================================
         NAVBAR CORPORATIVO SHIFTLY
         ========================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-shiftly py-3">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-extrabold fs-3 text-decoration-none text-white" href="{{ url('/') }}" style="color: #FFFFFF !important;">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="40" height="40">
                <span style="color: #FFFFFF !important;">Shiftly</span>
            </a>

            <div class="d-flex align-items-center gap-2 ms-auto">
                <a href="{{ url('/') }}" class="nav-link nav-link-app d-none d-sm-inline-block">Inicio</a>
                <a href="{{ url('/registro') }}" class="btn btn-3d-glass fs-6">
                    Registrarse
                </a>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         CONTENIDO PRINCIPAL: LOGIN 3D
         ========================================== -->
    <main class="auth-wrapper flex-grow-1 d-flex align-items-center justify-content-center py-5 px-3">
        <div class="login-card-3d">
            <div class="row g-0">

                <!-- Panel Izquierdo Decorativo (Visible en Desktop) -->
                <div class="col-lg-5 login-side-panel p-4 p-md-5 d-none d-lg-flex flex-column justify-content-between">
                    <div>
                        <span class="badge rounded-pill px-3 py-2 fw-extrabold mb-3"
                              style="background-color: rgba(255, 107, 53, 0.22); color: #FFB08E; border: 1.5px solid rgba(255, 107, 53, 0.45);">
                            <i class="bi bi-fire me-1" style="color: var(--orange-bright);"></i> CONTINÚA TU RACHA
                        </span>

                        <h2 class="h3 fw-extrabold mb-3" style="line-height: 1.25;">
                            Tu entrenamiento en inglés profesional te espera.
                        </h2>

                        <p class="text-white-50 small mb-4">
                            Retoma tus lecciones técnicas, repasa tu glosario especializado y sigue sumando puntos XP hacia tu meta laboral.
                        </p>

                        <div class="d-flex flex-column gap-3">
                            <div class="login-stat-pill d-flex align-items-center gap-3">
                                <i class="bi bi-lightning-charge-fill fs-4 text-warning"></i>
                                <div class="small">
                                    <div class="fw-extrabold text-white">Lecciones de 5 minutos</div>
                                    <div class="text-white-50">Práctica rápida con audio nativo</div>
                                </div>
                            </div>

                            <div class="login-stat-pill d-flex align-items-center gap-3">
                                <i class="bi bi-mortarboard-fill fs-4" style="color: var(--orange-bright);"></i>
                                <div class="small">
                                    <div class="fw-extrabold text-white">5 Especialidades</div>
                                    <div class="text-white-50">Sistemas, Medicina, Derecho y más</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-top border-white border-opacity-10 small text-white-50">
                        Universidad Politécnica de Pachuca · Shiftly 2026
                    </div>
                </div>

                <!-- Panel Derecho: Formulario -->
                <div class="col-lg-7 p-4 p-sm-5 bg-white">
                    <div class="mb-4">
                        <span class="badge rounded-pill px-3 py-2 fw-extrabold text-uppercase mb-2"
                              style="background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-stars me-1" style="color: var(--orange-bright);"></i> ¡BIENVENIDO DE NUEVO!
                        </span>
                        <h1 class="h2 fw-extrabold mb-1" style="color: var(--purple-dark);">Iniciar Sesión</h1>
                        <p class="text-muted small mb-0">
                            Accede a tu cuenta para continuar aprendiendo.
                        </p>
                    </div>

                    <form id="formularioLogin" method="POST" action="{{ route('login.autenticar') }}">
                        @csrf

                        <!-- Correo Electrónico -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Correo Electrónico
                            </label>

                            <div class="input-group-shiftly">
                                <i class="bi bi-envelope-fill icon-input"></i>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="input-auth @error('email') is-invalid @enderror"
                                    placeholder="ejemplo@correo.com"
                                    autocomplete="email"
                                    required>
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label small fw-extrabold mb-0" style="color: var(--purple-dark);">
                                    Contraseña
                                </label>

                                <a href="{{ url('/recuperar-password') }}" class="small fw-bold text-decoration-none" style="color: var(--purple-medium);">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>

                            <div class="input-group-shiftly">
                                <i class="bi bi-lock-fill icon-input"></i>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="input-auth @error('password') is-invalid @enderror"
                                    placeholder="Ingresa tu contraseña"
                                    autocomplete="current-password"
                                    required>

                                <button
                                    type="button"
                                    id="mostrarPassword"
                                    class="btn-eye-toggle"
                                    aria-label="Mostrar contraseña"
                                    aria-pressed="false">
                                    <i class="bi bi-eye" id="iconoPassword"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Mensajes de Error de Laravel -->
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-4 py-2 px-3 mb-3" role="alert">
                                <ul class="mb-0 small fw-bold ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Botón Submit 3D -->
                        <button
                            type="submit"
                            id="btnSubmitLogin"
                            class="btn btn-3d-orange w-100 py-3 fs-6">
                            <span>Iniciar Sesión</span>
                            <i class="bi bi-box-arrow-in-right fs-5"></i>
                        </button>
                    </form>

                    <!-- Enlace a Registro -->
                    <div class="text-center mt-4 pt-3 border-top" style="border-color: var(--border-purple) !important;">
                        <p class="text-muted small mb-0">
                            ¿Aún no tienes una cuenta?
                            <a href="{{ url('/registro') }}" class="fw-extrabold text-decoration-none ms-1" style="color: var(--orange-bright);">
                                Regístrate aquí <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- ==========================================
         FOOTER CORPORATIVO SHIFTLY
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    <script src="{{ asset('js/loginmain.js') }}"></script>

</body>
</html>