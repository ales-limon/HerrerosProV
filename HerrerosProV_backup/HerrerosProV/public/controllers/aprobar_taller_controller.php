<?php
/**
 * Controlador para aprobar talleres y enviar correo de activación
 */

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/environment.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar que sea una solicitud POST y que el usuario esté autenticado como administrador
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['admin_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Error de validación del formulario";
    header("Location: ../views/solicitudes.php");
    exit();
}

// Obtener el ID del taller
$id_taller = filter_var($_POST['id_taller'] ?? 0, FILTER_VALIDATE_INT);
if (!$id_taller) {
    $_SESSION['error'] = "ID de taller inválido";
    header("Location: ../views/solicitudes.php");
    exit();
}

// Crear conexión a la base de datos
$db = new Database();
$transactionStarted = false;

try {
    // Obtener información del taller
    $db->query("SELECT nombre, nombre_admin, email, estado FROM talleres WHERE id_taller = ?");
    $db->bind(1, $id_taller);
    $taller = $db->single();
    
    if (!$taller) {
        throw new Exception("Taller no encontrado");
    }
    
    if ($taller['estado'] !== 'pendiente') {
        throw new Exception("Este taller ya ha sido procesado anteriormente");
    }
    
    // Iniciar transacción
    $db->beginTransaction();
    $transactionStarted = true;
    
    // Generar token único
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    // Guardar token
    $db->query("INSERT INTO activation_tokens (id_taller, token, expires_at) VALUES (?, ?, ?)");
    $db->bind(1, $id_taller);
    $db->bind(2, $token);
    $db->bind(3, $expires_at);
    
    if (!$db->execute()) {
        throw new Exception("Error al generar el token de activación");
    }
    
    // Actualizar estado del taller
    $db->query("UPDATE talleres SET estado = 'aprobado' WHERE id_taller = ?");
    $db->bind(1, $id_taller);
    
    if (!$db->execute()) {
        throw new Exception("Error al actualizar el estado del taller");
    }
    
    // Confirmar transacción
    $db->commit();
    $transactionStarted = false;
    
    // Enviar correo de activación
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
        
        // Debug SMTP solo en archivo de log
        $mail->SMTPDebug = 3;
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP DEBUG: " . $str);
        };
        
        // Destinatarios
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($taller['email'], $taller['nombre_admin']);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Activación de cuenta - ' . $taller['nombre'];
        
        // URL de activación
        $activation_url = 'https://herrerospro.com/activar.php?token=' . $token;
        
        $mail->Body = "
            <h2>¡Felicidades! Tu solicitud ha sido aprobada</h2>
            <p>Estimado(a) {$taller['nombre_admin']},</p>
            <p>Nos complace informarte que tu solicitud para registrar el taller <strong>{$taller['nombre']}</strong> 
            en HerrerosPro ha sido aprobada.</p>
            
            <p>Para completar el proceso de registro y comenzar a utilizar la plataforma, 
            por favor haz clic en el siguiente enlace:</p>
            
            <p><a href='{$activation_url}'>Activar mi cuenta</a></p>
            
            <p><strong>IMPORTANTE:</strong> Este enlace es válido solo por 24 horas.</p>
            
            <p>Si el enlace no funciona, copia y pega esta dirección en tu navegador:</p>
            <p>{$activation_url}</p>
            
            <p>Una vez que actives tu cuenta, podrás acceder a todas las funcionalidades 
            de HerrerosPro y comenzar a gestionar tu taller de manera más eficiente.</p>
            
            <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>
            
            <p>¡Bienvenido a HerrerosPro!</p>
            
            <hr>
            <p><small>Este es un correo automático, por favor no respondas a esta dirección.</small></p>
        ";
        
        $mail->send();
        error_log("Correo de activación enviado exitosamente a " . $taller['email']);
        
        $_SESSION['success'] = "Taller aprobado y correo de activación enviado exitosamente.";
    } catch (Exception $e) {
        error_log("Error al enviar email de activación: " . $mail->ErrorInfo);
        $_SESSION['warning'] = "Taller aprobado pero hubo un error al enviar el correo de activación.";
    }
    
    header("Location: ../views/solicitudes.php");
    exit();
    
} catch (Exception $e) {
    // Revertir transacción si está activa
    if ($transactionStarted) {
        try {
            $db->rollBack();
        } catch (PDOException $pdoEx) {
            error_log("Error al hacer rollback: " . $pdoEx->getMessage());
        }
    }
    
    error_log("Error en aprobar_taller_controller: " . $e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../views/solicitudes.php");
    exit();
}
