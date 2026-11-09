document.addEventListener('DOMContentLoaded', function () {
    // SELECTORES DEL FORMULARIO Y VISTA PREVIA
    const inputNombre = document.getElementById('username') || document.getElementById('nombreUsuario');
    const inputCorreo = document.getElementById('email') || document.getElementById('correoUsuario');
    const formularioPerfil = document.getElementById('formPerfil');
    const btnEliminar = document.getElementById('btnEliminarPerfil');

    const displayUsername = document.getElementById('displayUsername');
    const displayEmail = document.getElementById('displayEmail');

    // Toast (si existe en tu HTML) y Modal emergente
    const toastElemento = document.getElementById('toastNotificacion');
    const toastBootstrap = toastElemento && typeof bootstrap !== 'undefined' ? new bootstrap.Toast(toastElemento) : null;

    const STORAGE_KEY = 'shiftly_user_profile';

    // --- 1. LEER (READ): Cargar datos desde localStorage al iniciar ---
    function cargarDatosPerfil() {
        const datosGuardados = localStorage.getItem(STORAGE_KEY);

        if (datosGuardados) {
            const perfil = JSON.parse(datosGuardados);
            if (inputNombre) inputNombre.value = perfil.username || '';
            if (inputCorreo) inputCorreo.value = perfil.email || '';

            if (displayUsername) displayUsername.textContent = perfil.username || 'Usuario';
            if (displayEmail) displayEmail.textContent = perfil.email || 'ejemplo@correo.com';
        } else {
            // Valores demostrativos por defecto
            if (inputNombre) inputNombre.value = 'Usuario Demo';
            if (inputCorreo) inputCorreo.value = 'usuario@shiftly.com';

            if (displayUsername) displayUsername.textContent = 'Usuario Demo';
            if (displayEmail) displayEmail.textContent = 'usuario@shiftly.com';
        }
    }

    cargarDatosPerfil();

    // --- FUNCIONES DE VALIDACIÓN ORIGINALES ---
    function validarNombre() {
        if (!inputNombre) return false;
        const valor = inputNombre.value.trim();
        if (valor.length >= 3) {
            inputNombre.classList.remove('is-invalid');
            inputNombre.classList.add('is-valid');
            return true;
        } else {
            inputNombre.classList.remove('is-valid');
            inputNombre.classList.add('is-invalid');
            return false;
        }
    }

    function validarCorreo() {
        if (!inputCorreo) return false;
        const valor = inputCorreo.value.trim();
        const patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (patronCorreo.test(valor)) {
            inputCorreo.classList.remove('is-invalid');
            inputCorreo.classList.add('is-valid');
            return true;
        } else {
            inputCorreo.classList.remove('is-valid');
            inputCorreo.classList.add('is-invalid');
            return false;
        }
    }

    // --- 2. CREAR / ACTUALIZAR (CREATE / UPDATE): Guardar y notificar ---
    function manejarEnvioFormulario(evento) {
        evento.preventDefault();

        const esNombreValido = validarNombre();
        const esCorreoValido = validarCorreo();

        if (esNombreValido && esCorreoValido) {
            const usernameVal = inputNombre.value.trim();
            const emailVal = inputCorreo.value.trim();

            // Guardar en localStorage
            const perfilActualizado = {
                username: usernameVal,
                email: emailVal,
                updatedAt: new Date().toISOString()
            };

            localStorage.setItem(STORAGE_KEY, JSON.stringify(perfilActualizado));

            // Actualizar vista previa dinámica
            if (displayUsername) displayUsername.textContent = usernameVal;
            if (displayEmail) displayEmail.textContent = emailVal;

            // Mostrar Toast si existe
            if (toastBootstrap) {
                toastBootstrap.show();
            }

            // Desplegar Modal emergente "Los cambios se guardaron correctamente"
            const modalElement = document.getElementById('modalCambiosGuardados');
            if (modalElement && typeof bootstrap !== 'undefined') {
                const modalExito = new bootstrap.Modal(modalElement);
                modalExito.show();
            }
        }
    }

    // --- 3. ELIMINAR (DELETE): Limpiar perfil ---
    if (btnEliminar) {
        btnEliminar.addEventListener('click', function () {
            if (confirm('¿Estás seguro de que deseas eliminar la información de tu perfil local?')) {
                localStorage.removeItem(STORAGE_KEY);

                if (inputNombre) {
                    inputNombre.value = '';
                    inputNombre.classList.remove('is-valid', 'is-invalid');
                }
                if (inputCorreo) {
                    inputCorreo.value = '';
                    inputCorreo.classList.remove('is-valid', 'is-invalid');
                }

                if (displayUsername) displayUsername.textContent = 'Sin Nombre';
                if (displayEmail) displayEmail.textContent = 'Sin Correo';
            }
        });
    }

    // EVENT LISTENERS DE VALIDACIÓN E INTERACCIÓN
    if (inputNombre) {
        inputNombre.addEventListener('input', validarNombre);
        inputNombre.addEventListener('blur', validarNombre);
    }

    if (inputCorreo) {
        inputCorreo.addEventListener('input', validarCorreo);
        inputCorreo.addEventListener('blur', validarCorreo);
    }

    if (formularioPerfil) {
        formularioPerfil.addEventListener('submit', manejarEnvioFormulario);
    }
});