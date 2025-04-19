<?php
/**
 * Configuración específica para la Plataforma Administrativa
 * @package HerrerosPro
 */

// Verificar que se accede desde common.php
if (!defined('ROOT_PATH')) {
    die('No se permite acceso directo');
}

// Definir ámbito
define('AMBITO_ACTUAL', 'plataforma');

// Rutas específicas de la plataforma
define('PLATAFORMA_VIEWS', PLATAFORMA_PATH . 'views' . DIRECTORY_SEPARATOR);
define('PLATAFORMA_CONTROLLERS', PLATAFORMA_PATH . 'controllers' . DIRECTORY_SEPARATOR);
define('PLATAFORMA_INCLUDES', PLATAFORMA_PATH . 'includes' . DIRECTORY_SEPARATOR);

// Mapeo de roles de la base de datos a roles de la plataforma
define('ROL_ADMIN', 'admin_sistema');
define('ROL_SUPERVISOR', 'gerente');
define('ROL_OPERADOR', 'empleado');

// Configuración de sesión para la plataforma
$session_config = [
    'lifetime' => 3600,
    'path' => '/HerrerosPro/plataforma/',
    'domain' => '',
    'secure' => false,
    'httponly' => true
];

// Configuración de la base de datos específica
$db_tables = [
    'usuarios' => 'usuarios',
    'roles' => 'roles',
    'permisos' => 'permisos'
];
