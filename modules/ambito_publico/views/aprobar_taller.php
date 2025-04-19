<?php
/**
 * Vista temporal para probar la aprobación de talleres
 */

session_start();

// Generar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Simular sesión de administrador para pruebas
$_SESSION['admin_id'] = 1;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobar Taller - HerrerosPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aprobar Taller Pendiente</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <?php 
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['warning'])): ?>
                            <div class="alert alert-warning">
                                <?php 
                                echo $_SESSION['warning'];
                                unset($_SESSION['warning']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        // Obtener información del taller pendiente
                        require_once __DIR__ . '/../../config/database.php';
                        $db = new Database();
                        
                        $db->query("SELECT id_taller, nombre, nombre_admin, email, telefono, direccion, tipo_plan, estado 
                                  FROM talleres WHERE estado = 'pendiente' ORDER BY fecha_creacion DESC LIMIT 1");
                        $taller = $db->single();
                        
                        if ($taller):
                        ?>
                            <div class="mb-4">
                                <h4>Detalles del Taller</h4>
                                <p><strong>Nombre del Taller:</strong> <?php echo htmlspecialchars($taller['nombre']); ?></p>
                                <p><strong>Administrador:</strong> <?php echo htmlspecialchars($taller['nombre_admin']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($taller['email']); ?></p>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($taller['telefono']); ?></p>
                                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($taller['direccion']); ?></p>
                                <p><strong>Plan:</strong> <?php echo htmlspecialchars($taller['tipo_plan']); ?></p>
                            </div>

                            <form action="../controllers/aprobar_taller_controller.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="id_taller" value="<?php echo $taller['id_taller']; ?>">
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success">
                                        Aprobar Taller
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-info">
                                No hay talleres pendientes de aprobación.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
