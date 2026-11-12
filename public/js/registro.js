document.addEventListener("DOMContentLoaded", function () {
    const STORAGE_KEY = "shiftly_cuentas_crud";

    const formulario = document.getElementById("formularioRegistro") || document.querySelector("form");
    const nombre = document.getElementById("nombre");
    const carrera = document.getElementById("carrera");
    const correo = document.getElementById("email") || document.getElementById("correo");
    const password = document.getElementById("password");
    const botonMostrar = document.getElementById("mostrarPassword");
    const iconoPassword = document.getElementById("iconoPassword");
    const btnSubmit = document.getElementById("btnSubmitRegistro");
    const strengthBar = document.getElementById("passwordStrengthBar");
    const strengthText = document.getElementById("passwordStrengthText");

    let mensaje = document.getElementById("mensajeRegistro");
    if (!mensaje && formulario) {
        mensaje = document.createElement("div");
        mensaje.id = "mensajeRegistro";
        mensaje.className = "alert d-none mt-3 rounded-4 py-2 px-3 small fw-bold";
        mensaje.setAttribute("role", "status");
        mensaje.setAttribute("aria-live", "polite");
        formulario.appendChild(mensaje);
    }

    // 1. Mostrar / Ocultar Contraseña
    if (botonMostrar && password && iconoPassword) {
        botonMostrar.addEventListener("click", function () {
            const mostrar = password.type === "password";
            password.type = mostrar ? "text" : "password";
            iconoPassword.className = mostrar ? "bi bi-eye-slash" : "bi bi-eye";
            botonMostrar.setAttribute("aria-label", mostrar ? "Ocultar contraseña" : "Mostrar contraseña");
            botonMostrar.setAttribute("aria-pressed", String(mostrar));
        });
    }

    // 2. Medidor interactivo de seguridad de contraseña + bordes en tiempo real
    function marcarBorde(input, esValido) {
        const caja = input.closest(".input-group-shiftly");
        if (!caja) return;
        caja.style.borderColor = esValido ? "#22C55E" : "#EF4444";
    }

    if (nombre) {
        nombre.addEventListener("input", () => {
            if (nombre.value.trim() === "") {
                nombre.closest(".input-group-shiftly").style.borderColor = "";
                return;
            }
            marcarBorde(nombre, nombre.value.trim().length >= 3);
        });
    }

    if (correo) {
        correo.addEventListener("input", () => {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (correo.value.trim() === "") {
                correo.closest(".input-group-shiftly").style.borderColor = "";
                return;
            }
            marcarBorde(correo, regex.test(correo.value.trim()));
        });
    }

    if (password) {
        password.addEventListener("input", () => {
            const val = password.value;
            if (!val) {
                password.closest(".input-group-shiftly").style.borderColor = "";
                if (strengthBar) strengthBar.style.width = "0%";
                if (strengthText) {
                    strengthText.textContent = "Mínimo 8 caracteres";
                    strengthText.className = "small fw-bold text-muted";
                }
                return;
            }

            let puntos = 0;
            if (val.length >= 8) puntos++;
            if (/[A-Z]/.test(val)) puntos++;
            if (/[0-9]/.test(val)) puntos++;
            if (/[^A-Za-z0-9]/.test(val)) puntos++;

            marcarBorde(password, val.length >= 8);

            if (strengthBar && strengthText) {
                if (val.length < 8) {
                    strengthBar.style.width = "30%";
                    strengthBar.style.backgroundColor = "#EF4444";
                    strengthText.textContent = "Muy corta (mín. 8)";
                    strengthText.style.color = "#DC2626";
                } else if (puntos <= 2) {
                    strengthBar.style.width = "65%";
                    strengthBar.style.backgroundColor = "#F59E0B";
                    strengthText.textContent = "Seguridad media";
                    strengthText.style.color = "#D97706";
                } else {
                    strengthBar.style.width = "100%";
                    strengthBar.style.backgroundColor = "#22C55E";
                    strengthText.textContent = "¡Contraseña fuerte!";
                    strengthText.style.color = "#15803D";
                }
            }
        });
    }

    function mostrarMensaje(texto, tipo) {
        if (!mensaje) return;
        mensaje.innerHTML = `<i class="bi bi-exclamation-circle-fill me-1"></i> ${texto}`;
        mensaje.className = "alert alert-" + tipo + " mt-3 d-block rounded-4 py-2 px-3 small fw-bold";
    }

    // 3. Guardar cuenta en el CRUD de localStorage y redirigir a /login?registro=exitoso
    if (formulario) {
        formulario.addEventListener("submit", function (evento) {
            evento.preventDefault();

            const nombreVal = nombre ? nombre.value.trim() : "";
            const carreraVal = carrera ? carrera.value : "Sistemas y Software";
            const correoVal = correo ? correo.value.trim().toLowerCase() : "";
            const passwordVal = password ? password.value : "";

            if (nombre && nombreVal.length < 3) {
                mostrarMensaje("Escribe tu nombre completo con al menos 3 caracteres.", "warning");
                marcarBorde(nombre, false);
                nombre.focus();
                return;
            }

            if (correo && (!correo.checkValidity() || correoVal === "")) {
                mostrarMensaje("Ingresa una dirección de correo electrónico válida.", "danger");
                marcarBorde(correo, false);
                correo.focus();
                return;
            }

            if (password && passwordVal.length < 8) {
                mostrarMensaje("La contraseña debe tener al menos 8 caracteres.", "warning");
                marcarBorde(password, false);
                password.focus();
                return;
            }

            // Sincronizar con el CRUD de cuentas usado en login.js
            const cuentasGuardadas = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");
            if (cuentasGuardadas.some(c => c.email === correoVal)) {
                mostrarMensaje("Este correo ya está registrado. Inicia sesión o usa otro correo.", "danger");
                marcarBorde(correo, false);
                correo.focus();
                return;
            }

            cuentasGuardadas.push({
                id: Date.now(),
                nombre: nombreVal,
                carrera: carreraVal,
                email: correoVal,
                password: passwordVal
            });
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cuentasGuardadas));

            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Creando cuenta...`;
            }

            setTimeout(() => {
                window.location.href = "/login?registro=exitoso";
            }, 600);
        });

        formulario.addEventListener("input", function () {
            if (mensaje && !mensaje.classList.contains("d-none")) {
                mensaje.textContent = "";
                mensaje.className = "alert d-none mt-3";
            }
        });
    }
});