<?php
/**
 * Script para corregir la codificación de los datos en la base de datos
 * 
 * Este script corrige los problemas de codificación en las tablas:
 * - solicitudes_talleres
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
    <title>Corrección de Codificación en Base de Datos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .btn { 
            display: inline-block; 
            padding: 10px 15px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px;
            margin-right: 10px;
        }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Corrección de Codificación en Base de Datos</h1>
    
    <?php
    // Verificar si se ha enviado el formulario para corregir
    $mensaje = '';
    $clase = '';
    
    if (isset($_POST['corregir']) && $_POST['corregir'] == '1') {
        try {
            $db = Database::getInstance();
            
            // Iniciar transacción para seguridad
            $db->beginTransaction();
            
            // 1. Corregir tabla solicitudes_talleres
            $stmt = $db->prepare("
                UPDATE solicitudes_talleres 
                SET 
                    nombre_taller = CONVERT(CAST(CONVERT(nombre_taller USING latin1) AS BINARY) USING utf8mb4),
                    propietario = CONVERT(CAST(CONVERT(propietario USING latin1) AS BINARY) USING utf8mb4)
            ");
            $stmt->execute();
            $filasActualizadas = $stmt->rowCount();
            
            // 2. Configurar la conexión para usar siempre utf8mb4
            $db->exec("SET NAMES utf8mb4");
            
            // Confirmar cambios
            $db->commit();
            
            $mensaje = "¡Corrección exitosa! Se actualizaron {$filasActualizadas} registros en la tabla solicitudes_talleres.";
            $clase = 'success';
            
        } catch (PDOException $e) {
            // Revertir cambios en caso de error
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            
            $mensaje = "Error al corregir la base de datos: " . $e->getMessage();
            $clase = 'error';
        }
    }
    
    // Mostrar mensaje si existe
    if (!empty($mensaje)) {
        echo "<div class='{$clase}' style='padding: 15px; margin-bottom: 20px;'>{$mensaje}</div>";
    }
    ?>
    
    <h2>Datos Actuales en la Tabla solicitudes_talleres</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre Taller</th>
            <th>Propietario</th>
            <th>Email</th>
            <th>Estado</th>
            <th>Fecha Solicitud</th>
        </tr>
        <?php
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM solicitudes_talleres ORDER BY fecha_solicitud DESC LIMIT 10");
            $stmt->execute();
            $solicitudes = $stmt->fetchAll();
            
            foreach ($solicitudes as $solicitud) {
                echo "<tr>";
                echo "<td>{$solicitud['id']}</td>";
                echo "<td>{$solicitud['nombre_taller']}</td>";
                echo "<td>{$solicitud['propietario']}</td>";
                echo "<td>{$solicitud['email']}</td>";
                echo "<td>{$solicitud['estado']}</td>";
                echo "<td>{$solicitud['fecha_solicitud']}</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='6' class='error'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
    
    <h2>Acciones</h2>
    <form method="post" onsubmit="return confirm('¿Estás seguro de que quieres corregir la codificación de los datos? Se recomienda hacer una copia de seguridad antes de continuar.');">
        <input type="hidden" name="corregir" value="1">
        <button type="submit" class="btn">Corregir Codificación de Datos</button>
        <a href="index.php?page=dashboard" class="btn" style="background-color: #6c757d;">Volver al Dashboard</a>
    </form>
    
    <h2>Información Técnica</h2>
    <p>Este script realiza las siguientes acciones:</p>
    <ol>
        <li>Convierte los datos de la tabla solicitudes_talleres de latin1 a utf8mb4</li>
        <li>Configura la conexión para usar siempre utf8mb4</li>
    </ol>
    
    <p><strong>Nota:</strong> Es recomendable hacer una copia de seguridad de la base de datos antes de ejecutar esta corrección.</p>
</body>
</html>
