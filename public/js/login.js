document.addEventListener("DOMContentLoaded", function () {
    // 1. Mostrar / Ocultar la contraseña
    const botonMostrar = document.getElementById("mostrarPassword");
    const password = document.getElementById("password");
    const iconoPassword = document.getElementById("iconoPassword");

    if (botonMostrar && password && iconoPassword) {
        botonMostrar.addEventListener("click", function () {
            const mostrar = password.type === "password";

            password.type = mostrar ? "text" : "password";

            iconoPassword.className = mostrar
                ? "bi bi-eye-slash"
                : "bi bi-eye";

            botonMostrar.setAttribute(
                "aria-label",
                mostrar ? "Ocultar contraseña" : "Mostrar contraseña"
            );

            botonMostrar.setAttribute("aria-pressed", String(mostrar));
        });
    }

    // 2. Ventana emergente (Modal) SOLO al crear cuenta
    const urlParams = new URLSearchParams(window.location.search);
    
    // Solo se dispara si la URL contiene exactamete '?registro=exitoso'
    if (urlParams.get('registro') === 'exitoso') {
        let modalElement = document.getElementById('modalRegistroExito');
        
        if (!modalElement) {
            const modalHTML = `
                <div class="modal fade" id="modalRegistroExito" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4 rounded-4 border-0 shadow">
                            <div class="modal-body">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 70px; height: 70px; background-color: #D1E7DD; color: #0F5132; font-size: 32px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <h3 class="h4 fw-bold mb-2" style="color: #111344;">¡Registro Exitoso!</h3>
                                <p class="text-muted small mb-4">Tu cuenta ha sido creada correctamente.</p>
                                <button type="button" class="btn text-white rounded-pill px-4 py-2 fw-semibold w-100" 
                                        data-bs-dismiss="modal" style="background-color: #52154E;">
                                    Entendido / Iniciar Sesión
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            modalElement = document.getElementById('modalRegistroExito');
        }

        if (typeof bootstrap !== 'undefined') {
            const modalExito = new bootstrap.Modal(modalElement);
            modalExito.show();
        }
    }

    // 3. Redirección al index ("/") al procesar el formulario de Login
    const formularioLogin = document.getElementById("formularioLogin") || document.querySelector("form");

    if (formularioLogin) {
        formularioLogin.addEventListener("submit", function (e) {
            e.preventDefault();
            window.location.href = "/";
        });
    }
});