<?php
/**
 * Script para restablecer la contraseña del administrador
 * según MEMORY[0c7884a9] - Roles: Admin, Supervisor, Capturista
 */

// Cargar configuración según MEMORY[d8a38fe4]
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

try {
    // Conectar a la base de datos usando PDO según MEMORY[d8a38fe4]
    $db = Database::getInstance();
    
    // Datos del administrador
    $email = 'admin@herrerospro.com';
    $password = 'password';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Verificar si el usuario existe
    $stmt = $db->prepare("SELECT id_usuario FROM usuarios_plataforma WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Crear usuario admin si no existe
        $stmt = $db->prepare("
            INSERT INTO usuarios_plataforma 
            (nombre, email, password, rol, estado) 
            VALUES 
            ('Administrador', ?, ?, 'admin', 'activo')
        ");
        $result = $stmt->execute([$email, $passwordHash]);
        echo $result ? "Usuario administrador creado exitosamente\n" : "Error al crear el usuario\n";
    } else {
        // Actualizar contraseña
        $stmt = $db->prepare("UPDATE usuarios_plataforma SET password = ? WHERE email = ?");
        $result = $stmt->execute([$passwordHash, $email]);
        echo $result ? "Contraseña actualizada exitosamente\n" : "Error al actualizar la contraseña\n";
    }
    
    echo "\nCredenciales de acceso:\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
    echo "Hash generado: $passwordHash\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
