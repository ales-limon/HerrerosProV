<?php
/**
 * Archivo de prueba para header y footer
 */

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir configuración común
require_once __DIR__ . '/config/common.php';

// Definir título y página actual
$page_title = 'Prueba Header y Footer';
$current_page = 'prueba';

try {
    // Incluir el header
    include_once PUBLIC_PATH . 'includes/header.php';
    
    // Contenido simple
    echo '<div class="container mt-5">';
    echo '<h1>Prueba de Header y Footer</h1>';
    echo '<p>Esta es una página de prueba para verificar si el header y el footer se cargan correctamente.</p>';
    echo '</div>';
    
    // Incluir el footer
    include_once PUBLIC_PATH . 'includes/footer.php';
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 