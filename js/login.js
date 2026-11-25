const formLogin = document.getElementById("form-login");
const inputUsuario = document.getElementById("usuario");
const inputContrasena = document.getElementById("contrasena");
const btnToggle = document.getElementById("toggle-contrasena");
const iconoAbierto = btnToggle?.querySelector(".icono-ojo-abierto");
const iconoCerrado = btnToggle?.querySelector(".icono-ojo-cerrado");
const btnIniciar = document.getElementById("btn-iniciar");
const mensajeLogin = document.getElementById("mensaje-login");
const enlaceOlvide = document.getElementById("enlace-olvide");

const mostrarMensaje = (texto, tipo = "error") => {
    if (!mensajeLogin) return;
    mensajeLogin.textContent = texto;
    mensajeLogin.className = `login-mensaje ${tipo}`;
    mensajeLogin.hidden = false;
};

const ocultarMensaje = () => {
    if (!mensajeLogin) return;
    mensajeLogin.hidden = true;
};

// Mostrar u ocultar contraseña
if (btnToggle && inputContrasena) {
    btnToggle.addEventListener("click", () => {
        const esPassword = inputContrasena.type === "password";
        inputContrasena.type = esPassword ? "text" : "password";
        btnToggle.setAttribute("aria-pressed", String(esPassword));
        btnToggle.setAttribute("aria-label", esPassword ? "Ocultar contraseña" : "Mostrar contraseña");
        if (iconoAbierto) iconoAbierto.hidden = esPassword;
        if (iconoCerrado) iconoCerrado.hidden = !esPassword;
    });
}

// Mensaje si viene redirigido con error en la URL
const params = new URLSearchParams(window.location.search);
if (params.get("error") === "1") {
    mostrarMensaje("Usuario o contraseña incorrectos.", "error");
} else if (params.get("error") === "campos") {
    mostrarMensaje("Completa tu usuario y contraseña.", "error");
}

// Aviso para "¿Olvidaste tu contraseña?"
if (enlaceOlvide) {
    enlaceOlvide.addEventListener("click", (e) => {
        e.preventDefault();
        mostrarMensaje(
            "Para restablecer tu contraseña, comunícate con la administración de la cafetería escolar.",
            "info"
        );
    });
}

// Envío del formulario vía fetch a auth.php
if (formLogin) {
    formLogin.addEventListener("submit", async (e) => {
        e.preventDefault();
        ocultarMensaje();

        const usuario = inputUsuario.value.trim();
        const contrasena = inputContrasena.value;

        if (!usuario || !contrasena) {
            mostrarMensaje("Completa tu usuario y contraseña.", "error");
            return;
        }

        btnIniciar.disabled = true;
        btnIniciar.textContent = "Verificando...";

        try {
            const datos = new FormData();
            datos.append("usuario", usuario);
            datos.append("contrasena", contrasena);

            const respuesta = await fetch("auth.php", {
                method: "POST",
                headers: { Accept: "application/json" },
                body: datos,
            });

            const resultado = await respuesta.json();

            if (respuesta.ok && resultado.ok && resultado.redirect) {
                window.location.href = resultado.redirect;
                return;
            }

            mostrarMensaje(resultado.mensaje || "Usuario o contraseña incorrectos.", "error");
        } catch {
            mostrarMensaje("No se pudo conectar con el servidor. Verifica que XAMPP esté activo.", "error");
        } finally {
            btnIniciar.disabled = false;
            btnIniciar.textContent = "Iniciar sesión";
        }
    });
}
