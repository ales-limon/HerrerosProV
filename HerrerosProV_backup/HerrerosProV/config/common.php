<?php
/**
 * Configuración común para HerrerosPro
 * 
 * Este archivo contiene la configuración común para todo el sistema
 * @package HerrerosPro
 */

// Definir constantes de rutas del sistema de archivos
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR);
define('PLATAFORMA_PATH', ROOT_PATH . 'plataforma' . DIRECTORY_SEPARATOR);
define('TALLERES_PATH', ROOT_PATH . 'talleres' . DIRECTORY_SEPARATOR);

// Definir constantes de URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
if (empty($host)) {
    $host = 'localhost'; // fallback al default
}

// Obtener la ruta base del proyecto (HerrerosProV)
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = '';

// Extraer la ruta base correcta independientemente de dónde se ejecute el script
if (strpos($script_name, '/HerrerosProV/') !== false) {
    $base_path = '/HerrerosProV';
} elseif (strpos($script_name, '/HerrerosProV') !== false) {
    $base_path = '/HerrerosProV';
} else {
    // Fallback a la lógica original
    $base_path = dirname($_SERVER['SCRIPT_NAME']);
    $base_path = rtrim($base_path, '/\\');
}

$base_url = $protocol . $host . $base_path . '/';

// Definir rutas base
define('BASE_URL', $base_url);
define('PUBLIC_URL', $base_url);
define('ASSETS_URL', $base_url . 'public/assets/');

// Definir rutas específicas de cada ámbito
define('PLATAFORMA_URL', $base_url . 'plataforma/');
define('TALLERES_URL', $base_url . 'talleres/');

// Definir constantes de sistema
define('SYSTEM_NAME', 'HerrerosPro');
define('SYSTEM_VERSION', '1.0.0');
define('SYSTEM_EMAIL', 'info@herrerospro.com');

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Detectar el ámbito actual basado en la URL
$current_script = $_SERVER['SCRIPT_NAME'];
if (strpos($current_script, '/plataforma/') !== false) {
    require_once CONFIG_PATH . 'plataforma.php';
} elseif (strpos($current_script, '/talleres/') !== false) {
    require_once CONFIG_PATH . 'talleres.php';
}

/**
 * Función para sanitizar inputs
 * @param string $data Datos a sanitizar
 * @return string Datos sanitizados
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Función para generar token CSRF
 * @return string Token CSRF
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Función para verificar token CSRF
 * @param string $token Token a verificar
 * @return boolean
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

/**
 * Función para redireccionar
 * @param string $url URL a redireccionar
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Función para establecer mensaje en sesión
 * @param string $type Tipo de mensaje (success, error, warning, info)
 * @param string $message Mensaje a mostrar
 */
function setMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
}

/**
 * Función para obtener mensaje de sesión
 * @return array|null Mensaje o null si no hay mensaje
 */
function getMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return null;
}

/**
 * Función para mostrar mensaje
 */
function showMessage() {
    $message = getMessage();
    if ($message) {
        echo '<div class="alert alert-' . $message['type'] . ' alert-dismissible fade show" role="alert">';
        echo $message['text'];
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span>';
        echo '</button>';
        echo '</div>';
    }
}

/**
 * Función para verificar si el usuario está autenticado
 * @return boolean
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Función para verificar si el usuario tiene un rol específico
 * @param string|array $roles Rol o roles permitidos
 * @return boolean
 */
function hasRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    
    return in_array($_SESSION['user_role'], $roles);
}

/**
 * Función para formatear fecha
 * @param string $date Fecha a formatear
 * @param string $format Formato deseado (default: d/m/Y)
 * @return string Fecha formateada
 */
function formatDate($date, $format = 'd/m/Y') {
    $dateObj = new DateTime($date);
    return $dateObj->format($format);
}

/**
 * Función para formatear moneda
 * @param float $amount Cantidad a formatear
 * @param string $symbol Símbolo de moneda (default: $)
 * @return string Cantidad formateada
 */
function formatCurrency($amount, $symbol = '$') {
    return $symbol . number_format($amount, 2, '.', ',');
}

/**
 * Función para generar slug
 * @param string $text Texto a convertir en slug
 * @return string Slug generado
 */
function generateSlug($text) {
    // Reemplazar caracteres especiales
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterar
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Eliminar caracteres no deseados
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Eliminar duplicados de -
    $text = preg_replace('~-+~', '-', $text);
    // Convertir a minúsculas
    $text = strtolower($text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

/**
 * Función para registrar actividad del usuario
 * @param string $action Acción realizada
 * @param string $details Detalles adicionales
 */
function logActivity($action, $details = '') {
    if (!isLoggedIn()) {
        return;
    }
    
    $db = new Database();
    $db->query("INSERT INTO activity_logs (user_id, action, details, ip_address, created_at) VALUES (:user_id, :action, :details, :ip, NOW())");
    $db->bind(':user_id', $_SESSION['user_id']);
    $db->bind(':action', $action);
    $db->bind(':details', $details);
    $db->bind(':ip', $_SERVER['REMOTE_ADDR']);
    $db->execute();
}

/**
 * Función para verificar permisos
 * @param string $permission Permiso requerido
 * @return boolean
 */
function hasPermission($permission) {
    if (!isLoggedIn()) {
        return false;
    }
    
    // Si es superadmin, tiene todos los permisos
    if ($_SESSION['user_role'] === 'superadmin') {
        return true;
    }
    
    // Verificar si el usuario tiene el permiso específico
    if (isset($_SESSION['user_permissions']) && is_array($_SESSION['user_permissions'])) {
        return in_array($permission, $_SESSION['user_permissions']);
    }
    
    return false;
}

/**
 * Función para cargar permisos del usuario
 * @param int $userId ID del usuario
 */
function loadUserPermissions($userId) {
    $db = new Database();
    $db->query("SELECT p.permission_name FROM user_permissions up 
                JOIN permissions p ON up.permission_id = p.id 
                WHERE up.user_id = :user_id");
    $db->bind(':user_id', $userId);
    $results = $db->resultSet();
    
    $permissions = [];
    foreach ($results as $row) {
        $permissions[] = $row['permission_name'];
    }
    
    $_SESSION['user_permissions'] = $permissions;
}

/**
 * Función para validar email
 * @param string $email Email a validar
 * @return boolean
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Función para validar teléfono (10 dígitos)
 * @param string $phone Teléfono a validar
 * @return boolean
 */
function isValidPhone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

/**
 * Función para validar RFC
 * @param string $rfc RFC a validar
 * @return boolean
 */
function isValidRFC($rfc) {
    return preg_match('/^([A-ZÑ&]{3,4})(\d{6})([A-Z\d]{3})$/', $rfc);
}

/**
 * Función para validar CURP
 * @param string $curp CURP a validar
 * @return boolean
 */
function isValidCURP($curp) {
    return preg_match('/^([A-Z][AEIOUX][A-Z]{2})(\d{2})(\d{2})(\d{2})([HM])([A-Z]{2})([BCDFGHJKLMNPQRSTVWXYZ]{3})([0-9A-Z])([0-9])$/', $curp);
}

/**
 * Función para validar código postal
 * @param string $cp Código postal a validar
 * @return boolean
 */
function isValidCP($cp) {
    return preg_match('/^[0-9]{5}$/', $cp);
}

/**
 * Función para obtener la extensión de un archivo
 * @param string $filename Nombre del archivo
 * @return string Extensión del archivo
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Función para verificar si una extensión de archivo es permitida
 * @param string $extension Extensión a verificar
 * @param array $allowedExtensions Extensiones permitidas
 * @return boolean
 */
function isAllowedExtension($extension, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']) {
    return in_array(strtolower($extension), $allowedExtensions);
}

/**
 * Función para generar nombre único para archivo
 * @param string $filename Nombre original del archivo
 * @return string Nombre único
 */
function generateUniqueFilename($filename) {
    $extension = getFileExtension($filename);
    return uniqid() . '_' . time() . '.' . $extension;
}

/**
 * Función para obtener la edad a partir de una fecha de nacimiento
 * @param string $birthdate Fecha de nacimiento (formato Y-m-d)
 * @return int Edad
 */
function getAge($birthdate) {
    $birth = new DateTime($birthdate);
    $today = new DateTime('today');
    $age = $birth->diff($today)->y;
    return $age;
}

/**
 * Función para truncar texto
 * @param string $text Texto a truncar
 * @param int $length Longitud máxima
 * @param string $append Texto a agregar al final (default: ...)
 * @return string Texto truncado
 */
function truncateText($text, $length = 100, $append = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    
    return $text . $append;
}

/**
 * Función para generar contraseña aleatoria
 * @param int $length Longitud de la contraseña
 * @return string Contraseña generada
 */
function generateRandomPassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    
    return $password;
}

// Incluir archivo de base de datos
require_once CONFIG_PATH . 'database.php';

// Incluir archivo de configuración de correo electrónico
require_once CONFIG_PATH . 'email.php';

// Incluir PHPMailer
require_once ROOT_PATH . 'PHPMailer/src/Exception.php';
require_once ROOT_PATH . 'PHPMailer/src/PHPMailer.php';
require_once ROOT_PATH . 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Función para enviar correo
 * @param string $to Destinatario
 * @param string $subject Asunto
 * @param string $message Mensaje
 * @param array $attachments Archivos adjuntos
 * @return boolean
 */
function sendEmail($to, $subject, $message, $attachments = []) {
    // Implementar con PHPMailer    
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'user@example.com';
    $mail->Password = 'password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    $mail->setFrom('info@herrerospro.com', 'HerrerosPro');
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->isHTML(true);
    
    // Agregar archivos adjuntos
    foreach ($attachments as $attachment) {
        $mail->addAttachment($attachment);
    }
    
    if (!$mail->send()) {
        error_log('Error al enviar correo: ' . $mail->ErrorInfo);
        return false;
    }
    
    return true;
}