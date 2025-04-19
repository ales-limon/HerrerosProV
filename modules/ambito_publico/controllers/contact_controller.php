<?php
// Prueba para ver si llegamos al controlador
// var_dump("Llegamos al controlador");
// exit;

/**
 * Controlador para procesar el formulario de contacto
 */

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir configuración y conexión a la base de datos
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Crear instancia de la base de datos
$db = new Database();

// Incluir el modelo
require_once __DIR__ . '/../models/contact_model.php';

// Inicializar el modelo con la instancia de Database
$contactModel = new ContactModel($db);

// Función para sanitizar datos
function sanitizar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Función para validar email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para redirigir
function redirectTo($page, $params = []) {
    // Obtener la URL base real (solo hasta HerrerosPro)
    $baseUrl = preg_replace('/\/public\/controllers\/?$/', '', rtrim(BASE_URL, '/'));
    
    // Construir la URL completa
    $url = "{$baseUrl}/public/views/{$page}";
    
    // Añadir parámetros si existen
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    header("Location: {$url}");
    exit;
}

// Inicializar array de errores
$errores = [];

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener IP y User-Agent
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    // Verificar si la IP está bloqueada
    if ($contactModel->verificarIPBloqueada($ip)) {
        redirectTo('contacto.php', ['error' => 3]);
    }

    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $contactModel->registrarSpam($ip, $userAgent, ['error' => 'CSRF token inválido']);
        redirectTo('contacto.php', ['error' => 2]);
    }

    // Verificar honeypot (si está lleno, es probable que sea un bot)
    if (!empty($_POST['honeypot'])) {
        $contactModel->registrarSpam($ip, $userAgent, ['honeypot' => $_POST['honeypot']]);
        redirectTo('contacto.php');
    }

    // Obtener y sanitizar datos
    $nombre = sanitizar($_POST['nombre'] ?? '');
    $email = sanitizar($_POST['email'] ?? '');
    $telefono = sanitizar($_POST['telefono'] ?? '');
    $asunto = sanitizar($_POST['asunto'] ?? '');
    $mensaje = sanitizar($_POST['mensaje'] ?? '');

    // Validaciones
    if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 50) {
        $errores['nombre'] = 'El nombre es requerido y debe tener entre 2 y 50 caracteres.';
    }

    if (empty($email) || !validarEmail($email)) {
        $errores['email'] = 'Por favor ingresa un correo electrónico válido.';
    }

    if (!empty($telefono) && !preg_match('/^[0-9\+\-\(\)\s]{5,20}$/', $telefono)) {
        $errores['telefono'] = 'El formato del teléfono no es válido.';
    }

    if (empty($asunto)) {
        $errores['asunto'] = 'Por favor selecciona un asunto.';
    }

    if (empty($mensaje) || strlen($mensaje) > 2000) {
        $errores['mensaje'] = 'El mensaje es requerido y no debe exceder los 2000 caracteres.';
    }

    if (!isset($_POST['privacidad']) || $_POST['privacidad'] !== 'on') {
        $errores['privacidad'] = 'Debes aceptar la política de privacidad.';
    }

    // Si hay errores, regresar al formulario
    if (!empty($errores)) {
        $_SESSION['form_errors'] = $errores;
        $_SESSION['form_data'] = $_POST;
        redirectTo('contacto.php', ['error' => 1]);
    }

    try {
        // Debug temporal
        $resultado = $contactModel->guardarMensaje($nombre, $email, $asunto, $mensaje, $ip, $userAgent);
        if (!$resultado) {
            error_log("Error al guardar mensaje: " . print_r($db->getError(), true));
        }
        
        // Guardar el mensaje usando el modelo
        if ($resultado) {
            // Éxito - Redirigir con mensaje de éxito
            redirectTo('contacto.php', ['enviado' => 1]);
        } else {
            throw new Exception("Error al guardar el mensaje");
        }
    } catch (Exception $e) {
        // Error - Redirigir con mensaje de error
        $_SESSION['form_data'] = $_POST;
        redirectTo('contacto.php', ['error' => 1]);
    }
} else {
    // Si no es POST, redirigir al formulario
    redirectTo('contacto.php');
}