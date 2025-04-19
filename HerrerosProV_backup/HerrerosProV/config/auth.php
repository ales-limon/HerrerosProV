<?php
/**
 * Configuración de autenticación
 * 
 * Este archivo contiene funciones relacionadas con la autenticación de usuarios.
 * 
 * @package HerrerosPro
 */

// Incluir archivo común si no está incluido
if (!defined('ROOT_PATH')) {
    require_once 'common.php';
}

/**
 * Función para autenticar usuario
 * @param string $email Email del usuario
 * @param string $password Contraseña del usuario
 * @param string $ambito Ámbito de autenticación (plataforma, talleres)
 * @return boolean|array False si falla, array con datos del usuario si es exitoso
 */
function authenticateUser($email, $password, $ambito = 'talleres') {
    $db = new Database();
    
    // Consulta según el ámbito
    if ($ambito === 'plataforma') {
        $db->query("SELECT * FROM admin_users WHERE email = :email AND status = 'active'");
    } else {
        $db->query("SELECT * FROM taller_users WHERE email = :email AND status = 'active'");
    }
    
    $db->bind(':email', $email);
    $user = $db->single();
    
    if (!$user) {
        return false;
    }
    
    // Verificar contraseña
    if (!password_verify($password, $user['password'])) {
        // Registrar intento fallido
        logFailedLoginAttempt($email, $ambito);
        return false;
    }
    
    // Actualizar último acceso
    if ($ambito === 'plataforma') {
        $db->query("UPDATE admin_users SET last_login = NOW(), login_ip = :ip WHERE id = :id");
    } else {
        $db->query("UPDATE taller_users SET last_login = NOW(), login_ip = :ip WHERE id = :id");
    }
    
    $db->bind(':ip', $_SERVER['REMOTE_ADDR']);
    $db->bind(':id', $user['id']);
    $db->execute();
    
    // Registrar acceso exitoso
    logSuccessfulLogin($user['id'], $ambito);
    
    // Cargar permisos del usuario
    loadUserPermissions($user['id']);
    
    return $user;
}

/**
 * Función para iniciar sesión de usuario
 * @param array $user Datos del usuario
 * @param string $ambito Ámbito de autenticación (plataforma, talleres)
 */
function loginUser($user, $ambito = 'talleres') {
    // Iniciar sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Regenerar ID de sesión para prevenir session fixation
    session_regenerate_id(true);
    
    // Guardar datos del usuario en sesión
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_ambito'] = $ambito;
    
    // Si es usuario de taller, guardar ID del taller
    if ($ambito === 'talleres' && isset($user['taller_id'])) {
        $_SESSION['taller_id'] = $user['taller_id'];
    }
    
    // Tiempo de inicio de sesión
    $_SESSION['login_time'] = time();
}

/**
 * Función para cerrar sesión
 */
function logoutUser() {
    // Iniciar sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Registrar cierre de sesión
    if (isset($_SESSION['user_id'])) {
        logActivity('logout', 'Cierre de sesión');
    }
    
    // Destruir todas las variables de sesión
    $_SESSION = [];
    
    // Destruir la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destruir la sesión
    session_destroy();
}

/**
 * Función para verificar si la sesión ha expirado
 * @param int $maxLifetime Tiempo máximo de vida de la sesión en segundos (default: 3600 = 1 hora)
 * @return boolean
 */
function isSessionExpired($maxLifetime = 3600) {
    if (!isset($_SESSION['login_time'])) {
        return true;
    }
    
    return (time() - $_SESSION['login_time']) > $maxLifetime;
}

/**
 * Función para renovar el tiempo de sesión
 */
function renewSession() {
    $_SESSION['login_time'] = time();
}

/**
 * Función para registrar intento fallido de inicio de sesión
 * @param string $email Email utilizado
 * @param string $ambito Ámbito de autenticación (plataforma, talleres)
 */
function logFailedLoginAttempt($email, $ambito = 'talleres') {
    $db = new Database();
    $db->query("INSERT INTO login_attempts (email, ip_address, ambito, status, created_at) VALUES (:email, :ip, :ambito, 'failed', NOW())");
    $db->bind(':email', $email);
    $db->bind(':ip', $_SERVER['REMOTE_ADDR']);
    $db->bind(':ambito', $ambito);
    $db->execute();
}

/**
 * Función para registrar inicio de sesión exitoso
 * @param int $userId ID del usuario
 * @param string $ambito Ámbito de autenticación (plataforma, talleres)
 */
function logSuccessfulLogin($userId, $ambito = 'talleres') {
    $db = new Database();
    $db->query("INSERT INTO login_attempts (user_id, email, ip_address, ambito, status, created_at) VALUES (:user_id, :email, :ip, :ambito, 'success', NOW())");
    $db->bind(':user_id', $userId);
    $db->bind(':email', $_SESSION['user_email'] ?? '');
    $db->bind(':ip', $_SERVER['REMOTE_ADDR']);
    $db->bind(':ambito', $ambito);
    $db->execute();
    
    // Registrar actividad
    logActivity('login', 'Inicio de sesión exitoso');
}

/**
 * Función para verificar si un usuario está bloqueado por intentos fallidos
 * @param string $email Email del usuario
 * @param string $ambito Ámbito de autenticación (plataforma, talleres)
 * @param int $maxAttempts Número máximo de intentos permitidos (default: 5)
 * @param int $lockoutTime Tiempo de bloqueo en segundos (default: 1800 = 30 minutos)
 * @return boolean
 */
function isUserLocked($email, $ambito = 'talleres', $maxAttempts = 5, $lockoutTime = 1800) {
    $db = new Database();
    $db->query("SELECT COUNT(*) as attempts FROM login_attempts 
                WHERE email = :email AND ambito = :ambito AND status = 'failed' 
                AND created_at > DATE_SUB(NOW(), INTERVAL :lockout_time SECOND)");
    $db->bind(':email', $email);
    $db->bind(':ambito', $ambito);
    $db->bind(':lockout_time', $lockoutTime);
    $result = $db->single();
    
    return $result['attempts'] >= $maxAttempts;
}

/**
 * Función para cambiar contraseña
 * @param int $userId ID del usuario
 * @param string $currentPassword Contraseña actual
 * @param string $newPassword Nueva contraseña
 * @param string $ambito Ámbito de autenticación (plataforma, talleres)
 * @return boolean
 */
function changePassword($userId, $currentPassword, $newPassword, $ambito = 'talleres') {
    $db = new Database();
    
    // Obtener usuario según ámbito
    if ($ambito === 'plataforma') {
        $db->query("SELECT * FROM admin_users WHERE id = :id");
    } else {
        $db->query("SELECT * FROM taller_users WHERE id = :id");
    }
    
    $db->bind(':id', $userId);
    $user = $db->single();
    
    if (!$user) {
        return false;
    }
    
    // Verificar contraseña actual
    if (!password_verify($currentPassword, $user['password'])) {
        return false;
    }
    
    // Hashear nueva contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Actualizar contraseña según ámbito
    if ($ambito === 'plataforma') {
        $db->query("UPDATE admin_users SET password = :password, updated_at = NOW() WHERE id = :id");
    } else {
        $db->query("UPDATE taller_users SET password = :password, updated_at = NOW() WHERE id = :id");
    }
    
    $db->bind(':password', $hashedPassword);
    $db->bind(':id', $userId);
    
    if ($db->execute()) {
        // Registrar actividad
        logActivity('change_password', 'Cambio de contraseña');
        return true;
    }
    
    return false;
}

/**
 * Función para generar token de recuperación de contraseña
 * @param string $email Email del usuario
 * @param string $ambito Ámbito de autenticación (plataforma, talleres)
 * @return string|boolean Token generado o false si falla
 */
function generatePasswordResetToken($email, $ambito = 'talleres') {
    $db = new Database();
    
    // Verificar si el email existe según ámbito
    if ($ambito === 'plataforma') {
        $db->query("SELECT id FROM admin_users WHERE email = :email AND status = 'active'");
    } else {
        $db->query("SELECT id FROM taller_users WHERE email = :email AND status = 'active'");
    }
    
    $db->bind(':email', $email);
    $user = $db->single();
    
    if (!$user) {
        return false;
    }
    
    // Generar token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Guardar token en la base de datos
    $db->query("INSERT INTO password_resets (user_id, email, token, ambito, expires_at, created_at) 
                VALUES (:user_id, :email, :token, :ambito, :expires, NOW())");
    $db->bind(':user_id', $user['id']);
    $db->bind(':email', $email);
    $db->bind(':token', $token);
    $db->bind(':ambito', $ambito);
    $db->bind(':expires', $expires);
    
    if ($db->execute()) {
        return $token;
    }
    
    return false;
}

/**
 * Función para verificar token de recuperación de contraseña
 * @param string $token Token a verificar
 * @return array|boolean Datos del token o false si es inválido
 */
function verifyPasswordResetToken($token) {
    $db = new Database();
    $db->query("SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW() AND used = 0");
    $db->bind(':token', $token);
    $result = $db->single();
    
    return $result ? $result : false;
}

/**
 * Función para restablecer contraseña con token
 * @param string $token Token de recuperación
 * @param string $newPassword Nueva contraseña
 * @return boolean
 */
function resetPasswordWithToken($token, $newPassword) {
    $tokenData = verifyPasswordResetToken($token);
    
    if (!$tokenData) {
        return false;
    }
    
    $db = new Database();
    
    // Hashear nueva contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Actualizar contraseña según ámbito
    if ($tokenData['ambito'] === 'plataforma') {
        $db->query("UPDATE admin_users SET password = :password, updated_at = NOW() WHERE id = :id");
    } else {
        $db->query("UPDATE taller_users SET password = :password, updated_at = NOW() WHERE id = :id");
    }
    
    $db->bind(':password', $hashedPassword);
    $db->bind(':id', $tokenData['user_id']);
    
    if (!$db->execute()) {
        return false;
    }
    
    // Marcar token como usado
    $db->query("UPDATE password_resets SET used = 1, used_at = NOW() WHERE token = :token");
    $db->bind(':token', $token);
    
    return $db->execute();
}

/**
 * Función para requerir autenticación
 * @param string $ambito Ámbito requerido (plataforma, talleres)
 * @param string $redirectUrl URL a redireccionar si no está autenticado
 */
function requireAuth($ambito = 'talleres', $redirectUrl = 'login.php') {
    // Iniciar sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Verificar si el usuario está autenticado
    if (!isLoggedIn()) {
        setMessage('warning', 'Debe iniciar sesión para acceder a esta página');
        redirect($redirectUrl);
    }
    
    // Verificar si la sesión ha expirado
    if (isSessionExpired()) {
        logoutUser();
        setMessage('warning', 'Su sesión ha expirado. Por favor, inicie sesión nuevamente');
        redirect($redirectUrl);
    }
    
    // Verificar si el usuario tiene acceso al ámbito requerido
    if ($_SESSION['user_ambito'] !== $ambito) {
        setMessage('error', 'No tiene permiso para acceder a esta sección');
        redirect($redirectUrl);
    }
    
    // Renovar tiempo de sesión
    renewSession();
}

/**
 * Función para requerir rol específico
 * @param string|array $roles Rol o roles permitidos
 * @param string $redirectUrl URL a redireccionar si no tiene el rol requerido
 */
function requireRole($roles, $redirectUrl = 'index.php') {
    // Verificar autenticación primero
    requireAuth();
    
    // Verificar rol
    if (!hasRole($roles)) {
        setMessage('error', 'No tiene permiso para acceder a esta sección');
        redirect($redirectUrl);
    }
}

/**
 * Función para requerir permiso específico
 * @param string $permission Permiso requerido
 * @param string $redirectUrl URL a redireccionar si no tiene el permiso requerido
 */
function requirePermission($permission, $redirectUrl = 'index.php') {
    // Verificar autenticación primero
    requireAuth();
    
    // Verificar permiso
    if (!hasPermission($permission)) {
        setMessage('error', 'No tiene permiso para realizar esta acción');
        redirect($redirectUrl);
    }
}

/**
 * Función para verificar si el usuario es administrador
 * @return boolean
 */
function isAdmin() {
    if (!isset($_SESSION['user_ambito']) || $_SESSION['user_ambito'] !== 'plataforma') {
        return false;
    }
    
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}