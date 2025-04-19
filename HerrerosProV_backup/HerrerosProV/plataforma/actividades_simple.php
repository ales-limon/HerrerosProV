<?php
/**
 * Página simple para mostrar actividades recientes
 */

// Incluir configuración y base de datos
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

// Verificar autenticación
$auth = Auth::getInstance();
if (!$auth->isAuthenticated()) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Obtener conexión a la base de datos
$db = Database::getInstance();

// Consultar actividades recientes
try {
    $query = "
        SELECT 
            a.id_actividad,
            a.tipo_actividad,
            a.descripcion,
            a.fecha_creacion as fecha,
            u.nombre as usuario,
            a.entidad,
            a.id_entidad
        FROM actividad_plataforma a
        LEFT JOIN usuarios_plataforma u ON a.id_usuario = u.id
        ORDER BY a.fecha_creacion DESC
        LIMIT 10
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Error al consultar actividades: " . $e->getMessage();
    $actividades = [];
}

// Función para obtener icono según tipo de actividad
function getIconoActividad($tipo) {
    switch($tipo) {
        case 'login':
            return '<i class="fas fa-sign-in-alt text-success"></i>';
        case 'logout':
            return '<i class="fas fa-sign-out-alt text-danger"></i>';
        case 'aprobar':
            return '<i class="fas fa-check-circle text-success"></i>';
        case 'rechazar':
            return '<i class="fas fa-times-circle text-danger"></i>';
        case 'crear':
            return '<i class="fas fa-plus-circle text-primary"></i>';
        case 'editar':
            return '<i class="fas fa-edit text-info"></i>';
        default:
            return '<i class="fas fa-info-circle"></i>';
    }
}

// Función para formatear fecha
function formatearFecha($fecha) {
    $timestamp = strtotime($fecha);
    return date('d/m/Y H:i', $timestamp);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades Recientes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Actividades Recientes</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if (empty($actividades)): ?>
                            <div class="alert alert-info">No hay actividades recientes para mostrar.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuario</th>
                                            <th>Actividad</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($actividades as $actividad): ?>
                                            <tr>
                                                <td><?php echo $actividad['id_actividad']; ?></td>
                                                <td><?php echo $actividad['usuario'] ?? 'Sistema'; ?></td>
                                                <td>
                                                    <?php echo getIconoActividad($actividad['tipo_actividad']); ?> 
                                                    <?php echo htmlspecialchars($actividad['descripcion']); ?>
                                                </td>
                                                <td><?php echo formatearFecha($actividad['fecha']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <a href="<?php echo BASE_URL; ?>/views/dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
