document.addEventListener("DOMContentLoaded", () => {
    // Selectores del DOM
    const formPerfil = document.getElementById("formularioPerfil");
    const inputNombre = document.getElementById("nombre-perfil");
    const inputApellido = document.getElementById("apellido-perfil");
    const inputEmail = document.getElementById("email-perfil");
    const selectArea = document.getElementById("area-perfil");
    const inputNivel = document.getElementById("nivel-perfil");

    const encabezadoNombre = document.getElementById("encabezadoNombre");
    const encabezadoSubtitulo = document.getElementById("encabezadoSubtitulo");
    const avatarIniciales = document.getElementById("avatarIniciales");
    const ultimaActualizacionTexto = document.getElementById("ultimaActualizacionTexto");
    const alertaPerfil = document.getElementById("alertaPerfil");
    const btnCancelar = document.getElementById("btnCancelarPerfil");
    const btnGuardar = document.getElementById("btnGuardarPerfil");
    const btnRestablecer = document.getElementById("btnRestablecerPerfil");

    // Claves de almacenamiento en localStorage
    const STORAGE_KEY = "shiftly_user_profile_full";
    const SESSION_KEY = "shiftly_usuario_activo";
    const CUENTAS_KEY = "shiftly_cuentas_crud";

    let valoresOriginales = {};

    // --- FUNCIÓN PARA MOSTRAR ALERTAS / FEEDBACK ---
    function mostrarAlerta(mensaje, tipo = "success") {
        if (!alertaPerfil) return;

        const iconos = {
            success: "bi-check-circle-fill",
            warning: "bi-exclamation-triangle-fill",
            info: "bi-info-circle-fill",
            danger: "bi-x-circle-fill"
        };

        alertaPerfil.className = `alert alert-${tipo} fade show mb-4 rounded-4 py-2 px-3 small fw-bold`;
        alertaPerfil.innerHTML = `<i class="bi ${iconos[tipo] || "bi-info-circle-fill"} me-1"></i> ${mensaje}`;
        alertaPerfil.classList.remove("d-none");

        setTimeout(() => {
            alertaPerfil.classList.add("d-none");
        }, 3500);
    }

    // --- 1. LEER (READ): Priorizar sesión activa y sincronizar con perfil guardado ---
    function cargarPerfilLocal() {
        const datosGuardados = JSON.parse(localStorage.getItem(STORAGE_KEY) || "null");
        const sesionActiva = JSON.parse(localStorage.getItem(SESSION_KEY) || "null");

        if (sesionActiva && (!datosGuardados || datosGuardados.email !== sesionActiva.email)) {
            const partes = (sesionActiva.nombre || "Usuario Demo").trim().split(" ");
            const nom = partes[0] || "Usuario";
            const ape = partes.slice(1).join(" ") || "Demo";

            if (inputNombre) inputNombre.value = nom;
            if (inputApellido) inputApellido.value = ape;
            if (inputEmail) inputEmail.value = sesionActiva.email || "usuario@shiftly.com";
            if (selectArea && sesionActiva.carrera) {
                const opcion = Array.from(selectArea.options).find(opt =>
                    opt.value.toLowerCase().includes(sesionActiva.carrera.split(" ")[0].toLowerCase())
                );
                if (opcion) selectArea.value = opcion.value;
            }
            if (inputNivel) inputNivel.value = "B1 Intermedio";
        } else if (datosGuardados) {
            if (inputNombre) inputNombre.value = datosGuardados.nombre || "Usuario";
            if (inputApellido) inputApellido.value = datosGuardados.apellido || "Demo";
            if (inputEmail) inputEmail.value = datosGuardados.email || "usuario@shiftly.com";
            if (selectArea) selectArea.value = datosGuardados.area || "Sistemas y Desarrollo";
            if (inputNivel) inputNivel.value = datosGuardados.nivel || "B1 Intermedio";

            if (datosGuardados.updatedAt && ultimaActualizacionTexto) {
                const fecha = new Date(datosGuardados.updatedAt).toLocaleString("es-MX", {
                    dateStyle: "short",
                    timeStyle: "short"
                });
                ultimaActualizacionTexto.textContent = `Última edición: ${fecha}`;
            }
        } else {
            if (inputNombre) inputNombre.value = "Usuario";
            if (inputApellido) inputApellido.value = "Demo";
            if (inputEmail) inputEmail.value = "usuario@shiftly.com";
            if (selectArea) selectArea.value = "Sistemas y Desarrollo";
            if (inputNivel) inputNivel.value = "B1 Intermedio";
        }

        actualizarEncabezado();
        guardarEstadoOriginal();
    }

    // --- ACTUALIZAR ENCABEZADO SUPERIOR Y AVATAR EN TIEMPO REAL ---
    function actualizarEncabezado() {
        const nom = inputNombre ? inputNombre.value.trim() : "";
        const ape = inputApellido ? inputApellido.value.trim() : "";
        const area = selectArea ? selectArea.value : "Sistemas y Desarrollo";

        if (encabezadoNombre) {
            const nombreCompleto = `${nom} ${ape}`.trim();
            encabezadoNombre.textContent = nombreCompleto || "Usuario Demo";
        }

        if (encabezadoSubtitulo) {
            encabezadoSubtitulo.textContent = `${area} · Estudiante Shiftly`;
        }

        if (avatarIniciales) {
            const i1 = nom ? nom.charAt(0).toUpperCase() : "U";
            const i2 = ape ? ape.charAt(0).toUpperCase() : "D";
            avatarIniciales.textContent = `${i1}${i2}`;
        }
    }

    if (inputNombre) inputNombre.addEventListener("input", actualizarEncabezado);
    if (inputApellido) inputApellido.addEventListener("input", actualizarEncabezado);
    if (selectArea) selectArea.addEventListener("change", actualizarEncabezado);

    // --- GUARDAR ESTADO PARA EL BOTÓN DESCARTAR ---
    function guardarEstadoOriginal() {
        valoresOriginales = {
            nombre: inputNombre ? inputNombre.value : "",
            apellido: inputApellido ? inputApellido.value : "",
            email: inputEmail ? inputEmail.value : "",
            area: selectArea ? selectArea.value : ""
        };
    }

    cargarPerfilLocal();

    // --- 2. CREAR / ACTUALIZAR (CREATE / UPDATE): Guardar en localStorage ---
    if (formPerfil) {
        formPerfil.addEventListener("submit", (evento) => {
            evento.preventDefault();

            const nuevoNombre = inputNombre.value.trim();
            const nuevoApellido = inputApellido.value.trim();
            const nuevoEmail = inputEmail ? inputEmail.value.trim().toLowerCase() : "";

            if (!nuevoNombre || nuevoNombre.length < 2) {
                mostrarAlerta("Por favor, ingresa un nombre válido (mínimo 2 caracteres).", "warning");
                inputNombre.focus();
                return;
            }

            if (!nuevoEmail || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(nuevoEmail)) {
                mostrarAlerta("Ingresa un correo electrónico válido.", "danger");
                inputEmail.focus();
                return;
            }

            const ahora = new Date().toISOString();
            const perfilActualizado = {
                nombre: nuevoNombre,
                apellido: nuevoApellido,
                email: nuevoEmail,
                area: selectArea ? selectArea.value : "Sistemas y Desarrollo",
                nivel: inputNivel ? inputNivel.value : "B1 Intermedio",
                updatedAt: ahora
            };

            if (btnGuardar) {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status"></span> Guardando...`;
            }

            setTimeout(() => {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(perfilActualizado));

                // Sincronizar con sesión activa si existe
                const sesionActiva = JSON.parse(localStorage.getItem(SESSION_KEY) || "null");
                if (sesionActiva) {
                    sesionActiva.nombre = `${nuevoNombre} ${nuevoApellido}`.trim();
                    sesionActiva.email = nuevoEmail;
                    localStorage.setItem(SESSION_KEY, JSON.stringify(sesionActiva));
                }

                // Sincronizar con el CRUD de cuentas si coincide el correo original
                const cuentas = JSON.parse(localStorage.getItem(CUENTAS_KEY) || "[]");
                const idx = cuentas.findIndex(c => c.email === valoresOriginales.email.toLowerCase());
                if (idx !== -1) {
                    cuentas[idx].nombre = `${nuevoNombre} ${nuevoApellido}`.trim();
                    cuentas[idx].email = nuevoEmail;
                    localStorage.setItem(CUENTAS_KEY, JSON.stringify(cuentas));
                }

                actualizarEncabezado();
                guardarEstadoOriginal();

                if (ultimaActualizacionTexto) {
                    const fecha = new Date(ahora).toLocaleString("es-MX", {
                        dateStyle: "short",
                        timeStyle: "short"
                    });
                    ultimaActualizacionTexto.textContent = `Última edición: ${fecha}`;
                }

                if (btnGuardar) {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = `<i class="bi bi-check-lg"></i> Guardar Cambios`;
                }

                mostrarAlerta("¡Los cambios en tu perfil se guardaron correctamente!", "success");
            }, 500);
        });
    }

    // --- 3. REVERTIR (RESTORE): Botón Descartar ---
    if (btnCancelar) {
        btnCancelar.addEventListener("click", () => {
            if (inputNombre) inputNombre.value = valoresOriginales.nombre;
            if (inputApellido) inputApellido.value = valoresOriginales.apellido;
            if (inputEmail) inputEmail.value = valoresOriginales.email;
            if (selectArea) selectArea.value = valoresOriginales.area;

            actualizarEncabezado();
            mostrarAlerta("Se descartaron los cambios no guardados.", "info");
        });
    }

    // --- 4. ELIMINAR / RESTABLECER (DELETE): Limpiar perfil personalizado ---
    if (btnRestablecer) {
        btnRestablecer.addEventListener("click", () => {
            localStorage.removeItem(STORAGE_KEY);
            if (ultimaActualizacionTexto) {
                ultimaActualizacionTexto.textContent = "Edita los datos de tu cuenta";
            }
            cargarPerfilLocal();
            mostrarAlerta("Perfil restablecido a sus valores predeterminados.", "info");
        });
    }
});