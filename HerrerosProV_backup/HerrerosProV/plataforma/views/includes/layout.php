<?php
session_start();
require_once __DIR__ . '/../config/common.php';
require_once __DIR__ . '/../config/auth.php';

// Verificar autenticación
if (!isset($_SESSION['user_id']) || $_SESSION['user_ambito'] !== 'plataforma') {
    redirect('login.php');
}

// Obtener la vista solicitada
$view = $_GET['view'] ?? 'dashboard';
$subview = $_GET['subview'] ?? '';
$viewPath = __DIR__ . '/views/' . $view . '/';
$viewFile = $viewPath . ($subview ? $subview . '.php' : 'index.php');

// Verificar que la vista existe y es segura
if (!is_dir($viewPath) || !file_exists($viewFile) || !is_file($viewFile)) {
    $view = 'dashboard';
    $viewFile = __DIR__ . '/views/dashboard/index.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php 
        include 'includes/navbar.php';
        include 'includes/sidebar.php';
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include $viewFile; ?>
        </div>
        <!-- /.content-wrapper -->

        <?php include 'includes/footer.php'; ?>
    </div>
    <!-- ./wrapper -->
</body>
</html>
