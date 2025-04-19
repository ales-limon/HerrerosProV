<?php
// modules/ambito_publico/controllers/HomeController.php

namespace modules\ambito_publico\controllers;

class HomeController {

    /**
     * Muestra la página principal pública.
     */
    public function index() {
        // Definir variables para el layout (como se esperaba en el layout.php original)
        $page_title = 'Inicio - HerrerosPro'; // Título para la etiqueta <title>
        $current_page = 'home'; // Identificador para marcar el menú activo en navbar.php

        // Ruta a los parciales de la vista (header, navbar, footer)
        $partialsPath = MODULES_PATH . 'ambito_publico' . DS . 'views' . DS . 'partials' . DS;
        // Ruta al contenido específico de esta página
        $content_path = MODULES_PATH . 'ambito_publico' . DS . 'views' . DS . 'index_content.php';

        // Incluir el header
        if (file_exists($partialsPath . 'header.php')) {
            include_once $partialsPath . 'header.php';
        } else {
            error_log("Error: No se encontró header.php en " . $partialsPath);
            echo "Error crítico: Falta el encabezado de la página.";
            // Considera detener la ejecución o mostrar una página de error más amigable
        }

        // Incluir el navbar
        if (file_exists($partialsPath . 'navbar.php')) {
            include_once $partialsPath . 'navbar.php';
        } else {
            error_log("Error: No se encontró navbar.php en " . $partialsPath);
            // No es tan crítico como el header/footer, pero avisa
        }

        // Incluir el contenido principal específico de la página de inicio
        if (file_exists($content_path)) {
            include_once $content_path;
        } else {
            // Mostrar un error si el contenido específico no se encuentra
            echo '<div class="container mt-5"><div class="alert alert-danger">Error: Contenido principal de la página de inicio no encontrado en '.$content_path.'</div></div>';
            error_log("Error: No se encontró el contenido principal en " . $content_path);
        }

        // Incluir el footer
        if (file_exists($partialsPath . 'footer.php')) {
            include_once $partialsPath . 'footer.php';
        } else {
            error_log("Error: No se encontró footer.php en " . $partialsPath);
            echo "Error crítico: Falta el pie de página.";
            // Considera detener la ejecución o mostrar una página de error más amigable
        }
    }

    // Podrías añadir otros métodos para otras páginas estáticas si HomeController las maneja
    // public function acerca() { ... }
    // public function contacto() { ... } // (Aunque ya tenemos ContactController)
}
?>
