document.addEventListener("DOMContentLoaded", function () {
    const STORAGE_KEY = "shiftly_cuentas_crud";
    const SESSION_KEY = "shiftly_usuario_activo";

    // =========================================================
    // 0. INICIALIZAR DATOS POR DEFECTO (CRUD EN LOCALSTORAGE)
    // =========================================================
    function obtenerCuentas() {
        const data = localStorage.getItem(STORAGE_KEY);
        if (!data) {
            const iniciales = [
                {
                    id: 1,
                    nombre: "Luis David Balandrano",
                    email: "luis.david@shiftly.edu.mx",
                    password: "password123",
                    carrera: "Sistemas y Software"
                },
                {
                    id: 2,
                    nombre: "Estudiante Demo UPP",
                    email: "demo@shiftly.edu.mx",
                    password: "password123",
                    carrera: "Medicina y Salud"
                }
            ];
            localStorage.setItem(STORAGE_KEY, JSON.stringify(iniciales));
            return iniciales;
        }
        return JSON.parse(data);
    }

    function guardarCuentas(lista) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(lista));
    }

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

    // =========================================================
    // 4. INTERFAZ CRUD DE CUENTAS (CREAR, LEER, EDITAR, ELIMINAR)
    // =========================================================
    function inyectarInterfazCRUD() {
        if (document.getElementById("modalCrudCuentas")) return;

        const crudHTML = `
            <!-- Botón Flotante para Administrar Cuentas (CRUD) -->
            <button type="button" id="btnAbrirCrud"
                    class="btn btn-3d-orange position-fixed bottom-0 end-0 m-3 shadow-lg"
                    style="z-index: 1040; padding: 0.7rem 1.2rem; font-size: 0.82rem; background-color: #52154E; box-shadow: 0 4px 0 #1A0B2E;"
                    data-bs-toggle="modal" data-bs-target="#modalCrudCuentas">
                <i class="bi bi-people-fill"></i> Gestionar Cuentas (CRUD)
            </button>

            <!-- Modal CRUD -->
            <div class="modal fade" id="modalCrudCuentas" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                        <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #1A0B2E 0%, #52154E 100%);">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-database-fill-gear me-2 text-warning"></i>Administrador de Cuentas Shiftly
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body p-4" style="background-color: #F6F1FC;">
                            <!-- Formulario Crear / Editar (CREATE & UPDATE) -->
                            <form id="formCrudCuenta" class="p-3 bg-white rounded-4 border mb-4 shadow-sm">
                                <input type="hidden" id="crudId">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold small text-uppercase" id="crudFormTitulo" style="color: #52154E;">
                                        <i class="bi bi-person-plus-fill me-1"></i> Nueva Cuenta
                                    </span>
                                    <button type="button" id="btnCancelarEdicion" class="btn btn-sm btn-outline-secondary rounded-pill d-none">
                                        Cancelar edición
                                    </button>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" id="crudNombre" class="form-control rounded-3" placeholder="Nombre completo" required>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="crudCarrera" class="form-select rounded-3">
                                            <option value="Sistemas y Software">Sistemas y Software</option>
                                            <option value="Medicina y Salud">Medicina y Salud</option>
                                            <option value="Derecho Corporativo">Derecho Corporativo</option>
                                            <option value="Gastronomía">Gastronomía</option>
                                            <option value="Arquitectura">Arquitectura</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" id="crudEmail" class="form-control rounded-3" placeholder="correo@ejemplo.com" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="crudPassword" class="form-control rounded-3" placeholder="Contraseña (mín. 6 caracteres)" minlength="6" required>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" id="btnGuardarCrud" class="btn btn-3d-orange w-100 py-2" style="font-size: 0.85rem;">
                                            <i class="bi bi-save-fill"></i> Guardar Cuenta
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Tabla de Cuentas Registradas (READ & DELETE) -->
                            <div class="bg-white rounded-4 border p-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small" style="color: #1A0B2E;">Cuentas guardadas (<span id="contadorCuentas">0</span>)</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Haz clic en "Usar" para rellenar el login</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Usuario</th>
                                                <th>Carrera</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaCrudCuentas"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML("beforeend", crudHTML);
        enlazarEventosCRUD();
        renderizarTablaCRUD();
    }

    function renderizarTablaCRUD() {
        const tbody = document.getElementById("tablaCrudCuentas");
        const contador = document.getElementById("contadorCuentas");
        if (!tbody) return;

        const cuentas = obtenerCuentas();
        contador.textContent = cuentas.length;

        if (cuentas.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-3">No hay cuentas registradas. Crea una arriba.</td></tr>`;
            return;
        }

        tbody.innerHTML = cuentas.map(c => `
            <tr>
                <td>
                    <div class="fw-bold" style="color: #1A0B2E;">${c.nombre}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">${c.email}</div>
                </td>
                <td><span class="badge rounded-pill" style="background-color: #F3E5F5; color: #52154E;">${c.carrera}</span></td>
                <td class="text-end text-nowrap">
                    <button type="button" class="btn btn-sm btn-success rounded-pill px-2 fw-bold btn-usar-cuenta" data-id="${c.id}" title="Autocompletar Login">
                        <i class="bi bi-box-arrow-in-right"></i> Usar
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2 btn-editar-cuenta" data-id="${c.id}" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-eliminar-cuenta" data-id="${c.id}" title="Eliminar">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `).join("");
    }

    function resetearFormularioCRUD() {
        document.getElementById("formCrudCuenta").reset();
        document.getElementById("crudId").value = "";
        document.getElementById("crudFormTitulo").innerHTML = `<i class="bi bi-person-plus-fill me-1"></i> Nueva Cuenta`;
        document.getElementById("btnGuardarCrud").innerHTML = `<i class="bi bi-save-fill"></i> Guardar Cuenta`;
        document.getElementById("btnCancelarEdicion").classList.add("d-none");
    }

    function enlazarEventosCRUD() {
        const formCrud = document.getElementById("formCrudCuenta");
        const tablaCrud = document.getElementById("tablaCrudCuentas");
        const btnCancelar = document.getElementById("btnCancelarEdicion");

        // CREATE & UPDATE
        formCrud.addEventListener("submit", function (e) {
            e.preventDefault();
            const idEdit = document.getElementById("crudId").value;
            const nombre = document.getElementById("crudNombre").value.trim();
            const carrera = document.getElementById("crudCarrera").value;
            const email = document.getElementById("crudEmail").value.trim().toLowerCase();
            const password = document.getElementById("crudPassword").value;

            let cuentas = obtenerCuentas();

            if (idEdit) {
                // Actualizar existente
                cuentas = cuentas.map(c =>
                    String(c.id) === String(idEdit) ? { ...c, nombre, carrera, email, password } : c
                );
            } else {
                // Crear nuevo (evitar duplicados de email)
                if (cuentas.some(c => c.email === email)) {
                    alert("Ese correo ya está registrado en la lista.");
                    return;
                }
                cuentas.push({
                    id: Date.now(),
                    nombre,
                    carrera,
                    email,
                    password
                });
            }

            guardarCuentas(cuentas);
            resetearFormularioCRUD();
            renderizarTablaCRUD();
        });

        btnCancelar.addEventListener("click", resetearFormularioCRUD);

        // Delegación para USAR, EDITAR y ELIMINAR (DELETE)
        tablaCrud.addEventListener("click", function (e) {
            const btnUsar = e.target.closest(".btn-usar-cuenta");
            const btnEditar = e.target.closest(".btn-editar-cuenta");
            const btnEliminar = e.target.closest(".btn-eliminar-cuenta");
            const cuentas = obtenerCuentas();

            if (btnUsar) {
                const cuenta = cuentas.find(c => String(c.id) === btnUsar.dataset.id);
                if (cuenta && emailInput && passwordInput) {
                    emailInput.value = cuenta.email;
                    passwordInput.value = cuenta.password;
                    marcarCampo(emailInput, true);
                    marcarCampo(passwordInput, true);
                    const modalInst = bootstrap.Modal.getInstance(document.getElementById("modalCrudCuentas"));
                    if (modalInst) modalInst.hide();
                }
            }

            if (btnEditar) {
                const cuenta = cuentas.find(c => String(c.id) === btnEditar.dataset.id);
                if (cuenta) {
                    document.getElementById("crudId").value = cuenta.id;
                    document.getElementById("crudNombre").value = cuenta.nombre;
                    document.getElementById("crudCarrera").value = cuenta.carrera;
                    document.getElementById("crudEmail").value = cuenta.email;
                    document.getElementById("crudPassword").value = cuenta.password;

                    document.getElementById("crudFormTitulo").innerHTML = `<i class="bi bi-pencil-square me-1"></i> Editando a ${cuenta.nombre}`;
                    document.getElementById("btnGuardarCrud").innerHTML = `<i class="bi bi-check-lg"></i> Actualizar Cuenta`;
                    btnCancelar.classList.remove("d-none");
                }
            }

            if (btnEliminar) {
                const filtradas = cuentas.filter(c => String(c.id) !== btnEliminar.dataset.id);
                guardarCuentas(filtradas);
                renderizarTablaCRUD();
            }
        });
    }

    inyectarInterfazCRUD();

    // =========================================================
    // 5. PROCESAMIENTO INTERACTIVO DEL LOGIN + REDIRECCIÓN
    // =========================================================
    const formularioLogin = document.getElementById("formularioLogin") || document.querySelector("form");
    const btnSubmitLogin = document.getElementById("btnSubmitLogin");

    function mostrarAlertaLogin(mensaje, tipo = "danger") {
        let alerta = document.getElementById("alertaDinamicaLogin");
        if (!alerta) {
            alerta = document.createElement("div");
            alerta.id = "alertaDinamicaLogin";
            formularioLogin.insertBefore(alerta, btnSubmitLogin);
        }
        alerta.className = `alert alert-${tipo} rounded-4 py-2 px-3 mb-3 small fw-bold`;
        alerta.innerHTML = `<i class="bi ${tipo === "success" ? "bi-check-circle-fill" : "bi-exclamation-triangle-fill"} me-1"></i> ${mensaje}`;
    }

    if (formularioLogin) {
        formularioLogin.addEventListener("submit", function (e) {
            e.preventDefault();

            const emailVal = emailInput ? emailInput.value.trim().toLowerCase() : "";
            const passVal = passwordInput ? passwordInput.value : "";
            const cuentas = obtenerCuentas();

            // Buscar coincidencia en el CRUD local
            const usuarioValido = cuentas.find(c => c.email === emailVal && c.password === passVal);

            if (!usuarioValido) {
                marcarCampo(emailInput, false);
                marcarCampo(passwordInput, false);
                mostrarAlertaLogin("Correo o contraseña incorrectos. Puedes crear o revisar cuentas en 'Gestionar Cuentas (CRUD)'.");
                return;
            }

            // Guardar sesión activa y animar botón antes de redirigir al index
            localStorage.setItem(SESSION_KEY, JSON.stringify(usuarioValido));

            if (btnSubmitLogin) {
                btnSubmitLogin.disabled = true;
                btnSubmitLogin.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Ingresando...`;
            }

            mostrarAlertaLogin(`¡Bienvenido, ${usuarioValido.nombre}! Redirigiendo...`, "success");

            setTimeout(() => {
                window.location.href = "/";
            }, 700);
        });
    }
});