<?php
/**
 * Controlador de login público para talleres
 */

require_once __DIR__ . '/../../config/common.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/database.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setMessage('error', 'Método no permitido');
    redirect('../views/login.php');
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    setMessage('error', 'Token de seguridad inválido');
    redirect('../views/login.php');
}

// Obtener datos del formulario
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

// Validar campos
if (empty($email) || empty($password)) {
    setMessage('error', 'Por favor complete todos los campos');
    redirect('../views/login.php');
}

// Validar formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setMessage('error', 'El formato del email no es válido');
    redirect('../views/login.php');
}

try {
    $db = new Database();
    
    // Verificar si el usuario está bloqueado
    if (isUserLocked($email, 'talleres')) {
        setMessage('error', 'Su cuenta está temporalmente bloqueada por múltiples intentos fallidos. Por favor intente más tarde.');
        redirect('../views/login.php');
    }
    
    // Buscar usuario en la base de datos
    $db->query("SELECT * FROM usuarios WHERE email = :email AND estado = 'activo'");
    $db->bind(':email', $email);
    $user = $db->single();
    
    if (!$user || !password_verify($password, $user['password'])) {
        logFailedLoginAttempt($email, 'talleres');
        setMessage('error', 'Credenciales inválidas');
        redirect('../views/login.php');
    }
    
    // Verificar que el taller esté activo
    $db->query("SELECT t.* FROM talleres t 
                INNER JOIN usuarios_taller ut ON t.id_taller = ut.id_taller 
                WHERE ut.id_usuario = :id_usuario AND t.estado = 'activo'");
    $db->bind(':id_usuario', $user['id_usuario']);
    $taller = $db->single();
    
    if (!$taller) {
        setMessage('error', 'No tiene un taller activo asociado');
        redirect('../views/login.php');
    }
    
    // Iniciar sesión
    $_SESSION['user_id'] = $user['id_usuario'];
    $_SESSION['taller_id'] = $taller['id_taller'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['rol'] = $user['rol'];
    $_SESSION['user_ambito'] = 'talleres';
    
    // Registrar acceso exitoso
    logSuccessfulLogin($user['id_usuario'], 'talleres');
    
    // Redirigir al dashboard del taller
    redirect(TALLERES_URL);
    
} catch (Exception $e) {
    error_log("Error en login de talleres: " . $e->getMessage());
    setMessage('error', 'Error al procesar la solicitud');
    redirect('../views/login.php');
}
