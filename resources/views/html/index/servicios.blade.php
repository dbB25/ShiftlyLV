<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Tarjetas de Vocabulario Técnico</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos de Shiftly -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/servicios.css') }}">
</head>

<body>

    <!-- NAVBAR CORPORATIVO -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="38" height="38">
                <span>Shiftly</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}"><i class="bi bi-house-door me-1"></i> Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/servicios') }}"><i class="bi bi-card-checklist me-1"></i> Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contacto') }}"><i class="bi bi-envelope me-1"></i> Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1">
        
        <!-- ENCABEZADO Y FILTROS -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold mb-2" style="background-color: var(--orange-bright); color: var(--white);">
                    Flashcards Interactivas
                </span>
                <h1 class="display-6 fw-extrabold mb-1" style="color: var(--purple-dark);">Vocabulario Técnico Profesional</h1>
                <p class="text-muted mb-0">Aprende términos especializados en inglés según tu área de trabajo.</p>
            </div>
            
            <!-- FILTROS DE CATEGORÍA -->
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-filter-category btn-sm rounded-pill active">Todos</button>
                <button type="button" class="btn btn-filter-category btn-sm rounded-pill">Medicina</button>
                <button type="button" class="btn btn-filter-category btn-sm rounded-pill">Gastronomía</button>
                <button type="button" class="btn btn-filter-category btn-sm rounded-pill">Derecho</button>
                <button type="button" class="btn btn-filter-category btn-sm rounded-pill">Arquitectura</button>
            </div>
        </div>

        <!-- GRILLA DE TARJETAS DE VOCABULARIO -->
        <div class="row g-4">
            
            <!-- 1. Medicina -->
            <div class="col-md-6">
                <div class="card card-vocabulario p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-shift">
                            <i class="bi bi-hospital me-1"></i> Medicina & Salud
                        </span>
                        <button class="btn btn-audio" title="Escuchar pronunciación">
                            <i class="bi bi-volume-up-fill"></i>
                        </button>
                    </div>
                    <h3 class="fw-bold h2 mb-2" style="color: var(--purple-dark);">1. Triage</h3>
                    <p class="text-muted mb-2"><strong>Traducción:</strong> Triaje / Clasificación de urgencias</p>
                    <p class="mb-1"><strong>Definición (EN):</strong> The assignment of degrees of urgency to wounds or illnesses to decide the order of treatment.</p>
                    <p class="mb-3 text-secondary"><strong>Definición (ES):</strong> Asignación del grado de urgencia a lesiones o enfermedades para decidir el orden de atención médica.</p>
                    
                    <div class="caja-ejemplo mt-auto">
                        <p class="fw-bold mb-1 fs-6" style="color: var(--purple-main);">Ejemplos de uso:</p>
                        <ul class="mb-0 ps-3 fst-italic text-secondary small">
                            <li>"The nurse performed triage upon the patient's arrival."</li>
                            <li>"Triage protocols ensure critical patients receive immediate care."</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 2. Gastronomía -->
            <div class="col-md-6">
                <div class="card card-vocabulario p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-shift">
                            <i class="bi bi-cup-hot me-1"></i> Gastronomía & Cocina
                        </span>
                        <button class="btn btn-audio" title="Escuchar pronunciación">
                            <i class="bi bi-volume-up-fill"></i>
                        </button>
                    </div>
                    <h3 class="fw-bold h2 mb-2" style="color: var(--purple-dark);">2. Mise en place</h3>
                    <p class="text-muted mb-2"><strong>Traducción:</strong> Preparación previa de ingredientes</p>
                    <p class="mb-1"><strong>Definición (EN):</strong> The preparation of dishes and ingredients before cooking begins in a restaurant kitchen.</p>
                    <p class="mb-3 text-secondary"><strong>Definición (ES):</strong> Organización y preparación previa de ingredientes y utensilios antes de comenzar a cocinar.</p>
                    
                    <div class="caja-ejemplo mt-auto">
                        <p class="fw-bold mb-1 fs-6" style="color: var(--purple-main);">Ejemplos de uso:</p>
                        <ul class="mb-0 ps-3 fst-italic text-secondary small">
                            <li>"Always organize your mise en place before starting the dinner rush."</li>
                            <li>"A clean mise en place increases kitchen efficiency."</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 3. Derecho -->
            <div class="col-md-6">
                <div class="card card-vocabulario p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-shift">
                            <i class="bi bi-briefcase me-1"></i> Derecho & Legal
                        </span>
                        <button class="btn btn-audio" title="Escuchar pronunciación">
                            <i class="bi bi-volume-up-fill"></i>
                        </button>
                    </div>
                    <h3 class="fw-bold h2 mb-2" style="color: var(--purple-dark);">3. Affidavit</h3>
                    <p class="text-muted mb-2"><strong>Traducción:</strong> Declaración jurada por escrito</p>
                    <p class="mb-1"><strong>Definición (EN):</strong> A written statement confirmed by oath or affirmation, for use as evidence in court.</p>
                    <p class="mb-3 text-secondary"><strong>Definición (ES):</strong> Declaración escrita y firmada bajo juramento para ser utilizada como prueba en un proceso legal.</p>
                    
                    <div class="caja-ejemplo mt-auto">
                        <p class="fw-bold mb-1 fs-6" style="color: var(--purple-main);">Ejemplos de uso:</p>
                        <ul class="mb-0 ps-3 fst-italic text-secondary small">
                            <li>"The witness signed an affidavit regarding the accident."</li>
                            <li>"Submit the signed affidavit to the judge before Monday."</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 4. Arquitectura -->
            <div class="col-md-6">
                <div class="card card-vocabulario p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-shift">
                            <i class="bi bi-building me-1"></i> Arquitectura & Diseño
                        </span>
                        <button class="btn btn-audio" title="Escuchar pronunciación">
                            <i class="bi bi-volume-up-fill"></i>
                        </button>
                    </div>
                    <h3 class="fw-bold h2 mb-2" style="color: var(--purple-dark);">4. Blueprint</h3>
                    <p class="text-muted mb-2"><strong>Traducción:</strong> Plano de construcción / Diseño arquitectónico</p>
                    <p class="mb-1"><strong>Definición (EN):</strong> A technical drawing or design plan for a building or structural project.</p>
                    <p class="mb-3 text-secondary"><strong>Definición (ES):</strong> Dibujo técnico o plano detallado que sirve como guía para la construcción de una obra.</p>
                    
                    <div class="caja-ejemplo mt-auto">
                        <p class="fw-bold mb-1 fs-6" style="color: var(--purple-main);">Ejemplos de uso:</p>
                        <ul class="mb-0 ps-3 fst-italic text-secondary small">
                            <li>"The architect reviewed the blueprint with the engineering team."</li>
                            <li>"All dimensions must match the approved blueprint."</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER CORPORATIVO -->
    <footer class="footer-custom py-4 mt-auto border-top border-purple">
        <div class="container text-center">
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>