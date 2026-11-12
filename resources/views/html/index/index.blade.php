<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Aprende Inglés Profesional Jugando</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Exclusivos de Index (con control de caché) -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v=4">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- ==========================================
         NAVBAR MORADO CORPORATIVO
         ========================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-shiftly sticky-top py-3">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-extrabold fs-3 text-decoration-none text-white" href="{{ url('/') }}" style="color: #FFFFFF !important;">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="42" height="42">
                <span style="color: #FFFFFF !important;">Shiftly</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarShiftly">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarShiftly">
                <div class="navbar-nav mx-auto gap-1 my-2 my-lg-0">
                    
                    <!-- Botón en el Navbar -->
                    <a href="{{ url('/perfil') }}" class="btn btn-3d-glass d-inline-flex align-items-center gap-2 ms-lg-2">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span id="navNombreUsuario">Mi Perfil</span>
                    </a>
                    <a href="{{ url('/diagnostico') }}" class="nav-link nav-link-app">Diagnóstico</a>
                    <a href="{{ url('/servicios') }}" class="nav-link nav-link-app">Vocabularios</a>
                    <a href="{{ url('/ejercicios') }}" class="nav-link nav-link-app">Practicar</a>
                    <a href="{{ url('/glosario') }}" class="nav-link nav-link-app">Glosario</a>
                    <a href="#equipo" class="nav-link nav-link-app">Equipo</a>
                    <a href="{{ url('/contacto') }}" class="nav-link nav-link-app">Contacto</a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ url('/login') }}" class="btn btn-3d-glass py-2 px-3 fs-6">
                        Iniciar Sesión
                    </a>
                    <a href="{{ url('/registro') }}" class="btn btn-3d-orange py-2 px-4 fs-6">
                        Empezar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         HERO PRINCIPAL CON FONDO MORADO + MINI RETO
         ========================================== -->
    <section class="hero-purple-section py-5">
        <div class="container-xl py-lg-4">
            <div class="row align-items-center g-5">

                <!-- Columna Izquierda -->
                <div class="col-lg-6 text-center text-lg-start">
                    <div class="d-inline-flex align-items-center gap-2 badge-streak mb-3">
                        <i class="bi bi-fire fs-5" style="color: var(--orange-bright);"></i>
                        <span>Plataforma Gamificada · Universidad Politécnica de Pachuca</span>
                    </div>

                    <h1 class="display-4 fw-extrabold mb-3" style="line-height: 1.15;">
                        Domina el inglés de tu <span style="color: var(--orange-bright);">carrera profesional</span> jugando.
                    </h1>

                    <p class="lead text-white-50 fw-medium mb-4" style="font-size: 1.12rem;">
                        Aprende vocabulario técnico real para Sistemas, Medicina, Derecho, Gastronomía y Arquitectura con retos interactivos de 5 minutos, audio nativo y rutas personalizadas.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ url('/diagnostico') }}" class="btn btn-3d-orange fs-6">
                            <i class="bi bi-lightning-charge-fill"></i> Hacer Test de Nivel Gratis
                        </a>
                        <a href="{{ url('/dashboard') }}" class="btn btn-3d-glass fs-6">
                            <i class="bi bi-grid-fill me-1"></i> Ir al Dashboard
                        </a>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-4 mt-4 pt-2 text-white-50 small fw-bold">
                        <span><i class="bi bi-check-circle-fill text-warning me-1"></i> +500 Términos Técnicos</span>
                        <span><i class="bi bi-volume-up-fill me-1" style="color: var(--orange-bright);"></i> Pronunciación Real</span>
                        <span><i class="bi bi-trophy-fill text-warning me-1"></i> Niveles A1 - C1</span>
                    </div>
                </div>

                <!-- Columna Derecha: Mini Lección Interactiva Estilo Duolingo -->
                <div class="col-lg-6">
                    <div class="lesson-preview-card mx-auto" style="max-width: 470px;">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="badge rounded-pill px-3 py-2 fw-bold" style="background-color: var(--purple-soft); color: var(--purple-main);">
                                <i class="bi bi-code-slash me-1"></i> Reto Rápido · Sistemas
                            </span>
                            <div class="progress flex-grow-1" style="height: 12px; border-radius: 50rem; background-color: var(--border-purple);">
                                <div id="heroProgressBar" class="progress-bar" style="width: 45%; background-color: var(--orange-bright); border-radius: 50rem; transition: width 0.4s ease;"></div>
                            </div>
                            <span class="fw-extrabold small" style="color: var(--orange-bright);">
                                <i class="bi bi-heart-fill"></i> 5
                            </span>
                        </div>

                        <h2 class="h5 fw-extrabold mb-3" style="color: var(--purple-dark);">
                            ¿Cómo se dice <span style="color: var(--purple-medium); text-decoration: underline;">"Desplegar en producción"</span> en inglés técnico?
                        </h2>

                        <div class="d-flex flex-column gap-2 mb-3" id="heroQuizOptions">
                            <button type="button" class="quiz-option-pill" data-correct="false">
                                <span class="badge me-2" style="background-color: var(--purple-main);">1</span> Upload the folder
                            </button>
                            <button type="button" class="quiz-option-pill" data-correct="true">
                                <span class="badge me-2" style="background-color: var(--purple-main);">2</span> Deploy to production
                            </button>
                            <button type="button" class="quiz-option-pill" data-correct="false">
                                <span class="badge me-2" style="background-color: var(--purple-main);">3</span> Compile the screen
                            </button>
                        </div>

                        <div id="heroQuizFeedback" class="p-3 rounded-4 d-none fw-bold small"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ==========================================
         APARTADO DESTACADO: EXAMEN DIAGNÓSTICO
         ========================================== -->
    <section class="py-5" id="apartado-diagnostico">
        <div class="container-xl">
            <div class="diagnostic-banner-card p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8 text-center text-lg-start">
                        <span class="badge rounded-pill px-3 py-2 fw-extrabold text-uppercase mb-3"
                              style="background-color: rgba(255, 107, 53, 0.22); color: #FFB08E; border: 1.5px solid rgba(255, 107, 53, 0.45);">
                            <i class="bi bi-compass-fill me-1" style="color: var(--orange-bright);"></i> EVALUACIÓN DIAGNÓSTICA POR CARRERA
                        </span>

                        <h2 class="display-6 fw-extrabold mb-3">
                            ¿No sabes por dónde empezar? Descubre tu nivel de inglés
                        </h2>

                        <p class="text-white-50 fs-6 mb-4" style="max-width: 640px;">
                            Realiza nuestro <strong class="text-white">Examen Diagnóstico</strong> adaptado a tu carrera (Sistemas, Medicina, Derecho, Gastronomía o Arquitectura) y conoce tu nivel real CEFR (A1 a C1) en menos de 10 minutos.
                        </p>

                        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-2 small fw-bold">
                            <span class="px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);">
                                <i class="bi bi-check-circle-fill text-warning me-1"></i> 10 Preguntas de tu Especialidad
                            </span>
                            <span class="px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);">
                                <i class="bi bi-volume-up-fill text-warning me-1"></i> Audio Interactivo
                            </span>
                            <span class="px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);">
                                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Resultado Inmediato
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-4 text-center">
                        <div class="diagnostic-action-box p-4">
                            <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 72px; height: 72px; background: linear-gradient(135deg, var(--orange-bright), #FF9642); color: var(--white); font-size: 2rem; box-shadow: 0 6px 18px rgba(255,107,53,0.4);">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>

                            <h3 class="h5 fw-extrabold mb-1">Examen Diagnóstico</h3>
                            <p class="small text-white-50 mb-3">Gratis · Ubicación A1 – C1</p>

                            <a href="{{ url('/diagnostico') }}" class="btn btn-3d-orange w-100 py-3">
                                <span>Hacer Diagnóstico</span>
                                <i class="bi bi-arrow-right-circle-fill fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         ESPECIALIDADES PROFESIONALES CON IMÁGENES
         ========================================== -->
    <section class="stats-bar-purple py-5">
        <div class="container-xl">
            <div class="text-center mb-4">
                <span class="badge rounded-pill px-3 py-2 fw-bold text-uppercase mb-2" style="background-color: var(--purple-soft); color: var(--purple-main);">
                    <i class="bi bi-briefcase-fill me-1"></i> Rutas por Especialidad
                </span>
                <h2 class="h3 fw-extrabold mb-1" style="color: var(--purple-dark);">
                    Elige tu carrera profesional
                </h2>
                <p class="text-muted small mb-0">Selecciona tu área para explorar su vocabulario técnico y ejercicios</p>
            </div>

            <div class="row g-3 justify-content-center row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
                
                <!-- 1. Sistemas y Software -->
                <div class="col">
                    <a href="{{ url('/servicios') }}" class="career-card-img">
                        <div class="career-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80" alt="Sistemas y Software">
                            <span class="career-img-badge"><i class="bi bi-laptop me-1 text-warning"></i> TI</span>
                        </div>
                        <div class="p-3 text-center d-flex flex-column flex-grow-1 justify-content-between">
                            <h3 class="h6 fw-extrabold mb-1">Sistemas y Software</h3>
                            <span class="small fw-bold" style="color: var(--orange-bright);">Explorar ruta <i class="bi bi-arrow-right-short"></i></span>
                        </div>
                    </a>
                </div>

                <!-- 2. Medicina y Salud -->
                <div class="col">
                    <a href="{{ url('/servicios') }}" class="career-card-img">
                        <div class="career-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80" alt="Medicina y Salud">
                            <span class="career-img-badge"><i class="bi bi-heart-pulse-fill me-1 text-danger"></i> Salud</span>
                        </div>
                        <div class="p-3 text-center d-flex flex-column flex-grow-1 justify-content-between">
                            <h3 class="h6 fw-extrabold mb-1">Medicina y Salud</h3>
                            <span class="small fw-bold" style="color: var(--orange-bright);">Explorar ruta <i class="bi bi-arrow-right-short"></i></span>
                        </div>
                    </a>
                </div>

                <!-- 3. Gastronomía -->
                <div class="col">
                    <a href="{{ url('/servicios') }}" class="career-card-img">
                        <div class="career-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=600&q=80" alt="Gastronomía">
                            <span class="career-img-badge"><i class="bi bi-cup-hot-fill me-1 text-warning"></i> Chef</span>
                        </div>
                        <div class="p-3 text-center d-flex flex-column flex-grow-1 justify-content-between">
                            <h3 class="h6 fw-extrabold mb-1">Gastronomía</h3>
                            <span class="small fw-bold" style="color: var(--orange-bright);">Explorar ruta <i class="bi bi-arrow-right-short"></i></span>
                        </div>
                    </a>
                </div>

                <!-- 4. Derecho Corporativo -->
                <div class="col">
                    <a href="{{ url('/servicios') }}" class="career-card-img">
                        <div class="career-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=600&q=80" alt="Derecho Corporativo">
                            <span class="career-img-badge"><i class="bi bi-bank2 me-1 text-info"></i> Legal</span>
                        </div>
                        <div class="p-3 text-center d-flex flex-column flex-grow-1 justify-content-between">
                            <h3 class="h6 fw-extrabold mb-1">Derecho Corporativo</h3>
                            <span class="small fw-bold" style="color: var(--orange-bright);">Explorar ruta <i class="bi bi-arrow-right-short"></i></span>
                        </div>
                    </a>
                </div>

                <!-- 5. Arquitectura -->
                <div class="col">
                    <a href="{{ url('/servicios') }}" class="career-card-img">
                        <div class="career-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=600&q=80" alt="Arquitectura">
                            <span class="career-img-badge"><i class="bi bi-building me-1 text-primary"></i> Diseño</span>
                        </div>
                        <div class="p-3 text-center d-flex flex-column flex-grow-1 justify-content-between">
                            <h3 class="h6 fw-extrabold mb-1">Arquitectura</h3>
                            <span class="small fw-bold" style="color: var(--orange-bright);">Explorar ruta <i class="bi bi-arrow-right-short"></i></span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- ==========================================
         MÁS INFORMACIÓN: ¿POR QUÉ SHIFTLY? + NIVELES
         ========================================== -->
    <section class="py-5">
        <div class="container-xl">
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 fw-bold text-uppercase mb-2" style="background-color: var(--purple-main); color: var(--white);">
                    Acerca de la Plataforma
                </span>
                <h2 class="display-6 fw-extrabold" style="color: var(--purple-dark);">
                    Aprende inglés enfocado en tu futuro laboral
                </h2>
                <p class="text-muted mx-auto" style="max-width: 680px;">
                    A diferencia de los cursos genéricos, Shiftly entrena tu oído y vocabulario con situaciones reales de oficina, hospitales, tribunales, cocinas profesionales y estudios de diseño.
                </p>
            </div>

            <!-- 3 Pilares Gamificados -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="feature-card-3d">
                        <div class="icon-box-3d" style="background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-compass-fill"></i>
                        </div>
                        <h3 class="h5 fw-extrabold mb-2" style="color: var(--purple-dark);">1. Diagnóstico Inteligente</h3>
                        <p class="text-muted mb-0">
                            Evalúa tu dominio inicial y recibe una ruta de aprendizaje adaptada a tu carrera y nivel actual sin perder tiempo en temas que ya dominas.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card-3d">
                        <div class="icon-box-3d" style="background-color: var(--orange-light); color: var(--orange-bright);">
                            <i class="bi bi-controller"></i>
                        </div>
                        <h3 class="h5 fw-extrabold mb-2" style="color: var(--purple-dark);">2. Ejercicios Interactivos</h3>
                        <p class="text-muted mb-0">
                            Resuelve trivias técnicas, escucha la pronunciación correcta y acumula puntos de experiencia (XP) mientras avanzas por unidades temáticas.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card-3d">
                        <div class="icon-box-3d" style="background-color: var(--purple-soft); color: var(--purple-medium);">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h3 class="h5 fw-extrabold mb-2" style="color: var(--purple-dark);">3. Glosario Técnico Contextual</h3>
                        <p class="text-muted mb-0">
                            Consulta definiciones claras, traducciones precisas y oraciones de ejemplo aplicadas directamente a entrevistas y entornos de trabajo reales.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Progresión de Niveles (Marco Común Europeo) -->
            <div class="p-4 p-md-5 rounded-4" style="background-color: var(--white); border: 2px solid var(--border-purple);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
                    <div>
                        <span class="text-uppercase fw-extrabold small" style="color: var(--orange-bright);">Ruta Progresiva</span>
                        <h3 class="h4 fw-extrabold mb-0" style="color: var(--purple-dark);">Niveles de Dominio en Shiftly</h3>
                    </div>
                    <a href="{{ url('/ruta-aprendizaje') }}" class="btn btn-3d-purple py-2 px-4 fs-6">
                        Ver Mapa de Ruta
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="level-badge-card">
                            <span class="badge rounded-pill mb-2 fw-extrabold" style="background-color: var(--purple-soft); color: var(--purple-main);">Nivel A1 - A2</span>
                            <h4 class="h6 fw-extrabold" style="color: var(--purple-dark);">Fundamentos Técnicos</h4>
                            <p class="small text-muted mb-0">Herramientas básicas, comandos esenciales y vocabulario cotidiano de tu área.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="level-badge-card">
                            <span class="badge rounded-pill mb-2 fw-extrabold" style="background-color: var(--purple-soft); color: var(--purple-main);">Nivel B1</span>
                            <h4 class="h6 fw-extrabold" style="color: var(--purple-dark);">Comunicación Operativa</h4>
                            <p class="small text-muted mb-0">Lectura de documentación, reportes técnicos y reuniones de seguimiento.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="level-badge-card">
                            <span class="badge rounded-pill mb-2 fw-extrabold" style="background-color: var(--orange-light); color: var(--orange-bright);">Nivel B2</span>
                            <h4 class="h6 fw-extrabold" style="color: var(--purple-dark);">Fluidez Profesional</h4>
                            <p class="small text-muted mb-0">Presentación de proyectos, resolución de incidencias y entrevistas laborales.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="level-badge-card">
                            <span class="badge rounded-pill mb-2 fw-extrabold" style="background-color: var(--purple-main); color: var(--white);">Nivel C1</span>
                            <h4 class="h6 fw-extrabold" style="color: var(--purple-dark);">Dominio Ejecutivo</h4>
                            <p class="small text-muted mb-0">Negociación internacional, arquitectura compleja y liderazgo global.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         INTEGRANTES DEL EQUIPO SHIFTLY (6 INTEGRANTES)
         ========================================== -->
    <section id="equipo" class="team-section-purple py-5">
        <div class="container-xl py-2">
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 fw-bold text-uppercase mb-2" style="background-color: var(--orange-bright); color: var(--white);">
                    <i class="bi bi-people-fill me-1"></i> Nuestro Equipo
                </span>
                <h2 class="display-6 fw-extrabold" style="color: var(--purple-dark);">
                    Integrantes del Proyecto Shiftly
                </h2>
                <p class="text-muted mx-auto" style="max-width: 620px;">
                    Desarrollado por estudiantes de Ingeniería en Tecnologías de la Información e Innovación Digital en la Universidad Politécnica de Pachuca.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- 1. Luis David Balandrano Delgado -->
                <div class="col-sm-6 col-lg-4">
                    <div class="team-card-3d">
                        <div class="team-avatar-circle" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-main) 100%);">
                            LD
                        </div>
                        <span class="badge rounded-pill px-3 py-1 mb-2 fw-bold" style="background-color: var(--orange-light); color: var(--orange-bright);">
                            <i class="bi bi-star-fill me-1"></i> Líder Técnico y Full Stack
                        </span>
                        <h3 class="h6 fw-extrabold mb-1" style="color: var(--purple-dark);">Luis David Balandrano Delgado</h3>
                        <p class="small text-muted mb-0">Arquitectura integral en Laravel, integración de módulos y lógica interactiva.</p>
                    </div>
                </div>

                <!-- 2. Arellano Carrillo Isis Dayane -->
                <div class="col-sm-6 col-lg-4">
                    <div class="team-card-3d">
                        <div class="team-avatar-circle" style="background: linear-gradient(135deg, var(--orange-bright) 0%, #FF8C42 100%);">
                            IA
                        </div>
                        <span class="badge rounded-pill px-3 py-1 mb-2 fw-bold" style="background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-layout-text-window-reverse me-1"></i> Frontend
                        </span>
                        <h3 class="h6 fw-extrabold mb-1" style="color: var(--purple-dark);">Arellano Carrillo Isis Dayane</h3>
                        <p class="small text-muted mb-0">Diseño de interfaces de usuario, maquetación responsiva y experiencia visual.</p>
                    </div>
                </div>

                <!-- 3. Villa Aldana Keydhy Mareli -->
                <div class="col-sm-6 col-lg-4">
                    <div class="team-card-3d">
                        <div class="team-avatar-circle" style="background: linear-gradient(135deg, var(--purple-medium) 0%, #9C27B0 100%);">
                            KV
                        </div>
                        <span class="badge rounded-pill px-3 py-1 mb-2 fw-bold" style="background-color: var(--orange-light); color: var(--orange-bright);">
                            <i class="bi bi-clipboard2-data-fill me-1"></i> Analista
                        </span>
                        <h3 class="h6 fw-extrabold mb-1" style="color: var(--purple-dark);">Villa Aldana Keydhy Mareli</h3>
                        <p class="small text-muted mb-0">Levantamiento de requerimientos, documentación funcional y estructura pedagógica.</p>
                    </div>
                </div>

                <!-- 4. Vaneza Lugo Ramírez -->
                <div class="col-sm-6 col-lg-4">
                    <div class="team-card-3d">
                        <div class="team-avatar-circle" style="background: linear-gradient(135deg, var(--purple-main) 0%, var(--purple-medium) 100%);">
                            VL
                        </div>
                        <span class="badge rounded-pill px-3 py-1 mb-2 fw-bold" style="background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-hdd-stack-fill me-1"></i> Backend
                        </span>
                        <h3 class="h6 fw-extrabold mb-1" style="color: var(--purple-dark);">Vaneza Lugo Ramírez</h3>
                        <p class="small text-muted mb-0">Desarrollo de lógica del servidor, controladores y gestión de flujo de datos.</p>
                    </div>
                </div>

                <!-- 5. Brian Jael Juarez Rodriguez -->
                <div class="col-sm-6 col-lg-4">
                    <div class="team-card-3d">
                        <div class="team-avatar-circle" style="background: linear-gradient(135deg, var(--orange-bright) 0%, #FF8C42 100%);">
                            BJ
                        </div>
                        <span class="badge rounded-pill px-3 py-1 mb-2 fw-bold" style="background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-window-sidebar me-1"></i> Frontend
                        </span>
                        <h3 class="h6 fw-extrabold mb-1" style="color: var(--purple-dark);">Brian Jael Juarez Rodriguez</h3>
                        <p class="small text-muted mb-0">Componentes interactivos en cliente, estilos modulares y vistas dinámicas.</p>
                    </div>
                </div>

                <!-- 6. Julio Cesar Licona Hernandez -->
                <div class="col-sm-6 col-lg-4">
                    <div class="team-card-3d">
                        <div class="team-avatar-circle" style="background: linear-gradient(135deg, var(--purple-main) 0%, var(--purple-medium) 100%);">
                            JL
                        </div>
                        <span class="badge rounded-pill px-3 py-1 mb-2 fw-bold" style="background-color: var(--purple-soft); color: var(--purple-main);">
                            <i class="bi bi-server me-1"></i> Backend
                        </span>
                        <h3 class="h6 fw-extrabold mb-1" style="color: var(--purple-dark);">Julio Cesar Licona Hernandez</h3>
                        <p class="small text-muted mb-0">Enrutamiento en Laravel, validaciones y arquitectura de servicios.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         BANNER FINAL CTA MORADO
         ========================================== -->
    <section class="py-5">
        <div class="container-xl">
            <div class="cta-banner-purple p-4 p-md-5 text-center">
                <div class="mx-auto" style="max-width: 650px;">
                    <span class="badge rounded-pill px-3 py-2 fw-bold mb-3" style="background-color: rgba(255,255,255,0.16); color: var(--white);">
                        <i class="bi bi-stars me-1 text-warning"></i> ¡Comienza hoy mismo!
                    </span>
                    <h2 class="display-6 fw-extrabold mb-3">
                        Impulsa tu carrera hablando el idioma del mundo laboral
                    </h2>
                    <p class="text-white-50 mb-4 fs-5">
                        Crea tu perfil gratuito, elige tu especialidad y completa tu primera lección en menos de 3 minutos.
                    </p>
                    <a href="{{ url('/registro') }}" class="btn btn-3d-orange fs-6 px-5 py-3">
                        Crear Cuenta Gratis <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

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
                <a href="{{ url('/diagnostico') }}" class="text-white-50 text-decoration-none">Diagnóstico</a>
                <a href="{{ url('/servicios') }}" class="text-white-50 text-decoration-none">Vocabularios</a>
                <a href="{{ url('/glosario') }}" class="text-white-50 text-decoration-none">Glosario</a>
                <a href="#equipo" class="text-white-50 text-decoration-none">Equipo</a>
                <a href="{{ url('/contacto') }}" class="text-white-50 text-decoration-none">Contacto</a>
                <a href="{{ url('/perfil') }}" class="text-white-50 text-decoration-none">Mi Perfil</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Interactividad del Mini-Reto en el Hero -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const opciones = document.querySelectorAll("#heroQuizOptions .quiz-option-pill");
            const feedback = document.getElementById("heroQuizFeedback");
            const progressBar = document.getElementById("heroProgressBar");

            opciones.forEach(boton => {
                boton.addEventListener("click", () => {
                    opciones.forEach(op => op.classList.remove("selected-correct", "selected-wrong"));

                    const esCorrecta = boton.getAttribute("data-correct") === "true";

                    if (esCorrecta) {
                        boton.classList.add("selected-correct");
                        progressBar.style.width = "100%";
                        feedback.className = "p-3 rounded-4 d-block fw-bold small bg-success-subtle text-success border border-success-subtle";
                        feedback.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> ¡Excelente! +15 XP ganados. Así de rápido aprendes en Shiftly.`;
                    } else {
                        boton.classList.add("selected-wrong");
                        progressBar.style.width = "45%";
                        feedback.className = "p-3 rounded-4 d-block fw-bold small bg-danger-subtle text-danger border border-danger-subtle";
                        feedback.innerHTML = `<i class="bi bi-x-circle-fill me-1"></i> Casi... Intenta de nuevo para ganar tus puntos XP.`;
                    }
                });
            });
        });
    </script>
    <!-- Script para mostrar el nombre del usuario en el botón del Navbar -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sesion = JSON.parse(localStorage.getItem("shiftly_usuario_activo") || "null");
        const perfilGuardado = JSON.parse(localStorage.getItem("shiftly_user_profile_full") || "null");
        const spanNav = document.getElementById("navNombreUsuario");

        if (spanNav) {
            if (perfilGuardado && perfilGuardado.nombre) {
                spanNav.textContent = perfilGuardado.nombre;
            } else if (sesion && sesion.nombre) {
                spanNav.textContent = sesion.nombre.split(" ")[0];
            }
        }
    });
</script>
</body>

</html>