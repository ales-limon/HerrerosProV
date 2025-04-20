<?php
namespace modules\ambito_publico\controllers;

/**
 * Controlador para la página de Planes y Precios
 */

// require_once __DIR__ . '/../models/PlanModel.php'; // (Si tuviéramos un modelo)

class PlanesController {

    /**
     * Muestra la página de planes y precios.
     */
    public function index() {
        // Título de la página
        $pageTitle = "Planes y Precios - HerrerosPro";

        // Datos de los planes (ajustados para coincidir con la vista)
        $planes = [
            [
                'nombre' => 'Básico',
                'precio' => '19', // Solo el número
                'id_plan' => 'basico', // ID para enlace
                'caracteristicas' => [
                    ['texto' => 'Gestión de Clientes', 'activo' => true],
                    ['texto' => 'Gestión de Proyectos (hasta 10)', 'activo' => true],
                    ['texto' => 'Presupuestos básicos', 'activo' => true],
                    ['texto' => 'Soporte por Email', 'activo' => true],
                    ['texto' => 'Facturación integrada', 'activo' => false],
                    ['texto' => 'Control de Inventario', 'activo' => false],
                ],
                'popular' => false // Cambiado de 'destacado'
            ],
            [
                'nombre' => 'Profesional',
                'precio' => '39', // Solo el número
                'id_plan' => 'profesional', // ID para enlace
                'caracteristicas' => [
                    ['texto' => 'Todo lo del plan Básico', 'activo' => true],
                    ['texto' => 'Gestión de Proyectos Ilimitados', 'activo' => true],
                    ['texto' => 'Facturación integrada', 'activo' => true],
                    ['texto' => 'Control de Inventario Simple', 'activo' => true],
                    ['texto' => 'Soporte Prioritario', 'activo' => true],
                    ['texto' => 'Multi-usuario', 'activo' => false],
                    ['texto' => 'Informes Avanzados', 'activo' => false],
                ],
                'popular' => true // Cambiado de 'destacado'
            ],
            [
                'nombre' => 'Empresarial',
                'precio' => '79', // Solo el número
                'id_plan' => 'empresarial', // ID para enlace
                'caracteristicas' => [
                    ['texto' => 'Todo lo del plan Profesional', 'activo' => true],
                    ['texto' => 'Multi-usuario (hasta 5)', 'activo' => true],
                    ['texto' => 'Informes Avanzados', 'activo' => true],
                    ['texto' => 'Integraciones Personalizadas', 'activo' => true],
                    ['texto' => 'Soporte VIP 24/7', 'activo' => true],
                    ['texto' => 'Sucursales Múltiples', 'activo' => true],
                ],
                'popular' => false // Cambiado de 'destacado'
            ]
        ];

        // Definir la ruta al contenido específico de esta página
        $content_path = __DIR__ . '/../views/planes_content.php';

        // Cargar la vista principal y pasarle los datos
        // Usamos ruta relativa desde el controlador hasta el layout en ../includes/
        include __DIR__ . '/../includes/layout.php'; 
    }
}
?>
