<?php
/**
 * Script para crear la tabla registro_actividad
 * Esta tabla almacenará la actividad reciente de los usuarios en la plataforma
 */

// Incluir configuración de base de datos
require_once __DIR__ . '/../config/database.php';

// Obtener conexión a la base de datos
$db = Database::getInstance();

try {
    // Verificar si la tabla ya existe
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('registro_actividad', $tables)) {
        echo "La tabla registro_actividad ya existe. ¿Desea eliminarla y recrearla? (s/n): ";
        $handle = fopen("php://stdin", "r");
        $line = trim(fgets($handle));
        if (strtolower($line) != 's') {
            echo "Operación cancelada.\n";
            exit;
        }
        
        // Eliminar la tabla existente
        $db->exec("DROP TABLE registro_actividad");
        echo "Tabla registro_actividad eliminada.\n";
    }
    
    // Crear la tabla registro_actividad
    $sql = "CREATE TABLE registro_actividad (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(100) NOT NULL,
        descripcion TEXT NOT NULL,
        tipo VARCHAR(50) NOT NULL,
        fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        id_usuario INT,
        id_referencia INT,
        entidad VARCHAR(50)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->exec($sql);
    echo "Tabla registro_actividad creada exitosamente.\n";
    
    // Insertar datos de ejemplo
    $datos = [
        [
            'usuario' => 'admin',
            'descripcion' => 'Inició sesión en el sistema',
            'tipo' => 'login',
            'fecha' => '2025-03-15 10:00:00',
            'id_usuario' => 1,
            'id_referencia' => null,
            'entidad' => null
        ],
        [
            'usuario' => 'admin',
            'descripcion' => 'Aprobó la solicitud del taller "Herrería Moderna"',
            'tipo' => 'solicitud',
            'fecha' => '2025-03-15 10:15:00',
            'id_usuario' => 1,
            'id_referencia' => 1,
            'entidad' => 'solicitudes_talleres'
        ],
        [
            'usuario' => 'supervisor',
            'descripcion' => 'Actualizó la información del taller "Taller Industrial XYZ"',
            'tipo' => 'taller',
            'fecha' => '2025-03-15 11:30:00',
            'id_usuario' => 2,
            'id_referencia' => 2,
            'entidad' => 'talleres'
        ],
        [
            'usuario' => 'admin',
            'descripcion' => 'Rechazó la solicitud del taller "Herrería Artesanal"',
            'tipo' => 'solicitud',
            'fecha' => '2025-03-15 12:45:00',
            'id_usuario' => 1,
            'id_referencia' => 3,
            'entidad' => 'solicitudes_talleres'
        ],
        [
            'usuario' => 'capturista',
            'descripcion' => 'Inició sesión en el sistema',
            'tipo' => 'login',
            'fecha' => '2025-03-15 14:00:00',
            'id_usuario' => 3,
            'id_referencia' => null,
            'entidad' => null
        ],
        [
            'usuario' => 'admin',
            'descripcion' => 'Creó un nuevo usuario "analista"',
            'tipo' => 'usuario',
            'fecha' => '2025-03-15 15:30:00',
            'id_usuario' => 1,
            'id_referencia' => 4,
            'entidad' => 'usuarios_plataforma'
        ],
        [
            'usuario' => 'supervisor',
            'descripcion' => 'Actualizó la suscripción del taller "Herrería Moderna"',
            'tipo' => 'suscripcion',
            'fecha' => '2025-03-15 16:45:00',
            'id_usuario' => 2,
            'id_referencia' => 1,
            'entidad' => 'suscripciones'
        ]
    ];
    
    $stmt = $db->prepare("INSERT INTO registro_actividad (usuario, descripcion, tipo, fecha, id_usuario, id_referencia, entidad) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($datos as $dato) {
        $stmt->execute([
            $dato['usuario'],
            $dato['descripcion'],
            $dato['tipo'],
            $dato['fecha'],
            $dato['id_usuario'],
            $dato['id_referencia'],
            $dato['entidad']
        ]);
    }
    
    echo "Se insertaron " . count($datos) . " registros de ejemplo.\n";
    
    echo "Proceso completado exitosamente.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
