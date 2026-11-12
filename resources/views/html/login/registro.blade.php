<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Crear Cuenta</title>

    <link rel="icon" href="data:,">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Corporativos Auth -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v=3">
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
                <a href="{{ url('/login') }}" class="btn btn-3d-glass fs-6">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         CONTENIDO PRINCIPAL: REGISTRO 3D
         ========================================== -->
    <main class="auth-wrapper flex-grow-1 d-flex align-items-center justify-content-center py-5 px-3">
        <div class="login-card-3d">
            <div class="row g-0">

                <!-- Panel Izquierdo Gamificado -->
                <div class="col-lg-5 login-side-panel p-4 p-md-5 d-none d-lg-flex flex-column justify-content-between">
                    <div>
                        <span class="badge rounded-pill px-3 py-2 fw-extrabold mb-3"
                              style="background-color: rgba(255, 107, 53, 0.22); color: #FFB08E; border: 1.5px solid rgba(255, 107, 53, 0.45);">
                            <i class="bi bi-rocket-takeoff-fill me-1" style="color: var(--orange-bright);"></i> EMPIEZA HOY GRATIS
                        </span>

                        <h2 class="h3 fw-extrabold mb-3" style="line-height: 1.25;">
                            Domina el vocabulario técnico de tu profesión.
                        </h2>

                        <p class="text-white-50 small mb-4">
                            Crea tu perfil en Shiftly, elige tu especialidad universitaria y desbloquea retos interactivos diseñados para tu carrera.
                        </p>

                        <div class="d-flex flex-column gap-3">
                            <div class="login-stat-pill d-flex align-items-center gap-3">
                                <i class="bi bi-compass-fill fs-4 text-warning"></i>
                                <div class="small">
                                    <div class="fw-extrabold text-white">Diagnóstico Personalizado</div>
                                    <div class="text-white-50">Ubica tu nivel real del A1 al C1</div>
                                </div>
                            </div>

                            <div class="login-stat-pill d-flex align-items-center gap-3">
                                <i class="bi bi-trophy-fill fs-4" style="color: var(--orange-bright);"></i>
                                <div class="small">
                                    <div class="fw-extrabold text-white">Progreso y Puntos XP</div>
                                    <div class="text-white-50">Guarda tus avances y mantén tu racha</div>
                                </div>
                            </div>

                            <div class="login-stat-pill d-flex align-items-center gap-3">
                                <i class="bi bi-volume-up-fill fs-4 text-info"></i>
                                <div class="small">
                                    <div class="fw-extrabold text-white">+500 Términos Reales</div>
                                    <div class="text-white-50">Con audio nativo y ejemplos prácticos</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-top border-white border-opacity-10 small text-white-50">
                        Universidad Politécnica de Pachuca · Shiftly 2026
                    </div>
                </div>

                <!-- Panel Derecho: Formulario de Registro -->
                <div class="col-lg-7 p-4 p-sm-5 bg-white">
                    <div class="mb-4">
                        <span class="badge rounded-pill px-3 py-2 fw-extrabold text-uppercase mb-2"
                              style="background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-stars me-1" style="color: var(--orange-bright);"></i> NUEVO PERFIL
                        </span>
                        <h1 class="h2 fw-extrabold mb-1" style="color: var(--purple-dark);">Crear Cuenta</h1>
                        <p class="text-muted small mb-0">
                            Únete a Shiftly y potencia tu inglés técnico en menos de 1 minuto.
                        </p>
                    </div>

                    <form id="formularioRegistro" method="POST" action="{{ route('registro.registrar') }}" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger rounded-4 py-2 px-3 mb-3">
                                <ul class="mb-0 small fw-bold ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Nombre Completo -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Nombre Completo
                            </label>
                            <div class="input-group-shiftly">
                                <i class="bi bi-person-fill icon-input"></i>
                                <input
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="{{ old('nombre') }}"
                                    class="input-auth"
                                    placeholder="Ej. Luis David Balandrano"
                                    autocomplete="name"
                                    required>
                            </div>
                        </div>

                        <!-- Especialidad / Carrera -->
                        <div class="mb-3">
                            <label for="carrera" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Carrera o Especialidad
                            </label>
                            <div class="input-group-shiftly">
                                <i class="bi bi-mortarboard-fill icon-input"></i>
                                <select id="carrera" name="carrera" class="select-auth" required>
                                    <option value="Sistemas y Software" selected>Sistemas y Software</option>
                                    <option value="Medicina y Salud">Medicina y Salud</option>
                                    <option value="Derecho Corporativo">Derecho Corporativo</option>
                                    <option value="Gastronomía">Gastronomía</option>
                                    <option value="Arquitectura">Arquitectura</option>
                                </select>
                            </div>
                        </div>

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
                                    class="input-auth"
                                    placeholder="ejemplo@correo.com"
                                    autocomplete="email"
                                    required>
                            </div>
                        </div>

                        <!-- Contraseña + Medidor Interactivo -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label small fw-extrabold mb-0" style="color: var(--purple-dark);">
                                    Contraseña
                                </label>
                                <span id="passwordStrengthText" class="small fw-bold text-muted" style="font-size: 0.76rem;">
                                    Mínimo 8 caracteres
                                </span>
                            </div>

                            <div class="input-group-shiftly">
                                <i class="bi bi-lock-fill icon-input"></i>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="input-auth"
                                    placeholder="Crea una contraseña segura"
                                    autocomplete="new-password"
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

                            <!-- Barra visual de seguridad -->
                            <div class="strength-meter-bar">
                                <div id="passwordStrengthBar" class="strength-meter-fill"></div>
                            </div>
                        </div>

                        <!-- Botón Submit 3D -->
                        <button type="submit" id="btnSubmitRegistro" class="btn btn-3d-orange w-100 py-3 fs-6">
                            <span>Crear Mi Cuenta</span>
                            <i class="bi bi-person-plus-fill fs-5"></i>
                        </button>

                        <!-- Contenedor dinámico para alertas de validación -->
                        <div id="mensajeRegistro" class="alert d-none mt-3 rounded-4 py-2 px-3 small fw-bold" role="status" aria-live="polite"></div>
                    </form>

                    <!-- Pie hacia Login -->
                    <div class="text-center mt-4 pt-3 border-top" style="border-color: var(--border-purple) !important;">
                        <p class="text-muted small mb-0">
                            ¿Ya tienes una cuenta?
                            <a href="{{ url('/login') }}" class="fw-extrabold text-decoration-none ms-1" style="color: var(--orange-bright);">
                                Inicia sesión <i class="bi bi-arrow-right-short"></i>
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
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/registro.js') }}"></script>
</body>

</html>