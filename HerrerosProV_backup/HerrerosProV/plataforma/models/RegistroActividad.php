<?php
/**
 * Modelo para gestionar el registro de actividades en la plataforma
 * Según MEMORY[0c7884a9]: MVC con PHP, Seguridad: CSRF, XSS protection
 */
class RegistroActividad {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Registra una nueva actividad en el sistema
     * 
     * @param string $usuario Nombre del usuario que realizó la actividad
     * @param string $descripcion Descripción de la actividad
     * @param string $tipo Tipo de actividad (login, solicitud, taller, etc.)
     * @param int $idUsuario ID del usuario que realizó la actividad
     * @param int|null $idReferencia ID de referencia (opcional, ej: ID de solicitud)
     * @param string|null $entidad Entidad relacionada (opcional, ej: solicitudes_talleres)
     * @return bool True si se registró correctamente, False en caso contrario
     */
    public function registrar($usuario, $descripcion, $tipo, $idUsuario, $idReferencia = null, $entidad = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO registro_actividad 
                (usuario, descripcion, tipo, id_usuario, id_referencia, entidad) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $usuario,
                $descripcion,
                $tipo,
                $idUsuario,
                $idReferencia,
                $entidad
            ]);
        } catch (PDOException $e) {
            error_log("Error al registrar actividad: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene las actividades más recientes
     * 
     * @param int $limite Número máximo de actividades a obtener
     * @return array Arreglo con las actividades recientes
     */
    public function obtenerRecientes($limite = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM registro_actividad 
                ORDER BY fecha DESC 
                LIMIT ?
            ");
            
            $stmt->bindParam(1, $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener actividades recientes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene las actividades de un usuario específico
     * 
     * @param int $idUsuario ID del usuario
     * @param int $limite Número máximo de actividades a obtener
     * @return array Arreglo con las actividades del usuario
     */
    public function obtenerPorUsuario($idUsuario, $limite = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM registro_actividad 
                WHERE id_usuario = ? 
                ORDER BY fecha DESC 
                LIMIT ?
            ");
            
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener actividades por usuario: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene las actividades por tipo
     * 
     * @param string $tipo Tipo de actividad
     * @param int $limite Número máximo de actividades a obtener
     * @return array Arreglo con las actividades del tipo especificado
     */
    public function obtenerPorTipo($tipo, $limite = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM registro_actividad 
                WHERE tipo = ? 
                ORDER BY fecha DESC 
                LIMIT ?
            ");
            
            $stmt->bindParam(1, $tipo, PDO::PARAM_STR);
            $stmt->bindParam(2, $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener actividades por tipo: " . $e->getMessage());
            return [];
        }
    }
}
?>
