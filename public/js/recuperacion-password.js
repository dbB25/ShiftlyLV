document.addEventListener("DOMContentLoaded", () => {
    class RecuperacionService {
        constructor() {
            this.storageKey = "recuperaciones";
            this.initStorage();
        }

        initStorage() {
            if (!localStorage.getItem(this.storageKey)) {
                localStorage.setItem(this.storageKey, JSON.stringify([]));
            }
        }

        crear(correo) {
            const registros = this.obtenerTodos();
            const nuevoRegistro = {
                id: Date.now().toString(),
                correo: correo,
                codigoVerificado: false,
                fecha: new Date().toISOString()
            };
            registros.push(nuevoRegistro);
            localStorage.setItem(this.storageKey, JSON.stringify(registros));
            return nuevoRegistro;
        }

        obtenerTodos() {
            return JSON.parse(localStorage.getItem(this.storageKey)) || [];
        }

        obtenerPorId(id) {
            const registros = this.obtenerTodos();
            return registros.find(item => item.id === id) || null;
        }

        obtenerPorCorreo(correo) {
            const registros = this.obtenerTodos();
            return registros.find(item => item.correo === correo) || null;
        }

        actualizar(id, datosActualizados) {
            const registros = this.obtenerTodos();
            const indice = registros.findIndex(item => item.id === id);
            if (indice !== -1) {
                registros[indice] = { ...registros[indice], ...datosActualizados };
                localStorage.setItem(this.storageKey, JSON.stringify(registros));
                return registros[indice];
            }
            return null;
        }

        eliminar(id) {
            const registros = this.obtenerTodos();
            const registrosFiltrados = registros.filter(item => item.id !== id);
            localStorage.setItem(this.storageKey, JSON.stringify(registrosFiltrados));
            return registros.length !== registrosFiltrados.length;
        }
    }

    const recuperacionService = new RecuperacionService();

    const formRecuperar = document.getElementById("formRecuperar");
    const correoInput = document.getElementById("correoRecuperacion");
    const mensajeRecuperacion = document.getElementById("mensajeRecuperacion");

    if (formRecuperar) {
        formRecuperar.addEventListener("submit", (evento) => {
            evento.preventDefault();

            const correo = correoInput ? correoInput.value.trim() : "";

            if (!correo || !/\S+@\S+\.\S+/.test(correo)) {
                if (mensajeRecuperacion) {
                    mensajeRecuperacion.textContent = "Por favor, ingresa un correo electrónico válido.";
                    mensajeRecuperacion.className = "alert alert-warning text-start d-block";
                }
                return;
            }
            const nuevoRegistro = recuperacionService.crear(correo);
            sessionStorage.setItem("recuperacionActualId", nuevoRegistro.id);
            if (mensajeRecuperacion) {
                mensajeRecuperacion.textContent = "¡Enlace enviado! Redirigiendo a la verificación de correo...";
                mensajeRecuperacion.className = "alert alert-success text-start d-block";
            }
            setTimeout(() => {
                window.location.href = "verificacion-correo.html";
            }, 800);
        });
    }
    const formVerificacion = document.getElementById("formularioVerificacion");
    const camposCodigo = document.querySelectorAll(".codigo");
    const mensajeVerificacion = document.getElementById("mensajeVerificacion");
    if (formVerificacion) {
        formVerificacion.addEventListener("submit", (evento) => {
            evento.preventDefault();
            let codigoCompleto = "";
            camposCodigo.forEach(campo => {
                codigoCompleto += campo.value.trim();
            });
            const idActual = sessionStorage.getItem("recuperacionActualId");
            if (idActual) {
                recuperacionService.actualizar(idActual, {
                    codigoVerificado: true,
                    codigoIngresado: codigoCompleto
                });
            }
            if (mensajeVerificacion) {
                mensajeVerificacion.textContent = "¡Código verificado con éxito! Redirigiendo...";
                mensajeVerificacion.className = "alert alert-success text-start d-block";
            }
            setTimeout(() => {
                window.location.href = "recuperar-password.html";
            }, 600);
        });
    }
});