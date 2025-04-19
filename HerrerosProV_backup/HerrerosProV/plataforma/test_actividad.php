<?php
/**
 * Script de prueba para verificar la consulta de actividades
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Obtener instancia de la base de datos
$db = Database::getInstance();

// Verificar si existe la tabla
$stmt = $db->prepare("SHOW TABLES LIKE 'actividad_plataforma'");
$stmt->execute();

if ($stmt->rowCount() === 0) {
    die("La tabla actividad_plataforma no existe");
}

// Consultar datos de actividad
$stmt = $db->prepare("
    SELECT 
        a.id_actividad,
        a.id_usuario,
        a.tipo_actividad,
        a.descripcion,
        a.fecha_creacion,
        a.entidad,
        a.id_entidad,
        u.nombre as nombre_usuario
    FROM actividad_plataforma a
    LEFT JOIN usuarios_plataforma u ON a.id_usuario = u.id
    ORDER BY a.fecha_creacion DESC
    LIMIT 10
");
$stmt->execute();
$actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Datos de actividad_plataforma</h1>";
echo "<pre>";
print_r($actividades);
echo "</pre>";

// Verificar estructura de la tabla usuarios_plataforma
echo "<h1>Estructura de usuarios_plataforma</h1>";
$stmt = $db->prepare("DESCRIBE usuarios_plataforma");
$stmt->execute();
$estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($estructura);
echo "</pre>";
