<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Según MEMORY[d8a38fe4], usar PDO con conexiones persistentes
$db = Database::getInstance();

try {
    // Generar hash para 'password'
    $hash = password_hash('password', PASSWORD_DEFAULT);
    
    // Usar consulta preparada según MEMORY[d8a38fe4]
    $stmt = $db->prepare("UPDATE usuarios_plataforma SET password = ? WHERE email = ?");
    $stmt->execute([$hash, 'admin@herrerospro.com']);
    
    echo "Contraseña actualizada correctamente.\n";
    echo "Hash generado: " . $hash . "\n";
    
    // Verificar el update
    $stmt = $db->prepare("SELECT password FROM usuarios_plataforma WHERE email = ?");
    $stmt->execute(['admin@herrerospro.com']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Hash almacenado: " . $result['password'] . "\n";
    echo "¿Hash almacenado correctamente?: " . ($result['password'] === $hash ? 'Sí' : 'No') . "\n";
    
} catch (PDOException $e) {
    // Manejo de errores según MEMORY[d8a38fe4]
    echo "Error: " . $e->getMessage() . "\n";
}
