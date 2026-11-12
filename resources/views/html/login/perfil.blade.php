<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Mi Perfil</title>

    <link rel="icon" href="data:,">

    <!-- Bootstrap CSS CDN & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Exclusivos de Perfil -->
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}?v=1">
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

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPerfil">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarPerfil">
                <div class="navbar-nav ms-auto gap-1 my-2 my-lg-0">
                    <a href="{{ url('/') }}" class="nav-link nav-link-app">Inicio</a>
                    <a href="{{ url('/dashboard') }}" class="nav-link nav-link-app">Dashboard</a>
                    <a href="{{ url('/ejercicios') }}" class="nav-link nav-link-app">Aprender</a>
                    <a href="{{ url('/glosario') }}" class="nav-link nav-link-app">Glosario</a>
                    <a href="{{ url('/perfil') }}" class="nav-link nav-link-app active">Mi Perfil</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         CONTENIDO PRINCIPAL: MI PERFIL 3D
         ========================================== -->
    <main class="profile-wrapper py-5 px-3 flex-grow-1">
        <div class="container-xl" style="max-width: 880px;">

            <!-- TARJETA SUPERIOR: ENCABEZADO DEL USUARIO -->
            <div class="profile-card-3d mb-4">
                <div class="profile-banner-top"></div>

                <div class="px-4 px-md-5 pb-4">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-3 gap-md-4" style="margin-top: -52px;">
                        <!-- Avatar Circular con Iniciales Dinámicas -->
                        <div class="position-relative flex-shrink-0">
                            <div class="avatar-perfil-contenedor" id="avatarContenedor" title="Avatar de usuario">
                                <span id="avatarIniciales">UD</span>
                            </div>
                            <span class="online-dot" title="En línea">
                                <span class="visually-hidden">En línea</span>
                            </span>
                        </div>

                        <!-- Nombre y Badges -->
                        <div class="text-center text-md-start flex-grow-1 pt-2">
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-start align-items-center gap-2 mb-1">
                                <span class="badge rounded-pill px-3 py-1 fw-extrabold text-uppercase"
                                      style="background-color: var(--purple-soft); color: var(--purple-main); font-size: 0.72rem;">
                                    USUARIO ACTIVO
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                    <i class="bi bi-patch-check-fill me-1"></i>Cuenta Verificada
                                </span>
                            </div>

                            <h1 class="h3 fw-extrabold mb-1" id="encabezadoNombre" style="color: var(--purple-dark);">
                                Usuario Demo
                            </h1>
                            <p class="text-muted mb-0 small fw-semibold" id="encabezadoSubtitulo">
                                Sistemas y Desarrollo · Estudiante Shiftly
                            </p>
                        </div>

                        <!-- Botón Restablecer Perfil (DELETE en localStorage) -->
                        <div class="mt-2 mt-md-0">
                            <button type="button" id="btnRestablecerPerfil" class="btn btn-3d-danger-outline" title="Restaurar valores por defecto">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer
                            </button>
                        </div>
                    </div>

                    <!-- Mini Resumen Gamificado -->
                    <div class="row g-3 mt-3 pt-3 border-top" style="border-color: var(--border-purple) !important;">
                        <div class="col-4">
                            <div class="stat-mini-card">
                                <div class="fw-extrabold fs-5" style="color: var(--orange-bright);">
                                    <i class="bi bi-fire"></i> 5 días
                                </div>
                                <div class="small text-muted fw-bold">Racha Actual</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-mini-card">
                                <div class="fw-extrabold fs-5" style="color: var(--purple-main);">
                                    <i class="bi bi-lightning-charge-fill text-warning"></i> 420 XP
                                </div>
                                <div class="small text-muted fw-bold">Experiencia</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-mini-card">
                                <div class="fw-extrabold fs-5" id="statNivelBadge" style="color: var(--purple-dark);">
                                    B1
                                </div>
                                <div class="small text-muted fw-bold">Nivel CEFR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA INFERIOR: FORMULARIO DE CONFIGURACIÓN -->
            <div class="profile-card-3d p-4 p-md-5">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4 pb-3 border-bottom gap-2" style="border-color: var(--border-purple) !important;">
                    <h2 class="h5 fw-extrabold mb-0 d-flex align-items-center gap-2" style="color: var(--purple-dark);">
                        <span class="rounded-3 d-inline-flex align-items-center justify-content-center"
                              style="width: 36px; height: 36px; background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-sliders"></i>
                        </span>
                        Información Personal
                    </h2>
                    <span class="small fw-bold" id="ultimaActualizacionTexto" style="color: var(--text-muted); font-size: 0.78rem;">
                        Edita los datos de tu cuenta
                    </span>
                </div>

                <!-- Alerta Dinámica de Estado -->
                <div id="alertaPerfil" class="alert d-none rounded-4 py-2 px-3 small fw-bold" role="alert"></div>

                <form id="formularioPerfil" method="POST" action="{{ route('perfil.actualizar') }}" novalidate>
                    @csrf
                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-6 text-start">
                            <label for="nombre-perfil" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Nombre
                            </label>
                            <div class="input-group-shiftly">
                                <i class="bi bi-person-fill icon-input"></i>
                                <input type="text" id="nombre-perfil" name="nombre" class="input-auth" value="Usuario" placeholder="Tu nombre" required>
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-6 text-start">
                            <label for="apellido-perfil" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Apellidos
                            </label>
                            <div class="input-group-shiftly">
                                <i class="bi bi-person-vcard-fill icon-input"></i>
                                <input type="text" id="apellido-perfil" name="apellido" class="input-auth" value="Demo" placeholder="Tus apellidos" required>
                            </div>
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="col-md-12 text-start">
                            <label for="email-perfil" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Correo Electrónico
                            </label>
                            <div class="input-group-shiftly">
                                <i class="bi bi-envelope-fill icon-input"></i>
                                <input type="email" id="email-perfil" name="email" class="input-auth" value="usuario@shiftly.com" placeholder="ejemplo@correo.com" required>
                            </div>
                        </div>

                        <!-- Área Profesional Principal -->
                        <div class="col-md-6 text-start">
                            <label for="area-perfil" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Área Profesional Principal
                            </label>
                            <div class="input-group-shiftly">
                                <i class="bi bi-briefcase-fill icon-input"></i>
                                <select id="area-perfil" name="area" class="select-auth">
                                    <option value="Sistemas y Desarrollo" selected>Sistemas y Desarrollo</option>
                                    <option value="Medicina">Medicina</option>
                                    <option value="Gastronomía">Gastronomía</option>
                                    <option value="Derecho">Derecho</option>
                                    <option value="Arquitectura">Arquitectura</option>
                                </select>
                            </div>
                        </div>

                        <!-- Nivel de Inglés Estimado -->
                        <div class="col-md-6 text-start">
                            <label for="nivel-perfil" class="form-label small fw-extrabold" style="color: var(--purple-dark);">
                                Nivel de Inglés Estimado
                            </label>
                            <div class="input-group-shiftly readonly-box">
                                <i class="bi bi-translate icon-input"></i>
                                <input type="text" id="nivel-perfil" name="nivel" class="input-auth" value="B1 Intermedio" readonly title="Nivel asignado por diagnóstico">
                                <span class="badge rounded-pill" style="background-color: var(--purple-soft); color: var(--purple-main); font-size: 0.7rem;">
                                    Diagnóstico
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción 3D -->
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-3 mt-4 pt-4 border-top" style="border-color: var(--border-purple) !important;">
                        <button type="button" id="btnCancelarPerfil" class="btn btn-3d-secondary">
                            <i class="bi bi-x-circle"></i> Descartar
                        </button>
                        <button type="submit" id="btnGuardarPerfil" class="btn btn-3d-orange">
                            <i class="bi bi-check-lg"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Interactivo Propio -->
    <script src="{{ asset('js/perfil.js') }}"></script>
</body>

</html>