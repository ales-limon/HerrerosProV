<?php
/**
 * Vista de detalles de taller activo
 * Según MEMORY[0c7884a9]: MVC con PHP, AdminLTE UI
 */

$pageTitle = 'Detalles del Taller';
$currentPage = 'talleres';

ob_start();
?>

<div class="row">
    <div class="col-md-3">
        <!-- Perfil del Taller -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <h3 class="profile-username text-center">Taller de Prueba</h3>
                <p class="text-muted text-center">Plan Profesional</p>
                
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Estado</b> <a class="float-right"><span class="badge badge-success">Activo</span></a>
                    </li>
                    <li class="list-group-item">
                        <b>Usuarios</b> <a class="float-right">4/5</a>
                    </li>
                    <li class="list-group-item">
                        <b>Vencimiento</b> <a class="float-right">15/04/2025</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Información de Contacto</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Dirección</strong>
                <p class="text-muted">Calle Example #123, Ciudad Example</p>

                <hr>

                <strong><i class="fas fa-phone mr-1"></i> Teléfono</strong>
                <p class="text-muted">(123) 456-7890</p>

                <hr>

                <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                <p class="text-muted">contact@tallerexample.com</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#actividad" data-toggle="tab">Actividad</a></li>
                    <li class="nav-item"><a class="nav-link" href="#suscripcion" data-toggle="tab">Suscripción</a></li>
                    <li class="nav-item"><a class="nav-link" href="#usuarios" data-toggle="tab">Usuarios</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Pestaña de Actividad -->
                    <div class="active tab-pane" id="actividad">
                        <div class="timeline timeline-inverse">
                            <!-- Timeline items se cargarán vía AJAX -->
                            <div>
                                <i class="fas fa-user bg-info"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="far fa-clock"></i> Hace 5 minutos</span>
                                    <h3 class="timeline-header">Nuevo usuario registrado</h3>
                                    <div class="timeline-body">
                                        Se agregó el usuario "Juan Pérez" al sistema.
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <i class="fas fa-sync bg-warning"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="far fa-clock"></i> Hace 2 horas</span>
                                    <h3 class="timeline-header">Actualización de plan</h3>
                                    <div class="timeline-body">
                                        El taller actualizó su plan a "Profesional".
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Suscripción -->
                    <div class="tab-pane" id="suscripcion">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Próximo Pago</span>
                                        <span class="info-box-number">15/04/2025</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Monto Mensual</span>
                                        <span class="info-box-number">$99.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Historial de Pagos</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Monto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>15/03/2025</td>
                                            <td>Plan Profesional - Marzo 2025</td>
                                            <td>$99.00</td>
                                            <td><span class="badge badge-success">Pagado</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Usuarios -->
                    <div class="tab-pane" id="usuarios">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Usuarios del Taller</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>John Doe</td>
                                            <td>john@example.com</td>
                                            <td>Administrador</td>
                                            <td><span class="badge badge-success">Activo</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$contentView = ob_get_clean();

// Script específico para esta página
$pageScript = <<<SCRIPT
$(function() {
    // Activar los tabs de Bootstrap
    $('a[data-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});
SCRIPT;

// Incluir la plantilla principal
require_once __DIR__ . '/../layouts/main.php';
?>
