<?php
// Configuración básica
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir archivos necesarios
require_once __DIR__ . '/config/database.php';

// Obtener conexión a la base de datos
$db = Database::getInstance();

// Consulta directa a la tabla actividad_plataforma
echo "<h1>Datos de la tabla actividad_plataforma</h1>";

try {
    // Verificar si la tabla existe
    $stmt = $db->prepare("SHOW TABLES LIKE 'actividad_plataforma'");
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        echo "<p>La tabla actividad_plataforma no existe en la base de datos.</p>";
    } else {
        // Consultar datos
        $stmt = $db->prepare("SELECT * FROM actividad_plataforma ORDER BY fecha_creacion DESC");
        $stmt->execute();
        $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($actividades) > 0) {
            echo "<p>Se encontraron " . count($actividades) . " registros.</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr>";
            foreach (array_keys($actividades[0]) as $columna) {
                echo "<th>" . htmlspecialchars($columna) . "</th>";
            }
            echo "</tr>";
            
            foreach ($actividades as $actividad) {
                echo "<tr>";
                foreach ($actividad as $valor) {
                    echo "<td>" . htmlspecialchars($valor ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No se encontraron registros en la tabla actividad_plataforma.</p>";
        }
    }
    
    // Verificar estructura de la tabla usuarios_plataforma
    echo "<h1>Estructura de la tabla usuarios_plataforma</h1>";
    $stmt = $db->prepare("DESCRIBE usuarios_plataforma");
    $stmt->execute();
    $estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($estructura) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th><th>Extra</th></tr>";
        
        foreach ($estructura as $campo) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($campo['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($campo['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($campo['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($campo['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($campo['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($campo['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se pudo obtener la estructura de la tabla usuarios_plataforma.</p>";
    }
    
    // Probar la consulta JOIN que se usa en el dashboard
    echo "<h1>Prueba de consulta JOIN</h1>";
    $stmt = $db->prepare("
        SELECT 
            a.id_actividad,
            a.tipo_actividad,
            a.descripcion,
            a.fecha_creacion as fecha,
            u.nombre as usuario,
            a.entidad,
            a.id_entidad
        FROM actividad_plataforma a
        LEFT JOIN usuarios_plataforma u ON a.id_usuario = u.id
        ORDER BY a.fecha_creacion DESC
        LIMIT 5
    ");
    $stmt->execute();
    $actividades_join = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($actividades_join) > 0) {
        echo "<p>Se encontraron " . count($actividades_join) . " registros con JOIN.</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr>";
        foreach (array_keys($actividades_join[0]) as $columna) {
            echo "<th>" . htmlspecialchars($columna) . "</th>";
        }
        echo "</tr>";
        
        foreach ($actividades_join as $actividad) {
            echo "<tr>";
            foreach ($actividad as $valor) {
                echo "<td>" . htmlspecialchars($valor ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron registros en la consulta JOIN.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>Error en la base de datos: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
