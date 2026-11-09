document.addEventListener("DOMContentLoaded", () => {
    // Selectores del DOM
    const formPerfil = document.getElementById("formularioPerfil");
    const inputNombre = document.getElementById("nombre-perfil");
    const inputApellido = document.getElementById("apellido-perfil");
    const inputEmail = document.getElementById("email-perfil");
    const selectArea = document.getElementById("area-perfil");
    const inputNivel = document.getElementById("nivel-perfil");
    
    const encabezadoNombre = document.getElementById("encabezadoNombre");
    const alertaPerfil = document.getElementById("alertaPerfil");
    const btnCancelar = document.getElementById("btnCancelarPerfil");
    const btnGuardar = document.getElementById("btnGuardarPerfil");

    // Clave de almacenamiento en localStorage
    const STORAGE_KEY = "shiftly_user_profile_full";

    let valoresOriginales = {};

    // --- FUNCIÓN PARA MOSTRAR ALERTAS / FEEDBACK ---
    function mostrarAlerta(mensaje, tipo = "success") {
        if (!alertaPerfil) return;

        alertaPerfil.className = `alert alert-${tipo} alert-dismissible fade show mt-3`;
        alertaPerfil.textContent = mensaje;
        alertaPerfil.classList.remove("d-none");

        setTimeout(() => {
            alertaPerfil.classList.add("d-none");
        }, 3500);
    }

    // --- 1. LEER (READ): Cargar datos de localStorage o asignar valores base ---
    function cargarPerfilLocal() {
        const datosGuardados = localStorage.getItem(STORAGE_KEY);

        if (datosGuardados) {
            const perfil = JSON.parse(datosGuardados);

            if (inputNombre) inputNombre.value = perfil.nombre || "Usuario";
            if (inputApellido) inputApellido.value = perfil.apellido || "Demo";
            if (inputEmail) inputEmail.value = perfil.email || "usuario@shiftly.com";
            if (selectArea) selectArea.value = perfil.area || "Sistemas y Desarrollo";
            if (inputNivel) inputNivel.value = perfil.nivel || "B1 Intermedio";
        } else {
            // Valores por defecto iniciales
            if (inputNombre) inputNombre.value = "Usuario";
            if (inputApellido) inputApellido.value = "Demo";
            if (inputEmail) inputEmail.value = "usuario@shiftly.com";
            if (selectArea) selectArea.value = "Sistemas y Desarrollo";
            if (inputNivel) inputNivel.value = "B1 Intermedio";
        }

        actualizarEncabezado();
        guardarEstadoOriginal();
    }

    // --- ACTUALIZAR ENCABEZADO SUPERIOR ---
    function actualizarEncabezado() {
        if (encabezadoNombre && inputNombre && inputApellido) {
            const nombreCompleto = `${inputNombre.value.trim()} ${inputApellido.value.trim()}`.trim();
            encabezadoNombre.textContent = nombreCompleto || "Usuario Demo";
        }
    }

    // --- GUARDAR ESTADO PARA EL BOTÓN DESCARTAR ---
    function guardarEstadoOriginal() {
        valoresOriginales = {
            nombre: inputNombre ? inputNombre.value : "",
            apellido: inputApellido ? inputApellido.value : "",
            area: selectArea ? selectArea.value : ""
        };
    }

    // Cargar perfil al iniciar
    cargarPerfilLocal();

    // --- 2. CREAR / ACTUALIZAR (CREATE / UPDATE): Guardar en localStorage ---
    if (formPerfil) {
        formPerfil.addEventListener("submit", (evento) => {
            evento.preventDefault(); // Previene la recarga del navegador

            const nuevoNombre = inputNombre.value.trim();
            const nuevoApellido = inputApellido.value.trim();

            if (!nuevoNombre || nuevoNombre.length < 2) {
                mostrarAlerta("Por favor, ingresa un nombre válido (mínimo 2 caracteres).", "warning");
                inputNombre.focus();
                return;
            }

            // Crear objeto de perfil
            const perfilActualizado = {
                nombre: nuevoNombre,
                apellido: nuevoApellido,
                email: inputEmail ? inputEmail.value : "usuario@shiftly.com",
                area: selectArea ? selectArea.value : "Sistemas y Desarrollo",
                nivel: inputNivel ? inputNivel.value : "B1 Intermedio",
                updatedAt: new Date().toISOString()
            };

            // Simulación visual en el botón de guardar
            if (btnGuardar) {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status"></span> Guardando...`;
            }

            setTimeout(() => {
                // Persistencia en localStorage
                localStorage.getItem(STORAGE_KEY);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(perfilActualizado));

                // Actualizar interfaz y estado interno
                actualizarEncabezado();
                guardarEstadoOriginal();

                if (btnGuardar) {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = `<i class="bi bi-check-lg me-1"></i> Guardar Cambios`;
                }

                mostrarAlerta("¡Los cambios en tu perfil se guardaron correctamente!", "success");
            }, 600);
        });
    }

    // --- 3. REVERTIR (RESTORE): Botón Descartar ---
    if (btnCancelar) {
        btnCancelar.addEventListener("click", () => {
            if (inputNombre) inputNombre.value = valoresOriginales.nombre;
            if (inputApellido) inputApellido.value = valoresOriginales.apellido;
            if (selectArea) selectArea.value = valoresOriginales.area;

            actualizarEncabezado();
            mostrarAlerta("Se descartaron los cambios no guardados.", "info");
        });
    }
});