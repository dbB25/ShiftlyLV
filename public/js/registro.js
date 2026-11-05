document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("formularioRegistro") || document.querySelector("form");
    const nombre = document.getElementById("nombre");
    const correo = document.getElementById("email") || document.getElementById("correo");
    const password = document.getElementById("password");
    const botonMostrar = document.getElementById("mostrarPassword");
    const iconoPassword = document.getElementById("iconoPassword");

    let mensaje = document.getElementById("mensajeRegistro");
    if (!mensaje && formulario) {
        mensaje = document.createElement("div");
        mensaje.id = "mensajeRegistro";
        mensaje.className = "alert d-none mt-3";
        mensaje.setAttribute("role", "status");
        mensaje.setAttribute("aria-live", "polite");
        formulario.appendChild(mensaje);
    }

    // Mostrar / Ocultar Contraseña
    if (botonMostrar && password && iconoPassword) {
        botonMostrar.addEventListener("click", function () {
            const mostrar = password.type === "password";
            password.type = mostrar ? "text" : "password";
            iconoPassword.className = mostrar ? "bi bi-eye-slash" : "bi bi-eye";
            botonMostrar.setAttribute("aria-label", mostrar ? "Ocultar contraseña" : "Mostrar contraseña");
            botonMostrar.setAttribute("aria-pressed", String(mostrar));
        });
    }

    function mostrarMensaje(texto, tipo) {
        if (!mensaje) return;
        mensaje.textContent = texto;
        mensaje.className = "alert alert-" + tipo + " mt-3 d-block";
    }

    // Intercepta el submit y redirige pasando el parámetro de éxito
    if (formulario) {
        formulario.addEventListener("submit", function (evento) {
            evento.preventDefault();

            const nombreVal = nombre ? nombre.value.trim() : "";
            const correoVal = correo ? correo.value.trim() : "";
            const passwordVal = password ? password.value : "";

            if (nombre && nombreVal.length < 3) {
                mostrarMensaje("Escribe tu nombre completo con al menos 3 caracteres.", "warning");
                nombre.focus();
                return;
            }

            if (correo && (!correo.checkValidity() || correoVal === "")) {
                mostrarMensaje("Ingresa una dirección de correo electrónico válida.", "danger");
                correo.focus();
                return;
            }

            if (password && passwordVal.length < 8) {
                mostrarMensaje("La contraseña debe tener al menos 8 caracteres.", "warning");
                password.focus();
                return;
            }

            // Redirección forzada hacia el login con el parámetro activador del modal
            window.location.href = "/login?registro=exitoso";
        });

        formulario.addEventListener("input", function () {
            if (mensaje) {
                mensaje.textContent = "";
                mensaje.className = "alert d-none mt-3";
            }
        });
    }
});