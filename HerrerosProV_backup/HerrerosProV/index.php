<?php
/**
 * Punto de entrada principal de HerrerosPro
 * 
 * Este archivo es el punto de entrada único al sistema.
 * Incluye la configuración común y redirecciona según el rol del usuario.
 */

// Incluir configuración común
require_once __DIR__ . '/config/common.php';

// Si el usuario está autenticado, redirigir según su rol
if (isLoggedIn()) {
    if (isset($_SESSION['user_ambito']) && $_SESSION['user_ambito'] === 'plataforma') {
        redirect(PLATAFORMA_URL);
    } else {
        redirect(TALLERES_URL);
    }
}

// Si no está autenticado, mostrar la página principal pública
try {
    // Incluir la página principal
    require_once PUBLIC_PATH . 'views/index.php';
} catch (Exception $e) {
    echo "Error al cargar la página principal: " . $e->getMessage();
}
?> 