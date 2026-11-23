<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Panel del Usuario</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos de Shiftly -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- App instalable (PWA) -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#1A0B2E">
</head>

<body data-url-base="{{ url('/') }}">

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
                    <a class="nav-link active" href="{{ url('/dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Panel del Usuario</a>
                    <a class="nav-link" href="{{ url('/ruta-aprendizaje') }}"><i class="bi bi-journal-bookmark me-1"></i> Ruta de Aprendizaje</a>
                    <a class="nav-link" href="{{ url('/resultados-leccion') }}"><i class="bi bi-bar-chart-line me-1"></i> Resultados</a>
                              <a class="nav-link" href="{{ url('/glosario') }}"><i class="bi bi-book me-1"></i> Glosario</a>
          <a class="nav-link" href="{{ url('/perfil') }}"><i class="bi bi-person me-1"></i> Mi perfil</a>

          <form action="{{ route('logout') }}" method="POST" class="ms-lg-3">
              @csrf
              <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
          </form>
      </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1">
        <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold mb-2" style="background-color: var(--orange-bright); color: var(--white);">Mi Cuenta</span>
            <h1 class="display-5 fw-extrabold mb-2" style="color: var(--purple-dark);">Panel del Usuario</h1>
            <p class="text-muted mx-auto" style="max-width: 550px;">Gestiona tu información personal y accede a las herramientas de aprendizaje técnico de Shiftly.</p>
        </div>

        <!-- ==========================================
             PROGRESO GLOBAL (dashboard-progreso.js)
             ========================================== -->
        <div class="row g-4 mb-4">

            <!-- Resumen global + racha -->
            <div class="col-lg-8">
                <section class="card card-custom p-4 h-100">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h2 class="card-title-custom d-flex align-items-center gap-2 fs-5 mb-0">
                            <i class="bi bi-graph-up-arrow" style="color: var(--orange-bright);"></i>
                            Mi Progreso Global
                        </h2>
                        <span class="badge rounded-pill px-3 py-2 nivel-global" id="nivelGlobal">Explorador</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="stat-tile">
                                <i class="bi bi-lightning-charge-fill" style="color: var(--orange-bright);"></i>
                                <div class="stat-tile-valor" id="xpGlobal">0</div>
                                <div class="stat-tile-etiqueta">XP total</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-tile">
                                <i class="bi bi-check2-circle text-success"></i>
                                <div class="stat-tile-valor" id="leccionesGlobal">0/0</div>
                                <div class="stat-tile-etiqueta">Lecciones</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-tile">
                                <i class="bi bi-fire" style="color: #F97316;"></i>
                                <div class="stat-tile-valor" id="rachaActual">0</div>
                                <div class="stat-tile-etiqueta">Días de racha</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-tile">
                                <i class="bi bi-bullseye" style="color: var(--purple-medium);"></i>
                                <div class="stat-tile-valor" id="precisionGlobal">—</div>
                                <div class="stat-tile-etiqueta">Precisión</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between small fw-bold mb-1">
                        <span class="text-muted">Avance en las 6 rutas</span>
                        <span style="color: var(--purple-main);" id="porcentajeGlobal">0%</span>
                    </div>
                    <div class="progress progress-global mb-4" role="progressbar" aria-label="Avance global">
                        <div class="progress-bar" id="barraGlobal" style="width: 0%;"></div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                        <span class="small fw-bold text-muted"><i class="bi bi-calendar-week me-1"></i> Últimos 7 días</span>
                        <span class="small text-muted">Mejor racha: <strong id="mejorRacha">0</strong> días</span>
                    </div>
                    <div class="semana-racha" id="semanaRacha"></div>
                </section>
            </div>

            <!-- Meta del día -->
            <div class="col-lg-4">
                <section class="card card-custom p-4 h-100">
                    <h2 class="card-title-custom d-flex align-items-center gap-2 fs-5">
                        <i class="bi bi-flag-fill" style="color: var(--orange-bright);"></i>
                        Meta del Día
                    </h2>
                    <div class="text-center my-2">
                        <div class="meta-anillo" id="metaAnillo" style="--avance: 0;">
                            <div class="meta-anillo-centro">
                                <div class="fw-extrabold fs-3" style="color: var(--purple-dark);" id="metaXpHoy">0</div>
                                <div class="small text-muted">de <span id="metaObjetivo">30</span> XP</div>
                            </div>
                        </div>
                        <p class="small fw-semibold mt-3 mb-3" id="metaMensaje">Completa una lección para empezar tu racha.</p>
                    </div>
                    <label for="selectMeta" class="form-label small fw-bold mb-1">Mi meta diaria</label>
                    <select class="form-select form-control-custom mb-3" id="selectMeta">
                        <option value="10">Casual · 10 XP</option>
                        <option value="20">Regular · 20 XP</option>
                        <option value="30">Constante · 30 XP</option>
                        <option value="50">Intenso · 50 XP</option>
                        <option value="100">Imparable · 100 XP</option>
                    </select>
                    <a href="{{ url('/ruta-aprendizaje') }}" class="btn-nav-item destacado mt-auto" id="btnContinuarRuta">
                        <span><i class="bi bi-play-fill me-2"></i><span id="textoContinuarRuta">Continuar aprendiendo</span></span>
                        <i class="bi bi-arrow-right-short fs-5"></i>
                    </a>
                </section>
            </div>

            <!-- Diagnóstico conectado -->
            <div class="col-lg-5">
                <section class="card card-custom p-4 h-100" id="tarjetaDiagnostico">
                    <h2 class="card-title-custom d-flex align-items-center gap-2 fs-5">
                        <i class="bi bi-clipboard2-pulse-fill" style="color: var(--purple-medium);"></i>
                        Mi Diagnóstico
                    </h2>
                    <div id="contenidoDiagnostico"></div>
                </section>
            </div>

            <!-- Insignias -->
            <div class="col-lg-7">
                <section class="card card-custom p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="card-title-custom d-flex align-items-center gap-2 fs-5 mb-0">
                            <i class="bi bi-award-fill" style="color: var(--orange-bright);"></i>
                            Insignias
                        </h2>
                        <span class="badge rounded-pill text-bg-light border" id="contadorInsignias">0/0</span>
                    </div>
                    <div class="insignias-grid" id="gridInsignias"></div>
                </section>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- SECCIÓN: NAVEGACIÓN RÁPIDA -->
            <div class="col-lg-4">
                <div class="card card-custom p-4 h-100">
                    <h5 class="card-title-custom d-flex align-items-center gap-2 fs-5">
                        <i class="bi bi-compass-fill" style="color: var(--orange-bright);"></i>
                        Navegación Rápida
                    </h5>
                    
                    <div class="d-flex flex-column gap-3 mt-2">
                        <a href="{{ url('/') }}" class="btn-nav-item">
                            <span><i class="bi bi-house-door me-2"></i>Inicio</span>
                            <i class="bi bi-chevron-right small"></i>
                        </a>
                        
                        <a href="{{ url('/ruta-aprendizaje') }}" class="btn-nav-item destacado">
                            <span><i class="bi bi-journal-check me-2"></i>Ir a Ruta de Aprendizaje</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                        
                        <a href="{{ url('/resultado') }}" class="btn-nav-item">
                            <span><i class="bi bi-trophy me-2"></i>Ver Últimos Resultados</span>
                            <i class="bi bi-chevron-right small"></i>
                        </a>
                        
                        <a href="{{ url('/glosario') }}" class="btn-nav-item">
                            <span><i class="bi bi-book me-2"></i>Consultar Glosario</span>
                            <i class="bi bi-chevron-right small"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: MI CUENTA (datos reales del usuario) -->
            <div class="col-lg-8">
                <div class="card card-custom p-4 h-100">
                    @include('partials.alertas')

                    <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-4" style="background-color: var(--purple-soft);">
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 55px; height: 55px; color: var(--purple-main); font-size: 1.5rem;">
                            <i class="bi bi-person-fill" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h2 class="h6 mb-1 fw-bold" style="color: var(--purple-dark);">
                                {{ trim(auth()->user()->name . ' ' . auth()->user()->apellido) }}
                            </h2>
                            <p class="mb-0 small text-muted">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <h5 class="card-title-custom d-flex align-items-center gap-2 fs-5">
                        <i class="bi bi-person-gear" style="color: var(--purple-medium);"></i>
                        Mi cuenta
                    </h5>

                    <dl class="row small mb-4">
                        <dt class="col-sm-4 text-muted">Carrera</dt>
                        <dd class="col-sm-8">{{ auth()->user()->carrera ?? 'Sin registrar' }}</dd>
                        <dt class="col-sm-4 text-muted">Área profesional</dt>
                        <dd class="col-sm-8">{{ auth()->user()->area_profesional ?? 'Sin registrar' }}</dd>
                    </dl>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('perfil') }}" class="btn btn-guardar">
                            <i class="bi bi-pencil-square me-2" aria-hidden="true"></i>Editar mi perfil
                        </a>
                        <a href="{{ route('gestion.lecciones.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                            Panel de contenido
                        </a>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: RUTAS POR CARRERA -->
            <div class="col-12">
                <div class="card card-custom p-4">
                    <h5 class="card-title-custom d-flex align-items-center gap-2 fs-5">
                        <i class="bi bi-signpost-split-fill" style="color: var(--orange-bright);"></i>
                        Mi Árbol de Lecciones por Carrera
                    </h5>
                    <p class="text-muted small mb-3">Elige tu área profesional para continuar con tu ruta gamificada.</p>

                    <!-- dashboard-progreso.js reemplaza estos enlaces por tarjetas con el avance de cada ruta -->
                    <div class="row g-3" id="progresoCarreras">
                        <div class="col-sm-6 col-lg-4">
                            <a href="{{ url('/ruta-sistemas') }}" class="btn-nav-item">
                                <span><i class="bi bi-cpu me-2"></i>Sistemas y TI</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <a href="{{ url('/ruta-medicina') }}" class="btn-nav-item">
                                <span><i class="bi bi-heart-pulse me-2"></i>Medicina</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <a href="{{ url('/ruta-gastronomia') }}" class="btn-nav-item">
                                <span><i class="bi bi-cup-hot me-2"></i>Gastronomía</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <a href="{{ url('/ruta-derecho') }}" class="btn-nav-item">
                                <span><i class="bi bi-bank me-2"></i>Derecho</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <a href="{{ url('/ruta-arquitectura') }}" class="btn-nav-item">
                                <span><i class="bi bi-building me-2"></i>Arquitectura</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <a href="{{ url('/ruta-general') }}" class="btn-nav-item">
                                <span><i class="bi bi-globe-americas me-2"></i>General</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer-custom py-4 mt-auto border-top border-purple">
        <div class="container text-center">
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Progreso del alumno desde la base de datos (antes de los demás scripts) -->
    @include('partials.progreso-servidor')

    <!-- Progreso global, racha, meta diaria, insignias y diagnóstico -->
    <script src="{{ asset('js/rutas-catalogo.js') }}"></script>
    <script src="{{ asset('js/shiftly-progreso.js') }}?v=2"></script>
    <script src="{{ asset('js/dashboard-progreso.js') }}"></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>
</body>

</html>