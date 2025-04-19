<?php
/**
 * Archivo de configuración de rutas base del sistema
 * 
 * Este archivo define las rutas absolutas y las URLs base del proyecto
 * para asegurar una correcta inclusión de archivos y carga de recursos,
 * independientemente de la ubicación desde donde se ejecuten los scripts.
 * 
 * Recomendado para entornos estructurados bajo patrón MVC o aplicaciones
 * web escalables.
 */

// URL base del sistema (ajustar según entorno local o en producción)
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/HerrerosProV/');
}

// Ruta absoluta del directorio raíz del sistema en el servidor
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/../') . '/');
}

// Definir rutas adicionales útiles si no existen
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', BASE_URL . 'public/assets/');
}

if (!defined('PLATAFORMA_URL')) {
    define('PLATAFORMA_URL', BASE_URL . 'plataforma/');
}

if (!defined('TALLERES_URL')) {
    define('TALLERES_URL', BASE_URL . 'talleres/');
}

/**
 * ¿Por qué usar BASE_URL y BASE_PATH?
 * 
 * BASE_URL se usa para generar enlaces y rutas públicas, como:
 * - Carga de imágenes
 * - Archivos JavaScript o CSS
 * - Redirecciones con header("Location: ...")
 * 
 * Ejemplo:
 * <script src="<?= BASE_URL ?>assets/js/main.js"></script>
 * 
 * BASE_PATH se usa para incluir archivos del sistema de forma segura:
 * - require_once BASE_PATH . 'includes/conexion.php';
 * - include BASE_PATH . 'modulos/clientes/controlador.php';
 * 
 * Esto evita errores por rutas relativas mal construidas como ../../includes/... 
 * y permite mover archivos sin romper la lógica.
 * 
 * Ventajas:
 * - Mantenibilidad
 * - Portabilidad
 * - Claridad en el código
 * - Escalabilidad
 * 
 * ¡Siempre incluye este archivo antes de cualquier otro!
 */ 