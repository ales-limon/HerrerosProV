<?php
/**
 * Barra de navegación principal para la parte pública de HerrerosPro
 */
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="<?php echo PUBLIC_URL; ?>">
            <img src="<?php echo ASSETS_URL; ?>img/logo.png" alt="HerrerosPro Logo" height="40">
        </a>

        <!-- Botón hamburguesa para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú principal -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'inicio') ? 'active' : ''; ?>" href="<?php echo PUBLIC_URL; ?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'planes') ? 'active' : ''; ?>" href="<?php echo PUBLIC_URL; ?>public/views/planes.php">Planes y Precios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'demo') ? 'active' : ''; ?>" href="<?php echo PUBLIC_URL; ?>public/views/demo.php">Demo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'contacto') ? 'active' : ''; ?>" href="<?php echo PUBLIC_URL; ?>public/views/contacto.php">Contacto</a>
                </li>
            </ul>

            <!-- Botones de acceso -->
            <div class="d-flex">
                <a href="<?php echo PUBLIC_URL; ?>public/views/login.php" class="btn btn-outline-primary me-2">
                    <i class="fas fa-sign-in-alt"></i> Acceder
                </a>
                <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Registrarse
                </a>
            </div>
        </div>
    </div>
</nav>
<!-- Espaciador para compensar el navbar fixed -->
<div style="margin-top: 76px;"></div> 