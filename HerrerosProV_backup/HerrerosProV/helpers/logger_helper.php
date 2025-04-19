<?php
/**
 * Helper de logging
 * Contiene funciones para registrar eventos en logs
 */

// Definir constantes para los tipos de logs si no están definidas
if (!defined('LOG_INFO')) define('LOG_INFO', 'INFO');
if (!defined('LOG_WARNING')) define('LOG_WARNING', 'WARNING');
if (!defined('LOG_ERROR')) define('LOG_ERROR', 'ERROR');
if (!defined('LOG_SECURITY')) define('LOG_SECURITY', 'SECURITY');

/**
 * Registra un mensaje en el log general
 * @param string $level Nivel del log (INFO, WARNING, ERROR)
 * @param string $message Mensaje a registrar
 * @return bool True si se registró correctamente, false en caso contrario
 */
function log_message($level, $message) {
    // Obtener la ruta del directorio de logs
    $log_dir = dirname(__DIR__) . '/logs';
    
    // Crear el directorio si no existe
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Nombre del archivo de log (uno por día)
    $log_file = $log_dir . '/app_' . date('Y-m-d') . '.log';
    
    // Formatear el mensaje
    $log_entry = '[' . date('Y-m-d H:i:s') . '] [' . $level . '] ' . $message . PHP_EOL;
    
    // Escribir en el archivo
    return file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX) !== false;
}

/**
 * Registra un evento de seguridad
 * @param string $event_type Tipo de evento (LOGIN_ATTEMPT, LOGIN_SUCCESS, etc.)
 * @param string $description Descripción del evento
 * @param string $ip_address Dirección IP desde donde se originó el evento
 * @param string $user_id ID del usuario (opcional)
 * @return bool True si se registró correctamente, false en caso contrario
 */
function log_security_event($event_type, $description, $ip_address, $user_id = null) {
    // Obtener la ruta del directorio de logs
    $log_dir = dirname(__DIR__) . '/logs';
    
    // Crear el directorio si no existe
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Nombre del archivo de log de seguridad
    $log_file = $log_dir . '/security_' . date('Y-m-d') . '.log';
    
    // Formatear el mensaje
    $log_entry = sprintf(
        '[%s] [%s] [IP: %s] [User: %s] %s%s',
        date('Y-m-d H:i:s'),
        $event_type,
        $ip_address,
        $user_id ?? 'N/A',
        $description,
        PHP_EOL
    );
    
    // Escribir en el archivo
    return file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX) !== false;
}

/**
 * Registra un error de la aplicación
 * @param string $error_message Mensaje de error
 * @param string $file Archivo donde ocurrió el error
 * @param int $line Línea donde ocurrió el error
 * @return bool True si se registró correctamente, false en caso contrario
 */
function log_error($error_message, $file = null, $line = null) {
    $context = '';
    if ($file !== null && $line !== null) {
        $context = " in {$file} on line {$line}";
    }
    
    return log_message(LOG_ERROR, $error_message . $context);
}

/**
 * Registra una acción del usuario
 * @param string $user_id ID del usuario
 * @param string $action Acción realizada
 * @param string $details Detalles adicionales (opcional)
 * @return bool True si se registró correctamente, false en caso contrario
 */
function log_user_action($user_id, $action, $details = null) {
    $message = "User {$user_id} performed action: {$action}";
    if ($details !== null) {
        $message .= " - Details: {$details}";
    }
    
    return log_message(LOG_INFO, $message);
} 