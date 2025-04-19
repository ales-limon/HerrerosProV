<?php
/**
 * Configuración de la base de datos
 * 
 * Este archivo contiene las credenciales y configuración para la conexión a la base de datos.
 * 
 * @package HerrerosPro
 */

// Definir constantes de conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'herrerospro_plataforma');
define('DB_CHARSET', 'utf8mb4');

/**
 * Clase Database para manejar la conexión a la base de datos
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset = DB_CHARSET;
    
    private $conn;
    private $error;
    private $stmt;
    
    /**
     * Constructor - Establece la conexión PDO
     */
    public function __construct() {
        // Configurar DSN
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        
        // Configurar opciones de PDO
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        // Crear instancia de PDO
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Error de conexión a la base de datos: " . $this->error);
            die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
        }
    }
    
    /**
     * Preparar consulta
     * @param string $sql Consulta SQL
     */
    public function query($sql) {
        $this->stmt = $this->conn->prepare($sql);
    }
    
    /**
     * Vincular valores a parámetros
     * @param string $param Nombre del parámetro
     * @param mixed $value Valor a vincular
     * @param mixed $type Tipo de dato (opcional)
     */
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
    }
    
    /**
     * Ejecutar la consulta preparada
     * @return boolean
     */
    public function execute() {
        try {
            return $this->stmt->execute();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Error al ejecutar consulta: " . $this->error);
            return false;
        }
    }
    
    /**
     * Obtener múltiples registros
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Obtener un solo registro
     * @return object
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Obtener el número de filas afectadas
     * @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Obtener el último ID insertado
     * @return int
     */
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
    
    /**
     * Iniciar una transacción
     */
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }
    
    /**
     * Confirmar una transacción
     */
    public function commit() {
        return $this->conn->commit();
    }
    
    /**
     * Revertir una transacción
     */
    public function rollBack() {
        return $this->conn->rollBack();
    }
    
    /**
     * Obtener el mensaje de error
     * @return string
     */
    public function getError() {
        return $this->error;
    }
} 