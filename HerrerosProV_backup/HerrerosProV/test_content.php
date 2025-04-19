<?php
/**
 * Archivo de prueba para el contenido
 */

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir configuración común
require_once __DIR__ . '/config/common.php';

try {
    // Incluir el contenido
    include_once PUBLIC_PATH . 'views/content/index_content.php';
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 