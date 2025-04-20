<?php
namespace modules\ambito_publico\controllers;

/**
 * Controlador para la página de Registro de Usuarios
 */
class RegistroController {

    /**
     * Muestra el formulario de registro.
     * Puede recibir un parámetro 'plan' para preseleccionar un plan.
     */
    public function index() {
        // Iniciar sesión si no está iniciada para manejar mensajes flash
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Obtener plan seleccionado de la URL (si existe)
        $planSeleccionado = $_GET['plan'] ?? null;

        // Definir los planes disponibles (AHORA SE LLAMA $planes_disponibles)
        // NOTA: Sería ideal obtener esto de una base de datos o un modelo en el futuro.
        $planes_disponibles = [
            'basico' => [
                'id' => 'basico', // Añadir ID para consistencia con la vista
                'nombre' => 'Plan Básico',
                'precio' => 499, // Usar número para formato
                'descripcion' => 'Ideal para pequeños talleres que empiezan.', // Añadir descripción
                'popular' => false,
                'caracteristicas' => [
                    ['texto' => 'Gestión de clientes', 'activo' => true],
                    ['texto' => 'Gestión de inventario básico', 'activo' => true],
                    ['texto' => 'Facturación simple', 'activo' => true],
                    ['texto' => 'Soporte por correo', 'activo' => true],
                    ['texto' => 'Reportes estándar', 'activo' => false],
                    ['texto' => 'Múltiples usuarios (hasta 3)', 'activo' => false],
                ],
                'url_registro' => PUBLIC_URL . '?route=registro&plan=basico'
            ],
            'profesional' => [
                'id' => 'profesional',
                'nombre' => 'Plan Profesional',
                'precio' => 999,
                'descripcion' => 'Perfecto para talleres en crecimiento con más necesidades.',
                'popular' => true,
                'caracteristicas' => [
                    ['texto' => 'Todo del Plan Básico', 'activo' => true],
                    ['texto' => 'Gestión de proyectos completa', 'activo' => true],
                    ['texto' => 'Control de gastos detallado', 'activo' => true],
                    ['texto' => 'Reportes avanzados y personalizables', 'activo' => true],
                    ['texto' => 'Múltiples usuarios (hasta 10)', 'activo' => true],
                    ['texto' => 'Soporte prioritario por chat', 'activo' => true],
                ],
                'url_registro' => PUBLIC_URL . '?route=registro&plan=profesional'
            ],
            // Cambiar la clave y el ID a 'empresarial'
            'empresarial' => [
                'id' => 'empresarial',
                'nombre' => 'Plan Enterprise', // Mantenemos el nombre visible como Enterprise
                'precio' => 1999,
                'descripcion' => 'La solución completa para grandes operaciones y múltiples sucursales.',
                'popular' => false,
                'caracteristicas' => [
                    ['texto' => 'Todo del Plan Profesional', 'activo' => true],
                    ['texto' => 'Gestión de múltiples sucursales', 'activo' => true],
                    ['texto' => 'Integraciones API', 'activo' => true],
                    ['texto' => 'Gerente de cuenta dedicado', 'activo' => true],
                    ['texto' => 'Usuarios ilimitados', 'activo' => true],
                    ['texto' => 'Soporte telefónico 24/7', 'activo' => true],
                ],
                // Actualizar también la URL generada por si se usa en otro lado
                'url_registro' => PUBLIC_URL . '?route=registro&plan=empresarial'
            ],
        ];

        // Preparar la información del plan específico si se seleccionó uno válido
        $plan_info = null;
        if ($planSeleccionado && array_key_exists($planSeleccionado, $planes_disponibles)) {
            $plan_info = $planes_disponibles[$planSeleccionado];
        } elseif ($planSeleccionado) {
            // Si se pasó un plan pero es inválido, loggear y proceder sin plan preseleccionado
            error_log("Intento de registro con plan inválido: " . $planSeleccionado);
            // Podrías opcionalmente añadir un mensaje de error en sesión aquí si quieres
            // $_SESSION['error_message'] = 'El plan seleccionado no es válido.';
        }

        // Definir la ruta al contenido específico que el layout espera
        $content_path = MODULES_PATH . 'ambito_publico' . DS . 'views' . DS . 'registro_content.php';

        // Datos para pasar a la vista (Usando los nombres correctos)
        $data = [
            'page_title' => 'Registro - HerrerosPro',
            'current_page' => 'registro',
            'plan_info' => $plan_info, // Información del plan específico (o null)
            'planes_disponibles' => $planes_disponibles, // Lista completa de planes
            'mensajeError' => $_SESSION['error_message'] ?? null,
            'mensajeExito' => $_SESSION['success_message'] ?? null,
            'formData' => $_SESSION['form_data'] ?? [],
            'formErrors' => $_SESSION['form_errors'] ?? [],
            'content_path' => $content_path // Añadido para que el layout lo reciba
        ];

        // Limpiar mensajes flash de la sesión
        unset($_SESSION['error_message'], $_SESSION['success_message'], $_SESSION['form_data'], $_SESSION['form_errors']);

        // Cargar la vista principal (layout) que incluirá el contenido
        // Asumiendo que tienes un layout que maneja header/navbar/footer
        $layoutPath = MODULES_PATH . 'ambito_publico' . DS . 'includes' . DS . 'layout.php'; // Corregido: 'includes' en lugar de 'views'

        if (file_exists($layoutPath)) {
            // Extraer variables para que estén disponibles en el layout y la vista de contenido
            extract($data);
            include $layoutPath; // El layout debe incluir $content_path en algún punto
        } else {
            // Fallback o manejo de error si no existe el layout principal
            echo "Error: Layout principal no encontrado.";
            error_log("Error: Layout principal no encontrado en " . $layoutPath);
            // Considera incluir directamente header/content/footer si no usas layout
        }
    }

    /**
     * Procesa el envío del formulario de registro.
     */
    public function doRegister() {
        // Iniciar sesión si no está iniciada (Importante para mensajes flash y CSRF)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Log para confirmar inicio de ejecución
        error_log("RegistroController::doRegister() ejecutado."); 

        // 1. Validar Token CSRF
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $_SESSION['error_message'] = 'Error de seguridad (CSRF). Intenta de nuevo.';
            error_log("CSRF Token Mismatch en registro."); // Log de error CSRF
            header('Location: ' . PUBLIC_URL . '?route=registro');
            exit;
        }
        error_log("CSRF Token Validado.");

        // 2. Recoger y Sanitizar Datos
        // Usar coalescencia nula para evitar errores si no existen
        $nombre = trim($_POST['nombre'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $telefono = trim($_POST['telefono'] ?? '');
        $nombre_taller = trim($_POST['nombre_taller'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $plan = trim($_POST['plan'] ?? ''); // El plan ya viene validado por la URL, pero lo validamos de nuevo

        error_log("Datos del POST recogidos: Nombre={$nombre_taller}, Email={$email}, Plan={$plan}");

        // 3. Validar Datos del Servidor
        $errors = [];
        // Nombre del taller
        if (empty($nombre_taller)) $errors['nombre_taller'] = 'El nombre del taller es obligatorio.';
        if (empty($nombre)) $errors['nombre'] = 'El nombre es obligatorio.';
        if (empty($email)) {
            $errors['email'] = 'El correo electrónico es obligatorio.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El formato del correo electrónico no es válido.';
        }
        // Opcional: Validar teléfono (ej. formato básico)
        if (!empty($telefono) && !preg_match('/^[0-9\s\-\+]+$/', $telefono)) {
             $errors['telefono'] = 'El formato del teléfono no es válido.';
        }
        if (empty($direccion)) $errors['direccion'] = 'La dirección es obligatoria.';
        
        // Validación del plan
        $planesValidos = ['basico', 'profesional', 'empresarial']; // IDs de planes válidos
        if (empty($plan)) {
            $errors['plan'] = 'Debes seleccionar un plan.';
        } elseif (!in_array($plan, $planesValidos)) {
            $errors['plan'] = 'El plan seleccionado no es válido.';
            error_log("Error de validación: Plan '{$plan}' no es válido."); // Log plan inválido
        }

        error_log("Validación de datos completada.");

        // Instanciar el manejador de BD UNA SOLA VEZ aquí
        require_once CONFIG_PATH . 'database.php';
        $db_handler = new \Database(); // Crear instancia ANTES de la comprobación
        error_log("Instancia única de Database creada.");

        // 4. Comprobar Solicitud Duplicada Pendiente
        if (empty($errors)) {
            // Verificar duplicados usando la columna email correcta
            error_log("Verificando duplicados - Usando columna email corregida");
            
            // Usar la instancia existente de Database para verificar duplicados
            $sql_check = "SELECT COUNT(*) as total FROM solicitudes_talleres WHERE email = :email AND estado = 'pendiente'";
            $db_handler->query($sql_check);
            $db_handler->bind(':email', $email);
            
            // Obtener el resultado
            $result = $db_handler->single();
            
            error_log("Verificación de duplicados - Resultado: " . (isset($result['total']) ? $result['total'] : 'error'));
            
            // Si hay al menos una fila con el mismo email y estado pendiente
            if ($result && isset($result['total']) && $result['total'] > 0) {
                error_log("Intento de registro duplicado para email: $email");
                setFlash('error', 'Ya existe una solicitud de registro pendiente para este correo electrónico.');
                header('Location: ' . PUBLIC_URL . '?route=registro');
                exit; // Detener ejecución aquí
            }
            
            error_log("No se encontró solicitud pendiente duplicada. Procediendo con inserción...");
        }

        // Añadido para Debug: Confirmar que se pasó la verificación de duplicados
        error_log("DEBUG: Pasó la verificación de duplicados. Preparando para insertar...");

        // 5. Procesar si no hay errores de validación inicial
        if (!empty($errors)) {
            // Si hay errores, guardarlos en sesión y redirigir de vuelta
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = $errors; // Guardamos los errores
            
            error_log("Redirigiendo de vuelta al formulario con errores...");
            // Redirigir de vuelta al formulario de registro
            header('Location: ' . PUBLIC_URL . '?route=registro');
            exit;
        }

        // 6. Insertar en BD si todo es válido
        // Si no hay errores (y no era duplicado), proceder con la inserción
        try {
            error_log("Conexión a BD obtenida.");

            // Preparar la consulta de inserción (con el orden correcto de los campos)
            $sql_insert = "INSERT INTO solicitudes_talleres 
                        (nombre_taller, propietario, email, telefono, plan_seleccionado, direccion, estado, fecha_solicitud) 
                    VALUES 
                        (:nombre_taller, :propietario, :email, :telefono, :plan_seleccionado, :direccion, 'pendiente', NOW())";
            error_log("SQL preparado: $sql_insert");
            
            // Usar el método query() de tu clase Database
            $db_handler->query($sql_insert);

            // Combinar nombre y apellidos para el campo 'propietario'
            $nombreCompletoPropietario = $nombre;

            // Usar el método bind() de tu clase Database
            $db_handler->bind(':nombre_taller', $nombre_taller);
            $db_handler->bind(':propietario', $nombreCompletoPropietario);
            $db_handler->bind(':telefono', $telefono); // Permitir NULL si está vacío
            $db_handler->bind(':email', $email);
            $db_handler->bind(':plan_seleccionado', $plan);
            $db_handler->bind(':direccion', $direccion); // Permitir NULL si está vacío
            
            error_log("Parámetros bindeados.");

            // Ejecutar la sentencia
            $db_handler->execute();

            error_log("Inserción ejecutada con éxito. ID generado: " . $db_handler->lastInsertId());

            // Regenerar el token CSRF DESPUÉS de una operación exitosa y ANTES de redirigir
            require_once HELPERS_PATH . 'security_helper.php'; // Asegurarse que está incluido
            generate_csrf_token();
            error_log("Controller - CSRF Token Regenerado.");

            // Establecer mensaje de éxito en la sesión - CAMBIO A FLASH
            // $_SESSION['success_message'] = '¡Solicitud enviada con éxito! Un administrador revisará tu información y recibirás un correo electrónico con los siguientes pasos una vez que sea aprobada.';
            
            // Log para verificar que la sesión se establece ANTES de redirigir
            // error_log("Controller - Mensaje Sesión establecido: " . $_SESSION['success_message']);
            // Usar el sistema de mensajes flash
            setFlash('success', '¡Solicitud enviada con éxito! Un administrador revisará tu información y recibirás un correo electrónico con los siguientes pasos una vez que sea aprobada.');
            error_log("Controller - Mensaje Flash 'success' establecido.");
 
            error_log("Redirigiendo a página de éxito...");
            
            // Redirigir de vuelta a la página de registro con un indicador de éxito
            // Mantenemos status=success por si acaso o para lógica futura, aunque flash es el principal
            header('Location: ' . PUBLIC_URL . '?route=registro&status=success'); 
            exit;

        } catch (PDOException $e) {
            error_log("ERROR PDO: " . $e->getMessage()); // Log específico del error PDO
            // Loggear el error detallado para el administrador
            // error_log('Error al insertar solicitud de taller: ' . $e->getMessage()); // Ya logueamos arriba
            
            // Mensaje genérico para el usuario
            $_SESSION['error_message'] = 'Error interno del servidor al procesar tu solicitud. Por favor, inténtalo más tarde.';
            header('Location: ' . PUBLIC_URL . '?route=registro');
            exit;
        }
    }
}

?>
