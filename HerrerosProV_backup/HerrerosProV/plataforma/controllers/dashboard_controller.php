<?php
/**
 * Controlador del Dashboard
 * 
 * Maneja las peticiones AJAX para mostrar:
 * - Contadores de solicitudes y talleres
 * - Últimas solicitudes
 * - Actividad reciente
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

class DashboardController {
    private $db;
    private $auth;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->auth = Auth::getInstance();
        
        // Verificar autenticación
        if (!$this->auth->isAuthenticated()) {
            $this->sendError('No autorizado');
        }
    }
    
    /**
     * Maneja las peticiones al controlador
     */
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendError('Método no permitido');
        }
        
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'contadores':
                $this->getContadores();
                break;
            case 'ultimas_solicitudes':
                $this->getUltimasSolicitudes();
                break;
            case 'actividad_reciente':
                $this->getActividadReciente();
                break;
            default:
                $this->sendError('Acción no válida');
        }
    }
    
    /**
     * Obtiene los contadores para el dashboard
     */
    private function getContadores() {
        try {
            // Contar solicitudes pendientes
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total
                FROM solicitudes_talleres
                WHERE estado = 'pendiente'
            ");
            $stmt->execute();
            $solicitudes = $stmt->fetch();
            
            // Contar talleres activos
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total
                FROM talleres
                WHERE estado = 'activo'
            ");
            $stmt->execute();
            $talleres = $stmt->fetch();
            
            $this->sendResponse([
                'success' => true,
                'solicitudes_pendientes' => $solicitudes['total'],
                'talleres_activos' => $talleres['total']
            ]);
        } catch (Exception $e) {
            error_log("Error obteniendo contadores: " . $e->getMessage());
            $this->sendError('Error al obtener los contadores');
        }
    }
    
    /**
     * Obtiene las últimas solicitudes
     */
    private function getUltimasSolicitudes() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id as id_solicitud,
                    nombre_taller,
                    estado,
                    fecha_solicitud
                FROM solicitudes_talleres
                ORDER BY fecha_solicitud DESC
                LIMIT 5
            ");
            $stmt->execute();
            
            $this->sendResponse([
                'success' => true,
                'solicitudes' => $stmt->fetchAll()
            ]);
        } catch (Exception $e) {
            error_log("Error obteniendo solicitudes: " . $e->getMessage());
            $this->sendError('Error al obtener las solicitudes');
        }
    }
    
    /**
     * Obtiene las actividades recientes para mostrar en el dashboard
     */
    private function getActividadReciente() {
        try {
            // Agregar información de depuración
            error_log("Iniciando consulta de actividad reciente");
            
            // Verificar si existe la tabla de actividad
            $stmt = $this->db->prepare("
                SHOW TABLES LIKE 'actividad_plataforma'
            ");
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                error_log("La tabla actividad_plataforma no existe");
                // La tabla no existe, crear datos de ejemplo
                $actividades = [
                    [
                        'usuario' => 'Administrador',
                        'descripcion' => 'Inicio de sesión en el sistema',
                        'fecha' => date('Y-m-d H:i:s')
                    ],
                    [
                        'usuario' => 'Sistema',
                        'descripcion' => 'Actualización de plataforma completada',
                        'fecha' => date('Y-m-d H:i:s', strtotime('-1 day'))
                    ],
                    [
                        'usuario' => 'Administrador',
                        'descripcion' => 'Solicitud de taller aprobada',
                        'fecha' => date('Y-m-d H:i:s', strtotime('-2 day'))
                    ]
                ];
                
                $this->sendResponse([
                    'success' => true,
                    'actividades' => $actividades
                ]);
                return;
            }
            
            error_log("La tabla actividad_plataforma existe, consultando datos");
            
            // Verificar estructura de la tabla usuarios_plataforma
            $stmt = $this->db->prepare("DESCRIBE usuarios_plataforma");
            $stmt->execute();
            $estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Estructura de usuarios_plataforma: " . print_r($estructura, true));
            
            // Determinar el nombre de la columna ID
            $idColumn = 'id';
            foreach ($estructura as $campo) {
                if ($campo['Key'] === 'PRI') {
                    $idColumn = $campo['Field'];
                    break;
                }
            }
            error_log("Columna ID de usuarios_plataforma: " . $idColumn);
            
            // Si la tabla existe, consultar los datos
            $query = "
                SELECT 
                    a.id_actividad,
                    a.tipo_actividad,
                    a.descripcion,
                    a.fecha_creacion as fecha,
                    u.nombre as usuario,
                    a.entidad,
                    a.id_entidad
                FROM actividad_plataforma a
                LEFT JOIN usuarios_plataforma u ON a.id_usuario = u." . $idColumn . "
                ORDER BY a.fecha_creacion DESC
                LIMIT 5
            ";
            
            error_log("Consulta SQL: " . $query);
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug
            error_log("Actividades encontradas: " . count($actividades));
            error_log("Datos de actividades: " . print_r($actividades, true));
            
            // Verificar si hay datos
            if (empty($actividades)) {
                error_log("No se encontraron actividades en la base de datos");
                $this->sendResponse([
                    'success' => true,
                    'actividades' => []
                ]);
                return;
            }
            
            // Enviar respuesta con los datos
            $this->sendResponse([
                'success' => true,
                'actividades' => $actividades
            ]);
        } catch (Exception $e) {
            error_log("Error obteniendo actividad: " . $e->getMessage());
            $this->sendError('Error al obtener la actividad');
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
     * Envía una respuesta JSON al cliente
     * 
     * @param array $data Datos a enviar
     */
    private function sendResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Iniciar el controlador
$controller = new DashboardController();
$controller->handleRequest();
