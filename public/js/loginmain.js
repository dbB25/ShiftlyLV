document.addEventListener("DOMContentLoaded", () => {

    const formLogin = document.getElementById("formularioLogin");
    const inputEmail = document.getElementById("email");
    const inputPassword = document.getElementById("password");
    const btnSubmit = document.getElementById("btnSubmitLogin");

    // Validar los campos antes de enviar
    if (formLogin) {
        formLogin.addEventListener("submit", (evento) => {

            const correo = inputEmail.value.trim();
            const password = inputPassword.value.trim();

            if (!correo || !password) {
                evento.preventDefault();
                alert("Por favor, llena todos los campos.");
                return;
            }

            // Permitir que Laravel reciba el formulario
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.textContent = "Iniciando sesión...";
            }
        });
    }

});