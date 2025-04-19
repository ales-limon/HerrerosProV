<?php
/**
 * Archivo de configuración para las rutas del sistema
 * Este archivo define las constantes de rutas utilizadas en todo el sistema
 */

// Prevenir acceso directo al archivo
if (!defined('BASEPATH')) {
    define('BASEPATH', true);
}

// Detectar si estamos en localhost o en producción
$base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$base_url .= $_SERVER['HTTP_HOST'];

// Si estamos en localhost, añadir la carpeta del proyecto
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
    // Obtener la ruta relativa al directorio raíz del servidor web
    $script_name = dirname($_SERVER['SCRIPT_NAME']);
    $base_url .= str_replace('\\', '/', $script_name);
    
    // Eliminar '/plataforma' si está al final de la URL
    $base_url = rtrim($base_url, '/');
    if (substr($base_url, -11) == '/plataforma') {
        $base_url = substr($base_url, 0, strlen($base_url) - 11);
    }
}

// Definir la constante BASE_URL sin slash al final
if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim($base_url, '/'));
}

// Definir la ruta base del sistema (ruta del servidor)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim(dirname(dirname(__FILE__)), '/'));
}

// Rutas de las diferentes secciones del sistema
if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', BASE_PATH . '/views');
}
if (!defined('INCLUDES_PATH')) {
    define('INCLUDES_PATH', VIEWS_PATH . '/includes');
}
if (!defined('MODELS_PATH')) {
    define('MODELS_PATH', BASE_PATH . '/models');
}
if (!defined('CONTROLLERS_PATH')) {
    define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
}
if (!defined('ASSETS_PATH')) {
    define('ASSETS_PATH', BASE_PATH . '/assets');
}
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', BASE_PATH . '/config');
}

// URLs para assets
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', BASE_URL . '/assets');
}
if (!defined('CSS_URL')) {
    define('CSS_URL', ASSETS_URL . '/css');
}
if (!defined('JS_URL')) {
    define('JS_URL', ASSETS_URL . '/js');
}
if (!defined('IMG_URL')) {
    define('IMG_URL', ASSETS_URL . '/img');
} 