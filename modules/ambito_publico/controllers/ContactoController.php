<?php

namespace modules\ambito_publico\controllers;

// Asegúrate de que las constantes de ruta estén definidas (generalmente en config.php o index.php)
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
// Asumiendo que MODULES_PATH está definido correctamente apuntando a la carpeta 'modules'
$partialsPath = MODULES_PATH . 'ambito_publico' . DS . 'views' . DS . 'partials' . DS;
$viewsPath = MODULES_PATH . 'ambito_publico' . DS . 'views' . DS;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Requerir archivos de PHPMailer (asegúrate que la ruta sea correcta)
require_once BASE_PATH . 'PHPMailer/src/Exception.php';
require_once BASE_PATH . 'PHPMailer/src/PHPMailer.php';
require_once BASE_PATH . 'PHPMailer/src/SMTP.php';

class ContactoController {

    public function index() {
        // Definir variables para el layout
        $page_title = "Contacto - Herreros Pro"; // Título específico para esta página
        $current_page = "contacto"; // Identificador para la navegación activa

        // Iniciar sesión si no está iniciada (para generar CSRF)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Generar token CSRF si no existe
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Incluir el encabezado
        $headerPath = $GLOBALS['partialsPath'] . 'header.php';
        if (file_exists($headerPath)) {
            include_once($headerPath);
        } else {
            error_log("Error: No se encontró el archivo header.php en: " . $headerPath);
            // Considera mostrar un error o detener la ejecución
        }

        // Incluir la barra de navegación
        $navbarPath = $GLOBALS['partialsPath'] . 'navbar.php';
        if (file_exists($navbarPath)) {
            include_once($navbarPath);
        } else {
            error_log("Error: No se encontró el archivo navbar.php en: " . $navbarPath);
        }

        // Incluir el contenido específico de la página de contacto
        $viewPath = $GLOBALS['viewsPath'] . 'contacto_content.php';
        if (file_exists($viewPath)) {
            include_once($viewPath);
        } else {
            // Si no se encuentra el archivo de contenido, mostrar un mensaje o cargar una vista de error
            echo "<div class='container'><p>Error: No se pudo cargar el contenido de la página de contacto.</p></div>";
            error_log("Error: No se encontró el archivo de vista contacto_content.php en: " . $viewPath);
        }

        // Incluir el pie de página
        $footerPath = $GLOBALS['partialsPath'] . 'footer.php';
        if (file_exists($footerPath)) {
            include_once($footerPath);
        } else {
            error_log("Error: No se encontró el archivo footer.php en: " . $footerPath);
        }
    }

    // Método para procesar el formulario de contacto
    public function procesarFormulario() {
        // Iniciar sesión si no está iniciada (para verificar CSRF y guardar flash message)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];
        $nombre = '';
        $email = '';
        $asunto = '';
        $mensaje = '';

        // 1. Validación básica y obtención de datos POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Limpieza básica y obtención de datos
            $nombre = isset($_POST['nombre']) ? trim(htmlspecialchars($_POST['nombre'])) : '';
            $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
            $asunto = isset($_POST['asunto']) ? trim(htmlspecialchars($_POST['asunto'])) : '';
            $mensaje = isset($_POST['mensaje']) ? trim(htmlspecialchars($_POST['mensaje'])) : '';
            $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

            // Validaciones
            if (empty($nombre)) $errors[] = "El nombre es obligatorio.";
            if (empty($email)) {
                $errors[] = "El correo electrónico es obligatorio.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El formato del correo electrónico no es válido.";
            }
            if (empty($asunto)) $errors[] = "El asunto es obligatorio.";
            if (empty($mensaje)) $errors[] = "El mensaje es obligatorio.";

            // 2. Verificación CSRF
            if (empty($csrf_token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                $errors[] = "Error de seguridad (token inválido). Por favor, recarga la página e intenta de nuevo.";
            }
            
            // Si no hay errores, proceder con el envío
            if (empty($errors)) {
                $mail = new PHPMailer(true); // Habilita excepciones

                try {
                    // Configuración del servidor SMTP (desde mail.development.php)
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Descomentar para depuración detallada
                    $mail->isSMTP();
                    $mail->Host       = SMTP_HOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = SMTP_USER;
                    $mail->Password   = SMTP_PASS;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS implícito para puerto 587
                    $mail->Port       = SMTP_PORT;
                    $mail->CharSet    = 'UTF-8'; // Especificar codificación

                    // Remitente y Destinatario(s)
                    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME); // Email configurado para enviar
                    $mail->addAddress(SUPPORT_EMAIL); // Enviar a la dirección de soporte definida en config
                    $mail->addReplyTo($email, $nombre); // Para poder responder directamente al usuario

                    // Contenido del correo
                    $mail->isHTML(false); // Enviar como texto plano
                    $mail->Subject = 'Nuevo Mensaje de Contacto: ' . $asunto;
                    $mailBody = "Has recibido un nuevo mensaje desde el formulario de contacto de HerrerosPro:\n\n";
                    $mailBody .= "Nombre: " . $nombre . "\n";
                    $mailBody .= "Email: " . $email . "\n";
                    $mailBody .= "Asunto: " . $asunto . "\n";
                    $mailBody .= "Mensaje:\n" . $mensaje . "\n";
                    $mail->Body = $mailBody;

                    $mail->send();
                    $_SESSION['flash_message'] = ['type' => 'success', 'text' => '¡Mensaje enviado correctamente! Gracias por contactarnos.'];
                    unset($_SESSION['csrf_token']); // Importante: invalidar token usado

                } catch (Exception $e) {
                    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => "Error al enviar el mensaje: {$mail->ErrorInfo}. Por favor, intenta más tarde."];
                    // Loggear el error detallado para el administrador
                    error_log("PHPMailer Error: {$e->getMessage()}");
                }
            } else {
                // Si hubo errores de validación, guardarlos en sesión para mostrarlos
                $_SESSION['flash_message'] = ['type' => 'danger', 'text' => implode('<br>', $errors)];
                // Opcionalmente, guardar los datos introducidos para repoblar el formulario
                // $_SESSION['form_data'] = $_POST;
            }
        } else {
            // Si no es POST, redirigir o mostrar error
            $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'Acceso inválido al procesamiento del formulario.'];
        }

        // Redirigir siempre de vuelta a la página de contacto
        header('Location: ' . PUBLIC_URL . '?route=contacto');
        exit;
    }
}
?>
