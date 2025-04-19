<?php
/**
 * Archivo de prueba
 */

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Información básica del servidor
echo "<h1>Información de diagnóstico</h1>";
echo "<p>Versión de PHP: " . phpversion() . "</p>";
echo "<p>Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Directorio actual: " . __DIR__ . "</p>";

// Verificar si podemos acceder a los archivos principales
echo "<h2>Verificación de archivos</h2>";
echo "<ul>";

// Verificar config/common.php
if (file_exists(__DIR__ . '/config/common.php')) {
    echo "<li style='color:green'>El archivo config/common.php existe</li>";
} else {
    echo "<li style='color:red'>El archivo config/common.php NO existe</li>";
}

// Verificar public/views/index.php
if (file_exists(__DIR__ . '/public/views/index.php')) {
    echo "<li style='color:green'>El archivo public/views/index.php existe</li>";
} else {
    echo "<li style='color:red'>El archivo public/views/index.php NO existe</li>";
}

// Verificar public/views/index_content.php
if (file_exists(__DIR__ . '/public/views/index_content.php')) {
    echo "<li style='color:green'>El archivo public/views/index_content.php existe</li>";
} else {
    echo "<li style='color:red'>El archivo public/views/index_content.php NO existe</li>";
}

// Verificar public/includes/layout.php
if (file_exists(__DIR__ . '/public/includes/layout.php')) {
    echo "<li style='color:green'>El archivo public/includes/layout.php existe</li>";
} else {
    echo "<li style='color:red'>El archivo public/includes/layout.php NO existe</li>";
}

echo "</ul>";

// Intentar cargar config/common.php de forma segura
echo "<h2>Prueba de carga de archivos</h2>";
try {
    echo "<p>Intentando cargar config/common.php...</p>";
    include_once __DIR__ . '/config/common.php';
    echo "<p style='color:green'>¡Éxito! config/common.php se cargó correctamente</p>";
    
    // Verificar si las constantes están definidas
    echo "<h3>Constantes definidas:</h3>";
    echo "<ul>";
    if (defined('ROOT_PATH')) echo "<li>ROOT_PATH: " . ROOT_PATH . "</li>";
    if (defined('PUBLIC_PATH')) echo "<li>PUBLIC_PATH: " . PUBLIC_PATH . "</li>";
    if (defined('PUBLIC_URL')) echo "<li>PUBLIC_URL: " . PUBLIC_URL . "</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error al cargar config/common.php: " . $e->getMessage() . "</p>";
}

// Mostrar errores PHP
echo "<h2>Errores PHP recientes</h2>";
$error_log = ini_get('error_log');
echo "<p>Archivo de log de errores: " . ($error_log ? $error_log : "No configurado") . "</p>";

// Intentar mostrar los últimos errores del log si es posible
if ($error_log && file_exists($error_log)) {
    echo "<pre>";
    echo htmlspecialchars(shell_exec("tail -n 20 " . escapeshellarg($error_log)));
    echo "</pre>";
} else {
    echo "<p>No se puede acceder al archivo de log de errores.</p>";
}
?> 