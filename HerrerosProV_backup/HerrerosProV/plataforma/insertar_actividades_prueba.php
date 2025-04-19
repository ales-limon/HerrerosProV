<?php
// Configuración básica
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir archivos necesarios
require_once __DIR__ . '/config/database.php';

// Obtener conexión a la base de datos
$db = Database::getInstance();

// Verificar si la tabla existe
$stmt = $db->prepare("SHOW TABLES LIKE 'actividad_plataforma'");
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo "<p>La tabla actividad_plataforma no existe. Creando tabla...</p>";
    
    // Crear la tabla
    $sql = "
    CREATE TABLE actividad_plataforma (
        id_actividad INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT,
        tipo_actividad VARCHAR(50) NOT NULL,
        descripcion TEXT NOT NULL,
        entidad VARCHAR(50),
        id_entidad INT,
        fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_usuario) REFERENCES usuarios_plataforma(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    
    try {
        $db->exec($sql);
        echo "<p>Tabla actividad_plataforma creada exitosamente.</p>";
    } catch (PDOException $e) {
        echo "<p>Error al crear la tabla: " . $e->getMessage() . "</p>";
        exit;
    }
}

// Insertar datos de prueba
try {
    // Limpiar tabla existente
    $db->exec("TRUNCATE TABLE actividad_plataforma");
    echo "<p>Tabla actividad_plataforma limpiada.</p>";
    
    // Datos de prueba
    $actividades = [
        [
            'id_usuario' => 1,
            'tipo_actividad' => 'login',
            'descripcion' => 'Inicio de sesión en el sistema',
            'entidad' => 'usuario',
            'id_entidad' => 1
        ],
        [
            'id_usuario' => 1,
            'tipo_actividad' => 'crear',
            'descripcion' => 'Creación de nuevo taller: Herrería Moderna',
            'entidad' => 'taller',
            'id_entidad' => 1
        ],
        [
            'id_usuario' => 1,
            'tipo_actividad' => 'aprobar',
            'descripcion' => 'Aprobación de solicitud #123',
            'entidad' => 'solicitud',
            'id_entidad' => 123
        ],
        [
            'id_usuario' => 1,
            'tipo_actividad' => 'editar',
            'descripcion' => 'Actualización de perfil de usuario',
            'entidad' => 'usuario',
            'id_entidad' => 1
        ],
        [
            'id_usuario' => 1,
            'tipo_actividad' => 'logout',
            'descripcion' => 'Cierre de sesión',
            'entidad' => 'usuario',
            'id_entidad' => 1
        ]
    ];
    
    // Preparar consulta
    $stmt = $db->prepare("
        INSERT INTO actividad_plataforma 
        (id_usuario, tipo_actividad, descripcion, entidad, id_entidad, fecha_creacion) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    // Insertar cada actividad con una fecha diferente
    $i = 0;
    foreach ($actividades as $actividad) {
        // Calcular fecha (cada una 1 hora antes que la anterior)
        $fecha = date('Y-m-d H:i:s', strtotime("-{$i} hour"));
        $i++;
        
        $stmt->execute([
            $actividad['id_usuario'],
            $actividad['tipo_actividad'],
            $actividad['descripcion'],
            $actividad['entidad'],
            $actividad['id_entidad'],
            $fecha
        ]);
    }
    
    echo "<p>Se insertaron " . count($actividades) . " actividades de prueba.</p>";
    echo "<p><a href='test_actividad_simple.php'>Ver datos de actividad</a></p>";
    echo "<p><a href='views/dashboard.php'>Ir al dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<p>Error al insertar datos: " . $e->getMessage() . "</p>";
}
?>
