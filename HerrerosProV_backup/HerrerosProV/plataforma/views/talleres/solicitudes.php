<?php
/**
 * Vista de Solicitudes de Talleres
 * Según MEMORY[0c7884a9] - Roles: Admin y Supervisor tienen acceso
 */

// Verificar permisos
if (!$auth->hasPermission('aprobar_solicitudes')) {
    header('Location: ' . BASE_URL . '?page=dashboard');
    exit;
}
?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Solicitudes de Talleres</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Inicio</a></li>
                    <li class="breadcrumb-item active">Solicitudes de Talleres</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Solicitudes Pendientes</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="solicitudesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Taller</th>
                                    <th>Propietario</th>
                                    <th>Email</th>
                                    <th>Fecha Solicitud</th>
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
    </div>
</section>

<!-- Modal de Detalles -->
<div class="modal fade" id="detallesSolicitudModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detalles de la Solicitud</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnAprobar">Aprobar</button>
                <button type="button" class="btn btn-danger" id="btnRechazar">Rechazar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#solicitudesTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "ajax": {
            "url": "<?= BASE_URL ?>controllers/talleres_controller.php",
            "type": "POST",
            "data": {
                "action": "listar_solicitudes"
            }
        },
        "columns": [
            {"data": "id_solicitud"},
            {"data": "nombre_taller"},
            {"data": "propietario"},
            {"data": "email"},
            {"data": "fecha_solicitud"},
            {
                "data": "estado",
                "render": function(data) {
                    switch(data) {
                        case 'pendiente':
                            return '<span class="badge badge-warning">Pendiente</span>';
                        case 'aprobada':
                            return '<span class="badge badge-success">Aprobada</span>';
                        case 'rechazada':
                            return '<span class="badge badge-danger">Rechazada</span>';
                        default:
                            return data;
                    }
                }
            },
            {
                "data": null,
                "render": function(data) {
                    return `
                        <button class="btn btn-sm btn-info btn-ver" data-id="${data.id_solicitud}">
                            <i class="fas fa-eye"></i>
                        </button>
                    `;
                }
            }
        ]
    });
    
    // Ver detalles de solicitud
    $('#solicitudesTable').on('click', '.btn-ver', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= BASE_URL ?>controllers/talleres_controller.php',
            type: 'POST',
            data: {
                action: 'ver_solicitud',
                id_solicitud: id
            },
            success: function(response) {
                if (response.success) {
                    $('#detallesSolicitudModal .modal-body').html(response.html);
                    $('#detallesSolicitudModal').modal('show');
                } else {
                    toastr.error(response.message || 'Error al cargar los detalles');
                }
            },
            error: function() {
                toastr.error('Error de conexión');
            }
        });
    });
    
    // Aprobar solicitud
    $('#btnAprobar').click(function() {
        var id = $('#detallesSolicitudModal').data('id_solicitud');
        procesarSolicitud(id, 'aprobar');
    });
    
    // Rechazar solicitud
    $('#btnRechazar').click(function() {
        var id = $('#detallesSolicitudModal').data('id_solicitud');
        procesarSolicitud(id, 'rechazar');
    });
    
    function procesarSolicitud(id, accion) {
        $.ajax({
            url: '<?= BASE_URL ?>controllers/talleres_controller.php',
            type: 'POST',
            data: {
                action: accion + '_solicitud',
                id_solicitud: id
            },
            success: function(response) {
                if (response.success) {
                    $('#detallesSolicitudModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Error al procesar la solicitud');
                }
            },
            error: function() {
                toastr.error('Error de conexión');
            }
        });
    }
});
</script>
