<?php
/**
 * Archivo de ejemplo para demostrar el sistema de layout
 */

// Prevenir acceso directo
define('BASE_URL', true);

// Incluir el archivo de configuración
require_once __DIR__ . '/../../config/config.php';

// Definir el título de la página
$page_title = 'Página de Ejemplo';

// Definir la página actual para el menú
$current_page = 'archivo';

// Definir la ruta al contenido específico
$content_path = __DIR__ . '/archivo_content.php';

// Incluir el layout común
require_once __DIR__ . '/../includes/layout.php';
?> 