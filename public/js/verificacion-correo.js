document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("formularioVerificacion");
    const campos = document.querySelectorAll(".codigo");
    const grupoInputsOtp = document.getElementById("grupoInputsOtp");
    const mensaje = document.getElementById("mensajeVerificacion");
    const botonReenviar = document.getElementById("reenviarCodigo");
    const contadorReenvio = document.getElementById("contadorReenvio");
    const barra = document.getElementById("barraProgreso");
    const textoProgreso = document.getElementById("textoProgreso");
    const btnVerificar = document.getElementById("btnVerificar");
    const correoDestinoTexto = document.getElementById("correoDestinoTexto");

    if (!formulario || !campos.length) return;

    // 1. Mostrar el correo guardado desde recuperar-password (si existe)
    const correoGuardado = sessionStorage.getItem("recuperacionCorreo");
    if (correoGuardado && correoDestinoTexto) {
        correoDestinoTexto.textContent = correoGuardado;
    }

    // Enfocar automáticamente el primer dígito al cargar
    campos[0].focus();

    // Mostrar mensajes dinámicos
    function mostrarMensaje(texto, tipo) {
        if (!mensaje) return;
        const iconos = {
            success: "bi-check-circle-fill",
            warning: "bi-exclamation-triangle-fill",
            info: "bi-info-circle-fill",
            danger: "bi-x-circle-fill"
        };
        const icono = iconos[tipo] || "bi-info-circle-fill";
        mensaje.innerHTML = `<i class="bi ${icono} me-1"></i> ${texto}`;
        mensaje.className = `alert alert-${tipo} text-start d-block rounded-4 py-2 px-3 small fw-bold`;
    }

    // Actualizar barra de progreso y estilos de cada casilla
    function actualizarProgreso() {
        let completados = 0;

        campos.forEach(campo => {
            const tieneValor = campo.value.trim() !== "";
            campo.classList.toggle("filled", tieneValor);
            if (tieneValor) completados++;
        });

        if (!barra || !textoProgreso) return;

        const porcentaje = Math.round((completados / campos.length) * 100);
        barra.style.width = porcentaje + "%";
        barra.setAttribute("aria-valuenow", porcentaje);
        textoProgreso.textContent = porcentaje + "%";
    }

    // Navegación interactiva y restricción a números entre campos
    campos.forEach((campo, indice) => {
        campo.addEventListener("input", () => {
            campo.value = campo.value.replace(/\D/g, "").slice(-1);

            if (campo.value && indice < campos.length - 1) {
                campos[indice + 1].focus();
            }

            if (mensaje) mensaje.className = "alert d-none text-start";
            actualizarProgreso();
        });

        campo.addEventListener("keydown", evento => {
            if (evento.key === "Backspace" && !campo.value && indice > 0) {
                campos[indice - 1].focus();
            }

            if (evento.key === "ArrowLeft" && indice > 0) {
                campos[indice - 1].focus();
            }

            if (evento.key === "ArrowRight" && indice < campos.length - 1) {
                campos[indice + 1].focus();
            }
        });

        // Soporte para pegar código completo de 4 dígitos
        campo.addEventListener("paste", evento => {
            evento.preventDefault();

            const codigoPegado = evento.clipboardData
                .getData("text")
                .replace(/\D/g, "")
                .slice(0, campos.length);

            codigoPegado.split("").forEach((digito, posicion) => {
                if (campos[posicion]) {
                    campos[posicion].value = digito;
                }
            });

            const siguienteVacio = Array.from(campos).findIndex(c => !c.value);
            const indiceEnfoque = siguienteVacio === -1 ? campos.length - 1 : siguienteVacio;
            campos[indiceEnfoque].focus();

            if (mensaje) mensaje.className = "alert d-none text-start";
            actualizarProgreso();
        });
    });

    // Validar código, actualizar CRUD en localStorage y redirigir a new-password
    formulario.addEventListener("submit", function (evento) {
        evento.preventDefault();

        const codigo = Array.from(campos)
            .map(campo => campo.value.trim())
            .join("");

        if (!/^\d{4}$/.test(codigo)) {
            mostrarMensaje("Ingresa los cuatro dígitos del código de verificación.", "warning");
            if (grupoInputsOtp) {
                grupoInputsOtp.classList.remove("shake-error");
                void grupoInputsOtp.offsetWidth;
                grupoInputsOtp.classList.add("shake-error");
            }
            const vacio = Array.from(campos).find(c => !c.value.trim());
            if (vacio) vacio.focus();
            return;
        }

        // UPDATE en el CRUD de 'recuperaciones' en localStorage
        const recuperacionActualId = sessionStorage.getItem("recuperacionActualId");
        if (recuperacionActualId) {
            const registros = JSON.parse(localStorage.getItem("recuperaciones") || "[]");
            const indice = registros.findIndex(item => item.id === recuperacionActualId);
            if (indice !== -1) {
                registros[indice].codigoVerificado = true;
                registros[indice].codigoIngresado = codigo;
                localStorage.setItem("recuperaciones", JSON.stringify(registros));
            }
        }

        // Marcar casillas en verde y animar botón
        campos.forEach(c => c.classList.add("verified-ok"));
        if (btnVerificar) {
            btnVerificar.disabled = true;
            btnVerificar.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Verificando...`;
        }

        mostrarMensaje("¡Código verificado correctamente! Redirigiendo para crear tu nueva contraseña...", "success");

        const rutaDestino = formulario.getAttribute("data-redirect") || "/new-password";
        setTimeout(() => {
            window.location.href = rutaDestino;
        }, 900);
    });

    // Reenviar código con cuenta regresiva interactiva de 30s
    let cooldownActivo = false;
    if (botonReenviar) {
        botonReenviar.addEventListener("click", (evento) => {
            evento.preventDefault();
            if (cooldownActivo) return;

            campos.forEach(c => {
                c.value = "";
                c.classList.remove("filled", "verified-ok");
            });
            actualizarProgreso();
            campos[0].focus();

            mostrarMensaje("Se ha enviado un nuevo código de 4 dígitos a tu correo.", "info");

            cooldownActivo = true;
            let segundos = 30;
            botonReenviar.style.opacity = "0.5";
            botonReenviar.style.pointerEvents = "none";

            if (contadorReenvio) {
                contadorReenvio.classList.remove("d-none");
                contadorReenvio.textContent = `Podrás solicitar otro código en ${segundos}s`;
            }

            const intervalo = setInterval(() => {
                segundos--;
                if (contadorReenvio) {
                    contadorReenvio.textContent = `Podrás solicitar otro código en ${segundos}s`;
                }
                if (segundos <= 0) {
                    clearInterval(intervalo);
                    cooldownActivo = false;
                    botonReenviar.style.opacity = "1";
                    botonReenviar.style.pointerEvents = "auto";
                    if (contadorReenvio) contadorReenvio.classList.add("d-none");
                }
            }, 1000);
        });
    }

    actualizarProgreso();
});