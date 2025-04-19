<?php
/**
 * Script para probar la inserción en la tabla solicitudes_talleres
 */

// Configuración de logs
$log_file = __DIR__ . '/logs/test_insert.log';
file_put_contents($log_file, "Iniciando prueba de inserción - " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

// Incluir configuración de base de datos
require_once __DIR__ . '/config/database.php';

try {
    // Crear conexión a la base de datos
    $db = new Database();
    file_put_contents($log_file, "Conexión a la base de datos establecida\n", FILE_APPEND);
    
    // Verificar la base de datos actual
    $db->query("SELECT DATABASE()");
    $current_db = $db->single();
    file_put_contents($log_file, "Base de datos actual: " . print_r($current_db, true) . "\n", FILE_APPEND);
    
    // Verificar que la tabla existe
    $db->query("SHOW TABLES LIKE 'solicitudes_talleres'");
    $tabla_existe = $db->single();
    file_put_contents($log_file, "Tabla solicitudes_talleres existe: " . ($tabla_existe ? "Sí" : "No") . "\n", FILE_APPEND);
    
    if (!$tabla_existe) {
        file_put_contents($log_file, "La tabla solicitudes_talleres no existe. Creando tabla...\n", FILE_APPEND);
        
        // Crear la tabla si no existe
        $db->query("CREATE TABLE IF NOT EXISTS solicitudes_talleres (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_taller VARCHAR(100) NOT NULL,
            propietario VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            plan_seleccionado VARCHAR(20),
            direccion TEXT,
            estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente',
            fecha_solicitud DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            fecha_respuesta DATETIME,
            comentarios TEXT
        )");
        $db->execute();
        file_put_contents($log_file, "Tabla creada correctamente\n", FILE_APPEND);
    }
    
    // Verificar la estructura de la tabla
    $db->query("DESCRIBE solicitudes_talleres");
    $estructura = $db->resultset();
    file_put_contents($log_file, "Estructura de la tabla solicitudes_talleres:\n", FILE_APPEND);
    foreach ($estructura as $campo) {
        file_put_contents($log_file, "Campo: " . $campo['Field'] . ", Tipo: " . $campo['Type'] . ", Nulo: " . $campo['Null'] . "\n", FILE_APPEND);
    }
    
    // Datos de prueba
    $nombre_taller = "Taller de Prueba Script";
    $propietario = "Usuario de Prueba";
    $email = "prueba_script@test.com";
    $telefono = "1234567890";
    $direccion = "Dirección de prueba";
    $plan = "basico";
    
    // Probar inserción directa sin prepared statements
    $sql = "INSERT INTO solicitudes_talleres (nombre_taller, propietario, email, telefono, direccion, plan_seleccionado, estado, fecha_solicitud) 
            VALUES ('$nombre_taller', '$propietario', '$email', '$telefono', '$direccion', '$plan', 'pendiente', NOW())";
    
    file_put_contents($log_file, "Intentando inserción directa con: $sql\n", FILE_APPEND);
    
    $result_direct = $db->conn->query($sql);
    file_put_contents($log_file, "Resultado inserción directa: " . ($result_direct ? "Éxito" : "Error") . "\n", FILE_APPEND);
    
    if (!$result_direct) {
        file_put_contents($log_file, "Error en inserción directa: " . print_r($db->conn->errorInfo(), true) . "\n", FILE_APPEND);
    } else {
        $id_insertado = $db->conn->lastInsertId();
        file_put_contents($log_file, "ID insertado: $id_insertado\n", FILE_APPEND);
    }
    
    // Probar inserción con prepared statements
    file_put_contents($log_file, "Intentando inserción con prepared statements\n", FILE_APPEND);
    
    $email2 = "prueba_script2@test.com"; // Email diferente para evitar duplicados
    
    $db->query("INSERT INTO solicitudes_talleres (nombre_taller, propietario, email, telefono, direccion, plan_seleccionado, estado, fecha_solicitud) 
                VALUES (?, ?, ?, ?, ?, ?, 'pendiente', NOW())");
    $db->bind(1, $nombre_taller);
    $db->bind(2, $propietario);
    $db->bind(3, $email2);
    $db->bind(4, $telefono);
    $db->bind(5, $direccion);
    $db->bind(6, $plan);
    
    $result_prepared = $db->execute();
    file_put_contents($log_file, "Resultado inserción con prepared statements: " . ($result_prepared ? "Éxito" : "Error - " . $db->getError()) . "\n", FILE_APPEND);
    
    if ($result_prepared) {
        $id_insertado2 = $db->lastInsertId();
        file_put_contents($log_file, "ID insertado (prepared): $id_insertado2\n", FILE_APPEND);
    }
    
    // Verificar los registros insertados
    $db->query("SELECT * FROM solicitudes_talleres WHERE email IN (?, ?)");
    $db->bind(1, $email);
    $db->bind(2, $email2);
    $registros = $db->resultset();
    
    file_put_contents($log_file, "Registros encontrados: " . count($registros) . "\n", FILE_APPEND);
    file_put_contents($log_file, "Datos de los registros: " . print_r($registros, true) . "\n", FILE_APPEND);
    
    echo "Prueba completada. Revisa el archivo de log: logs/test_insert.log";
    
} catch (Exception $e) {
    file_put_contents($log_file, "Error: " . $e->getMessage() . "\n", FILE_APPEND);
    file_put_contents($log_file, "Trace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
    echo "Error: " . $e->getMessage();
}
