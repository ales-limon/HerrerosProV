<?php
/**
 * Modelo de Actividad
 * 
 * Gestiona el registro de actividades en la plataforma
 */

require_once __DIR__ . '/../config/database.php';

class Actividad {
    private $db;
    
    // Tipos de actividad
    const TIPO_LOGIN = 'login';
    const TIPO_LOGOUT = 'logout';
    const TIPO_CREAR = 'crear';
    const TIPO_EDITAR = 'editar';
    const TIPO_ELIMINAR = 'eliminar';
    const TIPO_APROBAR = 'aprobar';
    const TIPO_RECHAZAR = 'rechazar';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Registra una nueva actividad en el sistema
     * 
     * @param int $idUsuario ID del usuario que realiza la actividad
     * @param string $tipoActividad Tipo de actividad (usar constantes de la clase)
     * @param string $descripcion Descripción de la actividad
     * @param string $entidad Entidad afectada (ej: 'taller', 'solicitud', etc.)
     * @param int $idEntidad ID de la entidad afectada (opcional)
     * @return bool Éxito de la operación
     */
    public function registrar($idUsuario, $tipoActividad, $descripcion, $entidad = null, $idEntidad = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO actividad_plataforma (
                    id_usuario, 
                    tipo_actividad, 
                    descripcion, 
                    entidad,
                    id_entidad,
                    fecha_creacion
                ) VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            return $stmt->execute([
                $idUsuario,
                $tipoActividad,
                $descripcion,
                $entidad,
                $idEntidad
            ]);
        } catch (Exception $e) {
            error_log("Error al registrar actividad: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Registra actividad de inicio de sesión
     * 
     * @param int $idUsuario ID del usuario
     * @param string $email Email del usuario
     * @return bool Éxito de la operación
     */
    public function registrarLogin($idUsuario, $email) {
        return $this->registrar(
            $idUsuario,
            self::TIPO_LOGIN,
            "Inicio de sesión con email: $email",
            'usuario',
            $idUsuario
        );
    }
    
    /**
     * Registra actividad de cierre de sesión
     * 
     * @param int $idUsuario ID del usuario
     * @return bool Éxito de la operación
     */
    public function registrarLogout($idUsuario) {
        return $this->registrar(
            $idUsuario,
            self::TIPO_LOGOUT,
            "Cierre de sesión",
            'usuario',
            $idUsuario
        );
    }
    
    /**
     * Registra actividad de aprobación de solicitud
     * 
     * @param int $idUsuario ID del usuario que aprueba
     * @param int $idSolicitud ID de la solicitud
     * @param string $nombreTaller Nombre del taller
     * @return bool Éxito de la operación
     */
    public function registrarAprobacionSolicitud($idUsuario, $idSolicitud, $nombreTaller) {
        return $this->registrar(
            $idUsuario,
            self::TIPO_APROBAR,
            "Aprobación de solicitud para taller: $nombreTaller",
            'solicitud',
            $idSolicitud
        );
    }
    
    /**
     * Registra actividad de rechazo de solicitud
     * 
     * @param int $idUsuario ID del usuario que rechaza
     * @param int $idSolicitud ID de la solicitud
     * @param string $nombreTaller Nombre del taller
     * @return bool Éxito de la operación
     */
    public function registrarRechazoSolicitud($idUsuario, $idSolicitud, $nombreTaller) {
        return $this->registrar(
            $idUsuario,
            self::TIPO_RECHAZAR,
            "Rechazo de solicitud para taller: $nombreTaller",
            'solicitud',
            $idSolicitud
        );
    }
}
