<?php
/**
 * Layout principal para la parte pública de HerrerosPro
 * Este archivo sirve como plantilla base para todas las páginas públicas
 */

// Verificar si se ha definido el título de la página
if (!isset($page_title)) {
    $page_title = 'Bienvenido';
}

// Verificar si se ha definido la página actual (para el menú activo)
if (!isset($current_page)) {
    $current_page = '';
}

try {
    // Incluir el header
    include_once __DIR__ . '/header.php';

    // Incluir el navbar
    include_once __DIR__ . '/navbar.php';

    // Contenido específico de la página
    if (isset($content_path) && file_exists($content_path)) {
        include_once $content_path;
    } else {
        echo '<div class="container mt-5"><div class="alert alert-danger">Error: Contenido no encontrado</div></div>';
    }

    // Incluir el footer
    include_once __DIR__ . '/footer.php';
} catch (Exception $e) {
    echo "Error en el layout: " . $e->getMessage();
}
?> 