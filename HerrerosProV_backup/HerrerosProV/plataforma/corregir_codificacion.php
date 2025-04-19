<?php
/**
 * Script para corregir la codificación de los datos en la tabla solicitudes_talleres
 */

// Cargar configuración de base de datos
require_once 'config/database.php';

// Función para corregir la codificación
function corregirCodificacion($texto) {
    // Intentar diferentes conversiones hasta encontrar la correcta
    $opciones = [
        'UTF-8' => $texto,
        'ISO-8859-1 a UTF-8' => mb_convert_encoding($texto, 'UTF-8', 'ISO-8859-1'),
        'Windows-1252 a UTF-8' => mb_convert_encoding($texto, 'UTF-8', 'Windows-1252'),
        'Latin1 a UTF-8' => mb_convert_encoding($texto, 'UTF-8', 'Latin1')
    ];
    
    return $opciones;
}

// Obtener datos de la tabla
$db = Database::getInstance();
$stmt = $db->prepare("SELECT id, nombre_taller, propietario FROM solicitudes_talleres ORDER BY fecha_solicitud DESC LIMIT 5");
$stmt->execute();
$solicitudes = $stmt->fetchAll();

// Mostrar resultados
echo "<h1>Diagnóstico de Codificación</h1>";

echo "<h2>Datos Originales y Opciones de Corrección</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Nombre Taller</th><th>Propietario</th></tr>";

foreach ($solicitudes as $solicitud) {
    echo "<tr>";
    echo "<td>{$solicitud['id']}</td>";
    echo "<td>{$solicitud['nombre_taller']}</td>";
    echo "<td>{$solicitud['propietario']}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Opciones de Corrección</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Codificación</th><th>Nombre Taller</th><th>Propietario</th></tr>";

foreach ($solicitudes as $solicitud) {
    $opcionesTaller = corregirCodificacion($solicitud['nombre_taller']);
    $opcionesPropietario = corregirCodificacion($solicitud['propietario']);
    
    foreach ($opcionesTaller as $tipo => $nombreTaller) {
        $propietario = $opcionesPropietario[$tipo];
        echo "<tr>";
        echo "<td>{$solicitud['id']}</td>";
        echo "<td>{$tipo}</td>";
        echo "<td>{$nombreTaller}</td>";
        echo "<td>{$propietario}</td>";
        echo "</tr>";
    }
}

echo "</table>";

// Sugerir solución para el dashboard
echo "<h2>Código Sugerido para el Dashboard</h2>";
echo "<pre>";
echo htmlspecialchars('
// Corregir la codificación de los datos
$nombreTaller = mb_convert_encoding($solicitud[\'nombre_taller\'], \'UTF-8\', \'ISO-8859-1\');
$propietario = mb_convert_encoding($solicitud[\'propietario\'], \'UTF-8\', \'ISO-8859-1\');

// O si eso no funciona, prueba con:
$nombreTaller = mb_convert_encoding($solicitud[\'nombre_taller\'], \'UTF-8\', \'Windows-1252\');
$propietario = mb_convert_encoding($solicitud[\'propietario\'], \'UTF-8\', \'Windows-1252\');
');
echo "</pre>";
