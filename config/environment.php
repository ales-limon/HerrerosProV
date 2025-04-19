<?php
/**
 * Configuración del ambiente
 */

// Definir el ambiente (development, production)
define('APP_ENV', 'development');

// Cargar configuración de correo según el ambiente
$mail_config = __DIR__ . '/mail.' . APP_ENV . '.php';
if (file_exists($mail_config)) {
    require_once $mail_config;
} else {
    die('Error: Archivo de configuración de correo no encontrado para el ambiente ' . APP_ENV);
}
