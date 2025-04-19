<?php
/**
 * Archivo de depuración para autenticación
 * Solo accesible desde localhost según .htaccess
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

header('Content-Type: text/plain');

echo "=== Información de Depuración ===\n\n";

// Verificar conexión a base de datos
try {
    $db = Database::getInstance();
    echo "✓ Conexión a base de datos establecida\n";
    
    // Verificar tabla usuarios_plataforma
    $stmt = $db->prepare("SHOW TABLES LIKE 'usuarios_plataforma'");
    $stmt->execute();
    if ($stmt->fetch()) {
        echo "✓ Tabla usuarios_plataforma existe\n";
        
        // Verificar usuario admin
        $stmt = $db->prepare("SELECT id_usuario, nombre, email, rol, estado, password FROM usuarios_plataforma WHERE email = ?");
        $stmt->execute(['admin@herrerospro.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "✓ Usuario admin encontrado:\n";
            echo "  - ID: {$user['id_usuario']}\n";
            echo "  - Nombre: {$user['nombre']}\n";
            echo "  - Email: {$user['email']}\n";
            echo "  - Rol: {$user['rol']}\n";
            echo "  - Estado: {$user['estado']}\n";
            echo "  - Hash: " . substr($user['password'], 0, 10) . "...\n";
            
            // Verificar hash de contraseña
            $password = 'password';
            if (password_verify($password, $user['password'])) {
                echo "✓ Hash de contraseña es válido\n";
            } else {
                echo "✗ Hash de contraseña NO es válido\n";
            }
        } else {
            echo "✗ Usuario admin NO encontrado\n";
        }
    } else {
        echo "✗ Tabla usuarios_plataforma NO existe\n";
    }
} catch (Exception $e) {
    echo "✗ Error de conexión: " . $e->getMessage() . "\n";
}

// Verificar configuración
echo "\n=== Configuración ===\n";
echo "BASE_URL: " . BASE_URL . "\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_NAME: " . DB_NAME . "\n";
echo "DB_USER: " . DB_USER . "\n";
echo "DB_CHARSET: " . DB_CHARSET . "\n";

// Verificar sesión
echo "\n=== Sesión ===\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . "\n";
echo "Session Cookie: " . (isset($_COOKIE[session_name()]) ? "Existe" : "No existe") . "\n";
if (isset($_SESSION['plataforma_user'])) {
    echo "Usuario en sesión:\n";
    print_r($_SESSION['plataforma_user']);
} else {
    echo "No hay usuario en sesión\n";
}

// Verificar rutas y permisos
echo "\n=== Rutas y Permisos ===\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "PHP SAPI: " . php_sapi_name() . "\n";
