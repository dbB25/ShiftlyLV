<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A0B2E">

    <title>Shiftly | Práctica Interactiva</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Propios -->
    <link rel="stylesheet" href="{{ asset('css/ejercicios.css') }}?v=2">

    <!-- App instalable (PWA) -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
</head>

<body class="d-flex flex-column min-vh-100" data-url-ejercicios="{{ url('/ejercicios') }}" data-url-ruta="{{ url('/ruta-') }}">

    <!-- ==========================================
         NAVBAR CORPORATIVO SHIFTLY
         ========================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-shiftly sticky-top py-3">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-extrabold fs-3 text-decoration-none text-white" href="{{ url('/') }}" style="color: #FFFFFF !important;">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" onerror="this.onerror=null; this.src='https://placehold.co/100x100/52154E/ffffff?text=S';" alt="Logo Shiftly" width="40" height="40">
                <span style="color: #FFFFFF !important;">Shiftly</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarShiftly">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarShiftly">
                <div class="navbar-nav mx-auto gap-1 my-2 my-lg-0">
                    <a href="{{ url('/') }}" class="nav-link nav-link-app">Inicio</a>
                    <a href="{{ url('/servicios') }}" class="nav-link nav-link-app">Vocabularios</a>
                    <a href="{{ url('/ejercicios') }}" class="nav-link nav-link-app active">Practicar</a>
                    <a href="{{ url('/glosario') }}" class="nav-link nav-link-app">Glosario</a>
                    <a href="{{ url('/dashboard') }}" class="nav-link nav-link-app">Dashboard</a>
                    <a href="{{ route('gestion.ejercicios.index') }}" class="nav-link nav-link-app">Gestionar ejercicios</a>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ url('/perfil') }}" class="text-white text-decoration-none fw-bold small">Mi Perfil</a>
                    <a href="{{ url('/perfil') }}" class="nav-avatar-badge" id="profile" title="Ver Perfil">B</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         ENCABEZADO DE UNIDAD Y MÉTRICAS EN VIVO
         ========================================== -->
    <section class="unit-banner py-4">
        <div class="container-xl">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill fw-bold small mb-2" id="badgeLeccion" style="background: rgba(255,107,53,0.2); color: #FFB08E; border: 1.5px solid rgba(255,107,53,0.45);">
                        <i class="bi bi-layers-fill"></i> UNIDAD 03 · LECCIÓN 2 DE 5
                    </div>
                    <h1 class="h2 fw-extrabold mb-1" id="tituloLeccion">Rutinas y Entorno Profesional</h1>
                    <p class="text-white-50 mb-0 small" id="descripcionLeccion">Completa los 6 retos interactivos para dominar el presente simple y expresiones técnicas diarias.</p>
                </div>

                <!-- Contadores Gamificados -->
                <div class="col-lg-5 d-flex flex-wrap gap-2 justify-content-lg-end">
                    <div class="stat-pill" title="Vidas restantes">
                        <i class="bi bi-heart-fill text-danger"></i>
                        <span id="heartsCount">5</span> Vidas
                    </div>
                    <div class="stat-pill" title="Experiencia ganada">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span id="xpCount">0</span> XP
                    </div>
                    <div class="stat-pill" title="Racha de aciertos seguidos">
                        <i class="bi bi-fire" style="color: var(--orange-bright);"></i>
                        Racha: <span id="streakCount">0</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         CONTENIDO PRINCIPAL
         ========================================== -->
    <main class="container-xl py-4 py-md-5 flex-grow-1" id="practice">

        <!-- BARRA DE PROGRESO -->
        <section class="progress-container-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-extrabold small text-uppercase" style="color: var(--purple-main);">
                    <i class="bi bi-trophy-fill text-warning me-1"></i> Progreso de la Lección
                </span>
                <span class="progress-copy fw-extrabold small" style="color: var(--purple-dark);">
                    0 de 6 ejercicios completados
                </span>
            </div>
            <div class="progress-track progress" role="progressbar" aria-label="Progreso de los ejercicios" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar-fill progress-bar" id="mainProgressBar"></div>
            </div>
        </section>

        <div class="row g-4">
            <!-- COLUMNA IZQUIERDA: TARJETA DE EJERCICIOS (8 cols) -->
            <div class="col-lg-8">
                <section class="lesson-card-3d" id="exercises">

                    <!-- Cabecera de la tarjeta -->
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--border-purple) !important;">
                        <div class="exercise-badge">
                            <span id="typeIcon">✍️</span>
                            <span id="typeName">Completa la frase</span>
                        </div>

                        <span class="fw-extrabold small px-3 py-1 rounded-pill" id="counter" style="background-color: var(--purple-tint); color: var(--purple-dark);">
                            Ejercicio 1 de 6
                        </span>
                    </div>

                    <!-- ==========================================
                         EJERCICIO 1: COMPLETAR LA FRASE
                         ========================================== -->
                    <article class="exercise active" data-type="Completa la frase" data-icon="✍️" data-tip="Después de 'I', 'You', 'We' y 'They' usamos el verbo en su forma base sin agregar '-s'.">
                        <h2 class="h4 fw-extrabold mb-1" style="color: var(--purple-dark);">Escribe la palabra que falta</h2>
                        <p class="text-muted small mb-4">Completa la rutina matutina en inglés. Puedes escuchar la frase completa con el botón de audio.</p>

                        <div class="sentence-box d-flex align-items-center justify-content-between gap-3 mb-4">
                            <div>
                                I <input id="fillInput" class="input-answer-3d" type="text" placeholder="verbo..." autocomplete="off"> coffee every morning before work.
                            </div>
                            <button type="button" class="btn-audio-sentence" onclick="speakSentence('I drink coffee every morning before work.')" title="Escuchar oración">
                                <i class="bi bi-volume-up-fill"></i>
                            </button>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button class="btn btn-3d-orange" id="checkFill" type="button">
                                <i class="bi bi-check-circle-fill me-1"></i> Comprobar respuesta
                            </button>
                            <button class="btn btn-3d-ghost" type="button" onclick="toggleHintBox('hint1')">
                                <i class="bi bi-lightbulb-fill text-warning me-1"></i> Pista
                            </button>
                            <button class="btn btn-3d-ghost ms-auto" data-next type="button">
                                Omitir <i class="bi bi-skip-forward-fill"></i>
                            </button>
                        </div>

                        <div id="hint1" class="small p-3 rounded-4 d-none mb-3" style="background-color: var(--orange-light); color: var(--purple-dark); border: 1.5px solid var(--orange-bright);">
                            <strong>Pista:</strong> El verbo en inglés para "beber/tomar" empieza con <strong>dr...</strong> (5 letras).
                        </div>

                        <div class="feedback-banner d-none" id="fillFeedback" role="status"></div>
                    </article>

                    <!-- ==========================================
                         EJERCICIO 2: ORDENA LAS PALABRAS
                         ========================================== -->
                    <article class="exercise" data-type="Ordena las palabras" data-icon="🧩" data-tip="Con 'She' o 'He' en presente simple, el auxiliar correcto para hacer yoga es 'does'." hidden>
                        <h2 class="h4 fw-extrabold mb-1" style="color: var(--purple-dark);">Construye la oración correcta</h2>
                        <p class="text-muted small mb-4">Toca las palabras del banco inferior en orden. Si te equivocas, pulsa sobre el espacio para devolverla.</p>

                        <div class="sentence-box d-flex align-items-center justify-content-between gap-3 mb-4">
                            <div>
                                She
                                <button class="blank-slot" data-slot="0" type="button">_____</button>
                                yoga
                                <button class="blank-slot" data-slot="1" type="button">_____</button>
                                <button class="blank-slot" data-slot="2" type="button">_____</button>.
                            </div>
                            <button type="button" class="btn-audio-sentence" onclick="speakSentence('She does yoga every morning.')" title="Escuchar oración objetivo">
                                <i class="bi bi-volume-up-fill"></i>
                            </button>
                        </div>

                        <div class="mb-4">
                            <div class="small fw-extrabold text-uppercase mb-2" style="color: var(--purple-main);">Banco de palabras:</div>
                            <div class="d-flex flex-wrap gap-2" id="wordBank">
                                <button class="word-chip" data-word="morning" type="button">morning</button>
                                <button class="word-chip" data-word="does" type="button">does</button>
                                <button class="word-chip" data-word="play" type="button">play</button>
                                <button class="word-chip" data-word="every" type="button">every</button>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button class="btn btn-3d-orange" id="checkDrag" type="button" disabled>
                                <i class="bi bi-check-circle-fill me-1"></i> Comprobar oración
                            </button>
                            <button class="btn btn-3d-ghost" id="resetDrag" type="button">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reiniciar
                            </button>
                        </div>

                        <div class="feedback-banner d-none" id="dragFeedback" role="status"></div>
                    </article>

                    <!-- ==========================================
                         EJERCICIO 3: OPCIÓN MÚLTIPLE
                         ========================================== -->
                    <article class="exercise" data-type="Opción múltiple" data-icon="🎯" data-tip="Para el pronombre plural 'They' (Ellos), el verbo 'go' no lleva '-es'." hidden>
                        <h2 class="h4 fw-extrabold mb-1" style="color: var(--purple-dark);">Selecciona la opción correcta</h2>
                        <p class="text-muted small mb-4">Elige la forma verbal adecuada para completar la rutina de transporte.</p>

                        <div class="sentence-box d-flex align-items-center justify-content-between gap-3 mb-4">
                            <div>They <span class="px-3 py-1 rounded-3" style="background-color: var(--purple-soft); color: var(--purple-main);">_____</span> to the office by bus.</div>
                            <button type="button" class="btn-audio-sentence" onclick="speakSentence('They go to the office by bus.')" title="Escuchar frase">
                                <i class="bi bi-volume-up-fill"></i>
                            </button>
                        </div>

                        <div class="row g-2 mb-4" id="choiceOptions">
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-answer="walk" type="button">
                                    <span class="option-letter">A</span> walk
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-answer="go" type="button">
                                    <span class="option-letter">B</span> go
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-answer="goes" type="button">
                                    <span class="option-letter">C</span> goes
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-answer="going" type="button">
                                    <span class="option-letter">D</span> going
                                </button>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button class="btn btn-3d-orange" id="checkChoice" type="button">
                                <i class="bi bi-check-circle-fill me-1"></i> Comprobar respuesta
                            </button>
                            <button class="btn btn-3d-ghost ms-auto" data-next type="button">
                                Omitir <i class="bi bi-skip-forward-fill"></i>
                            </button>
                        </div>

                        <div class="feedback-banner d-none" id="choiceFeedback" role="status"></div>
                    </article>

                    <!-- ==========================================
                         EJERCICIO 4: EMPAREJA LOS PARES
                         ========================================== -->
                    <article class="exercise" data-type="Empareja los pares" data-icon="🔗" data-tip="Los adverbios de frecuencia como 'usually' van antes del verbo principal." hidden>
                        <h2 class="h4 fw-extrabold mb-1" style="color: var(--purple-dark);">Conecta cada palabra con su significado</h2>
                        <p class="text-muted small mb-4">Haz clic en un término en inglés y luego en su traducción equivalente en español.</p>

                        <div class="row g-3 mb-4" id="matchGrid">
                            <div class="col-6">
                                <button class="match-btn-3d" data-side="en" data-pair="early" type="button">
                                    <span>early</span>
                                    <span class="badge" style="background-color: var(--purple-soft); color: var(--purple-main);">EN</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="match-btn-3d" data-side="es" data-pair="usually" type="button">
                                    <span>normalmente</span>
                                    <span class="badge" style="background-color: var(--orange-light); color: var(--orange-bright);">ES</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="match-btn-3d" data-side="en" data-pair="usually" type="button">
                                    <span>usually</span>
                                    <span class="badge" style="background-color: var(--purple-soft); color: var(--purple-main);">EN</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="match-btn-3d" data-side="es" data-pair="early" type="button">
                                    <span>temprano</span>
                                    <span class="badge" style="background-color: var(--orange-light); color: var(--orange-bright);">ES</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="match-btn-3d" data-side="en" data-pair="deadline" type="button">
                                    <span>deadline</span>
                                    <span class="badge" style="background-color: var(--purple-soft); color: var(--purple-main);">EN</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="match-btn-3d" data-side="es" data-pair="deadline" type="button">
                                    <span>fecha límite</span>
                                    <span class="badge" style="background-color: var(--orange-light); color: var(--orange-bright);">ES</span>
                                </button>
                            </div>
                        </div>

                        <div class="feedback-banner d-none mb-3" id="matchFeedback" role="status"></div>
                    </article>

                    <!-- ==========================================
                         EJERCICIO 5: COMPRENSIÓN AUDITIVA (LISTENING)
                         ========================================== -->
                    <article class="exercise" data-type="Comprensión auditiva" data-icon="🎧" data-tip="Presta atención a la palabra clave 'meeting' (reunión) y la hora 'nine o'clock'." hidden>
                        <h2 class="h4 fw-extrabold mb-1" style="color: var(--purple-dark);">Escucha y elige la traducción</h2>
                        <p class="text-muted small mb-4">Pulsa el botón grande para escuchar la frase en inglés y selecciona qué significa.</p>

                        <div class="p-4 rounded-4 text-center mb-4" style="background-color: var(--purple-light); border: 2px solid var(--border-purple);">
                            <button type="button" class="btn btn-3d-purple px-4 py-3 fs-5 mb-2" onclick="speakSentence('We have a team meeting at nine o clock.')">
                                <i class="bi bi-volume-up-fill me-2"></i> Escuchar Audio
                            </button>
                            <div class="small text-muted fw-bold">Velocidad normal · Pronunciación EE.UU.</div>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-4" id="listenOptions">
                            <button class="option-card-3d" data-correct="false" type="button">
                                <span class="option-letter">A</span> Terminamos el reporte a las cinco de la tarde.
                            </button>
                            <button class="option-card-3d" data-correct="true" type="button">
                                <span class="option-letter">B</span> Tenemos una reunión de equipo a las nueve en punto.
                            </button>
                            <button class="option-card-3d" data-correct="false" type="button">
                                <span class="option-letter">C</span> Ellos envían correos todos los lunes.
                            </button>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button class="btn btn-3d-orange" id="checkListen" type="button">
                                <i class="bi bi-check-circle-fill me-1"></i> Comprobar audio
                            </button>
                        </div>

                        <div class="feedback-banner d-none" id="listenFeedback" role="status"></div>
                    </article>

                    <!-- ==========================================
                         EJERCICIO 6: VOCABULARIO PROFESIONAL EN CONTEXTO
                         ========================================== -->
                    <article class="exercise" data-type="Inglés Profesional" data-icon="💼" data-tip="'Schedule' significa agenda, horario o programar una actividad." hidden>
                        <h2 class="h4 fw-extrabold mb-1" style="color: var(--purple-dark);">Completa la expresión laboral</h2>
                        <p class="text-muted small mb-4">¿Qué palabra completa mejor esta frase común en una oficina o proyecto?</p>

                        <div class="sentence-box d-flex align-items-center justify-content-between gap-3 mb-4">
                            <div>"Let's check the project <span class="px-3 py-1 rounded-3" style="background-color: var(--orange-light); color: var(--orange-bright);">_____</span> for this week."</div>
                            <button type="button" class="btn-audio-sentence" onclick="speakSentence('Lets check the project schedule for this week.')" title="Escuchar frase">
                                <i class="bi bi-volume-up-fill"></i>
                            </button>
                        </div>

                        <div class="row g-2 mb-4" id="proOptions">
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-correct="true" type="button">
                                    <span class="option-letter">A</span> schedule (cronograma)
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-correct="false" type="button">
                                    <span class="option-letter">B</span> breakfast (desayuno)
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-correct="false" type="button">
                                    <span class="option-letter">C</span> window (ventana)
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="option-card-3d" data-correct="false" type="button">
                                    <span class="option-letter">D</span> pillow (almohada)
                                </button>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button class="btn btn-3d-orange" id="checkPro" type="button">
                                <i class="bi bi-trophy-fill me-1"></i> Finalizar lección
                            </button>
                        </div>

                        <div class="feedback-banner d-none" id="proFeedback" role="status"></div>
                    </article>

                    <!-- ==========================================
                         PANTALLA FINAL DE LECCIÓN COMPLETADA
                         ========================================== -->
                    <div id="lessonSummary" class="text-center py-4 d-none">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--orange-bright), #FF9642); color: var(--white); font-size: 2.8rem; box-shadow: 0 8px 20px rgba(255,107,53,0.35);">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <h2 class="display-6 fw-extrabold mb-2" style="color: var(--purple-dark);">¡Lección Completada!</h2>
                        <p class="text-muted mb-4">Has terminado la Lección 2 de la Unidad 03. Tu progreso se ha guardado automáticamente.</p>

                        <div class="row g-3 justify-content-center mb-4" style="max-width: 480px; margin: 0 auto;">
                            <div class="col-4">
                                <div class="p-3 rounded-4" style="background-color: var(--purple-soft);">
                                    <div class="fw-extrabold fs-4" style="color: var(--purple-main);" id="finalXp">0</div>
                                    <div class="small fw-bold text-muted">XP Ganados</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-4" style="background-color: var(--orange-light);">
                                    <div class="fw-extrabold fs-4" style="color: var(--orange-bright);" id="finalScore">6/6</div>
                                    <div class="small fw-bold text-muted">Aciertos</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-4" style="background-color: #DCFCE7;">
                                    <div class="fw-extrabold fs-4 text-success" id="finalHearts">5</div>
                                    <div class="small fw-bold text-muted">Vidas</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <button type="button" class="btn btn-3d-ghost" onclick="location.reload()">
                                <i class="bi bi-arrow-repeat me-1"></i> Practicar de nuevo
                            </button>
                            <a href="{{ url('/glosario') }}" class="btn btn-3d-orange">
                                Ir al Glosario Técnico <i class="bi bi-arrow-right-circle-fill"></i>
                            </a>
                        </div>
                    </div>

                    <!-- ==========================================
                         MODO LECCIÓN (?carrera=…&leccion=…)
                         Lo dibuja public/js/ejercicios-leccion.js
                         ========================================== -->
                    <div id="practicaLeccion" class="d-none" aria-live="polite"></div>

                    <!-- PIE DE NAVEGACIÓN DE LA TARJETA -->
                    <div id="lessonFooterNav" class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4 pt-3 border-top" style="border-color: var(--border-purple) !important;">
                        <span class="text-muted small fw-bold">
                            <i class="bi bi-shield-check text-success me-1"></i> Progreso guardado localmente
                        </span>

                        <button class="btn btn-3d-purple py-2 px-4" id="nextExercise" type="button">
                            Siguiente ejercicio <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>

                </section>
            </div>

            <!-- COLUMNA DERECHA: PANEL DE AYUDA Y GLOSARIO RÁPIDO (4 cols) -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">

                    <!-- Tarjeta de Consejo Dinámico -->
                    <aside class="side-helper-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: var(--orange-light); color: var(--orange-bright); font-size: 1.2rem;">
                                <i class="bi bi-lightbulb-fill"></i>
                            </div>
                            <h3 class="h6 fw-extrabold mb-0" style="color: var(--purple-dark);">Consejo Gramatical</h3>
                        </div>
                        <p id="tip" class="small text-muted mb-0" style="line-height: 1.6;">
                            Después de "I", "You", "We" y "They" usamos el verbo en su forma base sin agregar "-s".
                        </p>
                    </aside>

                    <!-- Mini Glosario de la Unidad -->
                    <aside class="side-helper-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="h6 fw-extrabold mb-0" style="color: var(--purple-dark);">
                                <i class="bi bi-book-half me-1" style="color: var(--purple-medium);"></i> Palabras Clave
                            </h3>
                            <span class="badge rounded-pill" id="badgePalabras" style="background-color: var(--purple-soft); color: var(--purple-main);">Unidad 03</span>
                        </div>

                        <div class="d-flex flex-column gap-2 small" id="palabrasClave">
                            <div class="p-2 rounded-3 d-flex justify-content-between align-items-center" style="background-color: var(--purple-light);">
                                <div>
                                    <strong style="color: var(--purple-dark);">Deadline</strong>
                                    <div class="text-muted" style="font-size: 0.78rem;">Fecha límite de entrega</div>
                                </div>
                                <button type="button" class="btn btn-sm text-warning" onclick="speakSentence('Deadline')"><i class="bi bi-volume-up-fill fs-5"></i></button>
                            </div>

                            <div class="p-2 rounded-3 d-flex justify-content-between align-items-center" style="background-color: var(--purple-light);">
                                <div>
                                    <strong style="color: var(--purple-dark);">Schedule</strong>
                                    <div class="text-muted" style="font-size: 0.78rem;">Horario / Cronograma</div>
                                </div>
                                <button type="button" class="btn btn-sm text-warning" onclick="speakSentence('Schedule')"><i class="bi bi-volume-up-fill fs-5"></i></button>
                            </div>

                            <div class="p-2 rounded-3 d-flex justify-content-between align-items-center" style="background-color: var(--purple-light);">
                                <div>
                                    <strong style="color: var(--purple-dark);">Team meeting</strong>
                                    <div class="text-muted" style="font-size: 0.78rem;">Reunión de equipo</div>
                                </div>
                                <button type="button" class="btn btn-sm text-warning" onclick="speakSentence('Team meeting')"><i class="bi bi-volume-up-fill fs-5"></i></button>
                            </div>
                        </div>
                    </aside>

                </div>
            </div>
        </div>

    </main>

    <!-- ==========================================
         FOOTER CORPORATIVO SHIFTLY
         ========================================== -->
    <footer class="footer-shiftly py-4 mt-auto">
        <div class="container-xl d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="28" height="28">
                <span class="fw-bold">Shiftly</span>
                <span class="text-white-50 small ms-2">&copy; 2026 Todos los derechos reservados.</span>
            </div>
            <div class="d-flex gap-4 small">
                <a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Inicio</a>
                <a href="{{ url('/glosario') }}" class="text-white-50 text-decoration-none">Glosario</a>
                <a href="{{ url('/dashboard') }}" class="text-white-50 text-decoration-none">Dashboard</a>
            </div>
        </div>
    </footer>

    <!-- ==========================================
         MODAL: CONFIGURAR API KEY DE CHATGPT / OPENAI
         ========================================== -->
    <div class="modal fade" id="modalApiKey" tabindex="-1" aria-labelledby="tituloModalApiKey" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-ia">
                <div class="modal-header">
                    <h2 class="modal-title fs-5 fw-extrabold" id="tituloModalApiKey">
                        <i class="bi bi-stars me-1"></i> Configurar API Key de ChatGPT / OpenAI
                    </h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted">
                        Con una API Key de OpenAI puedes generar retos inéditos con <strong>ChatGPT (gpt-4o-mini)</strong>.
                        Sin clave, Shiftly usa los ejercicios locales de cada lección.
                    </p>
                    <div id="estadoApiKey" class="small fw-bold mb-3"></div>

                    <label for="inputApiKey" class="form-label fw-bold small">API Key de OpenAI</label>
                    <div class="input-group mb-1">
                        <input type="password" class="form-control input-api-key" id="inputApiKey" placeholder="sk-..." autocomplete="off" spellcheck="false">
                        <button class="btn btn-3d-ghost" type="button" id="btnVerApiKey" title="Mostrar u ocultar clave"><i class="bi bi-eye"></i></button>
                        <div class="invalid-feedback">La clave parece incompleta. Cópiala completa desde la plataforma de OpenAI.</div>
                    </div>
                    <a href="https://platform.openai.com/api-keys" target="_blank" rel="noopener noreferrer" class="small fw-bold" style="color: var(--orange-bright);">
                        Obtener una API Key en OpenAI Platform <i class="bi bi-box-arrow-up-right"></i>
                    </a>

                    <div class="alert alert-warning rounded-4 small mt-3 mb-0">
                        <i class="bi bi-shield-lock-fill me-1"></i>
                        La clave se guarda solo en este navegador (<code>localStorage</code>) y nunca en el código.
                        No la uses en computadoras compartidas.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-3d-ghost text-danger d-none me-auto" id="btnBorrarApiKey">
                        <i class="bi bi-trash3 me-1"></i> Borrar clave
                    </button>
                    <button type="button" class="btn btn-3d-ghost" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-3d-orange" id="btnGuardarApiKey">
                        <i class="bi bi-save me-1"></i> Guardar clave
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Progreso del alumno desde la base de datos (antes de los demás scripts) -->
    @include('partials.progreso-servidor')

    <!-- Voz femenina para la pronunciación (OpenAI TTS o voz del navegador) -->
    <script src="{{ asset('js/voz-femenina.js') }}"></script>

    <!-- Bancos locales de ejercicios por carrera -->
    <script src="{{ asset('js/ejercicios-sistemas.js') }}"></script>
    <script src="{{ asset('js/ejercicios-medicina.js') }}"></script>
    <script src="{{ asset('js/ejercicios-gastronomia.js') }}"></script>
    <script src="{{ asset('js/ejercicios-derecho.js') }}"></script>
    <script src="{{ asset('js/ejercicios-arquitectura.js') }}"></script>
    <script src="{{ asset('js/ejercicios-general.js') }}"></script>

    <!-- Servicio de IA (ChatGPT / OpenAI) y modo lección -->
    <script src="{{ asset('js/openai-ejercicios.js') }}"></script>

    <!-- Progreso global: racha diaria, meta del día e insignias -->
    <script src="{{ asset('js/rutas-catalogo.js') }}"></script>
    <script src="{{ asset('js/shiftly-progreso.js') }}?v=2"></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>
    <script src="{{ asset('js/ejercicios-leccion.js') }}?v=2"></script>

    <!-- Script de Ejercicios Interactivos (práctica general sin parámetros) -->
    <script src="{{ asset('js/ejercicios.js') }}?v=3"></script>

    <!-- Práctica libre: el avance se guarda en la base de datos -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // En modo lección el progreso se guarda en la clave de la ruta (ejercicios-leccion.js)
            if (window.SHIFTLY_MODO_LECCION) return;

            const userId = @json(auth()->id());
            const textoProgreso = document.querySelector('.progress-copy');
            if (!textoProgreso) return;

            const guardarProgreso = () => {
                const coincidencia = textoProgreso.textContent.match(/(\d+) de (\d+) ejercicios completados/);
                if (!coincidencia) return;

                const completados = Number(coincidencia[1]);
                const total = Number(coincidencia[2]);
                const progreso = {
                    usuario: userId ?? 'visitante',
                    tipo: 'ejercicios',
                    unidad: 'unidad-03-leccion-02',
                    completados,
                    total,
                    puntuacion: completados,
                    porcentaje: total ? Math.round((completados / total) * 100) : 0,
                    completado: total > 0 && completados === total,
                    actualizadoEn: new Date().toISOString()
                };

                // Guardar en la base de datos (PUT /progreso/practica)
                if (window.ShiftlyServidor) {
                    window.ShiftlyServidor.guardarPractica({
                        unidad: progreso.unidad,
                        completados: progreso.completados,
                        total: progreso.total
                    });
                }
            };

            new MutationObserver(guardarProgreso).observe(textoProgreso, {
                childList: true,
                characterData: true,
                subtree: true
            });
        });
    </script>
</body>

</html>