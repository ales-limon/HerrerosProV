<?php
// config/config.php

// Detectar si estamos en local o producción
$host = $_SERVER['HTTP_HOST'];
$isLocal = strpos($host, 'localhost') !== false;

// BASE_URL dinámica según entorno
define('BASE_URL', $isLocal ? 'http://localhost/herrerospro/public/' : 'https://herrerospro.com/');

// BASE_PATH absoluta al root del proyecto
define('BASE_PATH', dirname(__DIR__) . '/');

// Rutas absolutas comunes
define('CONFIG_PATH', BASE_PATH . 'config/');
define('INCLUDES_PATH', BASE_PATH . 'includes/');
define('SHARED_PATH', INCLUDES_PATH . 'shared/');
define('HELPERS_PATH', INCLUDES_PATH . 'helpers/');
define('MODULES_PATH', BASE_PATH . 'modules/');

// Info del sistema
define('APP_NAME', 'HerrerosPro');
define('VERSION', '1.0.0');
define('CREADO_POR', 'Alejandro Limón');
