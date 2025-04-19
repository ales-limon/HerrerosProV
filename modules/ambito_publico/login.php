<?php
// modules/ambito_publico/login.php

require_once '../../includes/shared/common.php';

$mensaje = null;

// Simulación de verificación de usuario (esto deberías conectar con tu DB real)
function verificarCredenciales($email, $password) {
    $usuariosDummy = [
        'admin@herrerospro.com' => ['password' => '1234', 'rol' => 'admin_general'],
        'taller@herrerospro.com' => ['password' => '1234', 'rol' => 'admin_taller'],
        'empleado@herrerospro.com' => ['password' => '1234', 'rol' => 'empleado']
    ];

    if (isset($usuariosDummy[$email]) && $usuariosDummy[$email]['password'] === $password) {
        return [
            'email' => $email,
            'rol' => $usuariosDummy[$email]['rol']
        ];
    }

    return false;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = limpiar($_POST['email'] ?? '');
    $password = limpiar($_POST['password'] ?? '');

    $usuario = verificarCredenciales($email, $password);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario;

        switch ($usuario['rol']) {
            case 'admin_general':
                header('Location: ../ambito_administracion/');
                exit;
            case 'admin_taller':
            case 'empleado':
                header('Location: ../ambito_talleres/');
                exit;
        }
    } else {
        $mensaje = 'Credenciales incorrectas';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login | HerrerosPro</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Herreros</b>Pro
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Inicia sesión para continuar</p>

            <?php if ($mensaje): ?>
                <div class="alert alert-danger"><?= $mensaje ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
                </div>
                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4"><button type="submit" class="btn btn-primary btn-block">Entrar</button></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="<?= BASE_URL ?>assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>
