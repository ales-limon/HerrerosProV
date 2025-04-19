<?php
// includes/shared/auth.php

function esAdminGeneral() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin_general';
}

function esAdminTaller() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin_taller';
}

function esEmpleadoTaller() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'empleado';
}
