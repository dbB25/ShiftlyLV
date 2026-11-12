<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Biblioteca de Repaso y Glosario Técnico</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Estilos Propios del Glosario -->
    <link rel="stylesheet" href="{{ asset('css/glosario-style.css') }}?v=2">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- ==========================================
         NAVBAR MORADO CORPORATIVO
         ========================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-shiftly sticky-top py-3">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-extrabold fs-3 text-decoration-none text-white" href="{{ url('/') }}" style="color: #FFFFFF !important;">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" onerror="this.onerror=null; this.src='https://placehold.co/100x100/52154E/ffffff?text=S';" alt="Shiftly Logo" width="42" height="42">
                <span style="color: #FFFFFF !important;">Shiftly</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarShiftly">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarShiftly">
                <div class="navbar-nav mx-auto gap-1 my-2 my-lg-0">
                    <a href="{{ url('/') }}" class="nav-link nav-link-app">Inicio</a>
                    <a href="{{ url('/servicios') }}" class="nav-link nav-link-app">Vocabularios</a>
                    <a href="{{ url('/ejercicios') }}" class="nav-link nav-link-app">Practicar</a>
                    <a href="{{ url('/glosario') }}" class="nav-link nav-link-app active">Glosario</a>
                    <a href="{{ url('/contacto') }}" class="nav-link nav-link-app">Contacto</a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ url('/dashboard') }}" class="btn btn-3d-glass">
                        <i class="bi bi-grid-fill me-1"></i> Dashboard
                    </a>
                    <a href="{{ url('/login') }}" class="btn btn-3d-orange">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         BANNER SUPERIOR DEL GLOSARIO
         ========================================== -->
    <section class="glosario-hero-banner py-4 py-md-5">
        <div class="container-xl">
            <div class="row align-items-center g-3">
                <div class="col-lg-8 text-center text-lg-start">
                    <div class="subtitle-tag mb-2">
                        <i class="bi bi-journal-bookmark-fill" style="color: var(--orange-bright);"></i>
                        <span>REPASO Y VOCABULARIO TÉCNICO</span>
                    </div>
                    <h1 class="display-5 fw-extrabold mb-2">Biblioteca de Repaso Shiftly</h1>
                    <p class="text-white-50 lead fs-6 mb-0">
                        Selecciona tu área profesional para explorar, escuchar la pronunciación nativa y poner a prueba tu memoria revelando cada traducción.
                    </p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <div class="d-inline-flex align-items-center gap-3 p-3 rounded-4" style="background: rgba(255,255,255,0.1); border: 1.5px solid rgba(255,255,255,0.2);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: var(--orange-bright); color: var(--white); font-size: 1.4rem;">
                            <i class="bi bi-volume-up-fill"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-extrabold small text-white">Audio Nativo Activo</div>
                            <div class="small text-white-50">Haz clic en la bocina para escuchar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         CONTENIDO PRINCIPAL: BUSCADOR, FILTROS Y TARJETAS
         ========================================== -->
    <main class="container-xl py-5 flex-grow-1">

        <!-- CONTROLES: BUSCADOR Y FILTROS POR ÁREA -->
        <div class="row gy-3 align-items-center mb-5">
            <div class="col-lg-5">
                <div class="input-group search-box">
                    <span class="input-group-text bg-white pe-2">
                        <i class="bi bi-search fs-5"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control ps-1" placeholder="Buscar concepto o palabra técnica..." onkeyup="filterGlossary()">
                </div>
            </div>
            <div class="col-lg-7 d-flex flex-wrap gap-2 justify-content-lg-end">
                <button class="btn btn-filter active" data-filter="all" onclick="filterBySector('all', this)">Todos</button>
                <button class="btn btn-filter" data-filter="Sistemas" onclick="filterBySector('Sistemas', this)">Sistemas</button>
                <button class="btn btn-filter" data-filter="Medicina" onclick="filterBySector('Medicina', this)">Medicina</button>
                <button class="btn btn-filter" data-filter="Gastronomía" onclick="filterBySector('Gastronomía', this)">Gastronomía</button>
                <button class="btn btn-filter" data-filter="Derecho" onclick="filterBySector('Derecho', this)">Derecho</button>
                <button class="btn btn-filter" data-filter="Arquitectura" onclick="filterBySector('Arquitectura', this)">Arquitectura</button>
            </div>
        </div>

        <!-- TARJETAS DE SECTORES TÉCNICOS -->
        <div class="row g-4" id="glossaryGrid">

            <!-- SISTEMAS -->
            <div class="col-md-6 col-lg-4 sector-card-wrapper" data-sector="Sistemas">
                <div class="card glossary-card h-100">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=600&auto=format&fit=crop" alt="Sistemas y Software">
                        <span class="badge sector-badge"><i class="bi bi-laptop me-1 text-warning"></i> Sector TI</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h2 class="card-title sector-title fs-4 mb-0">Sistemas</h2>
                                <span class="term-badge-count"><i class="bi bi-bookmark-fill"></i> <span class="term-count">2</span> términos</span>
                            </div>
                            <p class="text-muted small mb-0">Desarrollo de software, despliegue y arquitectura web.</p>
                        </div>

                        <div class="vocab-accordion pt-2">
                            <button class="btn btn-purple-accordion w-100 d-flex align-items-center justify-content-between" type="button" onclick="toggleVocab(this)">
                                <span><i class="bi bi-card-list me-2"></i>Ver vocabulario</span>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </button>

                            <div class="vocab-list mt-3 d-none">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item vocab-item" data-term="Deploy Desplegar en producción Publicar">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Deploy to production</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Deploy to production')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Desplegar en producción</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item vocab-item" data-term="Repository Repositorio Código fuente">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Repository</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Repository')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Repositorio de código</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MEDICINA -->
            <div class="col-md-6 col-lg-4 sector-card-wrapper" data-sector="Medicina">
                <div class="card glossary-card h-100">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=600&auto=format&fit=crop" alt="Medicina">
                        <span class="badge sector-badge"><i class="bi bi-heart-pulse-fill me-1 text-danger"></i> Sector Salud</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h2 class="card-title sector-title fs-4 mb-0">Medicina</h2>
                                <span class="term-badge-count"><i class="bi bi-bookmark-fill"></i> <span class="term-count">2</span> términos</span>
                            </div>
                            <p class="text-muted small mb-0">Evaluación clínica, urgencias médicas y diagnóstico.</p>
                        </div>
                        
                        <div class="vocab-accordion pt-2">
                            <button class="btn btn-purple-accordion w-100 d-flex align-items-center justify-content-between" type="button" onclick="toggleVocab(this)">
                                <span><i class="bi bi-card-list me-2"></i>Ver vocabulario</span>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </button>
                            
                            <div class="vocab-list mt-3 d-none">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item vocab-item" data-term="Triage Triaje Clasificación urgencias">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Triage</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Triage')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Triaje / Clasificación de urgencias</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item vocab-item" data-term="Vital signs Signos vitales">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Vital signs</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Vital signs')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Signos vitales</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GASTRONOMÍA -->
            <div class="col-md-6 col-lg-4 sector-card-wrapper" data-sector="Gastronomía">
                <div class="card glossary-card h-100">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=600&auto=format&fit=crop" alt="Gastronomía">
                        <span class="badge sector-badge"><i class="bi bi-cup-hot-fill me-1 text-warning"></i> Alta Cocina</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h2 class="card-title sector-title fs-4 mb-0">Gastronomía</h2>
                                <span class="term-badge-count"><i class="bi bi-bookmark-fill"></i> <span class="term-count">2</span> términos</span>
                            </div>
                            <p class="text-muted small mb-0">Técnicas culinarias, preparación y brigada de cocina.</p>
                        </div>

                        <div class="vocab-accordion pt-2">
                            <button class="btn btn-purple-accordion w-100 d-flex align-items-center justify-content-between" type="button" onclick="toggleVocab(this)">
                                <span><i class="bi bi-card-list me-2"></i>Ver vocabulario</span>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </button>

                            <div class="vocab-list mt-3 d-none">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item vocab-item" data-term="Mise en place Preparación previa ingredientes">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Mise en place</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Mise en place', 'fr-FR')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Preparación previa de ingredientes</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item vocab-item" data-term="Sauté Saltear Salteado">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Sauté</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Sauté')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Saltear / Salteado</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DERECHO -->
            <div class="col-md-6 col-lg-4 sector-card-wrapper" data-sector="Derecho">
                <div class="card glossary-card h-100">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=600&auto=format&fit=crop" alt="Derecho">
                        <span class="badge sector-badge"><i class="bi bi-bank2 me-1 text-info"></i> Sector Legal</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h2 class="card-title sector-title fs-4 mb-0">Derecho</h2>
                                <span class="term-badge-count"><i class="bi bi-bookmark-fill"></i> <span class="term-count">2</span> términos</span>
                            </div>
                            <p class="text-muted small mb-0">Contratos internacionales, litigio y derecho corporativo.</p>
                        </div>

                        <div class="vocab-accordion pt-2">
                            <button class="btn btn-purple-accordion w-100 d-flex align-items-center justify-content-between" type="button" onclick="toggleVocab(this)">
                                <span><i class="bi bi-card-list me-2"></i>Ver vocabulario</span>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </button>

                            <div class="vocab-list mt-3 d-none">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item vocab-item" data-term="Affidavit Declaración jurada por escrito">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Affidavit</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Affidavit')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Declaración jurada por escrito</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item vocab-item" data-term="Subpoena Citación judicial Cita legal">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Subpoena</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Subpoena')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Citación judicial / Cita legal</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ARQUITECTURA -->
            <div class="col-md-6 col-lg-4 sector-card-wrapper" data-sector="Arquitectura">
                <div class="card glossary-card h-100">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop" alt="Arquitectura">
                        <span class="badge sector-badge"><i class="bi bi-building me-1 text-primary"></i> Diseño & Obra</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h2 class="card-title sector-title fs-4 mb-0">Arquitectura</h2>
                                <span class="term-badge-count"><i class="bi bi-bookmark-fill"></i> <span class="term-count">2</span> términos</span>
                            </div>
                            <p class="text-muted small mb-0">Planos estructurales, edificación y urbanismo.</p>
                        </div>

                        <div class="vocab-accordion pt-2">
                            <button class="btn btn-purple-accordion w-100 d-flex align-items-center justify-content-between" type="button" onclick="toggleVocab(this)">
                                <span><i class="bi bi-card-list me-2"></i>Ver vocabulario</span>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </button>

                            <div class="vocab-list mt-3 d-none">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item vocab-item" data-term="Blueprint Plano de construcción Diseño">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Blueprint</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Blueprint')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Plano de construcción / Diseño</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item vocab-item" data-term="Load-bearing Muro de carga Estructural">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="term-text">Load-bearing</span>
                                            <button class="btn btn-sm btn-audio" onclick="speakText('Load bearing')" title="Escuchar pronunciación">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                        </div>
                                        <div class="translation-wrapper mt-2">
                                            <button class="btn-reveal" onclick="toggleTranslation(this)"><i class="bi bi-eye-fill me-1"></i>Revelar traducción</button>
                                            <span class="translation-text d-none">Muro de carga / Estructural</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MENSAJE NO RESULTADOS -->
        <div id="noResults" class="text-center py-5 d-none">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background-color: var(--purple-soft); color: var(--purple-main); font-size: 2.2rem;">
                <i class="bi bi-search-heart"></i>
            </div>
            <h3 class="fw-extrabold fs-3 mb-2" style="color: var(--purple-dark);">No encontramos coincidencias</h3>
            <p class="text-muted">Prueba buscando otro término o seleccionando un filtro diferente.</p>
        </div>
    </main>

    <!-- ==========================================
         FOOTER MORADO CORPORATIVO
         ========================================== -->
    <footer class="footer-shiftly py-4 mt-auto">
        <div class="container-xl d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="30" height="30">
                <span class="fw-bold">Shiftly</span>
                <span class="text-white-50 small ms-2">&copy; 2026 Todos los derechos reservados.</span>
            </div>
            <div class="d-flex flex-wrap gap-4 small">
                <a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Inicio</a>
                <a href="{{ url('/servicios') }}" class="text-white-50 text-decoration-none">Vocabularios</a>
                <a href="{{ url('/ejercicios') }}" class="text-white-50 text-decoration-none">Practicar</a>
                <a href="{{ url('/contacto') }}" class="text-white-50 text-decoration-none">Contacto</a>
                <a href="{{ url('/dashboard') }}" class="text-white-50 text-decoration-none">Dashboard</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/glosario-scrip.js') }}"></script>
</body>
</html>