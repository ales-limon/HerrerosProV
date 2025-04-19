<?php
/**
 * Script independiente para manejar solicitudes de login vía AJAX
 */

// Desactivar la salida de errores PHP para evitar que se mezclen con JSON
ini_set('display_errors', 0);

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/models/RegistroActividad.php';

// Establecer cabecera JSON
header('Content-Type: application/json; charset=utf-8');

// Función para responder con error
function responderError($mensaje, $codigo = 400) {
    http_response_code($codigo);
    echo json_encode(['success' => false, 'message' => $mensaje]);
    exit;
}

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderError('Método no permitido', 405);
}

// Verificar acción
if (!isset($_POST['action']) || $_POST['action'] !== 'login') {
    responderError('Acción no válida');
}

// Verificar campos requeridos
if (empty($_POST['email']) || empty($_POST['password'])) {
    responderError('Por favor ingresa email y contraseña');
}

try {
    // Obtener instancia de Auth
    $auth = Auth::getInstance();
    
    // Debug: Verificar usuario en base de datos
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM usuarios_plataforma WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();
    error_log("Usuario encontrado: " . ($user ? "Sí" : "No"));
    
    // Intentar login
    if ($auth->login($_POST['email'], $_POST['password'])) {
        // Registrar actividad
        $usuario = $auth->getUser();
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
        responderError('Email o contraseña incorrectos');
    }
} catch (Exception $e) {
    error_log("Error en login_ajax.php: " . $e->getMessage());
    responderError('Error al procesar la solicitud: ' . $e->getMessage(), 500);
}
