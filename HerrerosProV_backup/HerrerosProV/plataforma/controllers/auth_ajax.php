<?php
/**
 * Controlador de Autenticación para solicitudes AJAX
 * Maneja el login según MEMORY[0c7884a9]
 */

// Definir la ruta base si no está definida
if (!defined('BASE_URL')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

// Cargar dependencias
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/RegistroActividad.php';

// Asegurar que la respuesta sea JSON para todas las peticiones
header('Content-Type: application/json; charset=utf-8');

// Manejar errores para que devuelvan JSON
set_error_handler(function($errno, $errstr) {
    error_log("PHP Error: [$errno] $errstr");
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
    exit;
});

// Asegurarnos de que es una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Verificar que sea una acción de login
if (!isset($_POST['action']) || $_POST['action'] !== 'login') {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    exit;
}

// Validar campos requeridos
if (empty($_POST['email']) || empty($_POST['password'])) {
    echo json_encode(['success' => false, 'message' => 'Por favor ingresa email y contraseña']);
    exit;
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
    if ($user) {
        error_log("Hash almacenado: " . $user['password']);
        error_log("Estado del usuario: " . $user['estado']);
        error_log("Rol del usuario: " . $user['rol']);
    }
    
    // Intentar login
    if ($auth->login($_POST['email'], $_POST['password'])) {
        error_log("Login exitoso para " . $_POST['email']);
        
        // Registrar actividad de inicio de sesión
        $usuario = $auth->getUser();
        $registroActividad = new RegistroActividad();
        $registroActividad->registrar(
            $usuario['nombre'],
            "Inició sesión en el sistema",
            "login",
            $usuario['id']
        );
        
        echo json_encode([
            'success' => true, 
            'redirect' => rtrim(BASE_URL, '/') . '/?page=dashboard',
            'message' => 'Inicio de sesión exitoso'
        ]);
    } else {
        error_log("Login fallido para " . $_POST['email']);
        echo json_encode([
            'success' => false, 
            'message' => 'Email o contraseña incorrectos'
        ]);
    }
} catch (Exception $e) {
    error_log("Error en login: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
    ]);
}
