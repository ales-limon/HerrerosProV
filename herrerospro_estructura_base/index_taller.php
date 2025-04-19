<?php
// modules/ambito_talleres/index.php

require_once '../../includes/shared/common.php';

// Validar que el usuario tenga acceso
if (!esAdminTaller() && !esEmpleadoTaller()) {
    header('Location: ../ambito_publico/login.php');
    exit;
}

// Determinar qué módulo cargar
$modulo = $_GET['m'] ?? 'dashboard';

// Definir ruta de contenido y scripts
switch ($modulo) {
    case 'proyectos':
        $vistaActiva = 'modulos/proyectos/content_proyectos.php';
        $scriptsActivos = 'modulos/proyectos/scripts_proyectos.php';
        break;
    default:
        $vistaActiva = 'content_taller.php';
        $scriptsActivos = 'scripts_taller.php';
}

include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';
?>

<!-- Contenido principal -->
<div class="content-wrapper">
    <?php include $vistaActiva; ?>
</div>

<?php
include 'includes/footer.php';
include $scriptsActivos;
?>
