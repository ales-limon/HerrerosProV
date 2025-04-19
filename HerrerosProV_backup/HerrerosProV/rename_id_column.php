<?php
/**
 * Script para renombrar la columna id a id_taller en la tabla talleres
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
    $campo_id_taller_existe = $db->single();
    
    // Verificar si el campo id existe
    $db->query("SHOW COLUMNS FROM talleres LIKE 'id'");
    $campo_id_existe = $db->single();
    
    if ($campo_id_taller_existe) {
        echo "El campo id_taller ya existe en la tabla talleres.\n";
        
        // Si ambos campos existen, eliminar id_taller (el que acabamos de crear)
        if ($campo_id_existe) {
            echo "Eliminando el campo id_taller que acabamos de crear...\n";
            $db->query("ALTER TABLE talleres DROP COLUMN id_taller");
            
            if ($db->execute()) {
                echo "Campo id_taller eliminado correctamente.\n";
            } else {
                echo "Error al eliminar el campo id_taller: " . $db->getError() . "\n";
                exit;
            }
        }
    }
    
    if ($campo_id_existe) {
        echo "Renombrando el campo id a id_taller...\n";
        
        // Renombrar el campo id a id_taller manteniendo sus propiedades
        $db->query("ALTER TABLE talleres CHANGE COLUMN id id_taller INT AUTO_INCREMENT PRIMARY KEY");
        
        if ($db->execute()) {
            echo "El campo id ha sido renombrado a id_taller correctamente.\n";
        } else {
            echo "Error al renombrar el campo id: " . $db->getError() . "\n";
        }
    } else {
        echo "El campo id no existe en la tabla talleres.\n";
    }
} else {
    echo "La tabla talleres no existe en la base de datos.\n";
}

echo "Proceso completado.\n";
