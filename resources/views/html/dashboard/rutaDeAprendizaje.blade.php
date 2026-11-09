<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Ruta de Aprendizaje</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos de Shiftly -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rutaDeAprendizaje.css') }}">
</head>

<body>

    <!-- NAVBAR -->
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
                    <a class="nav-link active" href="{{ url('/ruta-aprendizaje') }}"><i class="bi bi-journal-bookmark me-1"></i> Ruta de Aprendizaje</a>
                    <a class="nav-link" href="{{ url('/resultados-leccion') }}"><i class="bi bi-bar-chart-line me-1"></i> Resultados</a>
                    <a class="nav-link" href="{{ url('/glosario') }}"><i class="bi bi-book me-1"></i> Glosario</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1">
        <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold mb-2" style="background-color: var(--primary-purple); color: var(--white);">Módulos Técnicos</span>
            <h1 class="display-5 fw-extrabold mb-2" style="color: var(--primary-navy);">Ruta de Aprendizaje</h1>
            <p class="text-muted mx-auto" style="max-width: 600px;">Selecciona y completa las áreas temáticas especializadas para avanzar en tu nivel de inglés profesional.</p>
        </div>

        <!-- TARJETA DE PROGRESO GENERAL -->
        <div class="card card-progreso p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill fs-4" style="color: var(--primary-purple);"></i>
                    <span class="fw-bold fs-5">Progreso General del Nivel</span>
                </div>
                <span id="textoProgreso" class="fw-bold fs-5" style="color: var(--primary-purple);">0%</span>
            </div>
            <div class="progress progress-custom">
                <div id="barraProgreso" class="progress-bar progress-bar-custom progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        <!-- BUSCADOR -->
        <div class="row mb-5">
            <div class="col-md-6 offset-md-3 position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted fs-5"></i>
                <input type="text" id="buscadorLecciones" class="form-control form-control-lg search-input-custom ps-5" placeholder="Buscar por área profesional (ej. Medicina, Derecho)...">
            </div>
        </div>

        <!-- GRILLA DE MÓDULOS -->
        <div class="row g-4" id="contenedorTarjetas">
            
            <!-- Medicina -->
            <div class="col-sm-6 col-md-3 tarjeta-modulo" data-titulo="medicina">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <div class="icon-box-modulo">
                                <i class="bi bi-hospital"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-2">Medicina</h5>
                            <p class="card-text text-muted small">Vocabulario clínico, diagnósticos y terminología médica.</p>
                        </div>
                        <button class="btn btn-completar w-100 mt-4 py-2">
                            <i class="bi bi-check-circle me-1"></i> Completar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Gastronomía -->
            <div class="col-sm-6 col-md-3 tarjeta-modulo" data-titulo="gastronomia">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <div class="icon-box-modulo">
                                <i class="bi bi-cup-hot"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-2">Gastronomía</h5>
                            <p class="card-text text-muted small">Técnicas culinarias, ingredientes y servicio internacional.</p>
                        </div>
                        <button class="btn btn-completar w-100 mt-4 py-2">
                            <i class="bi bi-check-circle me-1"></i> Completar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Derecho -->
            <div class="col-sm-6 col-md-3 tarjeta-modulo" data-titulo="derecho">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <div class="icon-box-modulo">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-2">Derecho</h5>
                            <p class="card-text text-muted small">Conceptos legales, contratos y jerga jurídica corporativa.</p>
                        </div>
                        <button class="btn btn-completar w-100 mt-4 py-2">
                            <i class="bi bi-check-circle me-1"></i> Completar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Arquitectura -->
            <div class="col-sm-6 col-md-3 tarjeta-modulo" data-titulo="arquitectura">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <div class="icon-box-modulo">
                                <i class="bi bi-building"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-2">Arquitectura</h5>
                            <p class="card-text text-muted small">Términos estructurales, planos y diseño espacial.</p>
                        </div>
                        <button class="btn btn-completar w-100 mt-4 py-2">
                            <i class="bi bi-check-circle me-1"></i> Completar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer-custom py-4 mt-auto border-top">
        <div class="container text-center">
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS de la Ruta -->
    <script src="{{ asset('js/rutaDeAprendizaje.js') }}"></script>
</body>

</html>