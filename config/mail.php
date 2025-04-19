<?php
/**
 * Configuración del sistema de correo
 * 
 * Este archivo contiene las configuraciones necesarias para el envío de correos
 * a través de SMTP. Incluye credenciales y configuraciones del servidor.
 */

// Configuración del servidor SMTP (Gmail)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);  // Puerto TLS
define('SMTP_USER', 'herrerospro@gmail.com');  // Reemplazar con tu correo de Gmail
define('SMTP_PASS', 'tu_contraseña_de_aplicacion');  // Usar contraseña de aplicación de Gmail

// Configuración de remitente
define('SMTP_FROM_EMAIL', 'herrerospro@gmail.com');
define('SMTP_FROM_NAME', 'HerrerosPro');

// Correos para notificaciones específicas
define('ADMIN_EMAIL', 'administrador@herrerospro.com');  // Correo para gestión de cuentas
define('SUPPORT_EMAIL', 'soporte@herrerospro.com');  // Correo para soporte técnico

// Configuración de plantillas de correo
define('EMAIL_TEMPLATES_DIR', __DIR__ . '/../templates/email/');

// Tiempo de expiración para tokens (en horas)
define('TOKEN_EXPIRATION_HOURS', 24);
