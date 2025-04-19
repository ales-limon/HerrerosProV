<?php
// modules/ambito_talleres/includes/sidebar.php
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light">HerrerosPro Taller</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                
                <!-- Visible para todos -->
                <li class="nav-item">
                    <a href="index.php?m=dashboard" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                <?php if (esAdminTaller()): ?>
                <li class="nav-item">
                    <a href="index.php?m=proyectos" class="nav-link">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>Proyectos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?m=cotizaciones" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Cotizaciones</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (esEmpleadoTaller()): ?>
                <li class="nav-item">
                    <a href="index.php?m=asistencias" class="nav-link">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Asistencias</p>
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="../ambito_publico/login.php?logout=1" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Salir</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
