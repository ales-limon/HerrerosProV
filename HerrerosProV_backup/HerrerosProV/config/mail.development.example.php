<?php
/**
 * Configuración del sistema de correo para DESARROLLO
 * 
 * IMPORTANTE: Copiar este archivo como mail.development.php y actualizar las credenciales
 */

// Configuración del servidor SMTP (Gmail para desarrollo)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);  // Puerto TLS
define('SMTP_USER', 'herrerospro.dev@gmail.com');  // Tu correo de desarrollo
define('SMTP_PASS', 'ikhg ocax odxw bgvf');  // Contraseña de aplicación

// Configuración de remitente
define('SMTP_FROM_EMAIL', 'herrerospro.dev@gmail.com');
define('SMTP_FROM_NAME', 'HerrerosPro (Desarrollo)');

// Correos para notificaciones
define('ADMIN_EMAIL', 'administrador@herrerospro.com');
define('SUPPORT_EMAIL', 'soporte@herrerospro.com');

// Configuración de plantillas
define('EMAIL_TEMPLATES_DIR', __DIR__ . '/../templates/email/');
define('TOKEN_EXPIRATION_HOURS', 24);
