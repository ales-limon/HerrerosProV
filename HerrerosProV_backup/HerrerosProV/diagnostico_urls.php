<?php
/**
 * Diagnóstico de URLs
 * Este archivo muestra la configuración actual de las URLs en el sistema
 */

// Incluir archivos necesarios
require_once __DIR__ . '/config/common.php';

// Mostrar información de configuración
echo "<h1>Diagnóstico de URLs</h1>";
echo "<pre>";

// Mostrar constantes de URL
echo "BASE_URL: " . BASE_URL . "\n";
echo "PUBLIC_URL: " . PUBLIC_URL . "\n";
echo "ASSETS_URL: " . ASSETS_URL . "\n";
echo "PLATAFORMA_URL: " . PLATAFORMA_URL . "\n";
echo "TALLERES_URL: " . TALLERES_URL . "\n";

// Mostrar URL correcta para el controlador de registro
echo "\nURL correcta para el controlador de registro:\n";
echo PUBLIC_URL . "controllers/registro_controller.php\n";

// Mostrar URL incorrecta (la que está usando actualmente)
echo "\nURL incorrecta (la que está usando actualmente):\n";
echo PUBLIC_URL . "public/controllers/registro_controller.php\n";

// Mostrar información del servidor
echo "\nInformación del servidor:\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";

echo "</pre>";

// Mostrar formulario de prueba
echo "<h2>Formulario de prueba</h2>";
echo "<form method='post' action='" . PUBLIC_URL . "controllers/registro_controller.php'>";
echo "<input type='hidden' name='test' value='1'>";
echo "<button type='submit'>Probar URL correcta</button>";
echo "</form>";

echo "<br>";

echo "<form method='post' action='" . PUBLIC_URL . "public/controllers/registro_controller.php'>";
echo "<input type='hidden' name='test' value='1'>";
echo "<button type='submit'>Probar URL incorrecta (actual)</button>";
echo "</form>";
