<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Resultado del Diagnóstico</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos de Shiftly -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/resultado.css') }}">
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
                    <a class="nav-link" href="{{ url('/') }}"><i class="bi bi-house-door me-1"></i> Inicio</a>
                    <a class="nav-link" href="{{ url('/ruta-aprendizaje') }}"><i class="bi bi-journal-bookmark me-1"></i> Aprender</a>
                    <a class="nav-link" href="{{ url('/dashboard') }}"><i class="bi bi-person me-1"></i> Mi perfil</a>
                    <a class="nav-link" href="{{ url('/contacto') }}"><i class="bi bi-question-circle me-1"></i> Ayuda</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1">
        
        <section class="resultado-diagnostico mx-auto" style="max-width: 720px;">

            <!-- ENCABEZADO -->
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold mb-2" style="background-color: var(--orange-bright); color: var(--white);">
                    <i class="bi bi-check-all me-1"></i> Diagnóstico Completado
                </span>
                <h1 class="display-5 fw-extrabold mb-2" style="color: var(--purple-dark);">¡Has terminado tu diagnóstico!</h1>
                <p class="text-muted" style="font-size: 1.05rem;">
                    Con base en tus respuestas, hemos identificado tu nivel actual de inglés.
                </p>
            </div>

            <!-- TARJETA DE NIVEL OBTENIDO -->
            <div class="card card-resultado p-4 p-md-5 text-center mb-4">
                <p class="text-uppercase fw-bold small mb-3" style="color: var(--purple-medium); letter-spacing: 1px;">
                    TU NIVEL ESTIMADO
                </p>

                <!-- Badge de Nivel B1 -->
                <div class="circle-nivel" id="nivel-resultado" aria-live="polite">
                    B1
                </div>

                <h2 class="h3 fw-bold mb-2" id="nombre-nivel" style="color: var(--purple-dark);">Intermedio</h2>

                <p class="text-muted mb-0 mx-auto" id="descripcion-nivel" style="max-width: 480px; font-size: 0.95rem;">
                    Tienes conocimientos que te permiten comprender y utilizar expresiones frecuentes en situaciones cotidianas y profesionales.
                </p>
            </div>

            <!-- TARJETA DE RECOMENDACIÓN -->
            <div class="card card-recomendacion p-4 mb-5">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: var(--orange-light); color: var(--orange-bright); min-width: 42px; height: 42px;">
                        <i class="bi bi-lightbulb-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--purple-dark);">Recomendación para ti</h5>
                        <p class="text-muted small mb-0" id="texto-recomendacion">
                            Continúa practicando vocabulario técnico, comprensión auditiva y comunicación activa para fortalecer tu dominio del inglés profesional.
                        </p>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                <button type="button" class="btn btn-outline-custom px-4 py-2" data-bs-toggle="modal" data-bs-target="#modal-revision">
                    <i class="bi bi-search me-1"></i> Revisar mis respuestas
                </button>

                <a href="{{ url('/diagnostico') }}" class="btn btn-repetir px-4 py-2 text-center text-decoration-none">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Repetir diagnóstico
                </a>

                <a href="{{ url('/ruta-aprendizaje') }}" class="btn btn-continuar px-4 py-2 text-center text-decoration-none">
                    Continuar <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <!-- MODAL DE REVISIÓN DE RESPUESTAS -->
            <div class="modal fade" id="modal-revision" tabindex="-1" aria-labelledby="titulo-modal-revision" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom shadow">
                        <div class="modal-header modal-header-custom px-4 py-3">
                            <h2 class="modal-title fs-5 fw-bold d-flex align-items-center gap-2" id="titulo-modal-revision">
                                <i class="bi bi-clipboard-check text-warning"></i> Repaso de tu diagnóstico
                            </h2>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p id="resumen-revision" class="text-muted small mb-3">Tu diagnóstico estima tu nivel de inglés con base en tus respuestas.</p>
                            <div id="lista-revision" class="d-flex flex-column gap-3"></div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

    <!-- FOOTER CORPORATIVO -->
    <footer class="footer-custom py-4 mt-auto border-top border-purple">
        <div class="container text-center">
            <p class="mb-0 text-white-50 small">En <strong>Shiftly</strong> creemos que el inglés técnico puede llevarte más lejos. &copy; 2026 Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de diagnóstico JS -->
    <script src="{{ asset('js/diagnostico.js') }}"></script>

</body>
</html>