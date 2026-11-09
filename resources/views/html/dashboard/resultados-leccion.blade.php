<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Resultados de la Lección</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos de Shiftly -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/resultados-leccion.css') }}">
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
                    <a class="nav-link" href="{{ url('/dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Panel del Usuario</a>
                    <a class="nav-link" href="{{ url('/ruta-aprendizaje') }}"><i class="bi bi-journal-bookmark me-1"></i> Ruta de Aprendizaje</a>
                    <a class="nav-link active" href="{{ url('/resultados-leccion') }}"><i class="bi bi-bar-chart-line me-1"></i> Resultados</a>
                    <a class="nav-link" href="{{ url('/glosario') }}"><i class="bi bi-book me-1"></i> Glosario</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1">
        
        <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold mb-2" style="background-color: var(--orange-bright); color: var(--white);">
                <i class="bi bi-award-fill me-1"></i> Evaluación Completada
            </span>
            <h1 class="display-5 fw-extrabold mb-2" style="color: var(--purple-dark);">Resultados de la Lección</h1>
            <p class="text-muted mx-auto" style="max-width: 550px;">Consulta el resumen global de tu rendimiento y revisa el detalle de aciertos por área profesional.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card card-puntuacion p-4 p-md-5 text-center">
                    
                    <h3 class="h5 fw-bold mb-3 text-uppercase" style="color: var(--purple-medium); letter-spacing: 1px;">
                        Puntuación Final
                    </h3>

                    <!-- CADA PUNTUACTION GENERAL -->
                    <div class="score-display mb-4">
                        <span class="score-number" id="contadorPuntaje">85</span>
                        <span class="fs-4 text-muted font-monospace"> / 100</span>
                    </div>
                    
                    <p class="small text-muted mb-4">Haz clic en cada botón para desplegar los detalles de aciertos y tiempo empleado en cada lección.</p>

                    <!-- LISTADO DE BOTONES CON ÍCONOS Y ESTILOS DE MARCA -->
                    <div class="d-grid gap-3">
                        <button class="btn btn-modulo-detalle destacado btn-detalle" data-modulo="Medicina" data-aciertos="9/10" data-tiempo="12 minutos">
                            <span class="d-flex align-items-center gap-2">
                                <span class="rounded-circle icon-area d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: var(--orange-light); color: var(--orange-bright);">
                                    <i class="bi bi-hospital"></i>
                                </span>
                                Detalle Medicina
                            </span>
                            <i class="bi bi-chevron-right small"></i>
                        </button>

                        <button class="btn btn-modulo-detalle btn-detalle" data-modulo="Gastronomía" data-aciertos="10/10" data-tiempo="8 minutos">
                            <span class="d-flex align-items-center gap-2">
                                <span class="rounded-circle icon-area d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: var(--purple-soft); color: var(--purple-main);">
                                    <i class="bi bi-cup-hot"></i>
                                </span>
                                Detalle Gastronomía
                            </span>
                            <i class="bi bi-chevron-right small"></i>
                        </button>

                        <button class="btn btn-modulo-detalle btn-detalle" data-modulo="Derecho" data-aciertos="8/10" data-tiempo="15 minutos">
                            <span class="d-flex align-items-center gap-2">
                                <span class="rounded-circle icon-area d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: var(--purple-soft); color: var(--purple-main);">
                                    <i class="bi bi-briefcase"></i>
                                </span>
                                Detalle Derecho
                            </span>
                            <i class="bi bi-chevron-right small"></i>
                        </button>

                        <button class="btn btn-modulo-detalle btn-detalle" data-modulo="Arquitectura" data-aciertos="9/10" data-tiempo="10 minutos">
                            <span class="d-flex align-items-center gap-2">
                                <span class="rounded-circle icon-area d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: var(--purple-soft); color: var(--purple-main);">
                                    <i class="bi bi-building"></i>
                                </span>
                                Detalle Arquitectura
                            </span>
                            <i class="bi bi-chevron-right small"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <!-- MODAL DE DETALLES POR MÓDULO -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom shadow">
                <div class="modal-header modal-header-custom px-4 py-3">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalTitulo">
                        <i class="bi bi-info-circle-fill text-warning"></i> Detalles del Módulo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="modal-info-box mb-3 d-flex align-items-center justify-content-between">
                        <span class="fw-semibold text-muted"><i class="bi bi-check2-square me-2 text-success"></i>Aciertos Obtenidos:</span>
                        <span class="fw-bold fs-5" id="modalAciertos" style="color: var(--purple-dark);">--</span>
                    </div>

                    <div class="modal-info-box d-flex align-items-center justify-content-between">
                        <span class="fw-semibold text-muted"><i class="bi bi-clock-history me-2 text-primary"></i>Tiempo Empleado:</span>
                        <span class="fw-bold fs-5" id="modalTiempo" style="color: var(--purple-dark);">--</span>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER CORPORATIVO -->
    <footer class="footer-custom py-4 mt-auto border-top border-purple">
        <div class="container text-center">
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script JS para la interactividad -->
    <script src="{{ asset('js/resultados-leccion.js') }}"></script>
</body>

</html>