<?php
/**
 * Script de diagnóstico para la configuración de la base de datos
 * 
 * Este script muestra información detallada sobre:
 * - Variables de sistema de MySQL relacionadas con la codificación
 * - Configuración de las tablas (charset y collation)
 * - Configuración de las columnas en tablas específicas
 */

// Cargar configuración de base de datos
require_once __DIR__ . '/config/database.php';

// Incluir archivo de autenticación si existe
if (file_exists(__DIR__ . '/config/auth.php')) {
    require_once __DIR__ . '/config/auth.php';
}

// Establecer cabeceras para mostrar correctamente caracteres UTF-8
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Configuración de Base de Datos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        h1, h2, h3 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; }
        .warning { color: orange; }
        .error { color: red; }
        .info { color: blue; }
        pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow: auto; }
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
        .container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Diagnóstico de Configuración de Base de Datos</h1>
        
        <h2>Variables del Sistema MySQL</h2>
        <table>
            <tr>
                <th>Variable</th>
                <th>Valor</th>
                <th>Recomendación</th>
            </tr>
            <?php
            try {
                $db = Database::getInstance();
                $stmt = $db->prepare("SHOW VARIABLES LIKE '%character%'");
                $stmt->execute();
                $variables = $stmt->fetchAll();
                
                foreach ($variables as $variable) {
                    $recomendacion = '';
                    $class = 'info';
                    
                    // Verificar si la variable tiene un valor recomendado
                    if ($variable['Variable_name'] == 'character_set_server' && $variable['Value'] != 'utf8mb4') {
                        $recomendacion = 'Debería ser utf8mb4';
                        $class = 'warning';
                    } elseif ($variable['Variable_name'] == 'character_set_database' && $variable['Value'] != 'utf8mb4') {
                        $recomendacion = 'Debería ser utf8mb4';
                        $class = 'warning';
                    } elseif ($variable['Variable_name'] == 'character_set_client' && $variable['Value'] != 'utf8mb4') {
                        $recomendacion = 'Debería ser utf8mb4';
                        $class = 'warning';
                    } elseif ($variable['Variable_name'] == 'character_set_connection' && $variable['Value'] != 'utf8mb4') {
                        $recomendacion = 'Debería ser utf8mb4';
                        $class = 'warning';
                    } elseif ($variable['Variable_name'] == 'character_set_results' && $variable['Value'] != 'utf8mb4') {
                        $recomendacion = 'Debería ser utf8mb4';
                        $class = 'warning';
                    }
                    
                    echo "<tr>";
                    echo "<td>{$variable['Variable_name']}</td>";
                    echo "<td>{$variable['Value']}</td>";
                    echo "<td class='{$class}'>{$recomendacion}</td>";
                    echo "</tr>";
                }
                
                // Mostrar variables de collation
                $stmt = $db->prepare("SHOW VARIABLES LIKE '%collation%'");
                $stmt->execute();
                $variables = $stmt->fetchAll();
                
                foreach ($variables as $variable) {
                    $recomendacion = '';
                    $class = 'info';
                    
                    // Verificar si la variable tiene un valor recomendado
                    if ($variable['Variable_name'] == 'collation_server' && $variable['Value'] != 'utf8mb4_unicode_ci') {
                        $recomendacion = 'Debería ser utf8mb4_unicode_ci';
                        $class = 'warning';
                    } elseif ($variable['Variable_name'] == 'collation_database' && $variable['Value'] != 'utf8mb4_unicode_ci') {
                        $recomendacion = 'Debería ser utf8mb4_unicode_ci';
                        $class = 'warning';
                    } elseif ($variable['Variable_name'] == 'collation_connection' && $variable['Value'] != 'utf8mb4_unicode_ci') {
                        $recomendacion = 'Debería ser utf8mb4_unicode_ci';
                        $class = 'warning';
                    }
                    
                    echo "<tr>";
                    echo "<td>{$variable['Variable_name']}</td>";
                    echo "<td>{$variable['Value']}</td>";
                    echo "<td class='{$class}'>{$recomendacion}</td>";
                    echo "</tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='3' class='error'>Error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </table>
        
        <h2>Configuración de Tablas</h2>
        <table>
            <tr>
                <th>Tabla</th>
                <th>Motor</th>
                <th>Charset</th>
                <th>Collation</th>
                <th>Recomendación</th>
            </tr>
            <?php
            try {
                $stmt = $db->prepare("SHOW TABLE STATUS FROM " . DB_NAME);
                $stmt->execute();
                $tables = $stmt->fetchAll();
                
                foreach ($tables as $table) {
                    $recomendacion = '';
                    $class = 'info';
                    
                    // Verificar si la tabla tiene la configuración recomendada
                    if ($table['Collation'] != 'utf8mb4_unicode_ci') {
                        $recomendacion = 'Debería usar utf8mb4_unicode_ci';
                        $class = 'warning';
                    }
                    
                    echo "<tr>";
                    echo "<td>{$table['Name']}</td>";
                    echo "<td>{$table['Engine']}</td>";
                    echo "<td>" . (strpos($table['Collation'], 'utf8mb4') === 0 ? 'utf8mb4' : substr($table['Collation'], 0, strpos($table['Collation'], '_'))) . "</td>";
                    echo "<td>{$table['Collation']}</td>";
                    echo "<td class='{$class}'>{$recomendacion}</td>";
                    echo "</tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='5' class='error'>Error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </table>
        
        <h2>Configuración de Columnas en Tablas Específicas</h2>
        <?php
        $tablasImportantes = ['solicitudes_talleres', 'actividad_plataforma', 'usuarios_plataforma'];
        
        foreach ($tablasImportantes as $tabla) {
            echo "<h3>Tabla: {$tabla}</h3>";
            echo "<table>";
            echo "<tr><th>Columna</th><th>Tipo</th><th>Charset</th><th>Collation</th><th>Recomendación</th></tr>";
            
            try {
                $stmt = $db->prepare("SHOW FULL COLUMNS FROM {$tabla}");
                $stmt->execute();
                $columnas = $stmt->fetchAll();
                
                foreach ($columnas as $columna) {
                    $recomendacion = '';
                    $class = 'info';
                    $charset = $columna['Collation'] ? (strpos($columna['Collation'], 'utf8mb4') === 0 ? 'utf8mb4' : substr($columna['Collation'], 0, strpos($columna['Collation'], '_'))) : 'N/A';
                    
                    // Verificar si la columna tiene la configuración recomendada
                    if ($columna['Collation'] && $columna['Collation'] != 'utf8mb4_unicode_ci') {
                        $recomendacion = 'Debería usar utf8mb4_unicode_ci';
                        $class = 'warning';
                    }
                    
                    echo "<tr>";
                    echo "<td>{$columna['Field']}</td>";
                    echo "<td>{$columna['Type']}</td>";
                    echo "<td>{$charset}</td>";
                    echo "<td>{$columna['Collation']}</td>";
                    echo "<td class='{$class}'>{$recomendacion}</td>";
                    echo "</tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='5' class='error'>Error: " . $e->getMessage() . "</td></tr>";
            }
            
            echo "</table>";
        }
        ?>
        
        <h2>Solución Recomendada</h2>
        <div>
            <p>Para corregir los problemas de codificación en la base de datos, se recomienda:</p>
            <ol>
                <li>Convertir todas las tablas a utf8mb4 con collation utf8mb4_unicode_ci</li>
                <li>Asegurar que la conexión siempre use utf8mb4 (ya implementado en database.php)</li>
                <li>Configurar el servidor MySQL para usar utf8mb4 por defecto</li>
            </ol>
            
            <h3>Consulta SQL para convertir todas las tablas:</h3>
            <pre>
ALTER DATABASE `<?= DB_NAME ?>` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

<?php
try {
    $stmt = $db->prepare("SHOW TABLES FROM " . DB_NAME);
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
    }
} catch (Exception $e) {
    echo "-- Error al generar consultas: " . $e->getMessage();
}
?>
            </pre>
            
            <h3>Configuración en my.ini (MySQL) o my.cnf (MariaDB):</h3>
            <pre>
[client]
default-character-set = utf8mb4

[mysql]
default-character-set = utf8mb4

[mysqld]
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci
            </pre>
        </div>
        
        <h2>Acciones</h2>
        <p>
            <a href="index.php?page=dashboard" class="btn">Volver al Dashboard</a>
            <a href="corregir_base_datos.php" class="btn">Ir a Corregir Base de Datos</a>
        </p>
    </div>
</body>
</html>
