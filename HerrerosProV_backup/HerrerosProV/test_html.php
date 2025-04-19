<?php
/**
 * Archivo de prueba con estructura HTML básica
 */

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba HTML - HerrerosPro</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .test-box {
            background-color: #f0f0f0;
            border: 2px solid #ccc;
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="test-box">
            <h1>Prueba de estructura HTML</h1>
            <p>Esta es una página de prueba para verificar si hay algún problema con la estructura HTML.</p>
        </div>
        
        <div class="test-box">
            <h2>Información del sistema</h2>
            <ul>
                <li>PHP Version: <?php echo phpversion(); ?></li>
                <li>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
                <li>Document Root: <?php echo $_SERVER['DOCUMENT_ROOT']; ?></li>
                <li>Script Filename: <?php echo $_SERVER['SCRIPT_FILENAME']; ?></li>
            </ul>
        </div>
    </div>
    
    <!-- Bootstrap 5 Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 