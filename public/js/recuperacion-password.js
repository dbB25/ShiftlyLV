document.addEventListener("DOMContentLoaded", () => {

    // ==========================================
    // CLASE DE SERVICIO CRUD EN LOCALSTORAGE
    // ==========================================
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

        // CREATE: Guarda una nueva solicitud de recuperación
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

        // READ: Obtiene todos los registros
        obtenerTodos() {
            return JSON.parse(localStorage.getItem(this.storageKey)) || [];
        }

        // READ: Buscar por ID
        obtenerPorId(id) {
            const registros = this.obtenerTodos();
            return registros.find(item => item.id === id) || null;
        }

        // UPDATE: Actualiza el estado de una recuperación
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

        // DELETE: Elimina un registro por ID
        eliminar(id) {
            const registros = this.obtenerTodos();
            const registrosFiltrados = registros.filter(item => item.id !== id);
            localStorage.setItem(this.storageKey, JSON.stringify(registrosFiltrados));
            return registros.length !== registrosFiltrados.length;
        }
    }

    const recuperacionService = new RecuperacionService();

    // Selectores del DOM
    const formRecuperar = document.getElementById("formRecuperar");
    const correoInput = document.getElementById("email");
    const mensajeRecuperacion = document.getElementById("mensajeRecuperacion");
    const btnEnviarEnlace = document.getElementById("btnEnviarEnlace");

    // Elementos de la Ventana Emergente (Modal)
    const modalElement = document.getElementById("modalConfirmacionCorreo");
    const textoCorreoEnviado = document.getElementById("textoCorreoEnviado");
    const btnAceptarModal = document.getElementById("btnAceptarModal");

    if (formRecuperar) {
        formRecuperar.addEventListener("submit", (evento) => {
            evento.preventDefault();

            const correo = correoInput ? correoInput.value.trim() : "";

            // Validación del formato del correo
            if (!correo || !/\S+@\S+\.\S+/.test(correo)) {
                if (mensajeRecuperacion) {
                    mensajeRecuperacion.textContent = "Por favor, ingresa un correo electrónico válido.";
                    mensajeRecuperacion.className = "alert alert-warning text-start d-block";
                }
                if (correoInput) correoInput.focus();
                return;
            }

            // Ocultar alertas previas de error
            if (mensajeRecuperacion) {
                mensajeRecuperacion.classList.add("d-none");
            }

            // 1. CREATE: Guardar registro en localStorage y sessionStorage
            const nuevoRegistro = recuperacionService.crear(correo);
            sessionStorage.setItem("recuperacionActualId", nuevoRegistro.id);
            sessionStorage.setItem("recuperacionCorreo", correo);

            // 2. Colocar el correo ingresado dentro del texto del Modal
            if (textoCorreoEnviado) {
                textoCorreoEnviado.textContent = correo;
            }

            // 3. Desplegar Modal Emergente de Bootstrap
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    }

    // Evento del botón Aceptar del Modal para redirigir
    if (btnAceptarModal) {
        btnAceptarModal.addEventListener("click", () => {
            window.location.href = "/verificacion-correo";
        });
    }

});