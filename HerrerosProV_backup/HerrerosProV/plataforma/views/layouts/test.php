<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo '<h1>Prueba de página</h1>';
echo '<p>Si puedes ver esto, el servidor PHP está funcionando correctamente.</p>';

// Prueba de variables de sesión
session_start();
echo '<h2>Información de sesión:</h2>';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// Prueba de constantes definidas
echo '<h2>Constantes definidas:</h2>';
echo '<p>BASE_URL: ' . (defined('BASE_URL') ? BASE_URL : 'no definida') . '</p>';
echo '<p>ROOT_PATH: ' . (defined('ROOT_PATH') ? ROOT_PATH : 'no definida') . '</p>';

// Prueba de rutas
echo '<h2>Información de rutas:</h2>';
echo '<p>__DIR__: ' . __DIR__ . '</p>';
echo '<p>Ruta a includes: ' . __DIR__ . '/../includes/' . '</p>';
echo '<p>document_root: ' . $_SERVER['DOCUMENT_ROOT'] . '</p>';

// Verificar si los archivos existen
echo '<h2>Verificación de archivos:</h2>';
$headerFile = __DIR__ . '/../includes/header.php';
$navbarFile = __DIR__ . '/../includes/navbar.php';
$sidebarFile = __DIR__ . '/../includes/sidebar.php';
$footerFile = __DIR__ . '/../includes/footer.php';

echo '<p>header.php: ' . (file_exists($headerFile) ? 'existe' : 'no existe') . '</p>';
echo '<p>navbar.php: ' . (file_exists($navbarFile) ? 'existe' : 'no existe') . '</p>';
echo '<p>sidebar.php: ' . (file_exists($sidebarFile) ? 'existe' : 'no existe') . '</p>';
echo '<p>footer.php: ' . (file_exists($footerFile) ? 'existe' : 'no existe') . '</p>';
?> 