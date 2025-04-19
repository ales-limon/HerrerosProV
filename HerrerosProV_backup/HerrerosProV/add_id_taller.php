<?php
/**
 * Script para agregar el campo id_taller a la tabla talleres
 */

// Incluir configuración de base de datos
require_once __DIR__ . '/config/database.php';

// Crear conexión a la base de datos
$db = new Database();

// Verificar si la tabla talleres existe
$db->query("SHOW TABLES LIKE 'talleres'");
$tabla_existe = $db->single();

if ($tabla_existe) {
    echo "La tabla talleres existe.\n";
    
    // Verificar si el campo id_taller ya existe
    $db->query("SHOW COLUMNS FROM talleres LIKE 'id_taller'");
    $campo_existe = $db->single();
    
    if ($campo_existe) {
        echo "El campo id_taller ya existe en la tabla talleres.\n";
    } else {
        echo "El campo id_taller no existe. Intentando agregarlo...\n";
        
        // Agregar el campo id_taller (no auto_increment)
        $db->query("ALTER TABLE talleres ADD COLUMN id_taller INT UNIQUE AFTER id");
        
        if ($db->execute()) {
            echo "El campo id_taller se ha agregado correctamente.\n";
            
            // Actualizar los registros existentes con valores únicos para id_taller
            $db->query("SELECT id FROM talleres WHERE id_taller IS NULL");
            $registros = $db->resultset();
            
            if (!empty($registros)) {
                echo "Actualizando registros existentes con valores para id_taller...\n";
                
                foreach ($registros as $registro) {
                    $id = $registro['id'];
                    $id_taller = time() + $id; // Usar timestamp + id para generar un valor único
                    
                    $db->query("UPDATE talleres SET id_taller = ? WHERE id = ?");
                    $db->bind(1, $id_taller);
                    $db->bind(2, $id);
                    $db->execute();
                }
                
                echo "Registros actualizados correctamente.\n";
            }
        } else {
            echo "Error al agregar el campo id_taller: " . $db->getError() . "\n";
        }
    }
} else {
    echo "La tabla talleres no existe en la base de datos.\n";
    
    // Crear la tabla talleres con el campo id_taller
    $sql = "CREATE TABLE talleres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_taller INT UNIQUE,
        nombre VARCHAR(100) NOT NULL,
        nombre_admin VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        telefono VARCHAR(20) NOT NULL,
        direccion TEXT NOT NULL,
        rfc VARCHAR(20),
        tipo_plan ENUM('basico', 'profesional', 'enterprise') NOT NULL DEFAULT 'basico',
        estado ENUM('pendiente', 'activo', 'inactivo', 'rechazado') NOT NULL DEFAULT 'pendiente',
        fecha_creacion DATETIME NOT NULL,
        fecha_activacion DATETIME
    )";
    
    $db->query($sql);
    
    if ($db->execute()) {
        echo "La tabla talleres se ha creado correctamente con el campo id_taller.\n";
    } else {
        echo "Error al crear la tabla talleres: " . $db->getError() . "\n";
    }
}

echo "Proceso completado.\n";
