<?php
/**
 * Funciones de validación para HerrerosPro
 * 
 * Este archivo contiene funciones de validación comunes utilizadas en todo el sistema.
 */

/**
 * Valida un email
 * @param string $email Email a validar
 * @return bool True si es válido, false si no
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida un teléfono (formato mexicano)
 * @param string $telefono Teléfono a validar
 * @return bool True si es válido, false si no
 */
function validarTelefono($telefono) {
    // Eliminar espacios y caracteres especiales
    $telefono = preg_replace('/[^0-9]/', '', $telefono);
    // Validar longitud (10 dígitos para México)
    return strlen($telefono) === 10;
}

/**
 * Valida un RFC
 * @param string $rfc RFC a validar
 * @return bool True si es válido, false si no
 */
function validarRFC($rfc) {
    $pattern = '/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/';
    return preg_match($pattern, $rfc) === 1;
}

/**
 * Sanitiza una cadena para prevenir XSS
 * @param string $str Cadena a sanitizar
 * @return string Cadena sanitizada
 */
function sanitizarString($str) {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida un plan
 * @param string $plan Plan a validar
 * @return bool True si es válido, false si no
 */
function validarPlan($plan) {
    return in_array($plan, ['basico', 'profesional', 'enterprise']);
}

/**
 * Valida que una cadena no esté vacía y tenga una longitud mínima
 * @param string $str Cadena a validar
 * @param int $minLength Longitud mínima (por defecto 3)
 * @return bool True si es válida, false si no
 */
function validarLongitudMinima($str, $minLength = 3) {
    $str = trim($str);
    return !empty($str) && strlen($str) >= $minLength;
}

/**
 * Genera un token seguro
 * @param int $length Longitud del token
 * @return string Token generado
 */
function generarToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Valida un token CSRF
 * @param string $token Token a validar
 * @return bool True si es válido, false si no
 */
function validarCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitiza y valida los datos de un formulario
 * @param array $data Datos a validar
 * @param array $rules Reglas de validación
 * @return array Array con errores (vacío si no hay errores)
 */
function validarFormulario($data, $rules) {
    $errores = [];
    
    foreach ($rules as $campo => $reglas) {
        if (!isset($data[$campo]) && $reglas['required']) {
            $errores[] = "El campo {$reglas['nombre']} es requerido";
            continue;
        }
        
        $valor = isset($data[$campo]) ? trim($data[$campo]) : '';
        
        if (isset($reglas['minLength']) && strlen($valor) < $reglas['minLength']) {
            $errores[] = "{$reglas['nombre']} debe tener al menos {$reglas['minLength']} caracteres";
        }
        
        if (isset($reglas['maxLength']) && strlen($valor) > $reglas['maxLength']) {
            $errores[] = "{$reglas['nombre']} no puede tener más de {$reglas['maxLength']} caracteres";
        }
        
        if (isset($reglas['pattern']) && !preg_match($reglas['pattern'], $valor)) {
            $errores[] = "{$reglas['nombre']} no tiene un formato válido";
        }
    }
    
    return $errores;
}
