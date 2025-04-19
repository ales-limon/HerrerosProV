<?php
/**
 * Controlador de Registro de Talleres
 */

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/environment.php';  // Primero cargar el ambiente
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/validation.php';
require_once __DIR__ . '/../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/registro.php");
    exit();
}

// Crear conexión a la base de datos
$db = new Database();
$transactionStarted = false;

// Logs para depuración - Escribir en un archivo específico
$log_file = __DIR__ . '/../../logs/registro_debug.log';
file_put_contents($log_file, "Iniciando proceso de registro - " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents($log_file, "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents($log_file, "Configuración de base de datos: " . DB_NAME . "\n", FILE_APPEND);

// Asegurarnos de que estamos usando la base de datos correcta
$db->query("USE herrerospro_plataforma");
file_put_contents($log_file, "Base de datos seleccionada: herrerospro_plataforma\n", FILE_APPEND);

try {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        file_put_contents($log_file, "Intento de CSRF detectado desde IP: " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);
        throw new Exception("Error de validación del formulario");
    }

    // Obtener y sanitizar datos del formulario
    $nombre = sanitizarString($_POST['nombre'] ?? '');
    $apellidos = sanitizarString($_POST['apellidos'] ?? '');
    $nombre_admin = sanitizarString($_POST['nombre_admin'] ?? $nombre);
    $nombre_taller = sanitizarString($_POST['nombre_taller'] ?? '');
    $rfc = sanitizarString($_POST['rfc'] ?? '');
    $direccion = sanitizarString($_POST['direccion'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefono = preg_replace('/[^0-9]/', '', $_POST['telefono'] ?? '');
    $plan_input = sanitizarString($_POST['plan'] ?? 'basico');

    // Validar datos del formulario
    $errores = [];

    // Validar nombre
    if (empty($nombre) || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]{2,50}$/', $nombre)) {
        $errores[] = "Por favor ingresa un nombre válido (solo letras y espacios)";
    }

    // Validar apellidos
    if (empty($apellidos) || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]{2,50}$/', $apellidos)) {
        $errores[] = "Por favor ingresa apellidos válidos (solo letras y espacios)";
    }

    // Validar nombre del taller
    if (empty($nombre_taller) || !preg_match('/^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s\.\-\&]{2,100}$/', $nombre_taller)) {
        $errores[] = "Por favor ingresa un nombre de taller válido";
    }

    // Validar dirección
    if (empty($direccion) || strlen($direccion) < 5 || strlen($direccion) > 200) {
        $errores[] = "Por favor ingresa una dirección válida (entre 5 y 200 caracteres)";
    }

    // Validar email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Por favor ingresa un correo electrónico válido";
    }

    // Validar teléfono
    if (empty($telefono) || !preg_match('/^[0-9]{10}$/', $telefono)) {
        $errores[] = "Por favor ingresa un número de teléfono válido (10 dígitos)";
    }

    // Validar plan
    // Convertir los valores del formulario a los valores de la base de datos
    $plan_mapping = [
        'basico' => 'Básico',
        'profesional' => 'Profesional',
        'enterprise' => 'Enterprise'
    ];

    // Asegurarse de que el plan esté en minúsculas para la comparación
    $plan_input = strtolower($plan_input);
    
    if (empty($plan_input) || !isset($plan_mapping[$plan_input])) {
        file_put_contents($log_file, "Plan inválido recibido: " . $plan_input . "\n", FILE_APPEND);
        $errores[] = "Por favor selecciona un plan válido";
    } else {
        // Convertir el valor del formulario al valor de la base de datos
        $plan = $plan_mapping[$plan_input];
    }

    // Validar términos y condiciones
    if (empty($_POST['terminos']) || $_POST['terminos'] !== 'on') {
        $errores[] = "Debes aceptar los términos y condiciones";
    }

    // Si hay errores, redirigir de vuelta al formulario
    if (!empty($errores)) {
        $_SESSION['error'] = implode("<br>", $errores);
        $_SESSION['form_data'] = $_POST;
        header("Location: " . PUBLIC_URL . "public/views/registro.php");
        exit;
    }

    file_put_contents($log_file, "Datos validados:\n", FILE_APPEND);
    file_put_contents($log_file, "Nombre: $nombre\n", FILE_APPEND);
    file_put_contents($log_file, "Apellidos: $apellidos\n", FILE_APPEND);
    file_put_contents($log_file, "Nombre Admin: $nombre_admin\n", FILE_APPEND);
    file_put_contents($log_file, "Nombre Taller: $nombre_taller\n", FILE_APPEND);
    file_put_contents($log_file, "RFC: " . ($rfc ? $rfc : "No proporcionado") . "\n", FILE_APPEND);
    file_put_contents($log_file, "Dirección: $direccion\n", FILE_APPEND);
    file_put_contents($log_file, "Email: $email\n", FILE_APPEND);
    file_put_contents($log_file, "Teléfono: $telefono\n", FILE_APPEND);
    file_put_contents($log_file, "Plan: $plan\n", FILE_APPEND);

    // Verificar si el email ya está registrado
    file_put_contents($log_file, "Verificando si el email ya existe en la base de datos\n", FILE_APPEND);

    // Usar el campo ID correcto en la consulta
    $db->query("SELECT id FROM solicitudes_talleres WHERE email = ? AND estado != 'rechazada'");
    $db->bind(1, $email);
    $resultado = $db->single();
    file_put_contents($log_file, "Verificación de email existente: " . ($resultado ? "Email ya registrado" : "Email disponible") . "\n", FILE_APPEND);

    if ($resultado) {
        throw new Exception("Este correo electrónico ya está registrado en el sistema. Por favor utiliza otro correo o inicia sesión si ya tienes una cuenta.");
    }

    // Iniciar transacción
    $db->beginTransaction();
    $transactionStarted = true;
    file_put_contents($log_file, "Transacción iniciada\n", FILE_APPEND);

    try {
        // Insertar nueva solicitud de taller
        $db->query("INSERT INTO solicitudes_talleres (nombre_taller, propietario, email, telefono, 
                    direccion, plan_seleccionado, estado, fecha_solicitud) 
                    VALUES (?, ?, ?, ?, ?, ?, 'pendiente', NOW())");
        file_put_contents($log_file, "Query preparada para inserción\n", FILE_APPEND);

        $db->bind(1, $nombre_taller);
        $db->bind(2, $nombre . ' ' . $apellidos); // Combinamos nombre y apellidos en el campo propietario
        $db->bind(3, $email);
        $db->bind(4, $telefono);
        $db->bind(5, $direccion);
        $db->bind(6, $plan);
        file_put_contents($log_file, "Parámetros vinculados para la inserción\n", FILE_APPEND);
        file_put_contents($log_file, "Valores a insertar: nombre_taller={$nombre_taller}, propietario={$nombre} {$apellidos}, email={$email}, telefono={$telefono}, direccion={$direccion}, plan_seleccionado={$plan}\n", FILE_APPEND);

        $resultado_insert = $db->execute();
        file_put_contents($log_file, "Resultado de inserción en solicitudes_talleres: " . ($resultado_insert ? "Éxito" : "Error - " . $db->getError()) . "\n", FILE_APPEND);
        file_put_contents($log_file, "SQL ejecutado: INSERT INTO solicitudes_talleres (nombre_taller, propietario, email, telefono, direccion, plan_seleccionado, estado, fecha_solicitud) VALUES ('{$nombre_taller}', '{$nombre} {$apellidos}', '{$email}', '{$telefono}', '{$direccion}', '{$plan}', 'pendiente', NOW())\n", FILE_APPEND);

        if (!$resultado_insert) {
            file_put_contents($log_file, "Error al ejecutar el insert. Error: " . $db->getError() . "\n", FILE_APPEND);
            throw new Exception("Error al registrar la solicitud. Por favor intenta nuevamente más tarde.");
        }

        // Obtener el ID de la solicitud insertada
        $id_solicitud = $db->lastInsertId();
        file_put_contents($log_file, "ID de solicitud generado: " . $id_solicitud . "\n", FILE_APPEND);
    } catch (Exception $e) {
        file_put_contents($log_file, "Excepción durante la inserción: " . $e->getMessage() . "\n", FILE_APPEND);
        throw $e;
    }

    // Confirmar transacción antes de enviar el correo
    if ($transactionStarted) {
        file_put_contents($log_file, "Confirmando transacción\n", FILE_APPEND);
        $db->commit();
        file_put_contents($log_file, "Transacción confirmada con éxito\n", FILE_APPEND);
    }

    // Preparar la respuesta antes de cualquier output
    $response = ['success' => true, 'message' => "Tu solicitud ha sido recibida correctamente. En breve nos pondremos en contacto contigo."];

    // Crear un archivo temporal con los datos para enviar el correo en segundo plano
    $email_data = [
        'id_solicitud' => $id_solicitud,
        'nombre_taller' => $nombre_taller,
        'propietario' => $nombre . ' ' . $apellidos,
        'email' => $email,
        'telefono' => $telefono,
        'direccion' => $direccion,
        'plan' => $plan,
        'fecha' => date('Y-m-d H:i:s')
    ];
    
    $email_data_file = __DIR__ . '/../../temp/email_data_' . $id_solicitud . '.json';
    // Asegurarse de que el directorio existe
    if (!file_exists(__DIR__ . '/../../temp')) {
        mkdir(__DIR__ . '/../../temp', 0777, true);
    }
    file_put_contents($email_data_file, json_encode($email_data));
    
    // Ejecutar el script de envío de correo en segundo plano
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows - usar la misma forma que en el script de prueba
        file_put_contents($log_file, "Ejecutando script de envío de correo...\n", FILE_APPEND);
        $cmd = 'php ' . __DIR__ . '/send_notification_email.php ' . $id_solicitud;
        file_put_contents($log_file, "Comando: $cmd\n", FILE_APPEND);
        
        // Ejecutar el comando y capturar la salida para depuración
        $output = [];
        $return_var = 0;
        exec($cmd, $output, $return_var);
        
        // Registrar resultado en el log
        file_put_contents($log_file, "Código de salida: $return_var\n", FILE_APPEND);
        if (!empty($output)) {
            file_put_contents($log_file, "Salida del comando: " . implode("\n", $output) . "\n", FILE_APPEND);
        }
    } else {
        // Linux/Unix
        exec('php ' . __DIR__ . '/send_notification_email.php ' . $id_solicitud . ' > /dev/null 2>&1 &');
    }
    
    file_put_contents($log_file, "Proceso de envío de correo iniciado en segundo plano\n", FILE_APPEND);
    $response['email_process'] = 'started';

    // Guardar mensaje en sesión y redirigir
    $_SESSION['success'] = $response['message'];
    header("Location: ../views/registro.php");
    exit();

} catch (Exception $e) {
    // Revertir transacción si está activa
    if ($transactionStarted) {
        try {
            $db->rollBack();
        } catch (PDOException $pdoEx) {
            file_put_contents($log_file, "Error al hacer rollback: " . $pdoEx->getMessage() . "\n", FILE_APPEND);
        }
    }

    file_put_contents($log_file, "Error en registro_controller: " . $e->getMessage() . "\n", FILE_APPEND);
    $_SESSION['error'] = $e->getMessage();
    $_SESSION['form_data'] = $_POST; // Mantener datos del formulario
    header("Location: ../views/registro.php");
    exit();
}
