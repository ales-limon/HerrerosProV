<?php
/**
 * Versión simplificada de la página principal
 */

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir configuración común
require_once __DIR__ . '/config/common.php';

// Definir título y página actual
$page_title = 'Inicio';
$current_page = 'inicio';

// Incluir el header
include_once PUBLIC_PATH . 'includes/header.php';

// Contenido simplificado
echo '<div class="container mt-5">';
echo '<h1>Bienvenido a HerrerosPro</h1>';
echo '<p>Esta es una versión simplificada de la página principal.</p>';
echo '</div>';

// Incluir el footer
include_once PUBLIC_PATH . 'includes/footer.php';
?> 