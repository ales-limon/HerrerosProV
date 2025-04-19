<?php
/**
 * Script de autenticación para HerrerosPro
 * Versión mejorada que verifica credenciales básicas
 */

// Desactivar todo tipo de salida de errores o advertencias
error_reporting(0);
ini_set('display_errors', 0);

// Establecer cabecera JSON
header('Content-Type: application/json; charset=utf-8');

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si es una solicitud POST y tiene los campos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    // Verificar que se enviaron email y password
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor ingresa email y contraseña'
        ]);
        exit;
    }
    
    // Credenciales de ejemplo (en producción, verificar contra base de datos)
    $usuarios_validos = [
        'admin@herrerospro.com' => [
            'password' => 'password',
            'id' => 1,
            'nombre' => 'Administrador',
            'rol' => 'admin'
        ],
        'supervisor@herrerospro.com' => [
            'password' => 'password',
            'id' => 2,
            'nombre' => 'Supervisor',
            'rol' => 'supervisor'
        ],
        'capturista@herrerospro.com' => [
            'password' => 'password',
            'id' => 3,
            'nombre' => 'Capturista',
            'rol' => 'capturista'
        ]
    ];
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Verificar si el usuario existe y la contraseña es correcta
    if (isset($usuarios_validos[$email]) && $usuarios_validos[$email]['password'] === $password) {
        // Login exitoso
        $_SESSION['plataforma_user'] = [
            'id' => $usuarios_validos[$email]['id'],
            'nombre' => $usuarios_validos[$email]['nombre'],
            'email' => $email,
            'rol' => $usuarios_validos[$email]['rol']
        ];
        
        // Responder con éxito
        echo json_encode([
            'success' => true,
            'redirect' => 'http://localhost/HerrerosPro/plataforma/?page=dashboard',
            'message' => 'Inicio de sesión exitoso'
        ]);
    } else {
        // Login fallido
        echo json_encode([
            'success' => false,
            'message' => 'Email o contraseña incorrectos'
        ]);
    }
} else {
    // Solicitud no válida
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud no válida'
    ]);
}
