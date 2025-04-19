/**
 * Módulo de Finanzas - HerrerosPro
 * Script principal para la gestión de finanzas
 */

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes
    FinanzasApp.init();
});

/**
 * Aplicación principal de Finanzas
 */
const FinanzasApp = {
    // Configuración
    config: {
        dataTable: {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            order: [[0, 'desc']]
        }
    },

    /**
     * Inicializa la aplicación
     */
    init: function() {
        console.log('Inicializando FinanzasApp');
        
        // Inicializar componentes
        this.initDataTables();
        this.initDatePickers();
        this.initEventListeners();
        this.initCustomFileInput();
        this.initFormComponents();
        
        // Inicializar módulos específicos
        if (document.getElementById('graficoFlujo')) {
            console.log('Inicializando GraficoFinanzas');
            GraficoFinanzas.init();
        }
        
        // Siempre inicializar TransaccionesFinanzas para que funcionen los modales
        console.log('Inicializando TransaccionesFinanzas');
        TransaccionesFinanzas.init();
        
        // Siempre inicializar FiltrosFinanzas para que funcionen los filtros
        console.log('Inicializando FiltrosFinanzas');
        FiltrosFinanzas.init();
        
        console.log('FinanzasApp inicializado');
    },

    /**
     * Inicializa las tablas de datos
     */
    initDataTables: function() {
        const tablas = document.querySelectorAll('.datatable');
        if (tablas.length > 0) {
            tablas.forEach(tabla => {
                $(tabla).DataTable({
                    responsive: true,
                    language: this.config.dataTable.language,
                    order: this.config.dataTable.order
                });
            });
        }
    },

    /**
     * Inicializa los selectores de fecha
     */
    initDatePickers: function() {
        const fechaInputs = document.querySelectorAll('input[type="date"]');
        if (fechaInputs.length > 0) {
            // Si se requiere un datepicker personalizado, se implementaría aquí
        }
    },

    /**
     * Inicializa los listeners de eventos
     */
    initEventListeners: function() {
        // Listener para botones de anular transacción
        document.querySelectorAll('.btn-anular-transaccion').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const idTransaccion = this.dataset.id;
                TransaccionesFinanzas.confirmarAnulacion(idTransaccion);
            });
        });

        // Listener para botones de ver detalle
        document.querySelectorAll('.btn-detalle-transaccion').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const idTransaccion = this.dataset.id;
                TransaccionesFinanzas.verDetalle(idTransaccion);
            });
        });

        // Listener para botón de generar reporte
        const btnReporte = document.getElementById('btnGenerarReporte');
        if (btnReporte) {
            btnReporte.addEventListener('click', function(e) {
                e.preventDefault();
                ReportesFinanzas.generarReporte();
            });
        }
    },

    /**
     * Inicializa el input de archivo personalizado
     */
    initCustomFileInput: function() {
        if (typeof bsCustomFileInput !== 'undefined') {
            bsCustomFileInput.init();
        }
    },

    /**
     * Muestra una notificación
     */
    mostrarNotificacion: function(titulo, mensaje, tipo = 'success') {
        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
            showConfirmButton: false,
            timer: 2000
        });
    },

    /**
     * Formatea un valor monetario
     */
    formatoMoneda: function(valor) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN'
        }).format(valor);
    },

    /**
     * Inicializa los componentes del formulario
     */
    initFormComponents: function() {
        console.log('Inicializando componentes del formulario');
        
        // Inicializar el input de archivo personalizado
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName || 'Seleccionar archivo');
        });
        
        // Hacer que el botón "Browse" funcione con el input de archivo
        $('#btn-browse').on('click', function() {
            $('#comprobante').click();
        });
        
        // Resto del código de inicialización de componentes...
    }
};

/**
 * Módulo para gestionar transacciones
 */
const TransaccionesFinanzas = {
    /**
     * Inicializa el módulo de transacciones
     */
    init: function() {
        console.log('Inicializando módulo TransaccionesFinanzas');
        this.initModalTransaccion();
        this.initFormSubmit();
        console.log('Módulo TransaccionesFinanzas inicializado');
    },

    /**
     * Inicializa el modal de transacción
     */
    initModalTransaccion: function() {
        console.log('Inicializando modal de transacción');
        
        // Configurar el modal según el tipo de transacción
        $('#modalNuevaTransaccion').on('show.bs.modal', function(event) {
            console.log('Evento show.bs.modal disparado');
            const button = $(event.relatedTarget);
            const tipo = button.data('tipo');
            const modal = $(this);
            
            console.log('Tipo de transacción:', tipo);
            
            // Establecer tipo
            $('#tipoTransaccion').val(tipo);
            
            // Limpiar el contenido del icono
            $('#iconoTransaccion').empty();
            
            // Cambiar título y contenido según el tipo de transacción
            if (tipo === 'ingreso') {
                // Configurar para ingreso
                $('#modalNuevaTransaccionLabel').html('<i class="fas fa-arrow-circle-up mr-2"></i> Nuevo Ingreso <span class="badge badge-light ml-2">Entrada de dinero</span>');
                
                // Actualizar el icono grande
                $('#iconoTransaccion').removeClass('gasto-icon').addClass('ingreso-icon');
                $('#iconoTransaccion').html('<i class="fas fa-plus-circle"></i>');
                
                // Cambiar el color del botón de guardar
                $('#btnGuardarTransaccion').removeClass('btn-danger btn-primary').addClass('btn-info');
                
                // Mostrar opción de anticipo
                $('#opcionAnticipo').show();
                
                console.log('Modal configurado para ingreso');
            } else {
                // Configurar para gasto
                $('#modalNuevaTransaccionLabel').html('<i class="fas fa-arrow-circle-down mr-2"></i> Nuevo Gasto <span class="badge badge-light ml-2">Salida de dinero</span>');
                
                // Actualizar el icono grande
                $('#iconoTransaccion').removeClass('ingreso-icon').addClass('gasto-icon');
                $('#iconoTransaccion').html('<i class="fas fa-minus-circle"></i>');
                
                // Cambiar el color del botón de guardar
                $('#btnGuardarTransaccion').removeClass('btn-success btn-primary').addClass('btn-info');
                
                // Ocultar opción de anticipo
                $('#opcionAnticipo').hide();
                
                console.log('Modal configurado para gasto');
            }
            
            // Forzar la visibilidad del icono
            $('#iconoTransaccion').css({
                'display': 'block',
                'visibility': 'visible',
                'opacity': '1'
            });
            
            $('#iconoTransaccion i').css({
                'display': 'block',
                'visibility': 'visible',
                'opacity': '1'
            });
            
            // Verificar después de añadir
            setTimeout(function() {
                console.log('Icono visible:', $('#iconoTransaccion').is(':visible'));
                console.log('Contenido del icono:', $('#iconoTransaccion').html());
                console.log('Título del modal:', $('#modalNuevaTransaccionLabel').html());
                console.log('Modal configurado correctamente');
                
                // Forzar nuevamente la visibilidad del icono
                $('#iconoTransaccion').css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1'
                });
                
                $('#iconoTransaccion i').css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1'
                });
            }, 100);
            
            // Evento adicional para asegurar que el icono sea visible después de que se muestre completamente el modal
            $('#modalNuevaTransaccion').on('shown.bs.modal', function() {
                console.log('Modal completamente mostrado');
                
                // Forzar la visibilidad del icono
                $('#iconoTransaccion').css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1'
                });
                
                $('#iconoTransaccion i').css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1'
                });
                
                console.log('Icono forzado a ser visible');
            });
            
            // Cargar categorías según el tipo
            this.cargarCategorias(tipo);
            
            // Cargar proyectos
            this.cargarProyectos();
        }.bind(this));
        
        console.log('Event listener para modal registrado');
    },

    /**
     * Inicializa el envío del formulario
     */
    initFormSubmit: function() {
        const btnGuardar = document.getElementById('btnGuardarTransaccion');
        if (btnGuardar) {
            btnGuardar.addEventListener('click', this.guardarTransaccion.bind(this));
        }
    },

    /**
     * Carga las categorías según el tipo
     */
    cargarCategorias: function(tipo) {
        console.log('Ejecutando cargarCategorias() para tipo:', tipo);
        const selectCategorias = document.getElementById('id_categoria');
        if (!selectCategorias) {
            console.error('No se encontró el elemento select con id="id_categoria"');
            return;
        }
        
        // Limpiar select
        selectCategorias.innerHTML = '<option value="">Sin categoría</option>';
        
        // Verificar si hay categorías
        if (!window.categorias || !Array.isArray(window.categorias)) {
            console.error('No se encontraron categorías o el formato es incorrecto', window.categorias);
            return;
        }
        
        console.log('Categorías disponibles:', window.categorias);
        
        // Filtrar categorías por tipo
        const categoriasFiltradas = window.categorias.filter(c => c.tipo === tipo);
        console.log('Categorías filtradas para tipo ' + tipo + ':', categoriasFiltradas);
        
        // Añadir opciones
        categoriasFiltradas.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria.id_categoria;
            option.textContent = categoria.nombre;
            option.style.color = categoria.color || '#000';
            selectCategorias.appendChild(option);
        });
        
        console.log('Categorías cargadas en el select');
    },
    
    /**
     * Carga los proyectos
     */
    cargarProyectos: function() {
        console.log('Ejecutando cargarProyectos()');
        const selectProyectos = document.getElementById('id_proyecto');
        if (!selectProyectos) {
            console.error('No se encontró el elemento select con id="id_proyecto"');
            return;
        }
        
        // Limpiar select
        selectProyectos.innerHTML = '<option value="">Seleccione un proyecto</option>';
        
        // Verificar si hay proyectos
        if (!window.proyectos || !Array.isArray(window.proyectos)) {
            console.error('No se encontraron proyectos o el formato es incorrecto', window.proyectos);
            return;
        }
        
        console.log('ID Taller actual:', window.id_taller);
        console.log('Proyectos disponibles:', window.proyectos);
        console.log('Tipo de datos de proyectos:', typeof window.proyectos);
        
        // Añadir opciones
        window.proyectos.forEach(proyecto => {
            console.log('Procesando proyecto:', proyecto);
            
            const option = document.createElement('option');
            option.value = proyecto.id_proyecto;
            
            let textoOpcion = proyecto.nombre_proyecto || 'Proyecto sin nombre';
            if (proyecto.nombre_cliente) {
                textoOpcion += ' - ' + proyecto.nombre_cliente;
            }
            
            option.textContent = textoOpcion;
            selectProyectos.appendChild(option);
        });
        
        console.log('Proyectos cargados en el select');
    },

    /**
     * Guarda una transacción
     */
    guardarTransaccion: function() {
        console.log('Ejecutando guardarTransaccion()');
        
        // Validar formulario
        const form = document.getElementById('formNuevaTransaccion');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Crear FormData
        const formData = new FormData(form);
        
        // Verificar valores críticos
        const tipo = formData.get('tipo');
        const idCategoria = formData.get('id_categoria');
        const idProyecto = formData.get('id_proyecto');
        
        console.log('Datos del formulario:');
        console.log('- Tipo:', tipo);
        console.log('- Categoría:', idCategoria);
        console.log('- Proyecto:', idProyecto);
        console.log('- Concepto:', formData.get('concepto'));
        console.log('- Monto:', formData.get('monto'));
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Guardando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Enviar datos
        fetch(BASE_URL + '/index.php?controller=finanzas&action=registrarTransaccion', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data);
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Transacción registrada',
                    text: 'La transacción se ha registrado correctamente.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Recargar página
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Ha ocurrido un error al registrar la transacción.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al procesar la solicitud.'
            });
        });
    },

    /**
     * Muestra el detalle de una transacción
     */
    verDetalle: function(idTransaccion) {
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Cargando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Obtener detalle
        fetch(BASE_URL + '/index.php?controller=finanzas&action=detalleTransaccion&id=' + idTransaccion)
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                // Mostrar modal con detalle
                const transaccion = data.data;
                const detalleBody = document.getElementById('detalleTransaccionBody');
                
                if (detalleBody) {
                    // Construir HTML del detalle
                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Concepto:</strong> ${transaccion.concepto}</p>
                                <p><strong>Monto:</strong> ${FinanzasApp.formatoMoneda(transaccion.monto)}</p>
                                <p><strong>Fecha:</strong> ${transaccion.fecha_transaccion}</p>
                                <p><strong>Tipo:</strong> ${transaccion.tipo === 'ingreso' ? 'Ingreso' : 'Gasto'}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Método de pago:</strong> ${transaccion.metodo_pago}</p>
                                <p><strong>Categoría:</strong> ${transaccion.nombre_categoria || 'Sin categoría'}</p>
                                <p><strong>Proyecto:</strong> ${transaccion.nombre_proyecto || 'Sin proyecto'}</p>
                                <p><strong>Referencia:</strong> ${transaccion.referencia || 'N/A'}</p>
                            </div>
                        </div>
                    `;
                    
                    if (transaccion.notas) {
                        html += `<div class="row mt-3">
                            <div class="col-12">
                                <p><strong>Notas:</strong></p>
                                <p>${transaccion.notas}</p>
                            </div>
                        </div>`;
                    }
                    
                    if (transaccion.ruta_comprobante) {
                        html += `<div class="row mt-3">
                            <div class="col-12">
                                <p><strong>Comprobante:</strong></p>
                                <p><a href="${BASE_URL}/${transaccion.ruta_comprobante}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-download mr-1"></i> Ver comprobante
                                </a></p>
                            </div>
                        </div>`;
                    }
                    
                    detalleBody.innerHTML = html;
                    $('#modalDetalleTransaccion').modal('show');
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Ha ocurrido un error al obtener el detalle de la transacción.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al comunicarse con el servidor.'
            });
        });
    },

    /**
     * Confirma la anulación de una transacción
     */
    confirmarAnulacion: function(idTransaccion) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.anularTransaccion(idTransaccion);
            }
        });
    },

    /**
     * Anula una transacción
     */
    anularTransaccion: function(idTransaccion) {
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Anulando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Crear FormData
        const formData = new FormData();
        formData.append('id_transaccion', idTransaccion);
        
        // Enviar datos
        fetch(BASE_URL + '/index.php?controller=finanzas&action=anularTransaccion', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Transacción anulada',
                    text: 'La transacción ha sido anulada correctamente.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Recargar página
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Ha ocurrido un error al anular la transacción.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al comunicarse con el servidor.'
            });
        });
    }
};

/**
 * Módulo para gestionar gráficos
 */
const GraficoFinanzas = {
    // Instancias de gráficos
    charts: {},

    /**
     * Inicializa los gráficos
     */
    init: function() {
        this.initGraficoFlujo();
        this.initGraficoCategorias();
    },

    /**
     * Inicializa el gráfico de flujo de efectivo
     */
    initGraficoFlujo: function() {
        const ctxFlujo = document.getElementById('graficoFlujo');
        if (!ctxFlujo) return;
        
        this.charts.flujo = new Chart(ctxFlujo.getContext('2d'), {
            type: 'line',
            data: {
                labels: window.datosGrafico ? window.datosGrafico.labels : [],
                datasets: [
                    {
                        label: 'Ingresos',
                        data: window.datosGrafico ? window.datosGrafico.ingresos : [],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Gastos',
                        data: window.datosGrafico ? window.datosGrafico.gastos : [],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    },

    /**
     * Inicializa el gráfico de categorías
     */
    initGraficoCategorias: function() {
        const ctxCategorias = document.getElementById('graficoCategorias');
        if (!ctxCategorias) return;
        
        // Preparar datos para el gráfico
        const datos = this.prepararDatosGraficoCategorias();
        
        this.charts.categorias = new Chart(ctxCategorias.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: datos.labels,
                datasets: [{
                    data: datos.valores,
                    backgroundColor: datos.colores,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    },

    /**
     * Prepara los datos para el gráfico de categorías
     */
    prepararDatosGraficoCategorias: function() {
        const datos = {
            labels: [],
            valores: [],
            colores: []
        };
        
        // Verificar si existen datos de categorías
        if (!window.categorias || !window.datosCategoriasGastos) {
            return datos;
        }
        
        // Filtrar categorías de gastos
        const categoriasGastos = window.categorias.filter(c => c.tipo === 'gasto');
        
        // Procesar datos
        categoriasGastos.forEach((categoria, index) => {
            const valor = window.datosCategoriasGastos[categoria.id_categoria] || 0;
            if (valor > 0) {
                datos.labels.push(categoria.nombre);
                datos.valores.push(valor);
                datos.colores.push(categoria.color || this.getRandomColor());
            }
        });
        
        return datos;
    },

    /**
     * Genera un color aleatorio
     */
    getRandomColor: function() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
};

/**
 * Módulo para gestionar filtros
 */
const FiltrosFinanzas = {
    /**
     * Inicializa el módulo
     */
    init: function() {
        this.initEventListeners();
        this.cargarProyectosFiltro();
    },

    /**
     * Inicializa los event listeners
     */
    initEventListeners: function() {
        const btnFiltrar = document.getElementById('btnFiltrar');
        if (btnFiltrar) {
            btnFiltrar.addEventListener('click', this.aplicarFiltros.bind(this));
        }

        const btnReiniciar = document.getElementById('btnReiniciar');
        if (btnReiniciar) {
            btnReiniciar.addEventListener('click', this.reiniciarFiltros.bind(this));
        }
    },
    
    /**
     * Carga los proyectos en el filtro
     */
    cargarProyectosFiltro: function() {
        console.log('Ejecutando cargarProyectosFiltro()');
        const selectProyectos = document.getElementById('filtro_proyecto');
        if (!selectProyectos) {
            console.error('No se encontró el elemento select con id="filtro_proyecto"');
            return;
        }
        
        // Obtener el proyecto seleccionado actualmente (si existe)
        const urlParams = new URLSearchParams(window.location.search);
        const proyectoSeleccionado = urlParams.get('id_proyecto');
        
        // Limpiar select manteniendo la primera opción
        const primeraOpcion = selectProyectos.options[0];
        selectProyectos.innerHTML = '';
        selectProyectos.appendChild(primeraOpcion);
        
        // Verificar si hay proyectos
        if (!window.proyectos || !Array.isArray(window.proyectos)) {
            console.error('No se encontraron proyectos o el formato es incorrecto', window.proyectos);
            return;
        }
        
        console.log('ID Taller actual:', window.id_taller);
        console.log('Proyectos disponibles para filtro:', window.proyectos);
        console.log('Tipo de datos de proyectos en filtro:', typeof window.proyectos);
        
        // Añadir opciones
        window.proyectos.forEach(proyecto => {
            console.log('Procesando proyecto para filtro:', proyecto);
            
            const option = document.createElement('option');
            option.value = proyecto.id_proyecto;
            
            let textoOpcion = proyecto.nombre_proyecto || 'Proyecto sin nombre';
            if (proyecto.nombre_cliente) {
                textoOpcion += ' - ' + proyecto.nombre_cliente;
            }
            
            option.textContent = textoOpcion;
            
            // Seleccionar el proyecto si coincide con el parámetro de la URL
            if (proyectoSeleccionado && proyecto.id_proyecto == proyectoSeleccionado) {
                option.selected = true;
            }
            
            selectProyectos.appendChild(option);
        });
        
        console.log('Proyectos cargados en el filtro');
    },

    /**
     * Aplica los filtros seleccionados
     */
    aplicarFiltros: function(e) {
        if (e) e.preventDefault();
        
        // Obtener valores de los filtros
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        const idProyecto = document.getElementById('filtro_proyecto').value;
        const tipo = document.getElementById('filtro_tipo').value;
        
        // Construir URL con parámetros
        let url = BASE_URL + '/index.php?controller=finanzas&action=index';
        
        if (fechaInicio) url += '&fecha_inicio=' + fechaInicio;
        if (fechaFin) url += '&fecha_fin=' + fechaFin;
        if (idProyecto) url += '&id_proyecto=' + idProyecto;
        if (tipo) url += '&tipo=' + tipo;
        
        // Redirigir
        window.location.href = url;
    },
    
    /**
     * Reinicia los filtros
     */
    reiniciarFiltros: function(e) {
        if (e) e.preventDefault();
        window.location.href = BASE_URL + '/index.php?controller=finanzas&action=index';
    },

    /**
     * Genera un reporte
     */
    generarReporte: function() {
        // Obtener parámetros de filtro
        const fechaInicio = document.getElementById('fecha_inicio')?.value || '';
        const fechaFin = document.getElementById('fecha_fin')?.value || '';
        const idProyecto = document.getElementById('filtro_proyecto')?.value || '';
        const tipo = document.getElementById('filtro_tipo')?.value || '';
        
        // Construir URL
        let url = BASE_URL + '/index.php?controller=finanzas&action=generarReporte';
        
        if (fechaInicio) url += '&fecha_inicio=' + fechaInicio;
        if (fechaFin) url += '&fecha_fin=' + fechaFin;
        if (idProyecto) url += '&id_proyecto=' + idProyecto;
        if (tipo) url += '&tipo=' + tipo;
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Generando reporte',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                
                // Abrir en nueva pestaña
                window.open(url, '_blank');
                
                // Cerrar indicador después de un tiempo
                setTimeout(() => {
                    Swal.close();
                }, 1500);
            }
        });
    }
};

/**
 * Módulo para gestionar reportes
 */
const ReportesFinanzas = {
    /**
     * Genera un reporte
     */
    generarReporte: function() {
        // Obtener parámetros de filtro
        const fechaInicio = document.getElementById('fecha_inicio')?.value || '';
        const fechaFin = document.getElementById('fecha_fin')?.value || '';
        const idProyecto = document.getElementById('filtro_proyecto')?.value || '';
        const tipo = document.getElementById('filtro_tipo')?.value || '';
        
        // Construir URL
        let url = BASE_URL + '/index.php?controller=finanzas&action=generarReporte';
        
        if (fechaInicio) url += '&fecha_inicio=' + fechaInicio;
        if (fechaFin) url += '&fecha_fin=' + fechaFin;
        if (idProyecto) url += '&id_proyecto=' + idProyecto;
        if (tipo) url += '&tipo=' + tipo;
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Generando reporte',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                
                // Abrir en nueva pestaña
                window.open(url, '_blank');
                
                // Cerrar indicador después de un tiempo
                setTimeout(() => {
                    Swal.close();
                }, 1500);
            }
        });
    }
}; 