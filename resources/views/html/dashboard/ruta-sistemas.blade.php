<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Ruta de Sistemas y TI</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos exclusivos de la ruta -->
    <link rel="stylesheet" href="{{ asset('css/ruta-sistemas.css') }}">

    <!-- App instalable (PWA) -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#180C2E">
</head>

<body data-url-ejercicios="{{ url('/ejercicios') }}">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-ruta py-3 sticky-top">
        <div class="container-xl">
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="38" height="38">
                <span>Shiftly</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="navbar-nav ms-auto gap-1">
                    <a class="nav-link" href="{{ url('/dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Panel del Usuario</a>
                    <a class="nav-link active" href="{{ url('/ruta-aprendizaje') }}"><i class="bi bi-journal-bookmark me-1"></i> Ruta de Aprendizaje</a>
                    <a class="nav-link" href="{{ url('/resultados-leccion') }}"><i class="bi bi-bar-chart-line me-1"></i> Resultados</a>
                    <a class="nav-link" href="{{ url('/glosario') }}"><i class="bi bi-book me-1"></i> Glosario</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO DE LA RUTA -->
    <header class="hero-ruta">
        <div class="container-xl">
            <a href="{{ url('/ruta-aprendizaje') }}" class="hero-chip text-decoration-none mb-3">
                <i class="bi bi-arrow-left"></i> Todas las rutas
            </a>

            <div class="row g-4 align-items-end">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="hero-icono"><i class="bi bi-cpu"></i></div>
                        <div>
                            <span class="hero-chip naranja mb-2"><i class="bi bi-diagram-2"></i> Diagrama de flujo</span>
                            <h1 class="hero-titulo display-6 mb-0">Ruta de Sistemas <span>y TI</span></h1>
                        </div>
                    </div>
                    <p class="hero-sub mb-4">Recorre tu diagrama de flujo: cada bloque es una lección de inglés para equipos de software.</p>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-shiftly" data-accion="agregar-leccion">
                            <i class="bi bi-plus-circle me-1"></i> Agregar lección
                        </button>
                        <button type="button" class="btn btn-glass" data-accion="reiniciar-ruta">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reiniciar Progreso de Ruta
                        </button>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="d-flex justify-content-lg-end mb-2">
                        <span class="hero-chip"><i class="bi bi-person-circle"></i> Progreso de <strong id="nombreUsuarioRuta">Invitado</strong></span>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="stat-glass">
                                <div class="stat-valor" id="statXP">0 XP</div>
                                <div class="stat-etiqueta">Acumulado</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-glass">
                                <div class="stat-valor" id="statLecciones">0/0</div>
                                <div class="stat-etiqueta">Lecciones</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-glass">
                                <div class="stat-valor fs-6 pt-1" id="statNivel">Principiante</div>
                                <div class="stat-etiqueta">Nivel</div>
                            </div>
                        </div>
                    </div>
                    <div class="progress progress-ruta" role="progressbar" aria-label="Progreso de la ruta" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" id="barraProgresoRuta" style="width: 0%;" aria-valuenow="0"></div>
                    </div>
                    <p class="small fw-semibold mt-2 mb-0" style="color: var(--sh-subtitulo);" id="textoPorcentajeRuta">0%</p>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENIDO -->
    <main class="container-xl contenido-ruta mb-5 flex-grow-1">
        <div class="row g-4">

            <!-- ÁRBOL DE LECCIONES -->
            <div class="col-lg-8">
                <section class="tarjeta-3d p-3 p-md-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h2 class="tarjeta-titulo mb-0"><i class="bi bi-signpost-split me-2" style="color: var(--sh-naranja);"></i>Árbol de lecciones</h2>
                        <span class="forma-chip"><i class="bi bi-hand-index-thumb"></i> Toca un nodo para abrirlo</span>
                    </div>

                    <div class="arbol-wrapper">
                        <div class="arbol" id="arbolRuta" aria-live="polite"></div>

                        <div class="meta-final" id="metaFinal">
                            <div class="bandera"><i class="bi bi-flag-fill"></i></div>
                            <p class="fw-bold mb-0">Meta: dominar Sistemas y TI</p>
                            <p class="small texto-suave mb-0">Completa todos los nodos para conquistarla.</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- PANEL LATERAL -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">

                    <!-- Siguiente lección -->
                    <section class="tarjeta-3d p-4">
                        <h2 class="tarjeta-titulo mb-3"><i class="bi bi-lightning-charge-fill me-2" style="color: var(--sh-naranja);"></i>Siguiente lección</h2>
                        <div class="tarjeta-siguiente p-3 mb-3">
                            <p class="fw-bold mb-1" id="siguienteTitulo">Cargando...</p>
                            <p class="small texto-suave mb-0" id="siguienteDescripcion"></p>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-shiftly" id="btnAbrirSiguiente">
                                <i class="bi bi-play-fill me-1"></i> Abrir lección
                            </button>
                            <a href="{{ url('/ejercicios') }}" class="btn btn-claro" id="enlacePracticarSiguiente">
                                <i class="bi bi-pencil-square me-1"></i> Practicar ejercicios
                            </a>
                            <a href="{{ url('/ejercicios') }}" class="btn btn-claro d-none" id="enlaceRepasarErrores">
                                <i class="bi bi-arrow-repeat me-1"></i> Repasar mis errores (<span id="contadorErrores">0</span>)
                            </a>
                        </div>
                    </section>

                    <!-- Leyenda -->
                    <section class="tarjeta-3d p-4">
                        <h2 class="tarjeta-titulo mb-3"><i class="bi bi-palette me-2" style="color: var(--sh-ciruela);"></i>Estados de los nodos</h2>
                        <div class="d-flex flex-column gap-3">
                            <div class="leyenda-item"><span class="leyenda-muestra completado"></span> Completado</div>
                            <div class="leyenda-item"><span class="leyenda-muestra actual"></span> En curso / desbloqueado</div>
                            <div class="leyenda-item"><span class="leyenda-muestra bloqueado"></span> Bloqueado</div>
                        </div>
                    </section>

                    <!-- Lecciones personalizadas -->
                    <section class="tarjeta-3d p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="tarjeta-titulo mb-0"><i class="bi bi-stars me-2" style="color: var(--sh-naranja);"></i>Mis lecciones</h2>
                            <span class="badge rounded-pill text-bg-light border" id="contadorPersonalizadas">0</span>
                        </div>
                        <div class="d-flex flex-column gap-2 mb-3" id="listaPersonalizadas"></div>
                        <button type="button" class="btn btn-claro w-100" data-accion="agregar-leccion">
                            <i class="bi bi-plus-lg me-1"></i> Crear lección personalizada
                        </button>
                    </section>

                    <!-- Otras rutas -->
                    <section class="tarjeta-3d p-4">
                        <h2 class="tarjeta-titulo mb-3"><i class="bi bi-compass me-2" style="color: var(--sh-ciruela);"></i>Otras rutas</h2>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ url('/ruta-medicina') }}" class="enlace-ruta"><span><i class="bi bi-heart-pulse me-2"></i>Medicina</span><i class="bi bi-chevron-right small"></i></a>
                            <a href="{{ url('/ruta-gastronomia') }}" class="enlace-ruta"><span><i class="bi bi-cup-hot me-2"></i>Gastronomía</span><i class="bi bi-chevron-right small"></i></a>
                            <a href="{{ url('/ruta-derecho') }}" class="enlace-ruta"><span><i class="bi bi-bank me-2"></i>Derecho</span><i class="bi bi-chevron-right small"></i></a>
                            <a href="{{ url('/ruta-arquitectura') }}" class="enlace-ruta"><span><i class="bi bi-building me-2"></i>Arquitectura</span><i class="bi bi-chevron-right small"></i></a>
                            <a href="{{ url('/ruta-general') }}" class="enlace-ruta"><span><i class="bi bi-globe-americas me-2"></i>Inglés General</span><i class="bi bi-chevron-right small"></i></a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL: DETALLE DE LECCIÓN -->
    <div class="modal fade modal-ruta" id="modalLeccion" tabindex="-1" aria-labelledby="detalleTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header gap-3">
                    <div class="modal-icono"><i id="detalleIcono" class="bi bi-cpu"></i></div>
                    <div class="flex-grow-1">
                        <p class="small mb-0" style="color: var(--sh-subtitulo);">Ruta de Sistemas y TI</p>
                        <h3 class="modal-title fs-5 fw-bold" id="detalleTitulo">Lección</h3>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="forma-chip" id="detalleForma"></span>
                        <span class="badge-estado" id="detalleEstado"></span>
                        <span class="badge rounded-pill text-bg-warning fw-bold" id="detalleXP">+0 XP</span>
                    </div>
                    <p class="mb-3" id="detalleDescripcion"></p>
                    <h4 class="fs-6 fw-bold mb-2"><i class="bi bi-translate me-1" style="color: var(--sh-naranja);"></i>Términos clave</h4>
                    <div class="d-flex flex-column gap-2" id="detalleVocab"></div>
                    <div class="alert alert-success rounded-4 small fw-semibold mt-3 mb-0 d-none" id="detalleAviso">
                        <i class="bi bi-check-circle-fill me-1"></i> Ya completaste esta lección. Puedes repasarla en Ejercicios.
                    </div>
                </div>
                <div class="modal-footer flex-wrap gap-2">
                    <button type="button" class="btn btn-claro btn-icono d-none" id="btnEditarDesdeDetalle" title="Editar lección">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-claro btn-icono text-danger d-none" id="btnEliminarDesdeDetalle" title="Eliminar lección">
                        <i class="bi bi-trash3"></i>
                    </button>
                    <a href="{{ url('/ejercicios') }}" class="btn btn-claro ms-auto" id="enlaceEjerciciosDetalle">
                        <i class="bi bi-pencil me-1"></i> Practicar ejercicios
                    </a>
                    <button type="button" class="btn btn-verde" id="btnCompletarLeccion">
                        <i class="bi bi-check2-circle me-1"></i> Completar Lección (+XP)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: CREAR / EDITAR LECCIÓN PERSONALIZADA -->
    <div class="modal fade modal-ruta" id="modalFormLeccion" tabindex="-1" aria-labelledby="tituloModalForm" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" id="formLeccion" novalidate>
                <div class="modal-header gap-3">
                    <div class="modal-icono"><i id="vistaPreviaIcono" class="bi bi-cpu"></i></div>
                    <h3 class="modal-title fs-5 fw-bold flex-grow-1" id="tituloModalForm">Nueva lección personalizada</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="leccionId">

                    <div class="mb-3">
                        <label for="leccionTitulo" class="form-label">Título de la lección *</label>
                        <input type="text" class="form-control form-control-ruta" id="leccionTitulo" maxlength="60" placeholder="Ej. Code review con mi equipo" required>
                        <div class="invalid-feedback">Escribe un título de 3 a 60 caracteres.</div>
                    </div>

                    <div class="mb-3">
                        <label for="leccionDescripcion" class="form-label">Meta o descripción</label>
                        <textarea class="form-control form-control-ruta" id="leccionDescripcion" rows="2" maxlength="240" placeholder="¿Qué quieres lograr con esta lección?"></textarea>
                        <div class="invalid-feedback">Máximo 240 caracteres.</div>
                    </div>

                    <div class="mb-3">
                        <label for="leccionVocab" class="form-label">Términos clave <span class="fw-normal texto-suave">(uno por línea: inglés = español)</span></label>
                        <textarea class="form-control form-control-ruta" id="leccionVocab" rows="3" placeholder="code review = revisión de código"></textarea>
                        <div class="invalid-feedback">Usa el formato «término en inglés = traducción», una línea por término.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label for="leccionXP" class="form-label">XP</label>
                            <select class="form-select form-select-ruta" id="leccionXP">
                                <option value="10">10 XP</option>
                                <option value="20" selected>20 XP</option>
                                <option value="30">30 XP</option>
                                <option value="50">50 XP</option>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <label for="leccionForma" class="form-label">Forma del nodo</label>
                            <select class="form-select form-select-ruta" id="leccionForma"></select>
                        </div>
                        <div class="col-12">
                            <label for="leccionIcono" class="form-label">Ícono</label>
                            <select class="form-select form-select-ruta" id="leccionIcono"></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-claro" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-shiftly" id="btnGuardarLeccion">
                        <i class="bi bi-plus-circle me-1"></i> Agregar al árbol
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: CONFIRMACIÓN -->
    <div class="modal fade modal-ruta" id="modalConfirmar" tabindex="-1" aria-labelledby="confirmarTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5 fw-bold" id="confirmarTitulo">Confirmar</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-0" id="confirmarMensaje"></p>
                    <div class="form-check mt-3 d-none" id="confirmarOpcion">
                        <input class="form-check-input" type="checkbox" id="chkBorrarPersonalizadas">
                        <label class="form-check-label small fw-semibold" for="chkBorrarPersonalizadas">
                            También eliminar mis lecciones personalizadas
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-claro" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger rounded-4 fw-bold" id="btnConfirmarAccion">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast toast-ruta align-items-center" id="toastRuta" role="status" aria-live="polite" aria-atomic="true">
            <div class="d-flex align-items-center gap-2 p-3">
                <i id="toastIcono" class="bi bi-info-circle-fill fs-5"></i>
                <span class="fw-semibold small flex-grow-1" id="toastMensaje"></span>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-ruta py-4 mt-auto">
        <div class="container-xl text-center">
            <p class="mb-0 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Progreso del alumno desde la base de datos (antes de los demás scripts) -->
    @include('partials.progreso-servidor')

    <!-- Voz femenina para la pronunciación (OpenAI TTS o voz del navegador) -->
    <script src="{{ asset('js/voz-femenina.js') }}"></script>

    <!-- Catálogo de rutas y progreso global (racha, meta diaria, insignias) -->
    <script src="{{ asset('js/rutas-catalogo.js') }}"></script>
    <script src="{{ asset('js/shiftly-progreso.js') }}?v=2"></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>

    <!-- JS exclusivo de la ruta -->
    <script src="{{ asset('js/ruta-sistemas.js') }}?v=2"></script>
</body>

</html>
