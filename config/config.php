<?php
// config/config.php

// Constante para separador de directorios
define('DS', DIRECTORY_SEPARATOR);

// BASE_PATH absoluta al root del proyecto
// Usamos dirname(__DIR__) para obtener la ruta al directorio padre (HerrerosProV)
$basePath = dirname(__DIR__) . DS;
define('BASE_PATH', $basePath);

// BASE_URL - URL pública base, apuntando a la carpeta 'public'
// Asegúrate que coincida con tu configuración de XAMPP/VirtualHost
$projectName = basename(dirname(__DIR__)); // Obtiene 'HerrerosProV'
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $projectName . '/public/'); 

// URL pública base (para enlaces en vistas)
if (!defined('PUBLIC_URL')) {
    define('PUBLIC_URL', BASE_URL);
}

// ASSETS_URL - URL de los assets (CSS, JS, Imágenes)
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', BASE_URL . 'assets/');
}

// --- Rutas absolutas del sistema de archivos --- 
// (Usando las constantes definidas previamente)
define('CONFIG_PATH',     BASE_PATH . 'config' . DS); 
define('INCLUDES_PATH',   BASE_PATH . 'includes' . DS);
define('SHARED_PATH',     INCLUDES_PATH . 'shared' . DS);
define('HELPERS_PATH',    INCLUDES_PATH . 'helpers' . DS);
define('MODULES_PATH',    BASE_PATH . 'modules' . DS);
define('PUBLIC_PATH',     BASE_PATH . 'public' . DS);
define('ASSETS_PATH',     PUBLIC_PATH . 'assets' . DS); // Ruta a los assets DENTRO de public

// Info del sistema
define('APP_NAME', 'HerrerosPro');
define('VERSION', '1.0.0');
define('CREADO_POR', 'Alejandro Limón');
