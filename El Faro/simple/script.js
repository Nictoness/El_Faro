document.addEventListener("DOMContentLoaded", function () {
    function actualizarReloj() {
        const reloj = document.getElementById("reloj");
        if (!reloj) return;
        const ahora = new Date();
        const opciones = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
        const fecha = ahora.toLocaleDateString("es-CL", opciones);
        const hora = ahora.toLocaleTimeString("es-CL");
        reloj.textContent = `${fecha} - ${hora}`;
    }
    actualizarReloj();
    setInterval(actualizarReloj, 1000);
    
    // Validación de contraseñas en registro
    const registroForm = document.querySelector(".contacto:last-of-type form");
    if (registroForm) {
        registroForm.addEventListener("submit", function (e) {
            const pass = registroForm.querySelector("input[name='reg_password']");
            const confirm = registroForm.querySelector("input[name='reg_confirm']");
            if (pass && confirm && pass.value !== confirm.value) {
                e.preventDefault();
                alert("Las contraseñas no coinciden.");
            }
        });
    }
});