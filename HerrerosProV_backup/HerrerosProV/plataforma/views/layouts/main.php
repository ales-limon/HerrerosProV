<?php
/**
 * Plantilla principal AdminLTE
 */

// Variable para la página actual
$currentPage = $currentPage ?? 'dashboard';
$pageTitle = $pageTitle ?? 'Dashboard';

// Incluir componentes usando rutas relativas
include_once __DIR__ . "/../includes/header.php";
?>

<div class="wrapper">
    <?php include_once __DIR__ . "/../includes/navbar.php"; ?>
    
    <?php include_once __DIR__ . "/../includes/sidebar.php"; ?>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php 
                // Mostrar el contenido principal
                if (empty($content)) {
                    echo '<div class="alert alert-warning">No hay contenido para mostrar</div>';
                } else {
                    echo $content;
                }
                ?>
            </div>
        </section>
    </div>
    
    <?php include_once __DIR__ . "/../includes/footer.php"; ?>
</div>

<?php 
// Incluir scripts adicionales si existen
if (isset($extraScripts)) echo $extraScripts; 
?>
