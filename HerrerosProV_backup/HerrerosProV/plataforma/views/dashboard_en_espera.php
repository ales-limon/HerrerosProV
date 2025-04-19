<?php
/**
 * Dashboard de la Plataforma Admin
 * 
 * Vista principal que muestra el resumen de:
 * - Solicitudes pendientes
 * - Talleres activos
 * - Actividad reciente
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

// Verificar autenticación
$auth = Auth::getInstance();
if (!$auth->isAuthenticated()) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Variables para la plantilla
$pageTitle = 'Dashboard';
$extraStyles = '
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
';

// Contenido específico del dashboard
ob_start();
?>

<script>
    // Definir BASE_URL para uso en JavaScript
    var BASE_URL = '<?php echo BASE_URL; ?>';
</script>

<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clipboard-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Solicitudes Pendientes</span>
                <span class="info-box-number" id="solicitudesPendientes">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-store"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Talleres Activos</span>
                <span class="info-box-number" id="talleresActivos">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Actividad Reciente -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Últimas Solicitudes</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0" id="tablaSolicitudes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Taller</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Actividad Reciente</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0" id="tablaActividad">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Actividad</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Consultar actividades directamente
                            $db = Database::getInstance();
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
                                LIMIT 5
                            ";
                            
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                            $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($actividades) > 0) {
                                foreach ($actividades as $actividad) {
                                    // Determinar icono según tipo de actividad
                                    $icono = '';
                                    switch($actividad['tipo_actividad']) {
                                        case 'login':
                                            $icono = '<i class="fas fa-sign-in-alt text-success"></i> ';
                                            break;
                                        case 'logout':
                                            $icono = '<i class="fas fa-sign-out-alt text-danger"></i> ';
                                            break;
                                        case 'aprobar':
                                            $icono = '<i class="fas fa-check-circle text-success"></i> ';
                                            break;
                                        case 'rechazar':
                                            $icono = '<i class="fas fa-times-circle text-danger"></i> ';
                                            break;
                                        case 'crear':
                                            $icono = '<i class="fas fa-plus-circle text-primary"></i> ';
                                            break;
                                        case 'editar':
                                            $icono = '<i class="fas fa-edit text-info"></i> ';
                                            break;
                                        default:
                                            $icono = '<i class="fas fa-info-circle"></i> ';
                                    }
                                    
                                    // Formatear fecha
                                    $fecha = new DateTime($actividad['fecha']);
                                    $fechaFormateada = $fecha->format('d/m/Y H:i');
                                    
                                    echo '<tr>';
                                    echo '<td>' . ($actividad['usuario'] ?? 'Sistema') . '</td>';
                                    echo '<td>' . $icono . htmlspecialchars($actividad['descripcion']) . '</td>';
                                    echo '<td>' . $fechaFormateada . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-center">No hay actividad reciente</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Scripts adicionales para el dashboard
$extraScripts = '
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <!-- Dashboard Scripts -->
    <script src="' . BASE_URL . 'assets/js/dashboard.js"></script>
';

// Incluir la plantilla principal
require_once __DIR__ . '/layouts/main.php';
