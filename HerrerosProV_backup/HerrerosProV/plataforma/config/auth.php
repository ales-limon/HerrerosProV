<?php
/**
 * Sistema de autenticación para la Plataforma Admin
 * Maneja roles y permisos para los usuarios
 */

require_once __DIR__ . '/database.php';

/**
 * Clase Auth para gestionar la autenticación y autorización
 */
class Auth {
    private static $instance = null;
    private $db = null;
    
    /**
     * Constructor privado que inicializa la conexión a la base de datos
     */
    private function __construct() {
        // Inicializar la conexión a la base de datos
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtiene la instancia única (patrón Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Inicia sesión con email y contraseña
     */
    public function login($email, $password) {
        try {
            // Verificar credenciales
            $stmt = $this->db->prepare("SELECT * FROM usuarios_plataforma WHERE email = ? AND estado = 'activo'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Regenerar ID de sesión por seguridad
                session_regenerate_id(true);
                
                // Guardar datos en sesión
                $_SESSION['plataforma_user'] = [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email'],
                    'rol' => $user['rol'],
                    'creado_en' => $user['creado_en']
                ];
                
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cierra la sesión actual
     */
    public function logout() {
        // Destruir la sesión
        session_unset();
        session_destroy();
        
        return true;
    }
    
    /**
     * Verifica si hay un usuario autenticado
     */
    public function isAuthenticated() {
        return isset($_SESSION['plataforma_user']);
    }
    
    /**
     * Obtiene los datos del usuario actual
     */
    public function getCurrentUser() {
        return $_SESSION['plataforma_user'] ?? null;
    }
    
    /**
     * Verifica si el usuario actual tiene un rol específico
     */
    public function hasRole($role) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        $user = $this->getCurrentUser();
        return $user['rol'] === $role;
    }
    
    /**
     * Verifica si el usuario actual tiene un permiso específico
     */
    public function hasPermission($permission) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        $user = $this->getCurrentUser();
        
        // Definir permisos por rol
        $rolePermissions = [
            'admin' => [
                'gestionar_talleres',
                'aprobar_solicitudes',
                'gestionar_suscripciones',
                'gestionar_usuarios'
            ],
            'supervisor' => [
                'gestionar_talleres',
                'aprobar_solicitudes',
                'gestionar_suscripciones'
            ],
            'capturista' => [
                'gestionar_talleres'
            ]
        ];
        
        return in_array($permission, $rolePermissions[$user['rol']] ?? []);
    }
} 