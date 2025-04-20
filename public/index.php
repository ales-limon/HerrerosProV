<?php
/**
 * HerrerosPro - Front Controller
 * 
 * Punto de entrada único para todas las solicitudes web.
 * Dirige las solicitudes a los módulos apropiados.
 */

// ---- 1. Carga de Configuración y Archivos Comunes ----

// Incluir el archivo de configuración PRIMERO. 
// Define constantes importantes (BASE_PATH, BASE_URL, DB_HOST, etc.) y rutas.
require_once dirname(__DIR__) . '/config/config.php';

// Incluir utilidades comunes (manejo de sesión, helpers base, etc.)
// Requiere que las constantes de ruta (SHARED_PATH, HELPERS_PATH) estén definidas.
require_once SHARED_PATH . 'common.php'; 

// --- 2. Authentication Check & Admin/Taller Redirect ---
if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['rol'])) {
    // Usuario está autenticado, redirigir a su área correspondiente
    // TODO: Considerar usar BASE_URL si está definida en common.php para rutas absolutas
    switch ($_SESSION['usuario']['rol']) {
        case 'admin_general':
            // Asegúrate que la ruta es correcta relativa a la ubicación del proyecto
            header('Location: ../modules/ambito_administracion/'); 
            exit;
        case 'admin_taller':
        case 'empleado':
            header('Location: ../modules/ambito_talleres/'); 
            exit;
        default:
            // Rol desconocido o inválido, destruir sesión y redirigir a login
            session_destroy();
            // Redirige a la ruta de login pública a través de este mismo script
            header('Location: index.php?route=login'); 
            exit;
    }
}

// --- 3. Public Area Routing (User is NOT Authenticated) ---

// Definir rutas a los componentes del módulo público
define('PUBLIC_CONTROLLERS_PATH', MODULES_PATH . 'ambito_publico' . DS . 'controllers' . DS);

// Obtener la ruta solicitada de la query string, default a 'home'
$route = trim($_GET['route'] ?? 'home', '/');
if (empty($route)) {
    $route = 'home';
}

// Mapeo simple de rutas públicas a [ControllerClass, method]
$publicRoutes = [
    'home' => ['HomeController', 'index'],
    'login' => ['LoginController', 'index'],
    'registro' => ['RegistroController', 'index'],
    'contacto' => ['ContactoController', 'index'],
    'procesar_contacto' => ['ContactoController', 'procesarFormulario'],
    'do_login' => ['AuthController', 'doLogin'],   // Procesa login (POST)
    'do_registro'  => ['RegistroController', 'doRegister'],// Procesa registro (POST)
    'send_contact' => ['ContactoController', 'send'],    // Procesa contacto (POST)
    'planes'       => ['PlanesController', 'index'],    // Muestra página de planes (GET)
    // Añadir aquí otras páginas públicas si existen
];

// Determinar el controlador y método a usar
$controllerName = 'ErrorController'; // Controlador por defecto si la ruta no se encuentra
$methodName = 'notFound';
$controllerNamespace = ''; // Namespace por defecto (global o error)
$moduleBasePath = '';

// Por ahora, asumimos que todas las rutas públicas pertenecen al módulo 'ambito_publico'
// En una implementación más compleja, podrías tener diferentes módulos públicos
if (array_key_exists($route, $publicRoutes)) {
    list($controllerName, $methodName) = $publicRoutes[$route];
    // Definir explícitamente el nombre del módulo para claridad
    $moduleName = 'ambito_publico';
    // Usar comillas dobles y la variable para construir las cadenas
    $controllerNamespace = "modules\\{$moduleName}\\controllers\\";
    $moduleBasePath = MODULES_PATH . $moduleName . DS . 'controllers' . DS;
} else {
    // Si la ruta no está definida, usar un ErrorController (podría estar en 'includes' o un módulo 'core')
    // Asumiendo que ErrorController está en el namespace global o requiere una lógica diferente
    // $controllerNamespace = 'core\controllers\'; // Ejemplo
    // $moduleBasePath = CORE_PATH . 'controllers' . DS; // Ejemplo
    // Por ahora, se mantiene simple
    $controllerName = 'ErrorController'; // Asegúrate que ErrorController.php exista donde lo busque
    $methodName = 'notFound';
    // TODO: Definir dónde vive el ErrorController y su archivo
    // $controllerFile = INCLUDES_PATH . 'controllers' . DS . $controllerName . '.php'; // O ubicación similar
}

$controllerFile = $moduleBasePath . $controllerName . '.php';
$fullControllerName = $controllerNamespace . $controllerName;

// Verificar si el archivo del controlador existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Verificar si la clase del controlador existe (¡usando el nombre completo con namespace!)
    if (class_exists($fullControllerName)) {
        // Crear instancia del controlador (¡usando el nombre completo con namespace!)
        $controller = new $fullControllerName();

        // Verificar si el método existe en el controlador
        if (method_exists($controller, $methodName)) {
            // Llamar al método
            try {
                $controller->$methodName();
            } catch (Exception $e) {
                // Manejo básico de excepciones durante la ejecución del controlador
                error_log("Error ejecutando {$fullControllerName}::{$methodName} - " . $e->getMessage());
                // Considera mostrar una página de error más amigable
                echo "Ocurrió un error inesperado.";
                // Si tienes un ErrorController, podrías redirigir a él
                // $errorController = new core\controllers\ErrorController(); // Ejemplo
                // $errorController->serverError($e);
            }
        } else {
            // Método no encontrado en el controlador
            error_log("Error: Método '{$methodName}' no encontrado en la clase '{$fullControllerName}'.");
            // Cargar método 'notFound' del ErrorController o un método por defecto
            // TODO: Implementar carga del ErrorController->notFound()
            echo "Error 404: Método no encontrado.";
        }
    } else {
        // Clase no encontrada DENTRO del archivo del controlador
        error_log("Error: Clase '{$fullControllerName}' no encontrada en el archivo '{$controllerFile}'. Verifica el namespace y el nombre de la clase.");
        // Cargar método 'notFound' del ErrorController
        // TODO: Implementar carga del ErrorController->notFound()
        echo "Error 404: Clase de controlador no encontrada.";
    }
} else {
    // Archivo del controlador no encontrado
    error_log("Error: Archivo del controlador no encontrado en '{$controllerFile}'.");
    // Cargar método 'notFound' del ErrorController
    // TODO: Implementar carga del ErrorController->notFound()
    echo "Error 404: Archivo de controlador no encontrado.";
}

// Limpiar el buffer de salida y enviar el contenido al navegador
ob_end_flush();

?>
