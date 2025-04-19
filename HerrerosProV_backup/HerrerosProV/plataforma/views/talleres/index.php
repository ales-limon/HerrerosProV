<?php
/**
 * Vista principal de Talleres
 * Según MEMORY[0c7884a9]: MVC con PHP, AdminLTE UI, DataTables
 */

$pageTitle = 'Gestión de Talleres';
$currentPage = 'talleres';

// Incluir la plantilla principal
ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Talleres</h3>
            </div>
            <div class="card-body">
                <table id="talleres-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Propietario</th>
                            <th>Plan</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán vía AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Solicitudes Pendientes</h3>
                <div class="card-tools">
                    <span class="badge badge-warning">13 nuevas</span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Taller</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán vía AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Talleres Activos</h3>
                <div class="card-tools">
                    <span class="badge badge-success">7 activos</span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Taller</th>
                            <th>Plan</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán vía AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$contentView = ob_get_clean();

// Script específico para esta página
$pageScript = <<<SCRIPT
$(function() {
    $('#talleres-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        }
    });
});
SCRIPT;

// Incluir la plantilla principal
require_once __DIR__ . '/../layouts/main.php';
?>
