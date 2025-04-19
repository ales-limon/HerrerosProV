<?php
/**
 * Script para probar el envío de correos electrónicos
 */

// Cargar configuraciones
require_once __DIR__ . '/config/common.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/email.php';

// Verificar que las constantes SMTP estén definidas
echo "Verificando configuración SMTP...\n";
echo "SMTP_HOST: " . (defined('SMTP_HOST') ? SMTP_HOST : 'No definido') . "\n";
echo "SMTP_USER: " . (defined('SMTP_USER') ? SMTP_USER : 'No definido') . "\n";
echo "SMTP_PORT: " . (defined('SMTP_PORT') ? SMTP_PORT : 'No definido') . "\n";
echo "SMTP_FROM_EMAIL: " . (defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'No definido') . "\n";
echo "SMTP_FROM_NAME: " . (defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'No definido') . "\n";
echo "ADMIN_EMAIL: " . (defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'No definido') . "\n";

// Crear directorio temp si no existe
$temp_dir = __DIR__ . '/temp';
if (!is_dir($temp_dir)) {
    mkdir($temp_dir, 0755, true);
    echo "Directorio temp creado\n";
}

// Crear datos de prueba
$id_solicitud = 999; // ID de prueba
$email_data = [
    'nombre_taller' => 'Taller de Prueba',
    'propietario' => 'Juan Pérez',
    'email' => 'juan@example.com',
    'telefono' => '1234567890',
    'direccion' => 'Calle Principal #123',
    'plan' => 'Básico',
    'fecha' => date('Y-m-d H:i:s')
];

// Guardar datos en archivo temporal
$email_data_file = $temp_dir . '/email_data_' . $id_solicitud . '.json';
file_put_contents($email_data_file, json_encode($email_data));
echo "Archivo de datos creado: $email_data_file\n";

// Crear directorio de logs si no existe
$log_dir = __DIR__ . '/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
    echo "Directorio logs creado\n";
}

// Ejecutar script de envío de correo
echo "Ejecutando script de envío de correo...\n";
$cmd = 'php ' . __DIR__ . '/public/controllers/send_notification_email.php ' . $id_solicitud;
echo "Comando: $cmd\n";

// Ejecutar el comando
$output = [];
$return_var = 0;
exec($cmd, $output, $return_var);

// Mostrar resultado
echo "Código de salida: $return_var\n";
echo "Salida:\n";
echo implode("\n", $output) . "\n";

// Mostrar contenido del archivo de log
$log_file = __DIR__ . '/logs/email_notification.log';
if (file_exists($log_file)) {
    echo "\nContenido del archivo de log:\n";
    echo file_get_contents($log_file) . "\n";
} else {
    echo "\nEl archivo de log no existe\n";
}
