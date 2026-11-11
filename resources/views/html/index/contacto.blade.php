<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiftly - Contacto</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos de Shiftly -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
</head>

<body>

    <!-- NAVBAR CORPORATIVO -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 sticky-top">
        <div class="container-xl">
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('img/logotipo interfaz.svg') }}" alt="Logo Shiftly" width="38" height="38">
                <span>Shiftly</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="navbar-nav ms-auto gap-2">
                    <a href="{{ url('/') }}" class="nav-link"><i class="bi bi-house-door me-1"></i> Inicio</a>
                    <a href="{{ url('/servicios') }}" class="nav-link"><i class="bi bi-card-checklist me-1"></i> Servicios</a>
                    <a href="{{ url('/ejercicios') }}" class="nav-link"><i class="bi bi-journal-bookmark me-1"></i> Aprender</a>
                    <a href="{{ url('/glosario') }}" class="nav-link"><i class="bi bi-book me-1"></i> Glosario</a>
                    <a href="{{ url('/contacto') }}" class="nav-link active"><i class="bi bi-envelope me-1"></i> Contacto</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="py-5 flex-grow-1 d-flex align-items-center">
        <div class="container-xl py-2">

            <!-- ENCABEZADO -->
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold mb-2" style="background-color: var(--orange-bright); color: var(--white);">
                    <i class="bi bi-headset me-1"></i> Estamos Para Ayudarte
                </span>
                <h1 class="display-5 fw-extrabold mb-3" style="color: var(--purple-dark);">Ponte en Contacto</h1>
                <p class="text-muted lead fs-5 mx-auto" style="max-width: 620px;">
                    ¿Tienes dudas o sugerencias? Escríbenos o visítanos directamente en nuestra ubicación institucional.
                </p>
            </div>

            <!-- GRILLA DE CONTENIDO PRINCIPAL -->
            <div class="row g-4 align-items-stretch">
                
                <!-- COLUMNA IZQUIERDA: INFORMACIÓN Y FORMULARIO -->
                <div class="col-lg-6 col-xl-5 d-flex flex-column gap-4">
                    
                    <!-- Tarjeta de Información de Contacto -->
                    <div class="card card-contacto p-4">
                        <h2 class="h5 fw-bold mb-4 d-flex align-items-center gap-2" style="color: var(--purple-dark);">
                            <i class="bi bi-info-circle-fill" style="color: var(--purple-medium);"></i> Información Directa
                        </h2>

                        <div class="d-flex flex-column gap-3">
                            <!-- Nombre -->
                            <div class="info-item-box d-flex align-items-center gap-3">
                                <div class="icon-wrapper-purple">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <span class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.725rem;">Contacto Principal</span>
                                    <strong class="fs-6" style="color: var(--purple-dark);">Luis David Balandrano Delgado</strong>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="info-item-box d-flex align-items-center gap-3">
                                <div class="icon-wrapper-purple">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div>
                                    <span class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.725rem;">Teléfono</span>
                                    <a href="tel:7714901816" class="text-decoration-none fw-bold fs-6" style="color: var(--orange-bright);">7714901816</a>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="info-item-box d-flex align-items-start gap-3">
                                <div class="icon-wrapper-purple mt-1">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <span class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.725rem;">Ubicación Institucional</span>
                                    <span class="fw-bold d-block" style="color: var(--purple-dark);">Universidad Politécnica de Pachuca</span>
                                    <small class="text-muted d-block mt-1" style="line-height: 1.35;">Carretera Pachuca-Sahagún Km 20, Ex-Hacienda de Santa Bárbara, Zempoala, Hidalgo.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Formulario de Contacto -->
                    <div class="card card-contacto p-4 flex-grow-1">
                        <h2 class="h5 fw-bold mb-3" style="color: var(--purple-dark);">
                            <i class="bi bi-send-fill me-1" style="color: var(--orange-bright);"></i> Envíanos un Mensaje
                        </h2>
                        
                        <div id="alert-container"></div>

                        <form id="form-contacto" action="{{ route('contacto.enviar') }}" method="POST" class="d-flex flex-column gap-3">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label fw-semibold small mb-1" style="color: var(--purple-dark);">Tu Nombre</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control form-control-custom" placeholder="Ej. Juan Pérez" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="correo" class="form-label fw-semibold small mb-1" style="color: var(--purple-dark);">Correo Electrónico</label>
                                    <input type="email" id="correo" name="correo" class="form-control form-control-custom" placeholder="ejemplo@correo.com" required>
                                </div>
                            </div>

                            <div>
                                <label for="mensaje" class="form-label fw-semibold small mb-1" style="color: var(--purple-dark);">Mensaje</label>
                                <textarea id="mensaje" name="mensaje" class="form-control form-control-custom" rows="3" placeholder="Escribe tu mensaje o consulta..." required></textarea>
                            </div>

                            <button type="submit" id="btn-enviar" class="btn btn-enviar-contacto w-100 mt-2">
                                Enviar Mensaje <i class="bi bi-send ms-1"></i>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- COLUMNA DERECHA: MAPA INTERACTIVO -->
                <div class="col-lg-6 col-xl-7 d-flex">
                    <div class="card card-contacto p-3 p-md-4 w-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                            <h2 class="h5 fw-bold mb-0" style="color: var(--purple-dark);">
                                <i class="bi bi-map-fill me-2" style="color: var(--purple-medium);"></i>Mapa de Ubicación
                            </h2>
                            <span class="badge rounded-pill px-3 py-2 fw-semibold" style="background-color: var(--purple-soft); color: var(--purple-main);">UPP Pachuca</span>
                        </div>
                        
                        <div class="rounded-4 overflow-hidden flex-grow-1 position-relative" style="min-height: 450px;">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3748.7495966373807!2d-98.67930542477028!3d20.018967981504994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1a1532e1f579d%3A0xb30f80a3ee41a18d!2sUniversidad%20Polit%C3%A9cnica%20de%20Pachuca!5e0!3m2!1ses-419!2smx!4v1710000000000!5m2!1ses-419!2smx" 
                                class="position-absolute top-0 start-0 w-100 h-100"
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- VENTANA EMERGENTE (MODAL) DE BOOTSTRAP -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom text-center p-4 shadow border-0">
                <div class="modal-body p-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 70px; height: 70px; background-color: #D1E7DD; color: #0F5132; font-size: 32px;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5 class="h4 fw-bold mb-2" id="modalConfirmacionLabel" style="color: var(--purple-dark);">¡Mensaje Enviado!</h5>
                    <p class="text-muted small mb-4">Se ha enviado tu mensaje correctamente. Nos pondremos en contacto contigo a la brevedad.</p>
                    <button type="button" class="btn text-white rounded-pill px-4 py-2 fw-semibold w-100" style="background-color: var(--purple-main);" data-bs-dismiss="modal">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER CORPORATIVO -->
    <footer class="footer-custom py-4 mt-auto border-top border-purple">
        <div class="container-xl d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <p class="mb-0 text-white-50 small">&copy; 2026 Shiftly. Todos los derechos reservados.</p>
            <div class="d-flex gap-3">
                <a href="#" class="text-white-50 text-decoration-none small">Privacidad</a>
                <a href="#" class="text-white-50 text-decoration-none small">Términos</a>
                <a href="{{ url('/contacto') }}" class="text-white-50 text-decoration-none small">Soporte</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de interactividad -->
    <script>
        document.getElementById('form-contacto').addEventListener('submit', function (event) {
            event.preventDefault();
            
            const modalElement = document.getElementById('modalConfirmacion');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            this.reset();
        });
    </script>
</body>

</html>