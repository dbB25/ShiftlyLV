document.addEventListener("DOMContentLoaded", () => {
    const STORAGE_KEY = "shiftly_cuentas_crud";

    const formCambiar = document.getElementById("formCambiarPassword");
    const passActual = document.getElementById("passActual");
    const passNueva = document.getElementById("passNueva");
    const passConfirmar = document.getElementById("passConfirmar");
    const mensajeCambio = document.getElementById("mensajeCambio");
    const btnCrearPassword = document.getElementById("btnCrearPassword");

    const strengthBar = document.getElementById("newPassStrengthBar");
    const strengthText = document.getElementById("newPassStrengthText");
    const matchStatusText = document.getElementById("matchStatusText");

    const cuentaBadge = document.getElementById("cuentaRecuperadaBadge");
    const cuentaTexto = document.getElementById("cuentaRecuperadaTexto");

    // 1. Mostrar cuenta en proceso de recuperación (si viene de verificacion-correo)
    const correoRecuperacion = sessionStorage.getItem("recuperacionCorreo");
    if (correoRecuperacion && cuentaBadge && cuentaTexto) {
        cuentaTexto.textContent = correoRecuperacion;
        cuentaBadge.classList.remove("d-none");
    }

    // 2. Botones para ocultar/mostrar contraseña
    document.querySelectorAll(".toggle-pass").forEach(boton => {
        boton.addEventListener("click", () => {
            const idTarget = boton.getAttribute("data-target");
            const input = document.getElementById(idTarget);
            const icono = boton.querySelector("i");

            if (input && input.type === "password") {
                input.type = "text";
                icono.className = "bi bi-eye-slash";
            } else if (input) {
                input.type = "password";
                icono.className = "bi bi-eye";
            }
        });
    });

    function mostrarMensaje(texto, tipo) {
        if (!mensajeCambio) return;
        const iconos = {
            success: "bi-check-circle-fill",
            warning: "bi-exclamation-triangle-fill",
            danger: "bi-x-circle-fill"
        };
        mensajeCambio.innerHTML = `<i class="bi ${iconos[tipo] || "bi-info-circle-fill"} me-1"></i> ${texto}`;
        mensajeCambio.className = `alert alert-${tipo} text-start d-block rounded-4 py-2 px-3 small fw-bold`;
    }

    function marcarBorde(input, estado) {
        const caja = input ? input.closest(".input-group-shiftly") : null;
        if (!caja) return;
        if (estado === "ok") caja.style.borderColor = "#22C55E";
        else if (estado === "error") caja.style.borderColor = "#EF4444";
        else caja.style.borderColor = "";
    }

    // 3. Medidor interactivo de fuerza y coincidencia en tiempo real
    function evaluarCoincidencia() {
        if (!passNueva || !passConfirmar || !matchStatusText) return;
        const nueva = passNueva.value;
        const conf = passConfirmar.value;

        if (!conf) {
            matchStatusText.textContent = "";
            marcarBorde(passConfirmar, "neutral");
            return;
        }

        if (nueva === conf) {
            matchStatusText.textContent = "✓ Coinciden";
            matchStatusText.style.color = "#15803D";
            marcarBorde(passConfirmar, "ok");
        } else {
            matchStatusText.textContent = "No coinciden";
            matchStatusText.style.color = "#DC2626";
            marcarBorde(passConfirmar, "error");
        }
    }

    if (passNueva) {
        passNueva.addEventListener("input", () => {
            const val = passNueva.value;
            evaluarCoincidencia();

            if (!val) {
                marcarBorde(passNueva, "neutral");
                if (strengthBar) strengthBar.style.width = "0%";
                if (strengthText) {
                    strengthText.textContent = "Mínimo 6 caracteres";
                    strengthText.style.color = "";
                }
                return;
            }

            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 8) score++;
            if (/[A-Z0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            if (val.length < 6) {
                marcarBorde(passNueva, "error");
                if (strengthBar) {
                    strengthBar.style.width = "30%";
                    strengthBar.style.backgroundColor = "#EF4444";
                }
                if (strengthText) {
                    strengthText.textContent = "Muy corta (mín. 6)";
                    strengthText.style.color = "#DC2626";
                }
            } else if (score <= 2) {
                marcarBorde(passNueva, "ok");
                if (strengthBar) {
                    strengthBar.style.width = "65%";
                    strengthBar.style.backgroundColor = "#F59E0B";
                }
                if (strengthText) {
                    strengthText.textContent = "Seguridad media";
                    strengthText.style.color = "#D97706";
                }
            } else {
                marcarBorde(passNueva, "ok");
                if (strengthBar) {
                    strengthBar.style.width = "100%";
                    strengthBar.style.backgroundColor = "#22C55E";
                }
                if (strengthText) {
                    strengthText.textContent = "¡Clave segura!";
                    strengthText.style.color = "#15803D";
                }
            }
        });
    }

    if (passConfirmar) {
        passConfirmar.addEventListener("input", evaluarCoincidencia);
    }

    // 4. Submit: Validar y ejecutar UPDATE de contraseña en el CRUD de cuentas
    if (formCambiar) {
        formCambiar.addEventListener("submit", (evento) => {
            evento.preventDefault();

            const valActual = passActual ? passActual.value.trim() : "";
            const valNueva = passNueva ? passNueva.value.trim() : "";
            const valConfirmar = passConfirmar ? passConfirmar.value.trim() : "";

            if (!valActual || !valNueva || !valConfirmar) {
                mostrarMensaje("Por favor, completa todos los campos de contraseña.", "warning");
                return;
            }

            if (valNueva.length < 6) {
                mostrarMensaje("La nueva contraseña debe tener al menos 6 caracteres.", "warning");
                passNueva.focus();
                return;
            }

            if (valNueva === valActual) {
                mostrarMensaje("La nueva contraseña debe ser diferente a tu contraseña actual.", "warning");
                passNueva.focus();
                return;
            }

            if (valNueva !== valConfirmar) {
                mostrarMensaje("La nueva contraseña y la confirmación no coinciden.", "danger");
                passConfirmar.focus();
                return;
            }

            // UPDATE en el CRUD localStorage ('shiftly_cuentas_crud') si existe el usuario
            const cuentas = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");
            if (correoRecuperacion) {
                const idx = cuentas.findIndex(c => c.email === correoRecuperacion.toLowerCase());
                if (idx !== -1) {
                    cuentas[idx].password = valNueva;
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(cuentas));
                }
            }

            if (btnCrearPassword) {
                btnCrearPassword.disabled = true;
                btnCrearPassword.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Actualizando...`;
            }

            mostrarMensaje("¡Contraseña actualizada con éxito! Redirigiendo al inicio de sesión...", "success");

            const rutaLogin = formCambiar.getAttribute("data-redirect") || "/login";
            setTimeout(() => {
                window.location.href = rutaLogin;
            }, 900);
        });
    }
});