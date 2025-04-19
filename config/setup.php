<?php
/**
 * Script de configuración inicial de la base de datos
 * 
 * Este script crea las tablas necesarias para el funcionamiento del sitio
 */

// Incluir configuración y conexión a la base de datos
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Crear instancia de la base de datos
$db = new Database();

// Leer el archivo SQL
$sql = file_get_contents(__DIR__ . '/schema.sql');

// Ejecutar las consultas SQL
try {
    // Dividir el SQL en consultas individuales
    $queries = explode(';', $sql);
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $db->query($query);
            $db->execute();
        }
    }
    
    echo "¡Configuración de la base de datos completada con éxito!";
} catch (Exception $e) {
    echo "Error al configurar la base de datos: " . $e->getMessage();
} 