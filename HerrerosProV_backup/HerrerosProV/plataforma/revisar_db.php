<?php
/**
 * Script simple para revisar la configuración de la base de datos
 */

// Definir constantes de base de datos si no están definidas
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'herrerospro_plataforma');
    define('DB_CHARSET', 'utf8mb4');
}

// Establecer cabeceras para mostrar correctamente caracteres UTF-8
header('Content-Type: text/html; charset=utf-8');

// Función para conectar a la base de datos
function conectarDB() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        $pdo->exec("SET NAMES utf8mb4");
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Conectar a la base de datos
$db = conectarDB();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión de Base de Datos</title>
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
    </style>
</head>
<body>
    <h1>Revisión de Base de Datos</h1>
    
    <h2>Variables del Sistema MySQL</h2>
    <table>
        <tr>
            <th>Variable</th>
            <th>Valor</th>
        </tr>
        <?php
        try {
            $stmt = $db->query("SHOW VARIABLES LIKE '%character%'");
            $variables = $stmt->fetchAll();
            
            foreach ($variables as $variable) {
                echo "<tr>";
                echo "<td>{$variable['Variable_name']}</td>";
                echo "<td>{$variable['Value']}</td>";
                echo "</tr>";
            }
            
            // Mostrar variables de collation
            $stmt = $db->query("SHOW VARIABLES LIKE '%collation%'");
            $variables = $stmt->fetchAll();
            
            foreach ($variables as $variable) {
                echo "<tr>";
                echo "<td>{$variable['Variable_name']}</td>";
                echo "<td>{$variable['Value']}</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='2' class='error'>Error: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
    
    <h2>Configuración de Tablas</h2>
    <table>
        <tr>
            <th>Tabla</th>
            <th>Charset</th>
            <th>Collation</th>
        </tr>
        <?php
        try {
            $stmt = $db->query("SHOW TABLE STATUS FROM " . DB_NAME);
            $tables = $stmt->fetchAll();
            
            foreach ($tables as $table) {
                echo "<tr>";
                echo "<td>{$table['Name']}</td>";
                echo "<td>" . (strpos($table['Collation'], 'utf8mb4') === 0 ? 'utf8mb4' : substr($table['Collation'], 0, strpos($table['Collation'], '_'))) . "</td>";
                echo "<td>{$table['Collation']}</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='3' class='error'>Error: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
    
    <h2>Consultas para Corregir la Codificación</h2>
    <pre>
ALTER DATABASE `<?= DB_NAME ?>` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

<?php
try {
    $stmt = $db->query("SHOW TABLES FROM " . DB_NAME);
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
    }
} catch (PDOException $e) {
    echo "-- Error al generar consultas: " . $e->getMessage();
}
?>
    </pre>
    
    <h2>Datos de Ejemplo (solicitudes_talleres)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre Taller</th>
            <th>Propietario</th>
            <th>Email</th>
            <th>Estado</th>
        </tr>
        <?php
        try {
            $stmt = $db->query("SELECT * FROM solicitudes_talleres LIMIT 5");
            $solicitudes = $stmt->fetchAll();
            
            foreach ($solicitudes as $solicitud) {
                echo "<tr>";
                echo "<td>{$solicitud['id']}</td>";
                echo "<td>{$solicitud['nombre_taller']}</td>";
                echo "<td>{$solicitud['propietario']}</td>";
                echo "<td>{$solicitud['email']}</td>";
                echo "<td>{$solicitud['estado']}</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='5' class='error'>Error: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
</body>
</html>
