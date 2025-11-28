// ===========================
// MOSTRAR / OCULTAR PASSWORD
// ===========================
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtns = document.querySelectorAll(".toggle-password");
    toggleBtns.forEach(function(btn) {
        btn.addEventListener("click", function() {
            const input = document.getElementById(btn.dataset.target);
            const icon = btn.querySelector("i");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    });

    // ===========================
    // CARGAR USUARIO RECORDADO
    // ===========================
    const savedUser = localStorage.getItem("rememberUser");
    if (savedUser) {
        const userInput = document.getElementById("login-register-login-email");
        if (userInput) {
            userInput.value = savedUser;
            document.getElementById("login-register-remember-me").checked = true;
        }
    }

    // Guardar usuario al enviar el login
    const loginForm = document.querySelector("#login-register-login-form form");
    if (loginForm) {
        loginForm.addEventListener("submit", function(e) {
            const remember = document.getElementById("login-register-remember-me").checked;
            const userInput = document.getElementById("login-register-login-email").value;
            if (remember) {
                localStorage.setItem("rememberUser", userInput);
            } else {
                localStorage.removeItem("rememberUser");
            }
        });
    }
});
