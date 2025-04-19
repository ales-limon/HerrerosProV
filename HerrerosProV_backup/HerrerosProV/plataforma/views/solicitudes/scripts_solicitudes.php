<?php
/**
 * Scripts para la vista de solicitudes
 * 
 * Este archivo contiene los scripts específicos para la funcionalidad
 * de la página de gestión de solicitudes.
 */
?>

<script>
$(document).ready(function() {
    // Configuración de DataTable
    const table = $('#solicitudesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= BASE_URL ?>?controller=solicitudes&action=getDataTable',
            type: 'POST',
            data: function(d) {
                d.estado = $('#filtroEstado').val();
                return d;
            }
        },
        columns: [
            { data: 'id' },
            { data: 'nombre_taller' },
            { data: 'contacto' },
            { data: 'plan' },
            { 
                data: 'estado',
                render: function(data) {
                    if (data === 'pendiente') {
                        return '<span class="badge badge-warning">Pendiente</span>';
                    } else if (data === 'aprobada') {
                        return '<span class="badge badge-success">Aprobada</span>';
                    } else if (data === 'rechazada') {
                        return '<span class="badge badge-danger">Rechazada</span>';
                    }
                    return data;
                }
            },
            { 
                data: 'fecha_creacion',
                render: function(data) {
                    return moment(data).format('DD/MM/YYYY HH:mm');
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(data) {
                    let buttons = `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info btn-ver-detalles" data-id="${data.id}">
                                <i class="fas fa-eye"></i>
                            </button>`;
                    
                    if (data.estado === 'pendiente') {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-success btn-aprobar" data-id="${data.id}">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-rechazar" data-id="${data.id}">
                                <i class="fas fa-times"></i>
                            </button>`;
                    }
                    
                    buttons += `</div>`;
                    return buttons;
                }
            }
        ],
        language: {
            url: '<?= BASE_URL ?>assets/plugins/datatables/es-ES.json'
        },
        order: [[0, 'desc']],
        responsive: true,
        autoWidth: false
    });
    
    // Aplicar filtro al cambiar el select
    $('#filtroEstado').change(function() {
        table.ajax.reload();
    });
    
    // Búsqueda personalizada
    $('#btnBuscar').click(function() {
        table.search($('#searchInput').val()).draw();
    });
    
    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            table.search($(this).val()).draw();
        }
    });
    
    // Ver detalles de solicitud
    $(document).on('click', '.btn-ver-detalles', function() {
        const id = $(this).data('id');
        
        // Mostrar loader
        $('#detallesModal .modal-body').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-2">Cargando detalles...</p></div>');
        $('#detallesModal').modal('show');
        
        // Cargar detalles vía AJAX
        $.ajax({
            url: '<?= BASE_URL ?>?controller=solicitudes&action=getDetails',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID de Solicitud:</label>
                                    <p class="form-control-static">${response.data.id}</p>
                                </div>
                                <div class="form-group">
                                    <label>Nombre del Taller:</label>
                                    <p class="form-control-static">${response.data.nombre_taller}</p>
                                </div>
                                <div class="form-group">
                                    <label>Dirección:</label>
                                    <p class="form-control-static">${response.data.direccion || '-'}</p>
                                </div>
                                <div class="form-group">
                                    <label>Teléfono:</label>
                                    <p class="form-control-static">${response.data.telefono || '-'}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contacto:</label>
                                    <p class="form-control-static">${response.data.nombre_contacto}</p>
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <p class="form-control-static">${response.data.email}</p>
                                </div>
                                <div class="form-group">
                                    <label>Plan Seleccionado:</label>
                                    <p class="form-control-static">${response.data.plan}</p>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de Solicitud:</label>
                                    <p class="form-control-static">${moment(response.data.fecha_creacion).format('DD/MM/YYYY HH:mm')}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Estado:</label>
                                    <p class="form-control-static">
                                        ${getEstadoBadge(response.data.estado)}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>Notas:</label>
                                    <div class="p-2 bg-light rounded">${response.data.notas || 'Sin notas'}</div>
                                </div>
                            </div>
                        </div>`;
                    
                    if (response.data.estado === 'pendiente') {
                        html += `
                            <div class="row mt-3">
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-success btn-aprobar" data-id="${response.data.id}">
                                        <i class="fas fa-check"></i> Aprobar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-rechazar" data-id="${response.data.id}">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                </div>
                            </div>`;
                    }
                    
                    $('#detallesModal .modal-body').html(html);
                } else {
                    $('#detallesModal .modal-body').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> ${response.message || 'Error al cargar los detalles'}
                        </div>`);
                }
            },
            error: function() {
                $('#detallesModal .modal-body').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error de conexión. Intente nuevamente más tarde.
                    </div>`);
            }
        });
    });
    
    // Función para obtener badge de estado
    function getEstadoBadge(estado) {
        if (estado === 'pendiente') {
            return '<span class="badge badge-warning">Pendiente</span>';
        } else if (estado === 'aprobada') {
            return '<span class="badge badge-success">Aprobada</span>';
        } else if (estado === 'rechazada') {
            return '<span class="badge badge-danger">Rechazada</span>';
        }
        return estado;
    }
    
    // Aprobar solicitud
    $(document).on('click', '.btn-aprobar', function() {
        const id = $(this).data('id');
        $('#aprobar_id_solicitud').val(id);
        $('#aprobarModal').modal('show');
    });
    
    $('#aprobarForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?= BASE_URL ?>?controller=solicitudes&action=approve',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                // Desactivar botón y mostrar spinner
                $('#aprobarForm button[type="submit"]').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Procesando...'
                );
            },
            success: function(response) {
                if (response.success) {
                    // Cerrar modal
                    $('#aprobarModal').modal('hide');
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        title: '¡Solicitud Aprobada!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                    
                    // Recargar tabla
                    table.ajax.reload();
                    
                    // Si está abierto el modal de detalles, cerrarlo
                    $('#detallesModal').modal('hide');
                } else {
                    // Mostrar mensaje de error
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Ha ocurrido un error al aprobar la solicitud',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error de conexión. Intente nuevamente más tarde.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            },
            complete: function() {
                // Reactivar botón
                $('#aprobarForm button[type="submit"]').prop('disabled', false).html(
                    '<i class="fas fa-check"></i> Aprobar Solicitud'
                );
                
                // Limpiar formulario
                $('#aprobarForm')[0].reset();
            }
        });
    });
    
    // Rechazar solicitud
    $(document).on('click', '.btn-rechazar', function() {
        const id = $(this).data('id');
        $('#rechazar_id_solicitud').val(id);
        $('#rechazarModal').modal('show');
    });
    
    $('#rechazarForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?= BASE_URL ?>?controller=solicitudes&action=reject',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                // Desactivar botón y mostrar spinner
                $('#rechazarForm button[type="submit"]').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Procesando...'
                );
            },
            success: function(response) {
                if (response.success) {
                    // Cerrar modal
                    $('#rechazarModal').modal('hide');
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        title: 'Solicitud Rechazada',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                    
                    // Recargar tabla
                    table.ajax.reload();
                    
                    // Si está abierto el modal de detalles, cerrarlo
                    $('#detallesModal').modal('hide');
                } else {
                    // Mostrar mensaje de error
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Ha ocurrido un error al rechazar la solicitud',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error de conexión. Intente nuevamente más tarde.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            },
            complete: function() {
                // Reactivar botón
                $('#rechazarForm button[type="submit"]').prop('disabled', false).html(
                    '<i class="fas fa-times"></i> Rechazar Solicitud'
                );
                
                // No limpiar el campo de notas para permitir corregir errores
            }
        });
    });
});
</script> 