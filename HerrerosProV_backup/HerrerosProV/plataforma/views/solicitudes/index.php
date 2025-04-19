<?php
/**
 * Vista principal del módulo de solicitudes
 * 
 * Este archivo carga el contenido del módulo de solicitudes
 * a través del layout principal.
 */

// Verificar autenticación
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '?controller=auth&action=login');
    exit;
}

// Verificar permisos (solo administradores pueden ver solicitudes)
if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ' . BASE_URL . '?controller=dashboard&action=index');
    exit;
}

// Configuración de la página
$pageTitle = 'Gestión de Solicitudes';
$currentPage = 'solicitudes';

// Cargar el contenido específico
ob_start();
include 'plataforma/views/solicitudes/content_solicitudes.php';
$content = ob_get_clean();

// Scripts específicos de esta página
ob_start();
include 'plataforma/views/solicitudes/script_solicitudes.php';
$extraScripts = ob_get_clean();

// Cargar el layout principal con el contenido
include 'plataforma/views/layouts/main.php';
