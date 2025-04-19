<?php
/**
 * Modelo para manejar las operaciones de la tabla mensajes_contacto
 */

class ContactModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Guarda un nuevo mensaje de contacto en la base de datos
     */
    public function guardarMensaje($nombre, $email, $asunto, $mensaje, $ip = null, $userAgent = null) {
        try {
            $sql = "INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje, ip_remitente, user_agent) 
                    VALUES (:nombre, :email, :asunto, :mensaje, :ip, :userAgent)";
            
            $this->db->query($sql);
            
            $this->db->bind(':nombre', $nombre);
            $this->db->bind(':email', $email);
            $this->db->bind(':asunto', $asunto);
            $this->db->bind(':mensaje', $mensaje);
            $this->db->bind(':ip', $ip ?? $_SERVER['REMOTE_ADDR']);
            $this->db->bind(':userAgent', $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? null);
            
            return $this->db->execute();
        } catch (PDOException $e) {
            // Registrar el error para debugging
            error_log("Error al guardar mensaje de contacto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registra un intento de spam
     */
    public function registrarSpam($ip, $userAgent, $datos) {
        try {
            // Registrar en el log
            $logMessage = date('Y-m-d H:i:s') . " | IP: $ip | UA: $userAgent | Datos: " . json_encode($datos);
            error_log($logMessage, 3, __DIR__ . '/../../logs/spam_attempts.log');
            
            // Aquí podrías agregar código para guardar en una tabla de spam si lo deseas
            return true;
        } catch (Exception $e) {
            error_log("Error al registrar spam: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si una IP ha sido bloqueada por spam
     */
    public function verificarIPBloqueada($ip) {
        // Aquí podrías implementar la lógica para verificar IPs bloqueadas
        // Por ejemplo, consultando una tabla de IPs bloqueadas
        return false;
    }

    /**
     * Obtiene todos los mensajes de contacto
     */
    public function obtenerMensajes($limite = 10, $offset = 0) {
        try {
            $sql = "SELECT * FROM mensajes_contacto 
                    ORDER BY fecha_creacion DESC 
                    LIMIT :limite OFFSET :offset";
            
            $this->db->query($sql);
            $this->db->bind(':limite', $limite, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Error al obtener mensajes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualiza el estado de un mensaje
     */
    public function actualizarEstado($id, $estado) {
        try {
            $sql = "UPDATE mensajes_contacto 
                    SET estado = :estado 
                    WHERE id = :id";
            
            $this->db->query($sql);
            $this->db->bind(':estado', $estado);
            $this->db->bind(':id', $id);
            
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene un mensaje específico por ID
     */
    public function obtenerMensajePorId($id) {
        try {
            $sql = "SELECT * FROM mensajes_contacto WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            
            return $this->db->single();
        } catch (PDOException $e) {
            error_log("Error al obtener mensaje por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cuenta el total de mensajes
     */
    public function contarMensajes() {
        try {
            $sql = "SELECT COUNT(*) FROM mensajes_contacto";
            $this->db->query($sql);
            return $this->db->single();
        } catch (PDOException $e) {
            error_log("Error al contar mensajes: " . $e->getMessage());
            return 0;
        }
    }
} 