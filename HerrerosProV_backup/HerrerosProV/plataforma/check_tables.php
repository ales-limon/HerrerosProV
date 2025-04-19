<?php
/**
 * Script para verificar las tablas de la base de datos
 */

// Incluir configuración de base de datos
require_once __DIR__ . '/config/database.php';

// Obtener conexión a la base de datos
$db = Database::getInstance();

// Obtener todas las tablas
try {
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Tablas en la base de datos:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Verificar si existe la tabla actividad_plataforma
    if (in_array('actividad_plataforma', $tables)) {
        echo "<h3>Estructura de la tabla actividad_plataforma:</h3>";
        $columns = $db->query("DESCRIBE actividad_plataforma")->fetchAll(PDO::FETCH_ASSOC);
        echo "<table border='1'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Predeterminado</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            foreach ($column as $key => $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        // Mostrar algunos registros
        echo "<h3>Registros en actividad_plataforma:</h3>";
        $activities = $db->query("SELECT * FROM actividad_plataforma LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        if (count($activities) > 0) {
            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($activities[0]) as $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "</tr>";
            foreach ($activities as $activity) {
                echo "<tr>";
                foreach ($activity as $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay registros en la tabla actividad_plataforma.</p>";
        }
    }
    
    // Verificar si existe la tabla registro_actividad
    if (in_array('registro_actividad', $tables)) {
        echo "<h3>Estructura de la tabla registro_actividad:</h3>";
        $columns = $db->query("DESCRIBE registro_actividad")->fetchAll(PDO::FETCH_ASSOC);
        echo "<table border='1'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Predeterminado</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            foreach ($column as $key => $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        // Mostrar algunos registros
        echo "<h3>Registros en registro_actividad:</h3>";
        $activities = $db->query("SELECT * FROM registro_actividad LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        if (count($activities) > 0) {
            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($activities[0]) as $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "</tr>";
            foreach ($activities as $activity) {
                echo "<tr>";
                foreach ($activity as $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay registros en la tabla registro_actividad.</p>";
        }
    } else {
        echo "<h3>La tabla registro_actividad no existe.</h3>";
        echo "<p>Necesitamos crear esta tabla para que el dashboard muestre la actividad reciente.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>Error al consultar la base de datos:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
