<?php
/**
 * Script para crear la tabla de actividad_plataforma
 * 
 * Este script lee el archivo SQL y lo ejecuta en la base de datos
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

try {
    // Obtener instancia de la base de datos
    $db = Database::getInstance();
    
    // Leer el archivo SQL
    $sqlFile = __DIR__ . '/../sql/actividad_plataforma.sql';
    $sql = file_get_contents($sqlFile);
    
    if (!$sql) {
        die("Error: No se pudo leer el archivo SQL.\n");
    }
    
    // Dividir las consultas SQL
    $queries = explode(';', $sql);
    
    // Ejecutar cada consulta
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $db->query($query);
            echo "Consulta ejecutada con éxito.\n";
        }
    }
    
    echo "Tabla actividad_plataforma creada correctamente.\n";
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
