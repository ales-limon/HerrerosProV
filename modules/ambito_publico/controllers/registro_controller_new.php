<?php
/**
 * Controlador de Registro de Talleres (Nueva versión)
 * 
 * Este controlador maneja el proceso de registro inicial de talleres,
 * creando una solicitud que debe ser aprobada por un administrador.
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/environment.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/validation.php';
require_once __DIR__ . '/../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/registro.php");
    exit();
}

// Crear conexión a la base de datos
$db = new Database();
$transactionStarted = false;

// Asegurarnos de que estamos usando la base de datos correcta
$db->query("USE herrerospro_plataforma");
error_log("Base de datos seleccionada en registro_controller_new: herrerospro_plataforma");

// Verificar explícitamente qué base de datos estamos usando
$db->query("SELECT DATABASE() as db_actual");
$db_actual = $db->single();
error_log("Base de datos actual según MySQL en registro_controller_new: " . ($db_actual ? $db_actual['db_actual'] : "No disponible"));

try {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception("Error de validación del formulario");
    }

    // Limpiar y validar datos
    $nombre = sanitizarString($_POST['nombre'] ?? '');
    $apellidos = sanitizarString($_POST['apellidos'] ?? '');
    $nombre_contacto = trim($nombre . ' ' . $apellidos);
    $nombre_taller = sanitizarString($_POST['nombre_taller'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefono = preg_replace('/[^0-9]/', '', $_POST['telefono'] ?? '');
    $direccion = sanitizarString($_POST['direccion'] ?? '');
    $rfc = sanitizarString($_POST['rfc'] ?? '');
    $plan = strtolower(sanitizarString($_POST['plan'] ?? 'basico'));

    // Validar campos requeridos
    $errores = [];
    if (!validarLongitudMinima($nombre, 2)) $errores[] = "El nombre es requerido (mínimo 2 caracteres)";
    if (!validarLongitudMinima($apellidos, 2)) $errores[] = "Los apellidos son requeridos (mínimo 2 caracteres)";
    if (!validarLongitudMinima($nombre_taller, 3)) $errores[] = "El nombre del taller es requerido (mínimo 3 caracteres)";
    if (!validarEmail($email)) $errores[] = "El correo electrónico no es válido";
    if (!validarTelefono($telefono)) $errores[] = "El teléfono debe tener 10 dígitos";
    if (!in_array($plan, ['basico', 'profesional', 'enterprise'])) {
        $errores[] = "Por favor selecciona un plan válido";
    }
    if (!isset($_POST['terminos'])) $errores[] = "Debes aceptar los términos y condiciones";

    if (!empty($errores)) {
        throw new Exception(implode("<br>", $errores));
    }
    
    // Verificar si ya existe una solicitud o taller con ese email
    $db->query("
        SELECT 'solicitud' as tipo FROM solicitudes_talleres WHERE email = ?
        UNION
        SELECT 'taller' as tipo FROM talleres WHERE email = ?
    ");
    $db->bind(1, $email);
    $db->bind(2, $email);
    
    if ($existente = $db->single()) {
        $tipo = $existente['tipo'];
        $mensaje = $tipo === 'solicitud' 
            ? "Ya existe una solicitud pendiente con este correo electrónico."
            : "Este correo electrónico ya está registrado en el sistema.";
        throw new Exception($mensaje);
    }
    
    // Iniciar transacción
    $db->beginTransaction();
    $transactionStarted = true;
    error_log("Transacción iniciada en registro_controller_new");
    
    // Verificar nuevamente qué base de datos estamos usando
    $db->query("SELECT DATABASE() as db_actual");
    $db_actual = $db->single();
    error_log("Base de datos antes de insertar en registro_controller_new: " . ($db_actual ? $db_actual['db_actual'] : "No disponible"));
    
    // Asegurarnos de que estamos usando la base de datos correcta nuevamente
    $db->query("USE herrerospro_plataforma");
    
    // Insertar nueva solicitud
    $db->query("
        INSERT INTO talleres (
            nombre,
            nombre_admin,
            email,
            telefono,
            direccion,
            rfc,
            tipo_plan,
            estado,
            fecha_creacion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente', NOW())
    ");
    
    $db->bind(1, $nombre_taller);
    $db->bind(2, $nombre_contacto);
    $db->bind(3, $email);
    $db->bind(4, $telefono);
    $db->bind(5, $direccion);
    $db->bind(6, $rfc ?: null);
    $db->bind(7, $plan);
    
    if (!$db->execute()) {
        throw new Exception("Error al registrar la solicitud en la base de datos");
    }
    
    $id_solicitud = $db->lastInsertId();
    
    // Confirmar transacción
    $db->commit();
    $transactionStarted = false;
    
    // Enviar notificación al administrador
    try {
        $mail = new PHPMailer(true);
        
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Destinatarios
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress(ADMIN_EMAIL);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Nueva Solicitud de Registro - ' . $nombre_taller;
        $mail->Body = "
            <h2>Nueva Solicitud de Registro</h2>
            <p><strong>ID Solicitud:</strong> {$id_solicitud}</p>
            <p><strong>Taller:</strong> {$nombre_taller}</p>
            <p><strong>Contacto:</strong> {$nombre_contacto}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Teléfono:</strong> {$telefono}</p>
            <p><strong>Plan:</strong> {$plan}</p>
            <p><a href='" . BASE_URL . "/plataforma/solicitudes/ver/{$id_solicitud}'>Ver Solicitud en Plataforma Admin</a></p>
        ";
        
        $mail->send();
        error_log("Correo de notificación enviado exitosamente");
    } catch (Exception $e) {
        error_log("Error al enviar email de notificación: " . $mail->ErrorInfo);
        // No afectamos la transacción por error en el email
    }
    
    // Guardar mensaje de éxito y redirigir
    $_SESSION['success'] = "Tu solicitud ha sido recibida correctamente. En breve nos pondremos en contacto contigo.";
    header("Location: ../views/registro.php");
    exit();
    
} catch (Exception $e) {
    if ($transactionStarted) {
        $db->rollBack();
    }
    
    error_log("Error en registro_controller: " . $e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    $_SESSION['form_data'] = $_POST;
    header("Location: ../views/registro.php");
    exit();
}
