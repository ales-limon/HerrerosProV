<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <title><?= $pageTitle ?? 'HerrerosPro' ?> | Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/adminlte/css/adminlte.min.css">
    <!-- Personalizado -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/custom.css">
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
</head>
<body class="hold-transition <?= $isErrorPage ?? false ? 'sidebar-collapse' : 'login-page' ?>">

    <?php if ($isErrorPage ?? false): ?>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <a href="<?= BASE_URL ?>" class="navbar-brand">
                <span class="brand-text font-weight-light">HerrerosPro</span>
            </a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>?page=login" class="nav-link">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                </li>
            </ul>
        </nav>
        <div class="content-wrapper" style="margin-left: 0; min-height: 100vh; padding-top: 50px;">
            <div class="container">
                <?= $content ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="<?= BASE_URL ?>"><b>Herreros</b>Pro</a>
        </div>
        <div class="card">
            <?= $content ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- jQuery -->
    <script src="<?= BASE_URL ?>assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= BASE_URL ?>assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= BASE_URL ?>assets/adminlte/js/adminlte.min.js"></script>
    <!-- Scripts adicionales -->
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html> 