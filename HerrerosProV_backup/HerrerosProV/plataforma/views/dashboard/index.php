<?php
/**
 * Vista del Dashboard
 * Panel principal de la plataforma
 */

// Definir variables para la plantilla
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';

// Inicializar arrays de mensajes
$dashboardErrors = [];
$dashboardMessages = [];

// Obtener estadísticas
$stats = [
    'talleres' => 0,
    'solicitudes' => 0,
    'suscripciones' => 0,
    'usuarios' => 0
];

try {
    $db = new Database();
    
    // Verificar si las tablas existen antes de hacer las consultas
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('talleres', $tables)) {
        $stmt = $db->query("SELECT COUNT(*) FROM talleres WHERE estado = 'activo'");
        $stats['talleres'] = $stmt->fetchColumn();
    }
    
    if (in_array('solicitudes_talleres', $tables)) {
        $stmt = $db->query("SELECT COUNT(*) FROM solicitudes_talleres WHERE estado = 'pendiente'");
        $stats['solicitudes'] = $stmt->fetchColumn();
    }
    
    if (in_array('suscripciones', $tables)) {
        $stmt = $db->query("SELECT COUNT(*) FROM suscripciones WHERE estado = 'activa'");
        $stats['suscripciones'] = $stmt->fetchColumn();
    }
    
    if (in_array('usuarios_plataforma', $tables)) {
        $stmt = $db->query("SELECT COUNT(*) FROM usuarios_plataforma WHERE estado = 'activo'");
        $stats['usuarios'] = $stmt->fetchColumn();
    }
} catch (PDOException $e) {
    $dashboardErrors[] = "Error al cargar estadísticas: " . $e->getMessage();
    error_log("Error en dashboard: " . $e->getMessage());
}

// Pasar mensajes al JavaScript
$extraScripts = '
<script>
    var dashboardErrors = ' . json_encode($dashboardErrors) . ';
    var dashboardMessages = ' . json_encode($dashboardMessages) . ';
</script>';

// Iniciar buffer de salida
ob_start();
?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>plataforma/">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-industry"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Talleres Activos</span>
                        <span class="info-box-number"><?= $stats['talleres'] ?></span>
                    </div>
                </div>
            </div>
            
            <?php if (!isset($auth) || (isset($auth) && $auth->hasPermission('aprobar_solicitudes'))): ?>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Solicitudes Pendientes</span>
                        <span class="info-box-number"><?= $stats['solicitudes'] ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!isset($auth) || (isset($auth) && $auth->hasPermission('gestionar_suscripciones'))): ?>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-credit-card"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Suscripciones Activas</span>
                        <span class="info-box-number"><?= $stats['suscripciones'] ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!isset($auth) || (isset($auth) && $auth->hasRole('admin'))): ?>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Usuarios Activos</span>
                        <span class="info-box-number"><?= $stats['usuarios'] ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Mensaje de bienvenida -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bienvenido a HerrerosPro</h3>
            </div>
            <div class="card-body">
                <p>
                    Este es el panel de administración donde podrás gestionar talleres, solicitudes y suscripciones.
                </p>
                <p>
                    Selecciona una opción del menú lateral para comenzar.
                </p>
            </div>
        </div>
    </div>
</section>

<?php
// Capturar el contenido
$content = ob_get_clean();

// Incluir la plantilla principal
include __DIR__ . '/../../views/layouts/main.php';
?>
