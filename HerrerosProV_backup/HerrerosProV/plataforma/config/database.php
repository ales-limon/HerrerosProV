<?php
/**
 * Configuración de la base de datos
 * Clase para manejar la conexión y operaciones con la base de datos
 */

class Database {
    private static $instance = null;
    private $pdo;
    private $isConnected = false;
    
    /**
     * Constructor - establece conexión con la base de datos
     */
    public function __construct() {
        $this->connect();
    }
    
    /**
     * Obtiene la instancia única de la base de datos (patrón Singleton)
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establece la conexión con la base de datos
     */
    private function connect() {
        if ($this->isConnected) {
            return;
        }
        
        try {
            // Datos de conexión - ajustar según la configuración
            $host = 'localhost';
            $dbname = 'herreros_pro';
            $username = 'root';
            $password = '';
            $charset = 'utf8mb4';
            
            $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, $username, $password, $options);
            $this->isConnected = true;
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Prepara una consulta SQL
     * @param string $sql Consulta SQL
     * @return PDOStatement
     */
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    /**
     * Ejecuta una consulta SQL
     * @param string $sql Consulta SQL
     * @return PDOStatement
     */
    public function query($sql) {
        return $this->pdo->query($sql);
    }
    
    /**
     * Inicia una transacción
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Confirma una transacción
     */
    public function commit() {
        return $this->pdo->commit();
    }
    
    /**
     * Revierte una transacción
     */
    public function rollback() {
        return $this->pdo->rollBack();
    }
    
    /**
     * Obtiene el último ID insertado
     * @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Cierra la conexión
     */
    public function close() {
        $this->pdo = null;
        $this->isConnected = false;
    }
} 