// Mostrar u ocultar la contraseña
const botonMostrar = document.getElementById("mostrarPassword");
const password = document.getElementById("password");
const iconoPassword = document.getElementById("iconoPassword");

if (botonMostrar && password && iconoPassword) {

    botonMostrar.addEventListener("click", function () {

        const mostrar = password.type === "password";

        password.type = mostrar ? "text" : "password";

        iconoPassword.className = mostrar
            ? "bi bi-eye-slash"
            : "bi bi-eye";

        botonMostrar.setAttribute(
            "aria-label",
            mostrar ? "Ocultar contraseña" : "Mostrar contraseña"
        );

        botonMostrar.setAttribute("aria-pressed", String(mostrar));
    });
}
