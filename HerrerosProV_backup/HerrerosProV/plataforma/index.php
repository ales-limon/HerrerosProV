<?php
/**
 * Front Controller para la plataforma
 * 
 * Este archivo actúa como punto de entrada único para todas las vistas de la plataforma.
 * Carga dinámicamente el contenido según el parámetro 'page'.
 */

// Definimos la constante BASEPATH
define('BASEPATH', true);

// Cargar configuración de rutas primero
if (file_exists(__DIR__ . '/../config/paths.php')) {
    require_once __DIR__ . '/../config/paths.php';
}

// Cargar configuración específica de la plataforma
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Verificar si existe auth.php
if (file_exists(__DIR__ . '/config/auth.php')) {
    require_once __DIR__ . '/config/auth.php';
    
    // Inicializar autenticación
    $auth = Auth::getInstance();
    
    // Verificar autenticación (excepto para login y recuperación de contraseña)
    $publicPages = ['login', 'recuperar_password', 'reset_password', 'activar_cuenta'];
    $currentPage = $_GET['page'] ?? 'dashboard';
    
    // Si el usuario no está autenticado y trata de acceder a una página protegida
    if (!$auth->isAuthenticated() && !in_array($currentPage, $publicPages)) {
        header('Location: ' . BASE_URL . 'plataforma/?page=login');
        exit;
    }
    
    // Si el usuario está autenticado pero intenta acceder a login, redirigir al dashboard
    if ($auth->isAuthenticated() && $currentPage === 'login') {
        header('Location: ' . BASE_URL . 'plataforma/?page=dashboard');
        exit;
    }
} else {
    // Para desarrollo - permitir acceso sin autenticación
    $currentPage = $_GET['page'] ?? 'dashboard';
    $auth = null;
}

// Determinar la ruta del archivo a cargar
$viewFile = '';
if (strpos($currentPage, '/') !== false) {
    // Si la página incluye una subcarpeta (ej: "talleres/detalles")
    list($folder, $file) = explode('/', $currentPage, 2);
    $viewFile = __DIR__ . '/views/' . $folder . '/' . $file . '.php';
} else {
    // Página directa (ej: "dashboard")
    $viewFile = __DIR__ . '/views/' . $currentPage . '.php';
    
    // Si no existe, buscar dentro de una carpeta con el mismo nombre
    if (!file_exists($viewFile)) {
        $viewFile = __DIR__ . '/views/' . $currentPage . '/index.php';
    }
}

// Comprobar si el archivo existe
if (!file_exists($viewFile)) {
    // Si no existe, mostrar página de error 404
    header("HTTP/1.0 404 Not Found");
    if (file_exists(__DIR__ . '/views/error/404.php')) {
        include __DIR__ . '/views/error/404.php';
    } else {
        echo "<h1>Error 404: Página no encontrada</h1>";
        echo "<p>La página solicitada no existe.</p>";
        echo "<p><a href='" . BASE_URL . "plataforma/'>Volver al inicio</a></p>";
    }
    exit;
}

// Establecer variables por defecto para el layout
$pageTitle = ucfirst(str_replace('_', ' ', $currentPage));
$user = $auth && $auth->isAuthenticated() ? $auth->getCurrentUser() : null;
$extraStyles = '';
$extraScripts = '';

// Incluir el archivo de la vista
// La vista debe definir su contenido en la variable $content usando ob_start/ob_get_clean
include $viewFile;

// Si no hay contenido definido, significa que la vista maneja su propia salida
// En ese caso no hacemos nada más
