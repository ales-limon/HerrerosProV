<?php
/**
 * Scripts para el módulo de solicitudes
 * 
 * Este archivo contiene los scripts JavaScript para la funcionalidad
 * del módulo de solicitudes.
 */
?>
<script>
$(document).ready(function() {
    // Variables globales
    let currentPage = 1;
    let totalPages = 1;
    let currentFilter = '';
    let searchTerm = '';
    
    // Inicializar la carga de datos
    cargarSolicitudes();
    
    // Filtrar por estado desde tarjetas de estadísticas
    $('.small-box-footer[data-filter]').on('click', function(e) {
        e.preventDefault();
        const filtro = $(this).data('filter');
        $('#filtro-estado').val(filtro === 'todas' ? '' : filtro);
        currentFilter = filtro === 'todas' ? '' : filtro;
        currentPage = 1;
        cargarSolicitudes();
    });
    
    // Cambio en selector de filtro
    $('#filtro-estado').change(function() {
        currentFilter = $(this).val();
        currentPage = 1;
        cargarSolicitudes();
    });
    
    // Búsqueda en tabla
    let searchTimeout;
    $('#tabla-busqueda').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            searchTerm = $('#tabla-busqueda').val();
            currentPage = 1;
            cargarSolicitudes();
        }, 500);
    });
    
    // Eventos para los modales
    // Abrir modal de detalles
    $(document).on('click', '.btn-ver', function() {
        const id = $(this).data('id');
        cargarDetallesSolicitud(id);
    });
    
    // Abrir modal de aprobar desde botón en tabla
    $(document).on('click', '.btn-aprobar', function() {
        const id = $(this).data('id');
        $('#aprobar-id').val(id);
        $('#modal-aprobar').modal('show');
    });
    
    // Abrir modal de rechazar desde botón en tabla
    $(document).on('click', '.btn-rechazar', function() {
        const id = $(this).data('id');
        $('#rechazar-id').val(id);
        $('#modal-rechazar').modal('show');
    });
    
    // Abrir modal de aprobar desde modal de detalles
    $('#btn-aprobar-detalle').click(function() {
        const id = $('#detalle-id').text();
        $('#aprobar-id').val(id);
        $('#modal-detalles').modal('hide');
        $('#modal-aprobar').modal('show');
    });
    
    // Abrir modal de rechazar desde modal de detalles
    $('#btn-rechazar-detalle').click(function() {
        const id = $('#detalle-id').text();
        $('#rechazar-id').val(id);
        $('#modal-detalles').modal('hide');
        $('#modal-rechazar').modal('show');
    });
    
    // Envío de formulario de aprobación
    $('#form-aprobar').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?= BASE_URL ?>?controller=solicitud&action=aprobar',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                mostrarLoader();
            },
            success: function(response) {
                if (response.success) {
                    mostrarAlerta('success', response.message);
                    $('#modal-aprobar').modal('hide');
                    $('#aprobar-notas').val('');
                    cargarSolicitudes();
                } else {
                    mostrarAlerta('error', response.message);
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error al procesar la solicitud.');
            },
            complete: function() {
                ocultarLoader();
            }
        });
    });
    
    // Envío de formulario de rechazo
    $('#form-rechazar').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?= BASE_URL ?>?controller=solicitud&action=rechazar',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                mostrarLoader();
            },
            success: function(response) {
                if (response.success) {
                    mostrarAlerta('success', response.message);
                    $('#modal-rechazar').modal('hide');
                    $('#rechazar-notas').val('');
                    cargarSolicitudes();
                } else {
                    mostrarAlerta('error', response.message);
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error al procesar la solicitud.');
            },
            complete: function() {
                ocultarLoader();
            }
        });
    });
    
    // Eventos de paginación
    $(document).on('click', '.pagina-link', function(e) {
        e.preventDefault();
        currentPage = $(this).data('pagina');
        cargarSolicitudes();
    });
    
    // Funciones auxiliares
    
    // Función para cargar la lista de solicitudes
    function cargarSolicitudes() {
        $.ajax({
            url: '<?= BASE_URL ?>?controller=solicitud&action=listar',
            type: 'GET',
            data: {
                page: currentPage,
                filter: currentFilter,
                search: searchTerm
            },
            dataType: 'json',
            beforeSend: function() {
                mostrarLoader();
            },
            success: function(response) {
                if (response.success) {
                    renderizarTablaSolicitudes(response.data);
                    renderizarPaginacion(response.pagination);
                    actualizarEstadisticas(response.stats);
                } else {
                    mostrarAlerta('error', response.message);
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error al cargar las solicitudes.');
            },
            complete: function() {
                ocultarLoader();
            }
        });
    }
    
    // Función para cargar los detalles de una solicitud
    function cargarDetallesSolicitud(id) {
        $.ajax({
            url: '<?= BASE_URL ?>?controller=solicitud&action=ver',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            beforeSend: function() {
                mostrarLoader();
            },
            success: function(response) {
                if (response.success) {
                    mostrarDetallesSolicitud(response.data);
                    $('#modal-detalles').modal('show');
                } else {
                    mostrarAlerta('error', response.message);
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error al cargar los detalles de la solicitud.');
            },
            complete: function() {
                ocultarLoader();
            }
        });
    }
    
    // Función para renderizar la tabla de solicitudes
    function renderizarTablaSolicitudes(data) {
        let html = '';
        if (data.length === 0) {
            html = '<tr><td colspan="6" class="text-center">No se encontraron solicitudes</td></tr>';
        } else {
            data.forEach(function(solicitud) {
                let estadoClass = '';
                let estadoTexto = '';
                
                switch(solicitud.estado) {
                    case 'pendiente':
                        estadoClass = 'badge-warning';
                        estadoTexto = 'Pendiente';
                        break;
                    case 'aprobada':
                        estadoClass = 'badge-success';
                        estadoTexto = 'Aprobada';
                        break;
                    case 'rechazada':
                        estadoClass = 'badge-danger';
                        estadoTexto = 'Rechazada';
                        break;
                }
                
                let botonesAccion = `
                    <button type="button" class="btn btn-sm btn-info btn-ver" data-id="${solicitud.id}">
                        <i class="fas fa-eye"></i>
                    </button>`;
                
                if (solicitud.estado === 'pendiente') {
                    botonesAccion += `
                    <button type="button" class="btn btn-sm btn-success btn-aprobar ml-1" data-id="${solicitud.id}">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger btn-rechazar ml-1" data-id="${solicitud.id}">
                        <i class="fas fa-times"></i>
                    </button>`;
                }
                
                html += `
                <tr>
                    <td>${solicitud.id}</td>
                    <td>${solicitud.nombre_taller}</td>
                    <td>${solicitud.nombre_usuario}</td>
                    <td>${solicitud.fecha_solicitud}</td>
                    <td><span class="badge ${estadoClass}">${estadoTexto}</span></td>
                    <td>${botonesAccion}</td>
                </tr>`;
            });
        }
        
        $('#tabla-solicitudes tbody').html(html);
    }
    
    // Función para renderizar la paginación
    function renderizarPaginacion(pagination) {
        totalPages = pagination.total_pages;
        currentPage = pagination.current_page;
        
        let html = '';
        
        // Botón Anterior
        html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link pagina-link" href="#" data-pagina="${currentPage - 1}" aria-label="Anterior">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>`;
        
        // Números de página
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        if (endPage - startPage < 4 && totalPages > 5) {
            startPage = Math.max(1, endPage - 4);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            html += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link pagina-link" href="#" data-pagina="${i}">${i}</a>
            </li>`;
        }
        
        // Botón Siguiente
        html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link pagina-link" href="#" data-pagina="${currentPage + 1}" aria-label="Siguiente">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>`;
        
        $('#paginacion-solicitudes').html(html);
    }
    
    // Función para mostrar los detalles de una solicitud en el modal
    function mostrarDetallesSolicitud(data) {
        $('#detalle-id').text(data.id);
        $('#detalle-taller').text(data.nombre_taller);
        $('#detalle-categoria').text(data.categoria);
        $('#detalle-precio').text(`$${data.precio}`);
        $('#detalle-solicitante').text(data.nombre_usuario);
        $('#detalle-fecha').text(data.fecha_solicitud);
        
        let estadoClass = '';
        let estadoTexto = '';
        
        switch(data.estado) {
            case 'pendiente':
                estadoClass = 'badge-warning';
                estadoTexto = 'Pendiente';
                break;
            case 'aprobada':
                estadoClass = 'badge-success';
                estadoTexto = 'Aprobada';
                break;
            case 'rechazada':
                estadoClass = 'badge-danger';
                estadoTexto = 'Rechazada';
                break;
        }
        
        $('#detalle-estado').html(`<span class="badge ${estadoClass}">${estadoTexto}</span>`);
        $('#detalle-notas').text(data.notas || 'Sin notas');
        $('#detalle-descripcion').html(data.descripcion);
        
        // Mostrar u ocultar botones según el estado
        if (data.estado === 'pendiente') {
            $('#btn-aprobar-detalle, #btn-rechazar-detalle').show();
        } else {
            $('#btn-aprobar-detalle, #btn-rechazar-detalle').hide();
        }
    }
    
    // Función para actualizar las estadísticas
    function actualizarEstadisticas(stats) {
        $('.small-box.bg-info .inner h3').text(stats.pendientes);
        $('.small-box.bg-success .inner h3').text(stats.aprobadas);
        $('.small-box.bg-danger .inner h3').text(stats.rechazadas);
        $('.small-box.bg-warning .inner h3').text(stats.total);
    }
    
    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        Swal.fire({
            icon: tipo,
            title: tipo === 'success' ? 'Éxito' : 'Error',
            text: mensaje,
            timer: 3000,
            showConfirmButton: false
        });
    }
    
    // Función para mostrar el loader
    function mostrarLoader() {
        $('#overlay').show();
    }
    
    // Función para ocultar el loader
    function ocultarLoader() {
        $('#overlay').hide();
    }
});
</script> 