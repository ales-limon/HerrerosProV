<?php
/**
 * Archivo de configuración principal del sistema
 */

// Iniciar la sesión si no está ya iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir constante BASEPATH para proteger los archivos contra acceso directo
define('BASEPATH', true);

// Configuración de la zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'herreros_app');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Incluir archivo de rutas
require_once __DIR__ . '/paths.php';

// Configuración del sistema
define('SISTEMA_NOMBRE', 'Herreros Pro');
define('SISTEMA_VERSION', '1.0.0');
define('SISTEMA_CORREO', 'contacto@herrerospro.com');

// Configuración de correo
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'contacto@herrerospro.com');
define('MAIL_PASSWORD', '');
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_ADDRESS', 'contacto@herrerospro.com');
define('MAIL_FROM_NAME', 'Herreros Pro');

// Configuración de seguridad
define('HASH_COST', 10); // Costo del algoritmo de hash para contraseñas

// Configuración para subida de archivos
define('MAX_FILE_SIZE', 5242880); // 5MB en bytes
define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx');
define('UPLOAD_PATH', BASE_PATH . '/uploads');

// Configuración de paginación
define('ITEMS_PER_PAGE', 10);

// Mostrar u ocultar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Funciones de utilidad global
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

function asset($path) {
    return BASE_URL . '/assets/' . $path;
}

// Configuración global para la plataforma

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Configuración de la aplicación
define('APP_NAME', 'HerrerosPro');
define('APP_VERSION', '1.0.0');

// Roles y permisos
define('ROLES', [
    'admin' => 'Administrador',
    'supervisor' => 'Supervisor',
    'capturista' => 'Capturista'
]);

// Estados de solicitudes
define('ESTADOS_SOLICITUD', [
    'pendiente' => [
        'nombre' => 'Pendiente',
        'clase_css' => 'badge-warning'
    ],
    'aprobada' => [
        'nombre' => 'Aprobada',
        'clase_css' => 'badge-success'
    ],
    'rechazada' => [
        'nombre' => 'Rechazada',
        'clase_css' => 'badge-danger'
    ]
]);

// Rutas del sistema
define('CONTROLLERS_PATH', dirname(__DIR__) . '/controllers');
define('MODELS_PATH', dirname(__DIR__) . '/models');
define('VIEWS_PATH', dirname(__DIR__) . '/views');
define('INCLUDES_PATH', dirname(__DIR__) . '/includes');
define('UPLOADS_PATH', dirname(__DIR__) . '/uploads'); 