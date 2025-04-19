<?php
/**
 * Modelo de Solicitudes
 * 
 * Maneja toda la lógica de negocio y acceso a datos para solicitudes de talleres
 */
class SolicitudModel {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtiene todas las solicitudes
     * 
     * @param array $filtros Filtros a aplicar en la consulta
     * @return array Lista de solicitudes
     */
    public function obtenerTodas($filtros = []) {
        $sql = "SELECT * FROM solicitudes_talleres";
        $where = [];
        $params = [];
        
        // Aplicar filtros
        if (isset($filtros['estado']) && !empty($filtros['estado'])) {
            $where[] = "estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $sql .= " ORDER BY fecha_solicitud DESC";
        
        // Paginación
        if (isset($filtros['limit']) && isset($filtros['offset'])) {
            $sql .= " LIMIT :offset, :limit";
            $params[':offset'] = $filtros['offset'];
            $params[':limit'] = $filtros['limit'];
        }
        
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $param => $value) {
                if ($param == ':limit' || $param == ':offset') {
                    $stmt->bindValue($param, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($param, $value);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener solicitudes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene una solicitud por su ID
     * 
     * @param int $id ID de la solicitud
     * @return array|false Datos de la solicitud o false si no existe
     */
    public function obtenerPorId($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM solicitudes_talleres WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener solicitud: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Aprueba una solicitud
     * 
     * @param int $id ID de la solicitud
     * @param int $usuarioId ID del usuario que aprueba
     * @param string $notas Notas de aprobación
     * @return bool Resultado de la operación
     */
    public function aprobar($id, $usuarioId, $notas = '') {
        try {
            $this->db->beginTransaction();
            
            // Actualizar estado de la solicitud
            $stmt = $this->db->prepare("
                UPDATE solicitudes_talleres 
                SET estado = 'aprobada', 
                    id_revisor = :usuario_id, 
                    fecha_revision = NOW(),
                    notas_revision = :notas
                WHERE id = :id
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(':notas', $notas);
            $stmt->execute();
            
            // Crear el taller
            $solicitud = $this->obtenerPorId($id);
            if ($solicitud) {
                $stmt = $this->db->prepare("
                    INSERT INTO talleres (
                        nombre, 
                        propietario, 
                        email, 
                        telefono,
                        plan, 
                        estado, 
                        fecha_creacion,
                        fecha_activacion,
                        id_solicitud
                    ) VALUES (
                        :nombre, 
                        :propietario, 
                        :email, 
                        :telefono,
                        :plan, 
                        'pendiente_activacion', 
                        NOW(),
                        NULL,
                        :id_solicitud
                    )
                ");
                $stmt->bindValue(':nombre', $solicitud['nombre_taller']);
                $stmt->bindValue(':propietario', $solicitud['propietario']);
                $stmt->bindValue(':email', $solicitud['email']);
                $stmt->bindValue(':telefono', $solicitud['telefono']);
                $stmt->bindValue(':plan', $solicitud['plan_seleccionado']);
                $stmt->bindValue(':id_solicitud', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                $tallerId = $this->db->lastInsertId();
                
                // Registrar actividad
                $this->registrarActividad('aprobar_solicitud', $usuarioId, $id, $tallerId);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al aprobar solicitud: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Rechaza una solicitud
     * 
     * @param int $id ID de la solicitud
     * @param int $usuarioId ID del usuario que rechaza
     * @param string $notas Motivo del rechazo
     * @return bool Resultado de la operación
     */
    public function rechazar($id, $usuarioId, $notas) {
        try {
            $this->db->beginTransaction();
            
            // Actualizar estado de la solicitud
            $stmt = $this->db->prepare("
                UPDATE solicitudes_talleres 
                SET estado = 'rechazada', 
                    id_revisor = :usuario_id, 
                    fecha_revision = NOW(),
                    notas_revision = :notas
                WHERE id = :id
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(':notas', $notas);
            $stmt->execute();
            
            // Registrar actividad
            $this->registrarActividad('rechazar_solicitud', $usuarioId, $id);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al rechazar solicitud: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Registra actividad relacionada con solicitudes
     * 
     * @param string $tipo Tipo de actividad
     * @param int $usuarioId ID del usuario
     * @param int $solicitudId ID de la solicitud
     * @param int|null $tallerId ID del taller (opcional)
     */
    private function registrarActividad($tipo, $usuarioId, $solicitudId, $tallerId = null) {
        try {
            // Obtener nombre de la solicitud
            $solicitud = $this->obtenerPorId($solicitudId);
            $nombreTaller = $solicitud ? $solicitud['nombre_taller'] : "Solicitud #$solicitudId";
            
            // Descripción según tipo
            $descripcion = '';
            switch ($tipo) {
                case 'aprobar_solicitud':
                    $descripcion = "Aprobó la solicitud de registro para '$nombreTaller'";
                    break;
                case 'rechazar_solicitud':
                    $descripcion = "Rechazó la solicitud de registro para '$nombreTaller'";
                    break;
                default:
                    $descripcion = "Acción sobre solicitud '$nombreTaller'";
            }
            
            // Registrar en actividad_plataforma
            $stmt = $this->db->prepare("
                INSERT INTO actividad_plataforma (
                    id_usuario, 
                    tipo_actividad, 
                    descripcion, 
                    id_solicitud,
                    id_taller,
                    fecha_creacion
                ) VALUES (
                    :usuario_id,
                    :tipo,
                    :descripcion,
                    :solicitud_id,
                    :taller_id,
                    NOW()
                )
            ");
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':solicitud_id', $solicitudId, PDO::PARAM_INT);
            $stmt->bindValue(':taller_id', $tallerId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al registrar actividad: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene el conteo de solicitudes por estado
     * 
     * @return array Conteo de solicitudes
     */
    public function obtenerEstadisticas() {
        try {
            $stats = [
                'total' => 0,
                'pendientes' => 0,
                'aprobadas' => 0,
                'rechazadas' => 0
            ];
            
            // Total
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM solicitudes_talleres");
            $stmt->execute();
            $stats['total'] = $stmt->fetchColumn();
            
            // Pendientes
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM solicitudes_talleres WHERE estado = 'pendiente'");
            $stmt->execute();
            $stats['pendientes'] = $stmt->fetchColumn();
            
            // Aprobadas
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM solicitudes_talleres WHERE estado = 'aprobada'");
            $stmt->execute();
            $stats['aprobadas'] = $stmt->fetchColumn();
            
            // Rechazadas
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM solicitudes_talleres WHERE estado = 'rechazada'");
            $stmt->execute();
            $stats['rechazadas'] = $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return $stats;
        }
    }
} 