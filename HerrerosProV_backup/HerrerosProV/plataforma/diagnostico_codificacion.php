<?php
/**
 * Script de diagnóstico para verificar problemas de codificación
 */

// Cargar configuración de base de datos
require_once 'config/database.php';

// Establecer cabeceras para mostrar correctamente caracteres UTF-8
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Codificación</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .debug { background-color: #f8f9fa; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Diagnóstico de Codificación</h1>
    
    <h2>Información de Conexión</h2>
    <div class="debug">
        <?php
        $db = Database::getInstance();
        $stmt = $db->query("SHOW VARIABLES LIKE 'character_set%'");
        $charsets = $stmt->fetchAll();
        
        echo "<table>";
        echo "<tr><th>Variable</th><th>Valor</th></tr>";
        foreach ($charsets as $charset) {
            echo "<tr><td>{$charset['Variable_name']}</td><td>{$charset['Value']}</td></tr>";
        }
        echo "</table>";
        ?>
    </div>
    
    <h2>Datos de Solicitudes</h2>
    <div class="debug">
        <?php
        try {
            $stmt = $db->prepare("SELECT * FROM solicitudes_talleres ORDER BY fecha_solicitud DESC LIMIT 10");
            $stmt->execute();
            $solicitudes = $stmt->fetchAll();
            
            if (count($solicitudes) > 0) {
                echo "<table>";
                echo "<tr>";
                foreach (array_keys($solicitudes[0]) as $column) {
                    echo "<th>{$column}</th>";
                }
                echo "</tr>";
                
                foreach ($solicitudes as $solicitud) {
                    echo "<tr>";
                    foreach ($solicitud as $key => $value) {
                        // Mostrar valores normales y hexadecimales para diagnóstico
                        if ($key == 'nombre_taller' || $key == 'propietario') {
                            echo "<td>{$value} <br><small>HEX: " . bin2hex($value) . "</small></td>";
                        } else {
                            echo "<td>{$value}</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay solicitudes para mostrar.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    
    <h2>Solución Propuesta</h2>
    <div class="debug">
        <?php
        try {
            $stmt = $db->prepare("SELECT id, nombre_taller, propietario FROM solicitudes_talleres ORDER BY fecha_solicitud DESC LIMIT 5");
            $stmt->execute();
            $solicitudes = $stmt->fetchAll();
            
            echo "<table>";
            echo "<tr><th>ID</th><th>Taller Original</th><th>Taller Corregido</th><th>Propietario Original</th><th>Propietario Corregido</th></tr>";
            
            foreach ($solicitudes as $solicitud) {
                // Intentar corregir la codificación
                $nombreCorregido = mb_convert_encoding($solicitud['nombre_taller'], 'UTF-8', 'ISO-8859-1');
                $propietarioCorregido = mb_convert_encoding($solicitud['propietario'], 'UTF-8', 'ISO-8859-1');
                
                echo "<tr>";
                echo "<td>{$solicitud['id']}</td>";
                echo "<td>{$solicitud['nombre_taller']}</td>";
                echo "<td>{$nombreCorregido}</td>";
                echo "<td>{$solicitud['propietario']}</td>";
                echo "<td>{$propietarioCorregido}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
</body>
</html>
