<?php
/**
 * Archivo de depuración para revisar constantes y variables
 */

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Mostrar información de depuración
header('Content-Type: text/plain');

echo "==== CONSTANTES ====\n";
echo "BASE_URL: " . BASE_URL . "\n";
echo "ASSETS_URL: " . (defined('ASSETS_URL') ? ASSETS_URL : 'No definido') . "\n";
echo "APP_PATH: " . APP_PATH . "\n";
echo "ROOT_PATH: " . ROOT_PATH . "\n";

echo "\n==== RUTAS DE ARCHIVOS ====\n";
echo "views/layouts/main.php existe: " . (file_exists(__DIR__ . '/views/layouts/main.php') ? 'Sí' : 'No') . "\n";
echo "views/dashboard/index.php existe: " . (file_exists(__DIR__ . '/views/dashboard/index.php') ? 'Sí' : 'No') . "\n";

echo "\n==== RUTAS A RECURSOS ====\n";
echo "Ruta a CSS: " . BASE_URL . "public/assets/adminlte/css/adminlte.min.css\n";
echo "Archivo existe: " . (file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url(BASE_URL, PHP_URL_PATH) . 'public/assets/adminlte/css/adminlte.min.css') ? 'Sí' : 'No') . "\n";

echo "\n==== VARIABLES DE SESIÓN ====\n";
echo "SESSION: " . print_r($_SESSION, true) . "\n";

echo "\n==== SERVER VARIABLES ====\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";

die("Debug completado"); 