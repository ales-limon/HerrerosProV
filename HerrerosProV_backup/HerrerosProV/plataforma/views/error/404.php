<?php
/**
 * Página de error 404
 */

// Definir variables para la plantilla
$pageTitle = 'Error 404';
$currentPage = 'error';
$extraStyles = '';
$extraScripts = '';

// Iniciar buffer de salida
ob_start();
?>

<div class="error-page">
    <h2 class="headline text-warning"> 404</h2>

    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> ¡Oops! Página no encontrada.</h3>

        <p>
            No pudimos encontrar la página que estabas buscando.
            Mientras tanto, puedes <a href="<?= BASE_URL ?>plataforma/">regresar al dashboard</a>.
        </p>
    </div>
</div>

<?php
// Capturar el contenido
$content = ob_get_clean();

// Incluir la plantilla principal
include __DIR__ . '/../../views/layouts/main.php';
?> 