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
    const estadoCuentaBadge = document.getElementById("estadoCuentaBadge");

    // Elementos de la Ventana Emergente (Modal)
    const modalElement = document.getElementById("modalConfirmacionCorreo");
    const textoCorreoEnviado = document.getElementById("textoCorreoEnviado");
    const btnAceptarModal = document.getElementById("btnAceptarModal");

    // ==========================================
    // INTERACTIVIDAD EN TIEMPO REAL DEL INPUT
    // ==========================================
    if (correoInput) {
        correoInput.addEventListener("input", () => {
            const val = correoInput.value.trim().toLowerCase();
            const caja = correoInput.closest(".input-group-shiftly");

            if (mensajeRecuperacion && !mensajeRecuperacion.classList.contains("d-none")) {
                mensajeRecuperacion.classList.add("d-none");
            }

            if (!val) {
                if (caja) caja.style.borderColor = "";
                if (estadoCuentaBadge) estadoCuentaBadge.textContent = "";
                return;
            }

            const esValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
            if (caja) caja.style.borderColor = esValido ? "#22C55E" : "#EF4444";

            if (estadoCuentaBadge) {
                if (!esValido) {
                    estadoCuentaBadge.textContent = "Formato inválido";
                    estadoCuentaBadge.style.color = "#DC2626";
                } else {
                    const cuentas = JSON.parse(localStorage.getItem("shiftly_cuentas_crud") || "[]");
                    const existe = cuentas.some(c => c.email === val);
                    estadoCuentaBadge.textContent = existe ? "✓ Cuenta detectada" : "Correo válido";
                    estadoCuentaBadge.style.color = "#15803D";
                }
            }
        });
    }

    // ==========================================
    // ENVÍO DEL FORMULARIO + CREATE EN CRUD
    // ==========================================
    if (formRecuperar) {
        formRecuperar.addEventListener("submit", (evento) => {
            evento.preventDefault();

            const correo = correoInput ? correoInput.value.trim().toLowerCase() : "";

            if (!correo || !/\S+@\S+\.\S+/.test(correo)) {
                if (mensajeRecuperacion) {
                    mensajeRecuperacion.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> Por favor, ingresa un correo electrónico válido.`;
                    mensajeRecuperacion.className = "alert alert-warning text-start d-block rounded-4 py-2 px-3 small fw-bold";
                }
                if (correoInput) {
                    const caja = correoInput.closest(".input-group-shiftly");
                    if (caja) caja.style.borderColor = "#EF4444";
                    correoInput.focus();
                }
                return;
            }

            if (mensajeRecuperacion) {
                mensajeRecuperacion.classList.add("d-none");
            }

            // Animación interactiva en botón
            const textoOriginalBtn = btnEnviarEnlace ? btnEnviarEnlace.innerHTML : "";
            if (btnEnviarEnlace) {
                btnEnviarEnlace.disabled = true;
                btnEnviarEnlace.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Enviando...`;
            }

            setTimeout(() => {
                // 1. CREATE: Guardar registro en localStorage y sessionStorage
                const nuevoRegistro = recuperacionService.crear(correo);
                sessionStorage.setItem("recuperacionActualId", nuevoRegistro.id);
                sessionStorage.setItem("recuperacionCorreo", correo);

                // Actualizar tabla visual del CRUD si está presente
                renderizarHistorialCRUD();

                // Restaurar botón
                if (btnEnviarEnlace) {
                    btnEnviarEnlace.disabled = false;
                    btnEnviarEnlace.innerHTML = textoOriginalBtn;
                }

                // 2. Colocar el correo ingresado dentro del texto del Modal
                if (textoCorreoEnviado) {
                    textoCorreoEnviado.textContent = correo;
                }

                // 3. Desplegar Modal Emergente de Bootstrap
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            }, 450);
        });
    }

    // Evento del botón Aceptar del Modal para redirigir
    if (btnAceptarModal) {
        btnAceptarModal.addEventListener("click", () => {
            window.location.href = "/verificacion-correo";
        });
    }

    // ==========================================
    // GESTOR VISUAL DEL CRUD DE SOLICITUDES
    // ==========================================
    function inyectarModalCrudRecuperaciones() {
        if (document.getElementById("modalCrudRecuperaciones")) return;

        const htmlModal = `
            <button type="button"
                    class="btn btn-3d-purple position-fixed bottom-0 end-0 m-3 shadow-lg"
                    style="z-index: 1040; padding: 0.65rem 1.15rem; font-size: 0.8rem;"
                    data-bs-toggle="modal" data-bs-target="#modalCrudRecuperaciones">
                <i class="bi bi-clock-history"></i> Solicitudes (<span id="badgeCountRec">0</span>)
            </button>

            <div class="modal fade" id="modalCrudRecuperaciones" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                        <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #1A0B2E 0%, #52154E 100%);">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-key-fill me-2 text-warning"></i>Historial de Recuperaciones (CRUD)
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body p-4" style="background-color: #F6F1FC;">
                            <div class="bg-white rounded-4 border p-3 shadow-sm">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Correo Solicitante</th>
                                                <th>Estado</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaCrudRecuperaciones"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML("beforeend", htmlModal);

        document.getElementById("tablaCrudRecuperaciones").addEventListener("click", (e) => {
            const btnToggle = e.target.closest(".btn-toggle-verificado");
            const btnEliminar = e.target.closest(".btn-eliminar-rec");
            const btnUsarCorreo = e.target.closest(".btn-usar-rec");

            if (btnToggle) {
                const reg = recuperacionService.obtenerPorId(btnToggle.dataset.id);
                if (reg) {
                    recuperacionService.actualizar(reg.id, { codigoVerificado: !reg.codigoVerificado });
                    renderizarHistorialCRUD();
                }
            }

            if (btnUsarCorreo) {
                const reg = recuperacionService.obtenerPorId(btnUsarCorreo.dataset.id);
                if (reg && correoInput) {
                    correoInput.value = reg.correo;
                    correoInput.dispatchEvent(new Event("input"));
                    const inst = bootstrap.Modal.getInstance(document.getElementById("modalCrudRecuperaciones"));
                    if (inst) inst.hide();
                }
            }

            if (btnEliminar) {
                recuperacionService.eliminar(btnEliminar.dataset.id);
                renderizarHistorialCRUD();
            }
        });

        renderizarHistorialCRUD();
    }

    function renderizarHistorialCRUD() {
        const tbody = document.getElementById("tablaCrudRecuperaciones");
        const badgeCount = document.getElementById("badgeCountRec");
        const lista = recuperacionService.obtenerTodos();

        if (badgeCount) badgeCount.textContent = lista.length;
        if (!tbody) return;

        if (lista.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-3">No hay solicitudes registradas aún.</td></tr>`;
            return;
        }

        tbody.innerHTML = lista.slice().reverse().map(item => {
            const fechaCorta = new Date(item.fecha).toLocaleString("es-MX", { dateStyle: "short", timeStyle: "short" });
            return `
                <tr>
                    <td>
                        <div class="fw-bold" style="color: #1A0B2E;">${item.correo}</div>
                        <div class="text-muted" style="font-size: 0.74rem;">${fechaCorta}</div>
                    </td>
                    <td>
                        <span class="badge rounded-pill ${item.codigoVerificado ? "bg-success-subtle text-success" : "bg-warning-subtle text-warning-emphasis"}">
                            ${item.codigoVerificado ? "Verificado" : "Pendiente"}
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 btn-usar-rec" data-id="${item.id}" title="Rellenar correo">
                            <i class="bi bi-arrow-up-left-circle"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2 btn-toggle-verificado" data-id="${item.id}" title="Cambiar estado (Update)">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-eliminar-rec" data-id="${item.id}" title="Eliminar (Delete)">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join("");
    }

    inyectarModalCrudRecuperaciones();
});