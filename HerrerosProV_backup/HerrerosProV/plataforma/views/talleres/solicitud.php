<?php
/**
 * Vista de detalles de solicitud de taller
 * Según MEMORY[56c03982]: Proceso de aprobación de talleres
 */

$pageTitle = 'Detalles de Solicitud';
$currentPage = 'talleres';

ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Solicitud #<span id="solicitud-id">32</span></h3>
                <div class="card-tools">
                    <span class="badge badge-warning">Pendiente</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Información del Taller</h4>
                        <dl class="row">
                            <dt class="col-sm-4">Nombre del Taller</dt>
                            <dd class="col-sm-8">Taller de Prueba</dd>
                            
                            <dt class="col-sm-4">Dirección</dt>
                            <dd class="col-sm-8">Calle Example #123</dd>
                            
                            <dt class="col-sm-4">Ciudad</dt>
                            <dd class="col-sm-8">Ciudad Example</dd>
                            
                            <dt class="col-sm-4">Teléfono</dt>
                            <dd class="col-sm-8">(123) 456-7890</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h4>Información del Propietario</h4>
                        <dl class="row">
                            <dt class="col-sm-4">Nombre</dt>
                            <dd class="col-sm-8">John Doe</dd>
                            
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">john@example.com</dd>
                            
                            <dt class="col-sm-4">Teléfono</dt>
                            <dd class="col-sm-8">(123) 456-7890</dd>
                        </dl>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h4>Plan Seleccionado</h4>
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Plan Profesional</h5>
                                <p class="card-text">
                                    <ul>
                                        <li>Hasta 5 usuarios</li>
                                        <li>Soporte prioritario</li>
                                        <li>Reportes avanzados</li>
                                    </ul>
                                </p>
                                <h6>Precio: $99/mes</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>Documentos</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                RFC
                                <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Identificación
                                <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Comprobante de domicilio
                                <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-rechazar">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-aprobar">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aprobar -->
<div class="modal fade" id="modal-aprobar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Aprobar Solicitud</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea aprobar esta solicitud?</p>
                <p class="text-muted">Se enviará un email de activación al propietario.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-aprobar">Aprobar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rechazar -->
<div class="modal fade" id="modal-rechazar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Rechazar Solicitud</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="motivo-rechazo">Motivo del rechazo</label>
                    <textarea class="form-control" id="motivo-rechazo" rows="3" placeholder="Ingrese el motivo del rechazo"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-rechazar">Rechazar</button>
            </div>
        </div>
    </div>
</div>

<?php
$contentView = ob_get_clean();

// Script específico para esta página
$pageScript = <<<SCRIPT
$(function() {
    // Por ahora solo cerraremos los modales
    $('#btn-aprobar').click(function() {
        $('#modal-aprobar').modal('hide');
    });
    
    $('#btn-rechazar').click(function() {
        $('#modal-rechazar').modal('hide');
    });
});
SCRIPT;

// Incluir la plantilla principal
require_once __DIR__ . '/../layouts/main.php';
?>
