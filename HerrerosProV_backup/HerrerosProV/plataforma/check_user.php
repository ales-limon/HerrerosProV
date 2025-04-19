<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$db->query("SELECT id_usuario, email, rol, estado FROM usuarios WHERE email = :email");
$db->bind(':email', 'ales.limon@gmail.com');
$user = $db->single();

if ($user) {
    echo "Usuario encontrado:\n";
    echo "ID: " . $user['id_usuario'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Rol: " . $user['rol'] . "\n";
    echo "Estado: " . $user['estado'] . "\n";
} else {
    echo "Usuario no encontrado";
}
