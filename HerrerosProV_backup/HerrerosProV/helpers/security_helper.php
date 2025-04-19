<?php
/**
 * Helper de seguridad
 * Contiene funciones relacionadas con la seguridad de la aplicación
 */

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Genera un token CSRF y lo guarda en la sesión
 * @return string Token CSRF generado
 */
function generate_csrf_token() {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

/**
 * Verifica si un token CSRF es válido
 * @param string $token Token CSRF a verificar
 * @return bool True si el token es válido, false en caso contrario
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    
    // Regenerar token después de la verificación para evitar reutilización
    generate_csrf_token();
    
    return $valid;
}

/**
 * Sanitiza una cadena para prevenir XSS
 * @param string $input Cadena a sanitizar
 * @return string Cadena sanitizada
 */
function sanitize_xss($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

/**
 * Valida una dirección de correo electrónico
 * @param string $email Correo electrónico a validar
 * @return bool True si el correo es válido, false en caso contrario
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida un número de teléfono (solo dígitos, entre 10 y 15 caracteres)
 * @param string $phone Número de teléfono a validar
 * @return bool True si el teléfono es válido, false en caso contrario
 */
function validate_phone($phone) {
    return preg_match('/^[0-9]{10,15}$/', $phone) === 1;
}

/**
 * Verifica si una contraseña cumple con los requisitos mínimos
 * @param string $password Contraseña a verificar
 * @return bool True si la contraseña cumple los requisitos, false en caso contrario
 */
function validate_password_strength($password) {
    // Mínimo 8 caracteres
    if (strlen($password) < 8) {
        return false;
    }
    
    // Al menos una letra y un número
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return false;
    }
    
    return true;
}

/**
 * Genera un hash seguro de una contraseña
 * @param string $password Contraseña a hashear
 * @return string Hash de la contraseña
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifica si una contraseña coincide con un hash
 * @param string $password Contraseña a verificar
 * @param string $hash Hash con el que comparar
 * @return bool True si la contraseña coincide, false en caso contrario
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
} 