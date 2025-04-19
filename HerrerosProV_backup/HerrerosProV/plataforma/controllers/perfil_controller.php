<?php
/**
 * Controlador de Perfil
 * Maneja la actualización de información personal y cambio de contraseña
 * según MEMORY[0c7884a9] - Plataforma Admin
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Método no permitido']));
}

// Verificar autenticación
$auth = Auth::getInstance();
if (!$auth->isAuthenticated()) {
    http_response_code(401);
    exit(json_encode(['error' => 'No autorizado']));
}

// Asegurar que la respuesta sea JSON
header('Content-Type: application/json');

try {
    $db = Database::getInstance();
    $userId = $_SESSION['plataforma_user']['id'];
    
    if (!isset($_POST['action'])) {
        throw new Exception('Acción no especificada');
    }

    switch ($_POST['action']) {
        case 'actualizar_perfil':
            // Validar campos requeridos
            if (empty($_POST['nombre']) || empty($_POST['email'])) {
                throw new Exception('Nombre y email son requeridos');
            }

            // Verificar si el email ya existe para otro usuario
            $stmt = $db->prepare("SELECT id FROM usuarios_plataforma WHERE email = ? AND id != ?");
            $stmt->execute([$_POST['email'], $userId]);
            if ($stmt->fetch()) {
                throw new Exception('El email ya está registrado');
            }

            // Actualizar información
            $stmt = $db->prepare("
                UPDATE usuarios_plataforma 
                SET nombre = ?, email = ?, actualizado_en = NOW() 
                WHERE id = ?
            ");
            
            if (!$stmt->execute([$_POST['nombre'], $_POST['email'], $userId])) {
                throw new Exception('Error al actualizar el perfil');
            }

            // Actualizar sesión
            $_SESSION['plataforma_user']['nombre'] = $_POST['nombre'];
            $_SESSION['plataforma_user']['email'] = $_POST['email'];

            echo json_encode([
                'success' => true,
                'message' => 'Perfil actualizado correctamente'
            ]);
            break;

        case 'cambiar_password':
            // Validar campos requeridos
            if (empty($_POST['password_actual']) || empty($_POST['password_nuevo']) || empty($_POST['password_confirmar'])) {
                throw new Exception('Todos los campos de contraseña son requeridos');
            }

            // Validar que las contraseñas nuevas coincidan
            if ($_POST['password_nuevo'] !== $_POST['password_confirmar']) {
                throw new Exception('Las contraseñas nuevas no coinciden');
            }

            // Validar longitud mínima
            if (strlen($_POST['password_nuevo']) < 8) {
                throw new Exception('La contraseña debe tener al menos 8 caracteres');
            }

            // Verificar contraseña actual
            $stmt = $db->prepare("SELECT password FROM usuarios_plataforma WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($_POST['password_actual'], $user['password'])) {
                throw new Exception('La contraseña actual es incorrecta');
            }

            // Actualizar contraseña
            $passwordHash = password_hash($_POST['password_nuevo'], PASSWORD_DEFAULT);
            $stmt = $db->prepare("
                UPDATE usuarios_plataforma 
                SET password = ?, actualizado_en = NOW() 
                WHERE id = ?
            ");
            
            if (!$stmt->execute([$passwordHash, $userId])) {
                throw new Exception('Error al actualizar la contraseña');
            }

            echo json_encode([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
