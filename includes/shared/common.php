<?php
// includes/shared/common.php

// Cargar configuración general
// require_once CONFIG_PATH . 'config.php'; // Eliminado: Ahora se incluye en public/index.php

// Cargar utilidades
require_once SHARED_PATH . 'auth.php';
require_once SHARED_PATH . 'flash.php';
require_once SHARED_PATH . 'funciones.php';

// Cargar helpers individuales
// require_once HELPERS_PATH . 'format.php'; // Temporalmente comentado porque format.php no existe
// require_once HELPERS_PATH . 'medidas.php'; // Temporalmente comentado porque medidas.php no existe
// require_once HELPERS_PATH . 'textos.php'; // Temporalmente comentado porque textos.php no existe
require_once HELPERS_PATH . 'security_helper.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
