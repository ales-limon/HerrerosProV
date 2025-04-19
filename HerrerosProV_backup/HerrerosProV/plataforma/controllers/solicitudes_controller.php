<?php
/**
 * Controlador de Solicitudes de Registro
 * 
 * Maneja la gestión de solicitudes de registro de talleres en la Plataforma Admin:
 * - Listado de solicitudes
 * - Revisión y aprobación/rechazo
 * - Creación de taller al aprobar
 * - Envío de email de activación
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Actividad.php';
require_once __DIR__ . '/../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SolicitudesController {
    private $db;
    private $auth;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->auth = Auth::getInstance();
        
        // Verificar autenticación
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        // Verificar permisos
        if (!$this->auth->hasPermission('gestionar_talleres') && 
            !$this->auth->hasPermission('aprobar_solicitudes')) {
            $this->sendError('No tienes permisos para realizar esta acción');
        }
    }
    
    /**
     * Maneja las peticiones al controlador
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? 'listar';
        
        switch ($action) {
            case 'listar':
                $this->listarSolicitudes();
                break;
            case 'ver':
                $id = $_GET['id'] ?? 0;
                $this->verSolicitud($id);
                break;
            case 'aprobar':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    $this->sendError('Método no permitido');
                }
                $this->aprobarSolicitud();
                break;
            case 'rechazar':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    $this->sendError('Método no permitido');
                }
                $this->rechazarSolicitud();
                break;
            default:
                $this->sendError('Acción no válida');
        }
    }
    
    /**
     * Lista todas las solicitudes
     */
    private function listarSolicitudes() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    s.*,
                    u.nombre as revisor_nombre
                FROM solicitudes_talleres s
                LEFT JOIN usuarios_plataforma u ON s.id_revisor = u.id_usuario
                ORDER BY 
                    CASE s.estado 
                        WHEN 'pendiente' THEN 1
                        WHEN 'aprobada' THEN 2
                        ELSE 3
                    END,
                    s.fecha_solicitud DESC
            ");
            
            $stmt->execute();
            $solicitudes = $stmt->fetchAll();
            
            $this->sendResponse([
                'success' => true,
                'solicitudes' => $solicitudes
            ]);
        } catch (Exception $e) {
            $this->sendError('Error al obtener las solicitudes');
        }
    }
    
    /**
     * Ver detalles de una solicitud
     */
    private function verSolicitud($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    s.*,
                    u.nombre as revisor_nombre
                FROM solicitudes_talleres s
                LEFT JOIN usuarios_plataforma u ON s.id_revisor = u.id_usuario
                WHERE s.id_solicitud = ?
            ");
            
            $stmt->execute([$id]);
            $solicitud = $stmt->fetch();
            
            if (!$solicitud) {
                $this->sendError('Solicitud no encontrada');
            }
            
            $this->sendResponse([
                'success' => true,
                'solicitud' => $solicitud
            ]);
        } catch (Exception $e) {
            $this->sendError('Error al obtener la solicitud');
        }
    }
    
    /**
     * Aprobar una solicitud
     */
    private function aprobarSolicitud() {
        if (!isset($_POST['id_solicitud']) || !isset($_POST['csrf_token'])) {
            $this->sendError('Datos incompletos');
        }
        
        if ($_POST['csrf_token'] !== $_SESSION[CSRF_TOKEN_NAME]) {
            $this->sendError('Token de seguridad inválido');
        }
        
        $id_solicitud = (int)$_POST['id_solicitud'];
        $notas = trim($_POST['notas'] ?? '');
        
        try {
            $this->db->beginTransaction();
            
            // Obtener datos de la solicitud
            $stmt = $this->db->prepare("
                SELECT * FROM solicitudes_talleres 
                WHERE id_solicitud = ? AND estado = 'pendiente'
            ");
            $stmt->execute([$id_solicitud]);
            $solicitud = $stmt->fetch();
            
            if (!$solicitud) {
                throw new Exception('Solicitud no encontrada o ya procesada');
            }
            
            // Actualizar estado de la solicitud
            $stmt = $this->db->prepare("
                UPDATE solicitudes_talleres 
                SET estado = 'aprobada',
                    fecha_revision = NOW(),
                    id_revisor = ?,
                    notas_revision = ?
                WHERE id_solicitud = ?
            ");
            $stmt->execute([
                $this->auth->getCurrentUser()['id'],
                $notas,
                $id_solicitud
            ]);
            
            // Crear el taller
            $stmt = $this->db->prepare("
                INSERT INTO talleres (
                    id_solicitud,
                    nombre,
                    email,
                    telefono,
                    tipo_plan,
                    estado,
                    fecha_inicio_plan,
                    fecha_fin_plan
                ) VALUES (?, ?, ?, ?, ?, 'activo', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH))
            ");
            $stmt->execute([
                $id_solicitud,
                $solicitud['nombre_taller'],
                $solicitud['email'],
                $solicitud['telefono'],
                $solicitud['plan_seleccionado']
            ]);
            
            $id_taller = $this->db->lastInsertId();
            
            // Generar token de activación
            $token = bin2hex(random_bytes(32));
            $stmt = $this->db->prepare("
                INSERT INTO tokens_activacion (
                    id_taller,
                    token,
                    fecha_expiracion
                ) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? HOUR))
            ");
            $stmt->execute([
                $id_taller,
                $token,
                TOKEN_EXPIRY_HOURS
            ]);
            
            $this->db->commit();
            
            // Enviar email de activación
            $this->enviarEmailActivacion($solicitud, $token);
            
            // Registrar actividad
            $this->registrarActividad(
                'aprobacion_solicitud',
                "Solicitud #{$id_solicitud} aprobada - Taller: {$solicitud['nombre_taller']}"
            );
            
            $this->sendResponse([
                'success' => true,
                'message' => 'Solicitud aprobada correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al aprobar solicitud: " . $e->getMessage());
            $this->sendError('Error al procesar la solicitud');
        }
    }
    
    /**
     * Rechazar una solicitud
     */
    private function rechazarSolicitud() {
        if (!isset($_POST['id_solicitud']) || !isset($_POST['csrf_token'])) {
            $this->sendError('Datos incompletos');
        }
        
        if ($_POST['csrf_token'] !== $_SESSION[CSRF_TOKEN_NAME]) {
            $this->sendError('Token de seguridad inválido');
        }
        
        $id_solicitud = (int)$_POST['id_solicitud'];
        $notas = trim($_POST['notas'] ?? '');
        
        if (empty($notas)) {
            $this->sendError('Debes especificar el motivo del rechazo');
        }
        
        try {
            // Actualizar estado de la solicitud
            $stmt = $this->db->prepare("
                UPDATE solicitudes_talleres 
                SET estado = 'rechazada',
                    fecha_revision = NOW(),
                    id_revisor = ?,
                    notas_revision = ?
                WHERE id_solicitud = ? AND estado = 'pendiente'
            ");
            
            $stmt->execute([
                $this->auth->getCurrentUser()['id'],
                $notas,
                $id_solicitud
            ]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception('Solicitud no encontrada o ya procesada');
            }
            
            // Registrar actividad
            $this->registrarActividad(
                'rechazo_solicitud',
                "Solicitud #{$id_solicitud} rechazada - Motivo: {$notas}"
            );
            
            $this->sendResponse([
                'success' => true,
                'message' => 'Solicitud rechazada correctamente'
            ]);
            
        } catch (Exception $e) {
            error_log("Error al rechazar solicitud: " . $e->getMessage());
            $this->sendError('Error al procesar la solicitud');
        }
    }
    
    /**
     * Envía el email de activación al taller
     */
    private function enviarEmailActivacion($solicitud, $token) {
        try {
            $mail = new PHPMailer(true);
            
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';
            
            $mail->setFrom(EMAIL_CONFIG['from_email'], EMAIL_CONFIG['from_name']);
            $mail->addAddress($solicitud['email'], $solicitud['nombre_contacto']);
            
            $activationUrl = BASE_URL . "/activar?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Subject = '¡Bienvenido a HerrerosPro! - Activa tu cuenta';
            $mail->Body = "
                <h2>¡Bienvenido a HerrerosPro!</h2>
                <p>Hola {$solicitud['nombre_contacto']},</p>
                <p>Tu solicitud de registro para el taller \"{$solicitud['nombre_taller']}\" ha sido aprobada.</p>
                <p>Para comenzar a usar tu cuenta, por favor haz clic en el siguiente enlace de activación (válido por " . TOKEN_EXPIRY_HOURS . " horas):</p>
                <p><a href='{$activationUrl}'>{$activationUrl}</a></p>
                <p>Si no solicitaste esta cuenta, puedes ignorar este mensaje.</p>
                <p>¡Gracias por confiar en HerrerosPro!</p>
            ";
            
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar email de activación: " . $mail->ErrorInfo);
            // No lanzamos excepción para no afectar el flujo principal
        }
    }
    
    /**
     * Registra una actividad en el sistema
     */
    private function registrarActividad($tipo, $descripcion) {
        try {
            // Usar la nueva clase Actividad
            $actividad = new Actividad();
            $usuario = $this->auth->getCurrentUser();
            
            // Mapear tipos de actividad a constantes de la clase Actividad
            $tipoActividad = '';
            $entidad = '';
            $idEntidad = null;
            
            switch ($tipo) {
                case 'aprobacion_solicitud':
                    $tipoActividad = Actividad::TIPO_APROBAR;
                    $entidad = 'solicitud';
                    // Extraer ID de la solicitud del mensaje
                    if (preg_match('/Solicitud #(\d+)/', $descripcion, $matches)) {
                        $idEntidad = $matches[1];
                    }
                    break;
                case 'rechazo_solicitud':
                    $tipoActividad = Actividad::TIPO_RECHAZAR;
                    $entidad = 'solicitud';
                    // Extraer ID de la solicitud del mensaje
                    if (preg_match('/Solicitud #(\d+)/', $descripcion, $matches)) {
                        $idEntidad = $matches[1];
                    }
                    break;
                default:
                    $tipoActividad = $tipo;
            }
            
            // Registrar la actividad usando el nuevo modelo
            return $actividad->registrar(
                $usuario['id'],
                $tipoActividad,
                $descripcion,
                $entidad,
                $idEntidad
            );
        } catch (Exception $e) {
            error_log("Error al registrar actividad: " . $e->getMessage());
            // No lanzamos excepción para no afectar el flujo principal
            return false;
        }
    }
    
    /**
     * Envía una respuesta de error
     */
    private function sendError($message) {
        $this->sendResponse([
            'success' => false,
            'message' => $message
        ]);
    }
    
    /**
     * Envía una respuesta JSON
     */
    private function sendResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

// Iniciar el controlador
$controller = new SolicitudesController();
$controller->handleRequest();
