document.addEventListener("DOMContentLoaded", function () {
    // =========================================================
    // 1. MOSTRAR / OCULTAR CONTRASEÑA
    // =========================================================
    const botonMostrar = document.getElementById("mostrarPassword");
    const passwordInput = document.getElementById("password");
    const emailInput = document.getElementById("email");
    const iconoPassword = document.getElementById("iconoPassword");

    if (botonMostrar && passwordInput && iconoPassword) {
        botonMostrar.addEventListener("click", function () {
            const mostrar = passwordInput.type === "password";
            passwordInput.type = mostrar ? "text" : "password";
            iconoPassword.className = mostrar ? "bi bi-eye-slash" : "bi bi-eye";
            botonMostrar.setAttribute("aria-label", mostrar ? "Ocultar contraseña" : "Mostrar contraseña");
            botonMostrar.setAttribute("aria-pressed", String(mostrar));
        });
    }

    // =========================================================
    // 2. VALIDACIÓN INTERACTIVA EN TIEMPO REAL
    // =========================================================
    function marcarCampo(input, esValido) {
        const contenedor = input.closest(".input-group-shiftly") || input.parentElement;
        if (!contenedor) return;
        contenedor.style.borderColor = esValido ? "#22C55E" : "#EF4444";
    }

    if (emailInput) {
        emailInput.addEventListener("input", () => {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailInput.value.trim() === "") {
                emailInput.closest(".input-group-shiftly").style.borderColor = "";
                return;
            }
            marcarCampo(emailInput, regex.test(emailInput.value.trim()));
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener("input", () => {
            if (passwordInput.value === "") {
                passwordInput.closest(".input-group-shiftly").style.borderColor = "";
                return;
            }
            marcarCampo(passwordInput, passwordInput.value.length >= 6);
        });
    }

    // =========================================================
    // 3. MODAL DE REGISTRO EXITOSO (?registro=exitoso)
    // =========================================================
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get("registro") === "exitoso") {
        let modalElement = document.getElementById("modalRegistroExito");

        if (!modalElement) {
            const modalHTML = `
                <div class="modal fade" id="modalRegistroExito" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4 rounded-4 border-0 shadow-lg">
                            <div class="modal-body">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 72px; height: 72px; background-color: #DCFCE7; color: #15803D; font-size: 34px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <h3 class="h4 fw-extrabold mb-2" style="color: #1A0B2E;">¡Registro Exitoso!</h3>
                                <p class="text-muted small mb-4">Tu cuenta en Shiftly ha sido creada correctamente. Ya puedes iniciar sesión.</p>
                                <button type="button" class="btn btn-3d-orange w-100 py-2" data-bs-dismiss="modal">
                                    Entendido / Iniciar Sesión
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            document.body.insertAdjacentHTML("beforeend", modalHTML);
            modalElement = document.getElementById("modalRegistroExito");
        }

        if (typeof bootstrap !== "undefined") {
            new bootstrap.Modal(modalElement).show();
        }
    }
});
