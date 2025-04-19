<?php
/**
 * Archivo de depuración
 */

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir configuración común
require_once __DIR__ . '/config/common.php';

// Mostrar información de depuración
echo "<h1>Depuración de HerrerosPro</h1>";
echo "<p>Verificando rutas y configuraciones...</p>";

// Verificar constantes de rutas
echo "<h2>Constantes de rutas:</h2>";
echo "<ul>";
echo "<li>ROOT_PATH: " . ROOT_PATH . "</li>";
echo "<li>CONFIG_PATH: " . CONFIG_PATH . "</li>";
echo "<li>PUBLIC_PATH: " . PUBLIC_PATH . "</li>";
echo "<li>PLATAFORMA_PATH: " . PLATAFORMA_PATH . "</li>";
echo "<li>TALLERES_PATH: " . TALLERES_PATH . "</li>";
echo "</ul>";

// Verificar constantes de URL
echo "<h2>Constantes de URL:</h2>";
echo "<ul>";
echo "<li>BASE_URL: " . BASE_URL . "</li>";
echo "<li>PUBLIC_URL: " . PUBLIC_URL . "</li>";
echo "<li>PLATAFORMA_URL: " . PLATAFORMA_URL . "</li>";
echo "<li>TALLERES_URL: " . TALLERES_URL . "</li>";
echo "<li>ASSETS_URL: " . ASSETS_URL . "</li>";
echo "</ul>";

// Verificar si existe el archivo index.php en public/views
echo "<h2>Verificación de archivos:</h2>";
echo "<ul>";
echo "<li>public/views/index.php: " . (file_exists(PUBLIC_PATH . 'views/index.php') ? 'Existe' : 'No existe') . "</li>";
echo "<li>public/views/content/index_content.php: " . (file_exists(PUBLIC_PATH . 'views/content/index_content.php') ? 'Existe' : 'No existe') . "</li>";
echo "<li>public/includes/layout.php: " . (file_exists(PUBLIC_PATH . 'includes/layout.php') ? 'Existe' : 'No existe') . "</li>";
echo "<li>public/includes/header.php: " . (file_exists(PUBLIC_PATH . 'includes/header.php') ? 'Existe' : 'No existe') . "</li>";
echo "<li>public/includes/navbar.php: " . (file_exists(PUBLIC_PATH . 'includes/navbar.php') ? 'Existe' : 'No existe') . "</li>";
echo "<li>public/includes/footer.php: " . (file_exists(PUBLIC_PATH . 'includes/footer.php') ? 'Existe' : 'No existe') . "</li>";
echo "</ul>";

// Intentar cargar la página principal
echo "<h2>Intentando cargar la página principal:</h2>";
try {
    echo "<p>Cargando index.php...</p>";
    ob_start();
    include PUBLIC_PATH . 'views/index.php';
    ob_end_clean();
    echo "<p style='color:green'>¡Éxito! La página se cargó correctamente.</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}

// Verificar la sesión
echo "<h2>Información de sesión:</h2>";
echo "<ul>";
echo "<li>session_status(): " . session_status() . " (1=disabled, 2=enabled but no session, 3=active)</li>";
echo "<li>session_id(): " . session_id() . "</li>";
echo "<li>isLoggedIn(): " . (function_exists('isLoggedIn') ? (isLoggedIn() ? 'true' : 'false') : 'Función no definida') . "</li>";
echo "</ul>";

// Verificar la base de datos
echo "<h2>Información de base de datos:</h2>";
try {
    $db = new Database();
    echo "<p style='color:green'>Conexión a la base de datos establecida correctamente.</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
}

echo "<p>Fin de la depuración.</p>";
?> 