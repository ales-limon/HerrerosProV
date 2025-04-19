<?php
/**
 * Sistema de plantillas PHP según MEMORY[0c7884a9]
 * 
 * Carga las dependencias comunes y define funciones auxiliares
 * para el sistema de plantillas.
 */

// Cargar configuración y autenticación
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Verificar autenticación
$auth = Auth::getInstance();
if (!$auth->isAuthenticated()) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

/**
 * Función para cargar una vista con la plantilla principal
 * 
 * @param string $view Ruta a la vista a cargar
 * @param array $data Variables para pasar a la vista
 */
function renderView($view, $data = []) {
    // Extraer variables para la vista
    extract($data);
    
    // Capturar el contenido de la vista
    ob_start();
    require $view;
    $content = ob_get_clean();
    
    // Cargar la plantilla principal
    require_once __DIR__ . '/../views/layouts/main.php';
}

/**
 * Función para cargar una vista sin plantilla
 * 
 * @param string $view Ruta a la vista a cargar
 * @param array $data Variables para pasar a la vista
 */
function renderPartial($view, $data = []) {
    extract($data);
    require $view;
}
