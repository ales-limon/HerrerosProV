<?php
/**
 * Barra lateral de navegación
 * Incluye el menú principal de la plataforma
 */
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= BASE_URL ?>plataforma/" class="brand-link">
        <img src="https://picsum.photos/id/137/200/200" alt="HerrerosPro Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">HerrerosPro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://picsum.photos/id/1005/200/200" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?= BASE_URL ?>plataforma/?page=perfil" class="d-block">
                    <?= isset($user['nombre']) ? htmlspecialchars($user['nombre']) : 'Administrador' ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>plataforma/?page=dashboard" class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <!-- Talleres -->
                <li class="nav-item <?= strpos($currentPage, 'talleres') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($currentPage, 'talleres') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Talleres
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=talleres/lista" class="nav-link <?= $currentPage == 'talleres/lista' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lista de Talleres</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=talleres/activos" class="nav-link <?= $currentPage == 'talleres/activos' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Talleres Activos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Solicitudes -->
                <?php if (!isset($auth) || (isset($auth) && $auth->hasPermission('aprobar_solicitudes'))): ?>
                <li class="nav-item <?= strpos($currentPage, 'solicitudes') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($currentPage, 'solicitudes') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Solicitudes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=solicitudes/lista" class="nav-link <?= $currentPage == 'solicitudes/lista' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lista de Solicitudes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=solicitudes/pendientes" class="nav-link <?= $currentPage == 'solicitudes/pendientes' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pendientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=solicitudes/historial" class="nav-link <?= $currentPage == 'solicitudes/historial' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Historial</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <!-- Suscripciones -->
                <?php if (!isset($auth) || (isset($auth) && $auth->hasPermission('gestionar_suscripciones'))): ?>
                <li class="nav-item <?= strpos($currentPage, 'suscripciones') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($currentPage, 'suscripciones') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Suscripciones
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=suscripciones/lista" class="nav-link <?= $currentPage == 'suscripciones/lista' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lista de Suscripciones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=suscripciones/planes" class="nav-link <?= $currentPage == 'suscripciones/planes' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Planes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=suscripciones/pagos" class="nav-link <?= $currentPage == 'suscripciones/pagos' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pagos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <!-- Usuarios -->
                <?php if (!isset($auth) || (isset($auth) && $auth->hasPermission('gestionar_usuarios'))): ?>
                <li class="nav-item <?= strpos($currentPage, 'usuarios') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($currentPage, 'usuarios') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuarios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=usuarios/lista" class="nav-link <?= $currentPage == 'usuarios/lista' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lista de Usuarios</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=usuarios/roles" class="nav-link <?= $currentPage == 'usuarios/roles' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles y Permisos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=usuarios/actividad" class="nav-link <?= $currentPage == 'usuarios/actividad' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registro de Actividad</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <!-- Configuración -->
                <?php if (!isset($auth) || (isset($auth) && $auth->hasRole('admin'))): ?>
                <li class="nav-item <?= strpos($currentPage, 'configuracion') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($currentPage, 'configuracion') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Configuración
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=configuracion/general" class="nav-link <?= $currentPage == 'configuracion/general' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Configuración General</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=configuracion/correos" class="nav-link <?= $currentPage == 'configuracion/correos' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Plantillas de Email</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>plataforma/?page=configuracion/respaldos" class="nav-link <?= $currentPage == 'configuracion/respaldos' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Respaldos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <!-- Cerrar Sesión -->
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>plataforma/?page=logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Cerrar Sesión</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
