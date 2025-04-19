<?php
/**
 * Controlador de Talleres
 * Maneja las solicitudes y gestión de talleres según MEMORY[0c7884a9]
 */

// Asegurar que la respuesta sea JSON para peticiones AJAX
header('Content-Type: application/json');

// Cargar configuración según MEMORY[d8a38fe4]
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener instancia de autenticación
$auth = Auth::getInstance();

// Verificar autenticación
if (!$auth->isAuthenticated()) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Verificar que se especifique una acción
if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Acción no especificada']);
    exit;
}

try {
    // Conectar a la base de datos
    $db = Database::getInstance();
    
    switch ($_POST['action']) {
        case 'listar_solicitudes':
            // Verificar permiso
            if (!$auth->hasPermission('aprobar_solicitudes')) {
                throw new Exception('No tienes permiso para ver las solicitudes');
            }
            
            // Obtener lista de solicitudes
            $stmt = $db->prepare("
                SELECT 
                    s.id_solicitud,
                    s.nombre_taller,
                    s.propietario,
                    s.email,
                    s.fecha_solicitud,
                    s.estado
                FROM 
                    solicitudes_talleres s
                ORDER BY 
                    s.fecha_solicitud DESC
            ");
            $stmt->execute();
            $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $solicitudes
            ]);
            break;
            
        case 'ver_solicitud':
            // Verificar permiso
            if (!$auth->hasPermission('aprobar_solicitudes')) {
                throw new Exception('No tienes permiso para ver los detalles');
            }
            
            // Validar ID
            if (!isset($_POST['id_solicitud'])) {
                throw new Exception('ID de solicitud no especificado');
            }
            
            // Obtener detalles de la solicitud
            $stmt = $db->prepare("
                SELECT 
                    s.*,
                    d.rfc,
                    d.direccion,
                    d.telefono,
                    d.documentos
                FROM 
                    solicitudes_talleres s
                    LEFT JOIN detalles_solicitud d ON s.id_solicitud = d.id_solicitud
                WHERE 
                    s.id_solicitud = ?
            ");
            $stmt->execute([$_POST['id_solicitud']]);
            $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$solicitud) {
                throw new Exception('Solicitud no encontrada');
            }
            
            // Generar HTML con los detalles
            $html = "
                <div class='row'>
                    <div class='col-md-6'>
                        <h5>Información del Taller</h5>
                        <p><strong>Nombre:</strong> {$solicitud['nombre_taller']}</p>
                        <p><strong>Propietario:</strong> {$solicitud['propietario']}</p>
                        <p><strong>Email:</strong> {$solicitud['email']}</p>
                        <p><strong>RFC:</strong> {$solicitud['rfc']}</p>
                    </div>
                    <div class='col-md-6'>
                        <h5>Detalles de Contacto</h5>
                        <p><strong>Dirección:</strong> {$solicitud['direccion']}</p>
                        <p><strong>Teléfono:</strong> {$solicitud['telefono']}</p>
                        <p><strong>Fecha Solicitud:</strong> {$solicitud['fecha_solicitud']}</p>
                        <p><strong>Estado:</strong> {$solicitud['estado']}</p>
                    </div>
                </div>
            ";
            
            echo json_encode([
                'success' => true,
                'html' => $html,
                'data' => $solicitud
            ]);
            break;
            
        case 'aprobar_solicitud':
        case 'rechazar_solicitud':
            // Verificar permiso
            if (!$auth->hasPermission('aprobar_solicitudes')) {
                throw new Exception('No tienes permiso para procesar solicitudes');
            }
            
            // Validar ID
            if (!isset($_POST['id_solicitud'])) {
                throw new Exception('ID de solicitud no especificado');
            }
            
            // Determinar el nuevo estado
            $estado = $_POST['action'] === 'aprobar_solicitud' ? 'aprobada' : 'rechazada';
            
            // Actualizar estado
            $stmt = $db->prepare("
                UPDATE solicitudes_talleres 
                SET 
                    estado = ?,
                    fecha_actualizacion = NOW(),
                    actualizado_por = ?
                WHERE 
                    id_solicitud = ?
            ");
            $stmt->execute([
                $estado,
                $auth->getCurrentUser()['id'],
                $_POST['id_solicitud']
            ]);
            
            // Si se aprobó, crear el taller
            if ($estado === 'aprobada') {
                // Obtener datos de la solicitud
                $stmt = $db->prepare("SELECT * FROM solicitudes_talleres WHERE id_solicitud = ?");
                $stmt->execute([$_POST['id_solicitud']]);
                $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Crear el taller
                $stmt = $db->prepare("
                    INSERT INTO talleres (
                        nombre,
                        propietario,
                        email,
                        estado,
                        fecha_creacion,
                        creado_por
                    ) VALUES (
                        ?, ?, ?, 'activo', NOW(), ?
                    )
                ");
                $stmt->execute([
                    $solicitud['nombre_taller'],
                    $solicitud['propietario'],
                    $solicitud['email'],
                    $auth->getCurrentUser()['id']
                ]);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Solicitud ' . ($estado === 'aprobada' ? 'aprobada' : 'rechazada') . ' exitosamente'
            ]);
            break;
            
        default:
            throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    error_log("Error en talleres_controller: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
