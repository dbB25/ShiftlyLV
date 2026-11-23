<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Lección Recomendada</title>

    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    <style>
        :root {
            --brand-primary: #111344;
            --brand-secondary: #52154E;
            --brand-bg: #DAE0F2;
            --brand-surface: #EFE9E7;
            --brand-accent: #EDD0EC;
            --brand-orange: #FF6B35;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-bg);
            color: var(--brand-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Utilidades de color personalizadas */
        .bg-brand-primary { background-color: var(--brand-primary) !important; }
        .bg-brand-secondary { background-color: var(--brand-secondary) !important; }
        .bg-brand-surface { background-color: var(--brand-surface) !important; }
        .bg-brand-accent { background-color: var(--brand-accent) !important; }
        .text-brand-primary { color: var(--brand-primary) !important; }
        .text-brand-secondary { color: var(--brand-secondary) !important; }
        .text-brand-accent { color: var(--brand-accent) !important; }
        .border-brand-accent { border-color: var(--brand-accent) !important; }

        .nav-link-rec {
            color: #6c757d;
            text-decoration: none;
            padding: 1rem 0;
            border-bottom: 3px solid transparent;
        }

        .nav-link-rec:hover,
        .nav-link-rec.active {
            color: var(--brand-secondary);
            border-bottom-color: var(--brand-secondary);
            font-weight: 700;
        }

        .card-custom {
            background-color: var(--brand-surface);
            border: 1px solid rgba(237, 208, 236, 0.6);
            border-radius: 1rem;
        }

        .top-gradient-bar {
            height: 6px;
            background: linear-gradient(90deg, var(--brand-primary) 0%, var(--brand-secondary) 50%, var(--brand-accent) 100%);
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .progress-bar-custom {
            background-color: var(--brand-secondary);
        }

        .selector-carrera {
            border: 1px solid var(--brand-accent);
            border-radius: 0.75rem;
            font-weight: 600;
            color: var(--brand-primary);
            background-color: var(--brand-surface);
        }

        .estado-modulo {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
        }

        .module-item.actual {
            border-color: var(--brand-secondary) !important;
            box-shadow: 0 0 0 2px rgba(82, 21, 78, 0.15);
        }

        .module-item.bloqueado {
            opacity: 0.6;
        }

        /* Animación de pulso */
        @keyframes subtle-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .animate-pulse-subtle {
            animation: subtle-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .animate-pulse-subtle { animation: none; }
        }
    </style>
</head>
<body data-url-base="{{ url('/') }}">

    <!-- HEADER / NAVIGATION -->
    <header class="bg-brand-surface border-bottom sticky-top shadow-sm">
        <div class="container-xl d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-4">
                <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 fw-bold fs-5 text-brand-primary text-decoration-none py-2">
                    <img src="{{ asset('img/logotipo interfaz.svg') }}" width="36" height="36" alt="Logo Shiftly">
                    <span>Shiftly</span>
                </a>
                <nav class="d-none d-md-flex gap-4 fs-6 fw-medium" aria-label="Navegación principal">
                    <a href="{{ url('/dashboard') }}" class="nav-link-rec">Dashboard</a>
                    <a href="{{ url('/recomendaciones') }}" class="nav-link-rec active" aria-current="page">Recomendaciones</a>
                    <a href="{{ url('/ruta-aprendizaje') }}" class="nav-link-rec">Rutas de Aprendizaje</a>
                    <a href="{{ url('/resultados-leccion') }}" class="nav-link-rec">Reportes</a>
                </nav>
            </div>

            <!-- Usuario activo -->
            <a href="{{ url('/perfil') }}" class="d-flex align-items-center gap-2 py-2 text-decoration-none">
                <div class="rounded-circle bg-brand-accent text-brand-primary d-flex align-items-center justify-content-center fw-bold border border-secondary border-opacity-25" style="width: 36px; height: 36px; font-size: 0.85rem;" id="inicialesUsuario">
                    ?
                </div>
                <div class="d-none d-sm-block text-start">
                    <div class="fw-semibold text-brand-primary lh-1" style="font-size: 0.85rem;" id="nombreUsuario">Invitado</div>
                    <small class="text-muted" style="font-size: 0.75rem;" id="nivelUsuario">Learner Account</small>
                </div>
            </a>
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container-xl py-4 flex-grow-1">

        <!-- Selector de carrera -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h4 fw-bold text-brand-primary mb-1">Tu siguiente paso</h1>
                <p class="small text-muted mb-0">Recomendaciones basadas en tu progreso real, tu diagnóstico y tus errores pendientes.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="selectorCarrera" class="small fw-semibold text-muted text-nowrap">Ver ruta:</label>
                <select class="form-select form-select-sm selector-carrera" id="selectorCarrera"></select>
            </div>
        </div>

        <div class="row g-4 align-items-start">

            <!-- COLUMNA PRINCIPAL -->
            <div class="col-lg-8">

                <!-- ESTADO 1: LECCIÓN RECOMENDADA -->
                <section id="card-active-state" class="card-custom shadow-sm overflow-hidden mb-4 d-none">
                    <div class="top-gradient-bar"></div>
                    <div class="p-4 p-sm-5">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <span class="badge rounded-pill bg-brand-accent text-brand-primary border border-secondary border-opacity-25 px-3 py-2 fw-semibold">
                                <span class="d-inline-block rounded-circle bg-brand-secondary me-2 animate-pulse-subtle" style="width: 6px; height: 6px;"></span>
                                Ruta: <span id="nombreRuta">—</span>
                            </span>
                            <div class="d-flex align-items-center gap-2 small text-muted">
                                <span class="badge bg-white text-brand-primary border px-2 py-1">
                                    <i class="bi bi-clock text-brand-secondary me-1"></i> ≈ 5 min
                                </span>
                                <span class="badge bg-white text-brand-secondary border px-2 py-1" id="xpLeccion">+0 XP</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-uppercase fw-bold text-brand-secondary small mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Siguiente lección recomendada</p>
                            <h2 class="h3 fw-bold text-brand-primary" id="tituloRecomendado">—</h2>
                            <p class="text-brand-primary text-opacity-75 small mt-2" id="descripcionRecomendada"></p>
                        </div>

                        <div class="p-3 rounded-3 bg-white bg-opacity-50 border border-brand-accent mb-4 d-flex align-items-start gap-3">
                            <div class="p-1 bg-brand-accent text-brand-primary rounded-2 flex-shrink-0 mt-1">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="small text-brand-primary text-opacity-75">
                                <strong class="text-brand-primary">¿Por qué esta lección?</strong>
                                <div class="mt-1" id="motivoRecomendacion"></div>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-brand-accent d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                            <div class="w-100 text-center text-sm-start" style="max-width: 220px;">
                                <span class="small text-muted">Progreso de la ruta: <strong class="text-brand-primary" id="porcentajeRuta">0%</strong></span>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar progress-bar-custom" role="progressbar" id="barraRuta" style="width: 0%;"></div>
                                </div>
                            </div>
                            <a href="{{ url('/ejercicios') }}" class="btn btn-lg bg-brand-secondary text-white px-4 py-2 rounded-3 shadow-sm fw-semibold fs-6" id="btnComenzarLeccion">
                                Comenzar lección <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- ESTADO 2: RUTA COMPLETADA -->
                <section id="card-completed-state" class="card-custom shadow-sm p-4 p-sm-5 text-center d-none mb-4">
                    <div class="rounded-circle bg-brand-accent text-brand-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-trophy-fill fs-3"></i>
                    </div>
                    <span class="badge rounded-pill bg-brand-accent text-brand-secondary border border-secondary border-opacity-25 px-3 py-2 fw-semibold mb-2">¡Ruta completada!</span>
                    <h2 class="h4 fw-bold text-brand-primary mb-2">¡Dominaste <span id="rutaCompletadaNombre">esta ruta</span>!</h2>
                    <p class="text-brand-primary text-opacity-75 small mx-auto mb-4" style="max-width: 420px;">
                        Completaste todas las lecciones. Repasa tus errores o empieza otra carrera para seguir sumando XP.
                    </p>
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-2">
                        <a href="{{ url('/ruta-aprendizaje') }}" class="btn btn-outline-secondary text-brand-primary border-brand-accent fw-semibold" id="btnRepasarRuta">
                            Repasar la ruta
                        </a>
                        <a href="{{ url('/ruta-aprendizaje') }}" class="btn bg-brand-secondary text-white fw-semibold">
                            Explorar otras rutas
                        </a>
                    </div>
                </section>

                <!-- ESTADO 3: SIN RUTA -->
                <section id="card-empty-state" class="card-custom shadow-sm p-4 p-sm-5 text-center d-none mb-4" style="border-style: dashed;">
                    <div class="rounded-circle bg-white text-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-compass fs-3"></i>
                    </div>
                    <h2 class="h5 fw-bold text-brand-primary mb-1">Sin ruta de aprendizaje asignada</h2>
                    <p class="text-brand-primary text-opacity-75 small mx-auto mb-4" style="max-width: 380px;">
                        Haz el examen diagnóstico para que te recomendemos una carrera y una lección inicial, o elige una ruta tú mismo.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ url('/diagnostico') }}" class="btn bg-brand-primary text-white fw-semibold px-4">Hacer diagnóstico</a>
                        <a href="{{ url('/ruta-aprendizaje') }}" class="btn btn-outline-secondary text-brand-primary border-brand-accent fw-semibold">Elegir ruta</a>
                    </div>
                </section>

                <!-- LECCIONES DE LA RUTA -->
                <div class="card-custom p-4 shadow-sm" id="tarjetaModulos">
                    <h3 class="fs-6 fw-bold text-brand-primary mb-3">Lecciones de la ruta</h3>
                    <label for="buscar-modulo" class="form-label small mb-1">Buscar en tus lecciones</label>
                    <input type="search" class="form-control mb-3" id="buscar-modulo" placeholder="Escribe para filtrar..." aria-label="Buscar lecciones de la ruta">
                    <div class="d-flex flex-column gap-2" id="lista-modulos"></div>
                    <p id="sin-resultados-modulo" class="small text-muted mt-3 mb-0 d-none">No hay lecciones que coincidan con la búsqueda.</p>
                </div>
            </div>

            <!-- COLUMNA LATERAL -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">
                    <div class="card-custom p-4 shadow-sm">
                        <h3 class="text-uppercase fw-bold text-brand-primary mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Resumen de Actividad</h3>
                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="p-3 bg-white bg-opacity-50 rounded-3 border border-brand-accent">
                                    <span class="fs-4 fw-bold text-brand-secondary d-block" id="resumenLecciones">0/0</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Lecciones completadas</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-white bg-opacity-50 rounded-3 border border-brand-accent">
                                    <span class="fs-4 fw-bold text-brand-primary d-block" id="resumenTiempo">0 s</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Tiempo invertido</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-white bg-opacity-50 rounded-3 border border-brand-accent">
                                    <span class="fs-4 fw-bold d-block" style="color: #EA580C;" id="resumenRacha">0</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Días de racha</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-white bg-opacity-50 rounded-3 border border-brand-accent">
                                    <span class="fs-4 fw-bold text-brand-secondary d-block" id="resumenMeta">0/30</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">XP de hoy</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Otras recomendaciones -->
                    <div class="card-custom p-4 shadow-sm">
                        <h3 class="text-uppercase fw-bold text-brand-primary mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">También te recomendamos</h3>
                        <div class="d-flex flex-column gap-2 small" id="otrasRecomendaciones"></div>
                    </div>

                    <!-- Ayuda -->
                    <div class="bg-brand-primary rounded-4 p-4 text-white shadow-sm border border-brand-primary">
                        <div class="rounded-3 bg-brand-accent bg-opacity-25 text-brand-accent d-flex align-items-center justify-content-center mb-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-question-circle"></i>
                        </div>
                        <h4 class="fw-bold fs-6 mb-1">¿Tienes dudas con este tema?</h4>
                        <p class="text-white-50 small mb-3">
                            Consulta el glosario técnico o escríbenos y te ayudamos con tu ruta.
                        </p>
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ url('/glosario') }}" class="small fw-semibold text-brand-accent text-decoration-none">Glosario técnico &rarr;</a>
                            <a href="{{ url('/contacto') }}" class="small fw-semibold text-brand-accent text-decoration-none">Centro de ayuda y soporte &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Progreso del alumno desde la base de datos (antes de los demás scripts) -->
    @include('partials.progreso-servidor')

    <!-- Progreso real del usuario y recomendaciones -->
    <script src="{{ asset('js/rutas-catalogo.js') }}"></script>
    <script src="{{ asset('js/shiftly-progreso.js') }}?v=2"></script>
    <script src="{{ asset('js/recomendaciones.js') }}"></script>
</body>
</html>
