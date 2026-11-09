<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Panel del Usuario</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos de Shiftly -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    <!-- NAVBAR CORPORATIVO -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="38" height="38">
                <span>Shiftly</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="navbar-nav ms-auto gap-2">
                    <a class="nav-link active" href="{{ url('/dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Panel del Usuario</a>
                    <a class="nav-link" href="{{ url('/ruta-aprendizaje') }}"><i class="bi bi-journal-bookmark me-1"></i> Ruta de Aprendizaje</a>
                    <a class="nav-link" href="{{ url('/resultados-leccion') }}"><i class="bi bi-bar-chart-line me-1"></i> Resultados</a>
                    <a class="nav-link" href="{{ url('/glosario') }}"><i class="bi bi-book me-1"></i> Glosario</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1">
        <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold mb-2" style="background-color: var(--orange-bright); color: var(--white);">Mi Cuenta</span>
            <h1 class="display-5 fw-extrabold mb-2" style="color: var(--purple-dark);">Panel del Usuario</h1>
            <p class="text-muted mx-auto" style="max-width: 550px;">Gestiona tu información personal y accede a las herramientas de aprendizaje técnico de Shiftly.</p>
        </div>

        <div class="row g-4">
            
            <!-- SECCIÓN: NAVEGACIÓN RÁPIDA -->
            <div class="col-lg-4">
                <div class="card card-custom p-4 h-100">
                    <h5 class="card-title-custom d-flex align-items-center gap-2 fs-5">
                        <i class="bi bi-compass-fill" style="color: var(--orange-bright);"></i>
                        Navegación Rápida
                    </h5>
                    
                    <div class="d-flex flex-column gap-3 mt-2">
                        <a href="{{ url('/') }}" class="btn-nav-item">
                            <span><i class="bi bi-house-door me-2"></i>Inicio</span>
                            <i class="bi bi-chevron-right small"></i>
                        </a>
                        
                        <a href="{{ url('/ruta-aprendizaje') }}" class="btn-nav-item destacado">
                            <span><i class="bi bi-journal-check me-2"></i>Ir a Ruta de Aprendizaje</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                        
                        <a href="{{ url('/resultado') }}" class="btn-nav-item">
                            <span><i class="bi bi-trophy me-2"></i>Ver Últimos Resultados</span>
                            <i class="bi bi-chevron-right small"></i>
                        </a>
                        
                        <a href="{{ url('/glosario') }}" class="btn-nav-item">
                            <span><i class="bi bi-book me-2"></i>Consultar Glosario</span>
                            <i class="bi bi-chevron-right small"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: EDITAR PERFIL -->
            <div class="col-lg-8">
                <div class="card card-custom p-4 h-100">
                    
                    <!-- Tarjeta de vista previa del perfil en tiempo real -->
                    <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-4" style="background-color: var(--purple-soft);">
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 55px; height: 55px; color: var(--purple-main); font-size: 1.5rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold" id="displayUsername" style="color: var(--purple-dark);">Cargando usuario...</h6>
                            <p class="mb-0 small text-muted" id="displayEmail">Cargando correo...</p>
                        </div>
                    </div>

                    <h5 class="card-title-custom d-flex align-items-center gap-2 fs-5">
                        <i class="bi bi-person-gear" style="color: var(--purple-medium);"></i>
                        Editar Perfil de Usuario
                    </h5>

                    <!-- Formulario de perfil -->
                    <form id="formPerfil" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="username" class="form-label fw-semibold small" style="color: var(--purple-dark);">Nombre de Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0" style="border-color: var(--purple-soft); border-radius: 0.75rem 0 0 0.75rem;">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" id="username" name="username" class="form-control form-control-custom border-start-0" placeholder="Mínimo 3 caracteres" required style="border-radius: 0 0.75rem 0.75rem 0;">
                                <div class="invalid-feedback">Escribe al menos 3 caracteres para tu nombre.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold small" style="color: var(--purple-dark);">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0" style="border-color: var(--purple-soft); border-radius: 0.75rem 0 0 0.75rem;">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" id="email" name="email" class="form-control form-control-custom border-start-0" placeholder="ejemplo@correo.com" required style="border-radius: 0 0.75rem 0.75rem 0;">
                                <div class="invalid-feedback">Ingresa un correo electrónico válido.</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-guardar flex-grow-1">
                                <i class="bi bi-floppy-fill me-2"></i>Guardar Cambios
                            </button>
                            
                            <button type="button" id="btnEliminarPerfil" class="btn btn-outline-danger rounded-pill px-4" title="Borrar perfil local">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </main>

    <!-- VENTANA EMERGENTE (MODAL DE ÉXITO) -->
    <div class="modal fade" id="modalCambiosGuardados" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4 rounded-4 border-0 shadow">
                <div class="modal-body">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 70px; height: 70px; background-color: #D1E7DD; color: #0F5132; font-size: 32px;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-2" style="color: var(--purple-dark);">¡Actualización Exitosa!</h3>
                    <p class="text-muted small mb-4">Los cambios se guardaron correctamente.</p>
                    <button type="button" class="btn text-white rounded-pill px-4 py-2 fw-semibold w-100" 
                            data-bs-dismiss="modal" style="background-color: var(--purple-main);">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-custom py-4 mt-auto border-top border-purple">
        <div class="container text-center">
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS del Dashboard (CRUD Simulado) -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>