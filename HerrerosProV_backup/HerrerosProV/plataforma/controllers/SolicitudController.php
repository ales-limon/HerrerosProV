<?php
/**
 * Controlador de Solicitudes
 * 
 * Maneja todas las operaciones relacionadas con solicitudes de talleres
 */
class SolicitudController {
    private $model;
    private $auth;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Cargar el modelo de solicitudes
        require_once __DIR__ . '/../models/SolicitudModel.php';
        $this->model = new SolicitudModel();
        
        // Obtener instancia de autenticación
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Procesa las solicitudes AJAX
     */
    public function processAjaxRequest() {
        // Verificar que sea una petición AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            $this->jsonResponse(false, 'Acceso no permitido');
            return;
        }
        
        // Verificar autenticación
        if (!$this->auth->isAuthenticated()) {
            $this->jsonResponse(false, 'No autenticado');
            return;
        }
        
        // Obtener la acción solicitada
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'listar':
                $this->listarSolicitudes();
                break;
                
            case 'ver':
                $this->verSolicitud();
                break;
                
            case 'aprobar':
                $this->aprobarSolicitud();
                break;
                
            case 'rechazar':
                $this->rechazarSolicitud();
                break;
                
            default:
                $this->jsonResponse(false, 'Acción no válida');
        }
    }
    
    /**
     * Lista todas las solicitudes
     */
    private function listarSolicitudes() {
        // Verificar permisos
        if (!$this->auth->hasPermission('aprobar_solicitudes') && !$this->auth->hasPermission('gestionar_talleres')) {
            $this->jsonResponse(false, 'No tienes permisos para ver solicitudes');
            return;
        }
        
        // Obtener filtros de la petición
        $filtros = [];
        if (isset($_GET['estado'])) {
            $filtros['estado'] = $_GET['estado'];
        }
        
        // Paginación
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $filtros['limit'] = $limit;
        $filtros['offset'] = $offset;
        
        // Obtener solicitudes
        $solicitudes = $this->model->obtenerTodas($filtros);
        
        // Responder
        $this->jsonResponse(true, 'Solicitudes obtenidas con éxito', [
            'solicitudes' => $solicitudes
        ]);
    }
    
    /**
     * Ver detalles de una solicitud
     */
    private function verSolicitud() {
        // Verificar permisos
        if (!$this->auth->hasPermission('aprobar_solicitudes') && !$this->auth->hasPermission('gestionar_talleres')) {
            $this->jsonResponse(false, 'No tienes permisos para ver solicitudes');
            return;
        }
        
        // Verificar parámetro ID
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->jsonResponse(false, 'ID de solicitud no válido');
            return;
        }
        
        $id = intval($_GET['id']);
        
        // Obtener solicitud
        $solicitud = $this->model->obtenerPorId($id);
        
        if (!$solicitud) {
            $this->jsonResponse(false, 'Solicitud no encontrada');
            return;
        }
        
        // Responder
        $this->jsonResponse(true, 'Solicitud obtenida con éxito', [
            'solicitud' => $solicitud
        ]);
    }
    
    /**
     * Aprueba una solicitud
     */
    private function aprobarSolicitud() {
        // Verificar permisos
        if (!$this->auth->hasPermission('aprobar_solicitudes')) {
            $this->jsonResponse(false, 'No tienes permisos para aprobar solicitudes');
            return;
        }
        
        // Verificar token CSRF
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            $this->jsonResponse(false, 'Token de seguridad inválido');
            return;
        }
        
        // Verificar parámetro ID
        if (!isset($_POST['id_solicitud']) || !is_numeric($_POST['id_solicitud'])) {
            $this->jsonResponse(false, 'ID de solicitud no válido');
            return;
        }
        
        $id = intval($_POST['id_solicitud']);
        $notas = isset($_POST['notas']) ? sanitizeInput($_POST['notas']) : '';
        
        // Obtener solicitud para verificar que exista y esté pendiente
        $solicitud = $this->model->obtenerPorId($id);
        
        if (!$solicitud) {
            $this->jsonResponse(false, 'Solicitud no encontrada');
            return;
        }
        
        if ($solicitud['estado'] !== 'pendiente') {
            $this->jsonResponse(false, 'La solicitud ya ha sido procesada');
            return;
        }
        
        // Aprobar solicitud
        $resultado = $this->model->aprobar($id, $this->auth->getCurrentUser()['id'], $notas);
        
        if (!$resultado) {
            $this->jsonResponse(false, 'Error al aprobar la solicitud');
            return;
        }
        
        // Responder
        $this->jsonResponse(true, 'Solicitud aprobada con éxito');
    }
    
    /**
     * Rechaza una solicitud
     */
    private function rechazarSolicitud() {
        // Verificar permisos
        if (!$this->auth->hasPermission('aprobar_solicitudes')) {
            $this->jsonResponse(false, 'No tienes permisos para rechazar solicitudes');
            return;
        }
        
        // Verificar token CSRF
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            $this->jsonResponse(false, 'Token de seguridad inválido');
            return;
        }
        
        // Verificar parámetro ID
        if (!isset($_POST['id_solicitud']) || !is_numeric($_POST['id_solicitud'])) {
            $this->jsonResponse(false, 'ID de solicitud no válido');
            return;
        }
        
        // Verificar que haya motivo de rechazo
        if (!isset($_POST['notas']) || empty($_POST['notas'])) {
            $this->jsonResponse(false, 'Debe proporcionar un motivo para el rechazo');
            return;
        }
        
        $id = intval($_POST['id_solicitud']);
        $notas = sanitizeInput($_POST['notas']);
        
        // Obtener solicitud para verificar que exista y esté pendiente
        $solicitud = $this->model->obtenerPorId($id);
        
        if (!$solicitud) {
            $this->jsonResponse(false, 'Solicitud no encontrada');
            return;
        }
        
        if ($solicitud['estado'] !== 'pendiente') {
            $this->jsonResponse(false, 'La solicitud ya ha sido procesada');
            return;
        }
        
        // Rechazar solicitud
        $resultado = $this->model->rechazar($id, $this->auth->getCurrentUser()['id'], $notas);
        
        if (!$resultado) {
            $this->jsonResponse(false, 'Error al rechazar la solicitud');
            return;
        }
        
        // Responder
        $this->jsonResponse(true, 'Solicitud rechazada con éxito');
    }
    
    /**
     * Vista principal de solicitudes
     */
    public function index() {
        // Verificar permisos
        if (!$this->auth->hasPermission('aprobar_solicitudes') && !$this->auth->hasPermission('gestionar_talleres')) {
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        // Variables para la vista
        $pageTitle = 'Gestión de Solicitudes';
        $currentPage = 'solicitudes';
        
        // Obtener estadísticas para mostrar en la vista
        $stats = $this->model->obtenerEstadisticas();
        
        // Iniciar buffer de salida para la vista
        ob_start();
        include __DIR__ . '/../views/solicitudes/content_solicitudes.php';
        $content = ob_get_clean();
        
        // Incluir scripts específicos
        $extraScripts = '';
        ob_start();
        include __DIR__ . '/../views/solicitudes/script_solicitudes.php';
        $extraScripts = ob_get_clean();
        
        // Cargar la vista con el layout principal
        include __DIR__ . '/../views/layouts/main.php';
    }
    
    /**
     * Envía una respuesta JSON
     * 
     * @param bool $success Indicador de éxito
     * @param string $message Mensaje
     * @param array $data Datos adicionales
     */
    private function jsonResponse($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'success' => $success,
            'message' => $message
        ], $data));
        exit;
    }
} 