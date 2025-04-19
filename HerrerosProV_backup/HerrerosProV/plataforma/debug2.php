<?php
/**
 * Archivo de depuración avanzado
 */

// Incluir todas las dependencias
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

// Activar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Iniciar captura de salida
ob_start();

// Iniciar autenticación
$auth = Auth::getInstance();
$user = $auth->isAuthenticated() ? $auth->getCurrentUser() : null;

// Variables de prueba para la plantilla
$pageTitle = 'Página de Depuración';
$currentPage = 'debug';
$extraStyles = '';
$extraScripts = '';

// Contenido de prueba
$content = "<h1>Página de depuración</h1>
<p>Esta página muestra información sobre el sistema.</p>
<div class='row'>
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-header'>
                <h3 class='card-title'>Variables de Entorno</h3>
            </div>
            <div class='card-body'>
                <p><strong>BASE_URL:</strong> " . BASE_URL . "</p>
                <p><strong>ROOT_PATH:</strong> " . ROOT_PATH . "</p>
                <p><strong>DOCUMENT_ROOT:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>
                <p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>
            </div>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-header'>
                <h3 class='card-title'>Información de Usuario</h3>
            </div>
            <div class='card-body'>
                <p><strong>Autenticado:</strong> " . ($auth->isAuthenticated() ? 'Sí' : 'No') . "</p>
                " . ($user ? "<p><strong>Usuario:</strong> " . htmlspecialchars($user['nombre']) . "</p>" : "") . "
            </div>
        </div>
    </div>
</div>";

// Incluir la plantilla
include_once __DIR__ . '/views/layouts/main.php';

// Finalizar depuración
$output = ob_get_clean();
echo $output; 