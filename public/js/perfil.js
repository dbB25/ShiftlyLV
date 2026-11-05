document.addEventListener("DOMContentLoaded", () => {

    const formPerfil = document.getElementById("formularioPerfil");
    const inputNombre = document.getElementById("nombre-perfil");
    const inputApellido = document.getElementById("apellido-perfil");
    const selectArea = document.getElementById("area-perfil");
    const encabezadoNombre = document.getElementById("encabezadoNombre");
    const alertaPerfil = document.getElementById("alertaPerfil");
    const btnCancelar = document.getElementById("btnCancelarPerfil");
    const btnGuardar = document.getElementById("btnGuardarPerfil");

    let valoresOriginales = {
        nombre: inputNombre.value,
        apellido: inputApellido.value,
        area: selectArea.value
    };

    function mostrarAlerta(mensaje, tipo) {
        if (!alertaPerfil) return;

        alertaPerfil.className = `alert alert-${tipo} mt-3`;
        alertaPerfil.textContent = mensaje;
    }

    if (formPerfil) {
        formPerfil.addEventListener("submit", (evento) => {

            const nuevoNombre = inputNombre.value.trim();

            if (!nuevoNombre || nuevoNombre.length < 2) {
                evento.preventDefault();
                mostrarAlerta("Ingresa un nombre válido.", "warning");
                return;
            }

            if (btnGuardar) {
                btnGuardar.disabled = true;
                btnGuardar.textContent = "Guardando...";
            }
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener("click", () => {
            inputNombre.value = valoresOriginales.nombre;
            inputApellido.value = valoresOriginales.apellido;
            selectArea.value = valoresOriginales.area;
        });
    }

});