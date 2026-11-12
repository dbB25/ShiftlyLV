<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Examen Diagnóstico de Nivel</title>

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Propios -->
    <link rel="stylesheet" href="{{ asset('css/diagnostico.css') }}?v=2">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- ==========================================
         NAVBAR NORMAL (Visible antes y después del examen)
         ========================================== -->
    <header id="normalNavbar">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-shiftly py-3">
            <div class="container-xl">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-extrabold fs-3 text-decoration-none text-white" href="{{ url('/') }}" style="color: #FFFFFF !important;">
                    <img src="{{ asset('img/logotipo interfaz.svg') }}" onerror="this.onerror=null; this.src='https://placehold.co/100x100/52154E/ffffff?text=S';" alt="Logo de Shiftly" width="40" height="40">
                    <span style="color: #FFFFFF !important;">Shiftly</span>
                </a>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <a href="{{ url('/') }}" class="nav-link-app">Inicio</a>
                    <a href="{{ url('/perfil') }}" class="nav-link-app">Mi perfil</a>
                    <a href="{{ url('/contacto') }}" class="nav-link-app">Ayuda</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ==========================================
         BARRA DE MODO EXAMEN PROTEGIDO (Solo visible durante el test)
         ========================================== -->
    <div id="examLockHeader" class="exam-lock-bar py-3 sticky-top d-none">
        <div class="container-xl d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-2 fw-extrabold" style="background-color: var(--orange-bright); color: var(--white);">
                    <i class="bi bi-shield-lock-fill me-1"></i> MODO EXAMEN ACTIVO
                </span>
                <span class="small text-white-50 d-none d-md-inline">Navegación bloqueada hasta finalizar la prueba</span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="small fw-bold text-warning" id="warningCounterBadge">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Salidas detectadas: <strong id="tabLeaveCount">0</strong>/3
                </span>
                <div class="px-3 py-1 rounded-pill fw-extrabold" style="background: rgba(255,255,255,0.15);">
                    <i class="bi bi-stopwatch-fill text-warning me-1"></i> <span id="examTimer">10:00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         CONTENIDO PRINCIPAL
         ========================================== -->
    <main class="container-xl py-4 py-md-5 flex-grow-1">
        <div class="mx-auto" style="max-width: 820px;">

            <!-- ==========================================
                 FASE 1: PANTALLA DE INSTRUCCIONES Y CONFIGURACIÓN
                 ========================================== -->
            <section id="pantalla-inicio" class="exam-card-3d text-center">
                <span class="badge rounded-pill px-3 py-2 fw-extrabold text-uppercase mb-3" style="background-color: var(--purple-soft); color: var(--purple-main);">
                    <i class="bi bi-mortarboard-fill me-1"></i> Evaluación Oficial CEFR (A1 - C1)
                </span>

                <h1 class="display-6 fw-extrabold mb-3" style="color: var(--purple-dark);">
                    Descubre tu nivel real de inglés
                </h1>

                <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
                    Este diagnóstico consta de <strong>10 preguntas progresivas</strong> (gramática, comprensión lectora, audio y vocabulario profesional) para ubicar tu punto exacto de partida en Shiftly.
                </p>

                <!-- Selección de Carrera Objetivo -->
                <div class="text-start mb-4 p-4 rounded-4" style="background-color: var(--purple-light); border: 2px solid var(--border-purple);">
                    <label class="fw-extrabold small text-uppercase d-block mb-3" style="color: var(--purple-main);">
                        1. Selecciona tu área profesional para personalizar tus recomendaciones:
                    </label>
                    <div class="row g-2" id="careerSelector">
                        <div class="col-6 col-md-4">
                            <div class="career-select-pill active" data-career="Sistemas y Software">💻 Sistemas y TI</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="career-select-pill" data-career="Medicina y Salud">🩺 Medicina</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="career-select-pill" data-career="Gastronomía">🍳 Gastronomía</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="career-select-pill" data-career="Derecho Corporativo">⚖️ Derecho</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="career-select-pill" data-career="Arquitectura">🏛️ Arquitectura</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="career-select-pill" data-career="General">🌎 General</div>
                        </div>
                    </div>
                </div>

                <!-- Reglas del Modo Bloqueo -->
                <div class="text-start p-3 rounded-4 mb-4 d-flex align-items-start gap-3" style="background-color: var(--orange-light); border: 2px solid rgba(255,107,53,0.4);">
                    <i class="bi bi-shield-exclamation fs-3" style="color: var(--orange-bright);"></i>
                    <div class="small" style="color: var(--purple-dark);">
                        <strong>Reglas del Examen Protegido:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            <li>Tendrás <strong>10 minutos</strong> para completar las 10 preguntas.</li>
                            <li>Al iniciar, <strong>no podrás salir de la interfaz</strong> ni cambiar de pestaña hasta terminar.</li>
                            <li>Si cambias de ventana 3 veces, el examen se enviará automáticamente con las respuestas actuales.</li>
                        </ul>
                    </div>
                </div>

                <button type="button" id="btnIniciarExamen" class="btn btn-3d-orange fs-5 px-5 py-3">
                    <i class="bi bi-play-circle-fill"></i> Iniciar Examen Ahora
                </button>
            </section>

            <!-- ==========================================
                 FASE 2: INTERFAZ DEL EXAMEN ACTIVO
                 ========================================== -->
            <section id="pantalla-examen" class="d-none">

                <!-- Barra de Progreso -->
                <div class="exam-card-3d p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-extrabold small text-uppercase" style="color: var(--purple-main);" id="nivel-pregunta-badge">
                            Nivel Evaluado: A1 · Básico
                        </span>
                        <strong id="progreso-texto" class="small" style="color: var(--purple-dark);" aria-live="polite">
                            Pregunta 1 de 10
                        </strong>
                    </div>
                    <div class="progress-track">
                        <div class="progress-bar-fill" id="barra-progreso" style="width: 10%;"></div>
                    </div>
                </div>

                <!-- Formulario de Pregunta -->
                <form id="diagnostico-form" novalidate>
                    <div class="exam-card-3d pregunta-card mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge rounded-pill px-3 py-2 fw-bold" id="pregunta-categoria" style="background-color: var(--purple-soft); color: var(--purple-main);">
                                Gramática · Presente Simple
                            </span>

                            <button type="button" id="btnEscucharPregunta" class="btn-audio-q" title="Escuchar enunciado en inglés">
                                <i class="bi bi-volume-up-fill"></i>
                            </button>
                        </div>

                        <h2 class="h4 fw-extrabold mb-2" id="pregunta-texto" style="color: var(--purple-dark); line-height: 1.45;">
                            Choose the correct option:
                        </h2>

                        <p class="text-muted small mb-4" id="pregunta-contexto">
                            Selecciona la estructura gramatical correcta.
                        </p>

                        <!-- Opciones -->
                        <div class="row g-3" id="opciones-respuesta" role="group" aria-labelledby="pregunta-texto">
                            <div class="col-md-6">
                                <button type="button" class="opcion-respuesta btn w-100 text-start" data-option="0">
                                    <span class="option-key-badge">A</span>
                                    <span class="option-label">Opción A</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="opcion-respuesta btn w-100 text-start" data-option="1">
                                    <span class="option-key-badge">B</span>
                                    <span class="option-label">Opción B</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="opcion-respuesta btn w-100 text-start" data-option="2">
                                    <span class="option-key-badge">C</span>
                                    <span class="option-label">Opción C</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="opcion-respuesta btn w-100 text-start" data-option="3">
                                    <span class="option-key-badge">D</span>
                                    <span class="option-label">Opción D</span>
                                </button>
                            </div>
                        </div>

                        <!-- Alerta de validación -->
                        <div id="mensaje-error" class="alert alert-danger rounded-4 mt-3 mb-0 py-2 px-3 small fw-bold d-none" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> Selecciona una respuesta para continuar.
                        </div>
                    </div>

                    <!-- Navegación entre preguntas -->
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-3d-ghost" id="anterior" disabled>
                            <i class="bi bi-arrow-left me-1"></i> Anterior
                        </button>

                        <button type="submit" class="btn btn-3d-orange" id="siguiente">
                            Siguiente <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>
            </section>

            <!-- ==========================================
                 FASE 3: RESULTADOS COMPLETOS Y REVISIÓN
                 ========================================== -->
            <section id="pantalla-resultados" class="d-none">
                <div class="exam-card-3d text-center mb-4">
                    <span class="badge rounded-pill px-3 py-2 fw-extrabold mb-3" style="background-color: #DCFCE7; color: #15803D;">
                        <i class="bi bi-check-circle-fill me-1"></i> Diagnóstico Completado · Interfaz Desbloqueada
                    </span>

                    <div class="mx-auto my-3 d-flex align-items-center justify-content-center rounded-circle fw-extrabold text-white"
                         style="width: 110px; height: 110px; font-size: 2.5rem; background: linear-gradient(135deg, var(--purple-main), var(--orange-bright)); box-shadow: 0 10px 24px rgba(82,21,78,0.3);"
                         id="resultado-nivel-codigo">
                        B1
                    </div>

                    <h2 class="display-6 fw-extrabold mb-1" style="color: var(--purple-dark);" id="resultado-nivel-nombre">
                        Nivel Intermedio
                    </h2>
                    <p class="fw-bold mb-3" style="color: var(--orange-bright);" id="resultado-puntaje-texto">
                        Obtuviste 6 de 10 aciertos (60%)
                    </p>

                    <p class="text-muted mx-auto mb-4" style="max-width: 620px;" id="resultado-descripcion">
                        Descripción del nivel obtenido.
                    </p>

                    <!-- Tarjeta de Recomendación Personalizada por Carrera -->
                    <div class="text-start p-4 rounded-4 mb-4" style="background-color: var(--purple-light); border: 2px solid var(--border-purple);">
                        <div class="fw-extrabold small text-uppercase mb-1" style="color: var(--purple-main);">
                            <i class="bi bi-compass-fill me-1"></i> Ruta Recomendada para <span id="resultado-carrera">Sistemas</span>
                        </div>
                        <p class="mb-0 small text-dark fw-medium" id="resultado-recomendacion">
                            Recomendación personalizada.
                        </p>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ url('/ejercicios') }}" class="btn btn-3d-orange">
                            <i class="bi bi-lightning-charge-fill"></i> Comenzar a Practicar
                        </a>
                        <a href="{{ url('/glosario') }}" class="btn btn-3d-purple">
                            <i class="bi bi-journal-bookmark-fill me-1"></i> Ir al Glosario Técnico
                        </a>
                        <button type="button" class="btn btn-3d-ghost" onclick="location.reload()">
                            <i class="bi bi-arrow-repeat me-1"></i> Repetir Test
                        </button>
                    </div>
                </div>

                <!-- Desglose pregunta por pregunta -->
                <div class="exam-card-3d">
                    <h3 class="h5 fw-extrabold mb-3" style="color: var(--purple-dark);">
                        <i class="bi bi-list-check me-2" style="color: var(--orange-bright);"></i> Revisión Detallada de tus Respuestas
                    </h3>
                    <div id="lista-revision" class="d-flex flex-column gap-3"></div>
                </div>
            </section>

        </div>
    </main>

    <!-- ==========================================
         MODAL DE ADVERTENCIA ANTI-SALIDA
         ========================================== -->
    <div class="modal fade" id="modalSalidaExamen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #B91C1C, #EF4444);">
                    <i class="bi bi-shield-fill-exclamation display-4"></i>
                    <h4 class="fw-extrabold mt-2 mb-0">¡No salgas del examen!</h4>
                </div>
                <div class="p-4 text-center">
                    <p class="fw-bold text-dark mb-2" id="textoAdvertenciaSalida">
                        Detectamos que intentaste salir de la pantalla o cambiar de pestaña.
                    </p>
                    <p class="small text-muted mb-4">
                        Por seguridad académica, debes permanecer en esta interfaz hasta finalizar tu diagnóstico. Al tercer intento el test se cerrará automáticamente.
                    </p>
                    <button type="button" class="btn btn-3d-orange w-100" data-bs-dismiss="modal" id="btnVolverAlExamen">
                        Regresar al Examen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         FOOTER
         ========================================== -->
    <footer id="normalFooter" class="footer-shiftly py-4 mt-auto">
        <div class="container-xl text-center">
            <p class="small text-white-50 mb-0">
                &copy; 2026 Shiftly · En Shiftly creemos que el inglés puede llevarte más lejos.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de sincronización con LocalStorage de Laravel -->
    <script>
        (() => {
            const userId = @json(auth()->id());
            const originalSetItem = Storage.prototype.setItem;

            Storage.prototype.setItem = function (key, value) {
                originalSetItem.call(this, key, value);

                if (key === 'shiftlyDiagnosticoResultado') {
                    try {
                        const resultado = JSON.parse(value);
                        const progreso = {
                            usuario: userId ?? 'visitante',
                            tipo: 'diagnostico',
                            puntuacion: resultado.aciertos,
                            total: resultado.total,
                            nivel: resultado.nivel,
                            carrera: resultado.carrera,
                            revision: resultado.revision,
                            completado: true,
                            actualizadoEn: new Date().toISOString()
                        };
                        originalSetItem.call(
                            this,
                            `shiftly:progreso:${progreso.usuario}:diagnostico`,
                            JSON.stringify(progreso)
                        );
                    } catch (error) {
                        console.error('No se pudo guardar el progreso del diagnóstico.', error);
                    }
                }
            };
        })();
    </script>

    <!-- Script Principal del Diagnóstico -->
    <script src="{{ asset('js/diagnostico.js') }}?v=2"></script>
</body>

</html>