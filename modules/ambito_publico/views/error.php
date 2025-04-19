<?php
require_once __DIR__ . '/../../config/common.php';

$error_code = isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : 404;
$error_messages = [
    403 => 'Acceso Denegado',
    404 => 'Página No Encontrada',
    500 => 'Error Interno del Servidor'
];
$error_message = isset($error_messages[$error_code]) ? $error_messages[$error_code] : 'Error Desconocido';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HerrerosPro | <?php echo $error_message; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>adminlte/css/adminlte.min.css">
</head>
<body class="hold-transition">
    <div class="wrapper">
        <section class="content">
            <div class="error-page" style="margin-top: 100px;">
                <h2 class="headline text-warning"><?php echo $error_code; ?></h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> ¡Oops! <?php echo $error_message; ?></h3>
                    <p>
                        No pudimos encontrar la página que estás buscando.
                        Mientras tanto, puedes <a href="<?php echo PUBLIC_URL; ?>">volver a la página principal</a>.
                    </p>
                    <?php if (isset($_SERVER['HTTP_REFERER'])): ?>
                    <p>
                        O <a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?>">regresar a la página anterior</a>.
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>

    <!-- jQuery -->
    <script src="<?php echo ASSETS_URL; ?>adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo ASSETS_URL; ?>adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo ASSETS_URL; ?>adminlte/js/adminlte.min.js"></script>
</body>
</html>
