<?php
/**
 * Configuración de correo electrónico para HerrerosPro
 * 
 * Este archivo contiene la configuración para el envío de correos electrónicos
 * @package HerrerosPro
 */

// Cargar configuración específica del entorno si existe
$env_config_file = __DIR__ . '/mail.development.php';
if (file_exists($env_config_file)) {
    include $env_config_file;
} else {
    // Configuración por defecto si no existe el archivo de desarrollo
    define('SMTP_HOST', 'smtp.gmail.com');
    define('SMTP_PORT', 587);
    define('SMTP_SECURE', 'tls');
    define('SMTP_AUTH', true);
    define('SMTP_USER', 'notificaciones@herrerospro.com');
    define('SMTP_PASS', 'contraseña_segura'); // Reemplazar con la contraseña real
    define('SMTP_FROM_EMAIL', 'notificaciones@herrerospro.com');
    define('SMTP_FROM_NAME', 'HerrerosPro - Notificaciones');
    define('ADMIN_EMAIL', 'admin@herrerospro.com'); // Reemplazar con el correo del administrador real
    define('SUPPORT_EMAIL', 'info@herrerospro.com');
    define('EMAIL_TEMPLATES_DIR', __DIR__ . '/../templates/email/');
    define('TOKEN_EXPIRATION_HOURS', 24);
}

// Configuración de notificaciones
define('NOTIFY_NEW_WORKSHOP', true); // Notificar nuevas solicitudes de talleres
define('NOTIFY_APPROVED_WORKSHOP', true); // Notificar talleres aprobados
define('NOTIFY_REJECTED_WORKSHOP', true); // Notificar talleres rechazados
