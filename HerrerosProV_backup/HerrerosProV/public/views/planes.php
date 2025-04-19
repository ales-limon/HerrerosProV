<?php
/**
 * Página de planes y precios de HerrerosPro
 * Este archivo carga el layout principal con el contenido de la página de planes
 */

// Incluir configuración común
require_once __DIR__ . '/../../config/common.php';

// Definir el título de la página
$page_title = 'Planes y Precios';

// Definir la página actual para el menú
$current_page = 'planes';

// Definir la ruta al contenido específico
$content_path = __DIR__ . '/planes_content.php';

// Verificar si el archivo de contenido existe
if (!file_exists($content_path)) {
    echo "Error: El archivo de contenido no existe: " . $content_path;
    exit;
}

// Incluir el layout principal
require_once __DIR__ . '/../includes/layout.php';
?> 