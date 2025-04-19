<?php
/**
 * Vista de Perfil de Usuario
 * Según MEMORY[0c7884a9] - Plataforma Admin
 */

$user = $_SESSION['plataforma_user'] ?? null;
if (!$user) {
    header('Location: ' . BASE_URL . '?page=login');
    exit;
}
?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Mi Perfil</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Inicio</a></li>
                    <li class="breadcrumb-item active">Mi Perfil</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Información del Usuario -->
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/img/user2-160x160.jpg"
                                 alt="Foto de perfil">
                        </div>
                        <h3 class="profile-username text-center"><?= htmlspecialchars($user['nombre']) ?></h3>
                        <p class="text-muted text-center"><?= htmlspecialchars($user['rol']) ?></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right"><?= htmlspecialchars($user['email']) ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Miembro desde</b> <a class="float-right"><?= date('d/m/Y', strtotime($user['creado_en'])) ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Pestañas de Edición -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#informacion" data-toggle="tab">
                                    Información Personal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#seguridad" data-toggle="tab">
                                    Seguridad
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Información Personal -->
                            <div class="active tab-pane" id="informacion">
                                <form id="form-perfil">
                                    <input type="hidden" name="action" value="actualizar_perfil">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                               value="<?= htmlspecialchars($user['nombre']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Guardar Cambios
                                    </button>
                                </form>
                            </div>

                            <!-- Seguridad -->
                            <div class="tab-pane" id="seguridad">
                                <form id="form-password">
                                    <input type="hidden" name="action" value="cambiar_password">
                                    <div class="form-group">
                                        <label for="password_actual">Contraseña Actual</label>
                                        <input type="password" class="form-control" id="password_actual" 
                                               name="password_actual" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_nuevo">Nueva Contraseña</label>
                                        <input type="password" class="form-control" id="password_nuevo"
                                               name="password_nuevo" required>
                                        <small class="form-text text-muted">
                                            La contraseña debe tener al menos 8 caracteres
                                        </small>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmar">Confirmar Nueva Contraseña</label>
                                        <input type="password" class="form-control" id="password_confirmar"
                                               name="password_confirmar" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Cambiar Contraseña
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts específicos para el perfil -->
<?php ob_start(); ?>
<script>
$(document).ready(function() {
    // Manejar actualización de perfil
    $('#form-perfil').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= BASE_URL ?>controllers/perfil_controller.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.error);
                }
            },
            error: function() {
                toastr.error('Error al procesar la solicitud');
            }
        });
    });

    // Manejar cambio de contraseña
    $('#form-password').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= BASE_URL ?>controllers/perfil_controller.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#form-password')[0].reset();
                } else {
                    toastr.error(response.error);
                }
            },
            error: function() {
                toastr.error('Error al procesar la solicitud');
            }
        });
    });
});
</script>
<?php
$extraScripts = ob_get_clean();
?>
