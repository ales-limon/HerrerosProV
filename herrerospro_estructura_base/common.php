<?php
// includes/shared/common.php

// Cargar configuración general
require_once CONFIG_PATH . 'config.php';

// Cargar utilidades
require_once SHARED_PATH . 'auth.php';
require_once SHARED_PATH . 'flash.php';
require_once SHARED_PATH . 'funciones.php';

// Cargar helpers individuales
require_once HELPERS_PATH . 'format.php';
require_once HELPERS_PATH . 'medidas.php';
require_once HELPERS_PATH . 'textos.php';
require_once HELPERS_PATH . 'seguridad.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
