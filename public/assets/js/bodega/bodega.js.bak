/**
 * Módulo de Bodega - HerrerosPro
 * Funciones JavaScript para la gestión de bodega
 */

// Objeto principal del módulo
const BodegaApp = {
    // Inicialización del módulo
    init: function() {
        console.log('Inicializando módulo de Bodega...');
        this.initEventListeners();
        this.initQRScanner();
    },

    // Inicializar listeners de eventos
    initEventListeners: function() {
        // Eventos para el modal de categorías
        $('#modalCategoria').on('show.bs.modal', function (e) {
            if (!e.relatedTarget || !e.relatedTarget.hasAttribute('data-id')) {
                $('#modalCategoriaLabel').text('Nueva Categoría');
                $('#id').val(0);
                $('#nombre').val('');
                $('#descripcion').val('');
            }
        });

        // Eventos para el modal de productos
        $('#modalProducto').on('show.bs.modal', function (e) {
            if (!e.relatedTarget || !e.relatedTarget.hasAttribute('data-id')) {
                $('#modalProductoLabel').text('Nuevo Producto');
                $('#id_producto').val(0);
                $('#codigo').val('');
                $('#nombre_producto').val('');
                $('#descripcion_producto').val('');
                $('#tipo').val('');
                $('#consumible').prop('checked', false);
                $('#cantidad').val(1);
                $('#ubicacion').val('');
                $('#imagen_actual').hide();
            }
        });

        // Eventos para el modal de movimientos
        $('#tipo_movimiento').change(function() {
            const tipo = $(this).val();
            if (tipo === 'prestamo') {
                $('#grupo_fecha_devolucion').show();
            } else {
                $('#grupo_fecha_devolucion').hide();
            }
        });

        // Botón para guardar categoría
        $('#btnGuardarCategoria').click(function() {
            BodegaApp.guardarCategoria();
        });

        // Botón para guardar producto
        $('#btnGuardarProducto').click(function() {
            BodegaApp.guardarProducto();
        });

        // Botón para guardar movimiento
        $('#btn-guardar-movimiento').click(function() {
            BodegaApp.guardarMovimiento();
        });

        // Botón para imprimir QR
        $('#btn-imprimir-qr').click(function() {
            BodegaApp.imprimirQR();
        });
    },

    // Inicializar escáner de QR
    initQRScanner: function() {
        $('#modalEscanearQR').on('shown.bs.modal', function () {
            // Aquí se implementará la lógica para inicializar el escáner QR
            // Esto requiere una biblioteca como html5-qrcode
            console.log('Inicializando escáner QR...');
            
            // Ejemplo de implementación con html5-qrcode (requiere incluir la biblioteca)
            if (typeof Html5Qrcode !== 'undefined') {
                const html5QrCode = new Html5Qrcode("qr-reader");
                const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                    console.log(`Código QR detectado: ${decodedText}`);
                    html5QrCode.stop();
                    
                    // Mostrar el resultado
                    $('#qr-content').text(decodedText);
                    $('#qr-result').removeClass('d-none');
                    
                    // Buscar el producto por código
                    BodegaApp.buscarProductoPorCodigo(decodedText);
                };
                
                const config = { fps: 10, qrbox: 250 };
                
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config, 
                    qrCodeSuccessCallback
                );
                
                // Detener el escáner cuando se cierra el modal
                $('#modalEscanearQR').on('hidden.bs.modal', function () {
                    if (html5QrCode.isScanning) {
                        html5QrCode.stop();
                    }
                });
            }
        });
    },

    // Función para guardar categoría
    guardarCategoria: function() {
        const form = $('#formCategoria');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        const id = $('#id').val();
        const nombre = $('#nombre').val();
        const descripcion = $('#descripcion').val();
        
        $.ajax({
            url: 'index.php?controller=bodega&action=guardarCategoria',
            type: 'POST',
            data: {
                id: id,
                nombre: nombre,
                descripcion: descripcion
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#modalCategoria').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la respuesta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para editar categoría
    editarCategoria: function(id) {
        $.ajax({
            url: 'index.php?controller=bodega&action=getCategoriaById',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const categoria = response.data;
                    
                    $('#modalCategoriaLabel').text('Editar Categoría');
                    $('#id').val(categoria.id);
                    $('#nombre').val(categoria.nombre);
                    $('#descripcion').val(categoria.descripcion);
                    
                    $('#modalCategoria').modal('show');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para eliminar categoría
    eliminarCategoria: function(id) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede revertir. Si hay productos asociados a esta categoría, se marcará como inactiva en lugar de eliminarla.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php?controller=bodega&action=eliminarCategoria',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la respuesta',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al comunicarse con el servidor',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    },

    // Función para guardar producto
    guardarProducto: function() {
        const form = $('#formProducto');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        const formData = new FormData(form[0]);
        
        $.ajax({
            url: 'index.php?controller=bodega&action=guardarProducto',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#modalProducto').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la respuesta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para editar producto
    editarProducto: function(id) {
        $.ajax({
            url: 'index.php?controller=bodega&action=getProductoById',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const producto = response.data;
                    
                    $('#modalProductoLabel').text('Editar Producto');
                    $('#id_producto').val(producto.id);
                    $('#codigo').val(producto.codigo);
                    $('#nombre_producto').val(producto.nombre);
                    $('#descripcion_producto').val(producto.descripcion);
                    $('#id_categoria').val(producto.id_categoria);
                    $('#tipo').val(producto.tipo);
                    $('#consumible').prop('checked', producto.consumible == 1);
                    $('#cantidad').val(producto.cantidad);
                    $('#ubicacion').val(producto.ubicacion);
                    
                    if (producto.imagen) {
                        $('#imagen_actual').attr('src', 'uploads/bodega/' + producto.imagen);
                        $('#imagen_actual').show();
                    } else {
                        $('#imagen_actual').hide();
                    }
                    
                    $('#modalProducto').modal('show');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para eliminar producto
    eliminarProducto: function(id) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede revertir. Si hay movimientos asociados a este producto, se marcará como baja en lugar de eliminarlo.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php?controller=bodega&action=eliminarProducto',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la respuesta',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al comunicarse con el servidor',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    },

    // Función para registrar movimiento
    registrarMovimiento: function(id) {
        // Cargar datos del producto
        $.ajax({
            url: 'index.php?controller=bodega&action=getProductoById',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const producto = response.data;
                    $('#id_producto').val(producto.id);
                    $('#producto_nombre').val(producto.nombre);
                    
                    // Cargar proyectos
                    $.ajax({
                        url: 'index.php?controller=proyectos&action=getProyectosByTaller',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                const proyectos = response.data;
                                let options = '<option value="">Ninguno</option>';
                                proyectos.forEach(proyecto => {
                                    options += `<option value="${proyecto.id}">${proyecto.nombre}</option>`;
                                });
                                $('#id_proyecto').html(options);
                            }
                        }
                    });
                    
                    $('#modalRegistrarMovimiento').modal('show');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para guardar movimiento
    guardarMovimiento: function() {
        const form = $('#formRegistrarMovimiento');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        const formData = new FormData(form[0]);
        
        $.ajax({
            url: 'index.php?controller=bodega&action=registrarMovimiento',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#modalRegistrarMovimiento').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la respuesta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para mostrar QR de un producto
    verQR: function(id) {
        $.ajax({
            url: 'index.php?controller=bodega&action=getProductoById',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const producto = response.data;
                    
                    // Mostrar información del producto
                    $('#qr-producto-nombre').text(producto.nombre);
                    $('#qr-producto-codigo').text(producto.codigo);
                    
                    // Mostrar QR
                    if (producto.qr_code) {
                        $('#qr-code-container').html(`<img src="uploads/bodega/qr/${producto.qr_code}" class="img-fluid" alt="Código QR">`);
                    } else {
                        // Si no hay QR, generarlo
                        BodegaApp.generarQR(producto.codigo, producto.id);
                    }
                    
                    $('#modalVerQR').modal('show');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para generar QR
    generarQR: function(codigo, id_producto) {
        $.ajax({
            url: 'index.php?controller=bodega&action=generarQR',
            type: 'POST',
            data: { 
                codigo: codigo,
                id_producto: id_producto
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#qr-code-container').html(`<img src="uploads/bodega/qr/${response.qr_code}" class="img-fluid" alt="Código QR">`);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    },

    // Función para imprimir QR
    imprimirQR: function() {
        const contenido = document.getElementById('qr-code-container').innerHTML;
        const nombre = document.getElementById('qr-producto-nombre').innerText;
        const codigo = document.getElementById('qr-producto-codigo').innerText;
        
        const ventana = window.open('', '_blank');
        ventana.document.write(`
            <html>
            <head>
                <title>Código QR - ${nombre}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        text-align: center;
                        padding: 20px;
                    }
                    .qr-container {
                        margin: 0 auto;
                        max-width: 300px;
                    }
                    img {
                        max-width: 100%;
                    }
                    h2 {
                        margin-top: 10px;
                        margin-bottom: 5px;
                    }
                    p {
                        margin-top: 0;
                        color: #666;
                    }
                    @media print {
                        body {
                            padding: 0;
                        }
                        button {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="qr-container">
                    ${contenido}
                    <h2>${nombre}</h2>
                    <p>${codigo}</p>
                </div>
                <button onclick="window.print()">Imprimir</button>
            </body>
            </html>
        `);
        ventana.document.close();
    },

    // Función para buscar producto por código (usado con el escáner QR)
    buscarProductoPorCodigo: function(codigo) {
        $.ajax({
            url: 'index.php?controller=bodega&action=getProductoByCodigo',
            type: 'POST',
            data: { codigo: codigo },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const producto = response.data;
                    
                    // Mostrar información del producto
                    let html = `
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">${producto.nombre}</h5>
                                <p class="card-text">
                                    <strong>Código:</strong> ${producto.codigo}<br>
                                    <strong>Categoría:</strong> ${producto.categoria_nombre}<br>
                                    <strong>Tipo:</strong> ${producto.tipo}<br>
                                    <strong>Estado:</strong> ${producto.estado}<br>
                                </p>
                                <button type="button" class="btn btn-primary" onclick="BodegaApp.registrarMovimiento(${producto.id})">
                                    Registrar Movimiento
                                </button>
                            </div>
                        </div>
                    `;
                    
                    $('#qr-product-info').html(html);
                    $('#btn-registrar-movimiento').removeClass('d-none');
                    $('#btn-registrar-movimiento').attr('onclick', `BodegaApp.registrarMovimiento(${producto.id})`);
                } else {
                    $('#qr-product-info').html(`
                        <div class="alert alert-warning">
                            No se encontró ningún producto con el código: ${codigo}
                        </div>
                    `);
                    $('#btn-registrar-movimiento').addClass('d-none');
                }
            },
            error: function() {
                $('#qr-product-info').html(`
                    <div class="alert alert-danger">
                        Ocurrió un error al comunicarse con el servidor
                    </div>
                `);
                $('#btn-registrar-movimiento').addClass('d-none');
            }
        });
    }
};

// Inicializar el módulo cuando el documento esté listo
$(document).ready(function() {
    BodegaApp.init();
}); 