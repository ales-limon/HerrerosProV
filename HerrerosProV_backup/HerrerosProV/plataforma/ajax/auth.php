<?php
/**
 * Controlador AJAX para autenticación
 * Este archivo maneja las solicitudes AJAX relacionadas con la autenticación
 */

// Primero, desactivamos la salida de errores para evitar que se mezclen con JSON
error_reporting(0);
ini_set('display_errors', 0);

// Establecer cabecera JSON desde el principio
header('Content-Type: application/json; charset=utf-8');

// Función para responder con error en formato JSON
function responderError($mensaje, $codigo = 400) {
    http_response_code($codigo);
    echo json_encode(['success' => false, 'message' => $mensaje]);
    exit;
}

// Manejador de errores personalizado para devolver JSON en caso de error
set_error_handler(function($errno, $errstr) {
    error_log("PHP Error en auth.php: [$errno] $errstr");
    responderError('Error interno del servidor', 500);
});

// Manejador de excepciones para devolver JSON en caso de excepción
set_exception_handler(function($exception) {
    error_log("Excepción en auth.php: " . $exception->getMessage());
    responderError('Error interno del servidor: ' . $exception->getMessage(), 500);
});

try {
    // Cargar configuración
    $configFile = __DIR__ . '/../config/config.php';
    if (!file_exists($configFile)) {
        responderError('Archivo de configuración no encontrado', 500);
    }
    require_once $configFile;
    
    // Cargar dependencias
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../config/auth.php';
    require_once __DIR__ . '/../models/RegistroActividad.php';
    
    // Verificar método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        responderError('Método no permitido', 405);
    }
    
    // Verificar acción
    if (!isset($_POST['action'])) {
        responderError('Acción no especificada');
    }
    
    // Procesar según la acción
    switch ($_POST['action']) {
        case 'login':
            // Verificar campos requeridos
            if (empty($_POST['email']) || empty($_POST['password'])) {
                responderError('Por favor ingresa email y contraseña');
            }
            
            // Obtener instancia de Auth
            $auth = Auth::getInstance();
            
            // Intentar login
            if ($auth->login($_POST['email'], $_POST['password'])) {
                // Login exitoso
                $usuario = $auth->getUser();
                
                // Registrar actividad
                $registroActividad = new RegistroActividad();
                $registroActividad->registrar(
                    $usuario['nombre'],
                    "Inició sesión en el sistema",
                    "login",
                    $usuario['id']
                );
                
                // Responder éxito
                echo json_encode([
                    'success' => true,
                    'redirect' => rtrim(BASE_URL, '/') . '/?page=dashboard',
                    'message' => 'Inicio de sesión exitoso'
                ]);
            } else {
                // Login fallido
                responderError('Email o contraseña incorrectos');
            }
            break;
            
        default:
            responderError('Acción no válida');
            break;
    }
} catch (Exception $e) {
    // Este catch no debería ejecutarse debido al manejador de excepciones,
    // pero lo incluimos como precaución adicional
    error_log("Error no capturado en auth.php: " . $e->getMessage());
    responderError('Error al procesar la solicitud', 500);
}
