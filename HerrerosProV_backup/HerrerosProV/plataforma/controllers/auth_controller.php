<?php
/**
 * Controlador de Autenticación
 * Maneja el login y logout según MEMORY[0c7884a9]
 */

// Verificar si es una solicitud de logout desde el router
if (isset($route) && isset($route['action']) && $route['action'] === 'logout') {
    // Obtener instancia de Auth
    $auth = Auth::getInstance();
    
    // Cerrar sesión
    $auth->logout();
    
    // Redireccionar al login
    header('Location: ' . BASE_URL . '?page=login');
    exit;
}

// Definir la ruta base si no está definida
if (!defined('BASE_URL')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

// Cargar dependencias
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

// Asegurarnos de que es una petición POST para login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Asegurar que la respuesta sea JSON para peticiones POST
        header('Content-Type: application/json; charset=utf-8');

        // Desactivar la salida de errores PHP para evitar que se mezclen con JSON
        ini_set('display_errors', 0);
        error_reporting(0);

        // Manejar errores para que devuelvan JSON
        set_error_handler(function($errno, $errstr) {
            error_log("PHP Error: [$errno] $errstr");
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
            exit;
        });
        
        // Verificar que sea una acción de login
        if (!isset($_POST['action']) || $_POST['action'] !== 'login') {
            error_log("Error: Acción no válida");
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            exit;
        }
        
        // Validar campos requeridos
        if (empty($_POST['email']) || empty($_POST['password'])) {
            error_log("Error: Campos requeridos faltantes");
            echo json_encode(['success' => false, 'message' => 'Por favor ingresa email y contraseña']);
            exit;
        }
        
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
                'redirect' => dirname(dirname($_SERVER['PHP_SELF'])) . '/?page=dashboard',
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
            'message' => 'Error al procesar la solicitud'
        ]);
    }
    exit;
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Manejar solicitud de logout directa
    $auth = Auth::getInstance();
    
    // Registrar actividad de cierre de sesión
    $usuario = $auth->getUser();
    if ($usuario) {
        $registroActividad = new RegistroActividad();
        $registroActividad->registrar(
            $usuario['nombre'],
            "Cerró sesión en el sistema",
            "logout",
            $usuario['id']
        );
    }
    
    $auth->logout();
    header('Location: ' . BASE_URL . '?page=login');
    exit;
} else {
    // Para cualquier otra solicitud no válida
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}
