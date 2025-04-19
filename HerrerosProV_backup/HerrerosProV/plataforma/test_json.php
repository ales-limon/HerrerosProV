<?php
// Desactivar todo tipo de salida de errores o advertencias
error_reporting(0);
ini_set('display_errors', 0);

// Establecer cabecera JSON
header('Content-Type: application/json; charset=utf-8');

// Responder con un JSON simple
echo json_encode(['success' => true, 'message' => 'Test JSON funcionando correctamente']);
