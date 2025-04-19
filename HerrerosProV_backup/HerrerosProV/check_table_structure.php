<?php
/**
 * Script para verificar la estructura de la tabla solicitudes_talleres
 */

// Incluir configuración de base de datos
require_once __DIR__ . '/config/database.php';

// Crear conexión a la base de datos
$db = new Database();

// Verificar si la tabla solicitudes_talleres existe
$db->query("SHOW TABLES LIKE 'solicitudes_talleres'");
$tabla_existe = $db->single();

if ($tabla_existe) {
    echo "La tabla solicitudes_talleres existe.\n";
    
    // Obtener la estructura de la tabla
    $db->query("DESCRIBE solicitudes_talleres");
    $estructura = $db->resultset();
    
    echo "Estructura de la tabla solicitudes_talleres:\n";
    echo "----------------------------------------\n";
    echo "| Campo | Tipo | Nulo | Clave | Default | Extra |\n";
    echo "----------------------------------------\n";
    
    foreach ($estructura as $campo) {
        echo "| " . $campo['Field'] . " | " . $campo['Type'] . " | " . $campo['Null'] . " | " . $campo['Key'] . " | " . $campo['Default'] . " | " . $campo['Extra'] . " |\n";
    }
} else {
    echo "La tabla solicitudes_talleres no existe en la base de datos.\n";
    
    // Sugerir estructura para crear la tabla
    echo "Sugerencia de estructura para la tabla solicitudes_talleres:\n";
    echo "CREATE TABLE solicitudes_talleres (\n";
    echo "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
    echo "    nombre_taller VARCHAR(100) NOT NULL,\n";
    echo "    nombre_propietario VARCHAR(50) NOT NULL,\n";
    echo "    apellidos_propietario VARCHAR(50) NOT NULL,\n";
    echo "    email VARCHAR(100) NOT NULL UNIQUE,\n";
    echo "    telefono VARCHAR(20) NOT NULL,\n";
    echo "    direccion TEXT NOT NULL,\n";
    echo "    rfc VARCHAR(20),\n";
    echo "    tipo_plan ENUM('basico', 'profesional', 'enterprise') NOT NULL DEFAULT 'basico',\n";
    echo "    estado ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL DEFAULT 'pendiente',\n";
    echo "    fecha_solicitud DATETIME NOT NULL,\n";
    echo "    fecha_revision DATETIME\n";
    echo ");\n";
}

echo "\nCampos del formulario de registro:\n";
echo "- nombre (Nombre del propietario)\n";
echo "- apellidos (Apellidos del propietario)\n";
echo "- nombre_taller (Nombre del taller)\n";
echo "- rfc (RFC del taller, opcional)\n";
echo "- direccion (Dirección del taller)\n";
echo "- email (Correo electrónico)\n";
echo "- telefono (Teléfono)\n";
echo "- plan (Plan seleccionado: basico, profesional, enterprise)\n";
echo "- terminos (Checkbox de aceptación de términos)\n";
