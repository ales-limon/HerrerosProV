<?php
/**
 * Login de la Plataforma Admin
 * 
 * Página de inicio de sesión para administradores, supervisores y capturistas
 * según MEMORY[0c7884a9]
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';

// Si ya está autenticado, redirigir al dashboard
$auth = Auth::getInstance();
if ($auth->isAuthenticated()) {
    header('Location: ' . BASE_URL . '?page=dashboard');
    exit;
}

// Generar token CSRF
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

// Definir variables para la plantilla
$pageTitle = 'Iniciar Sesión';
$extraScripts = '
<script>
$(document).ready(function() {
    $("#loginForm").on("submit", function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find("button[type=\"submit\"]");
        const $message = $("#loginMessage");
        
        // Deshabilitar botón y mostrar spinner
        $button.prop("disabled", true).html("<i class=\"fas fa-spinner fa-spin\"></i> Procesando...");
        $message.hide();
        
        const formData = {
            action: "login",
            email: $form.find("input[name=\"email\"]").val(),
            password: $form.find("input[name=\"password\"]").val(),
            csrf_token: $form.find("input[name=\"csrf_token\"]").val()
        };
        
        $.ajax({
            url: "' . BASE_URL . 'auth_ajax.php",
            type: "POST",
            data: formData,
            dataType: "json",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect || "' . BASE_URL . '?page=dashboard";
                } else {
                    $message
                        .removeClass("text-success")
                        .addClass("text-danger")
                        .html("<i class=\"fas fa-exclamation-circle\"></i> " + (response.message || "Error al iniciar sesión"))
                        .show();
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = "Error de conexión";
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response && response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error("Error al parsear respuesta:", e);
                }
                
                $message
                    .removeClass("text-success")
                    .addClass("text-danger")
                    .html("<i class=\"fas fa-exclamation-circle\"></i> " + errorMessage)
                    .show();
            },
            complete: function() {
                // Restaurar botón
                $button.prop("disabled", false).text("Iniciar Sesión");
            }
        });
    });
});
</script>';

// Contenido de la página
ob_start();
?>

<p class="login-box-msg">Inicia sesión para acceder al panel</p>

<form id="loginForm" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION[CSRF_TOKEN_NAME] ?>">
    <div class="input-group mb-3">
        <input type="email" 
               name="email" 
               class="form-control" 
               placeholder="Email" 
               required 
               autocomplete="username">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="password" 
               name="password" 
               class="form-control" 
               placeholder="Contraseña" 
               required 
               autocomplete="current-password">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </div>
    </div>
</form>

<div id="loginMessage" class="mt-3 text-center" style="display: none;"></div>

<?php
// Obtener contenido del buffer
$content = ob_get_clean();

// Incluir el layout simple
require_once __DIR__ . '/layouts/simple.php';
