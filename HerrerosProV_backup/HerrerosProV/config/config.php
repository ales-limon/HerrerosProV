<?php
/**
 * Configuración básica
 * 
 * Este archivo contiene constantes básicas utilizadas en todo el sistema.
 */

// Definir constantes de rutas del sistema de archivos
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR);

// Definir constantes de URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$base_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($base_path, '/\\');
$base_url = $protocol . $host . $base_path . '/';

define('BASE_URL', $base_url);
define('PUBLIC_URL', $base_url);
define('ASSETS_URL', $base_url . 'public/assets/');

// Definir constantes de sistema
define('SYSTEM_NAME', 'HerrerosPro');
define('SYSTEM_VERSION', '1.0.0');
define('SYSTEM_EMAIL', 'info@herrerospro.com');

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Función para sanitizar inputs
 * @param string $data Datos a sanitizar
 * @return string Datos sanitizados
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Función para generar token CSRF
 * @return string Token CSRF
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Función para verificar token CSRF
 * @param string $token Token a verificar
 * @return boolean
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Incluir otros archivos de configuración si es necesario
if (file_exists(CONFIG_PATH . 'database.php')) {
    require_once CONFIG_PATH . 'database.php';
}

// Incluir helpers
if (file_exists(ROOT_PATH . 'helpers/security_helper.php')) {
    require_once ROOT_PATH . 'helpers/security_helper.php';
}

if (file_exists(ROOT_PATH . 'helpers/logger_helper.php')) {
    require_once ROOT_PATH . 'helpers/logger_helper.php';
} 