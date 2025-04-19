/**
 * JavaScript para el Dashboard de HerrerosPro
 */
$(document).ready(function() {
    // Inicializar DataTables para ambas tablas
    $("#tablaSolicitudes, #tablaActividad").DataTable({
        "pageLength": 5,
        "lengthChange": false,
        "searching": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        }
    });
    
    // Cargar contadores
    cargarContadores();
    
    // Cargar solicitudes
    cargarSolicitudes();
});

// Función para cargar contadores
function cargarContadores() {
    $.ajax({
        url: BASE_URL + "controllers/dashboard_controller.php?action=contadores",
        method: "GET",
        success: function(response) {
            if (response.success) {
                $("#solicitudesPendientes").text(response.solicitudes_pendientes);
                $("#talleresActivos").text(response.talleres_activos);
            }
        }
    });
}

// Función para cargar solicitudes
function cargarSolicitudes() {
    $.ajax({
        url: BASE_URL + "controllers/dashboard_controller.php?action=solicitudes_recientes",
        method: "GET",
        success: function(response) {
            var tabla = $("#tablaSolicitudes").DataTable();
            tabla.clear();
            
            if (response.solicitudes && response.solicitudes.length > 0) {
                $.each(response.solicitudes, function(i, item) {
                    var estado = "";
                    if (item.estado === "pendiente") {
                        estado = '<span class="badge badge-warning">Pendiente</span>';
                    } else if (item.estado === "aprobada") {
                        estado = '<span class="badge badge-success">Aprobada</span>';
                    } else if (item.estado === "rechazada") {
                        estado = '<span class="badge badge-danger">Rechazada</span>';
                    }
                    
                    tabla.row.add([
                        item.id,
                        item.nombre_taller,
                        estado,
                        item.fecha
                    ]);
                });
            } else {
                tabla.row.add(["", "No hay solicitudes recientes", "", ""]);
            }
            
            tabla.draw();
        },
        error: function() {
            var tabla = $("#tablaSolicitudes").DataTable();
            tabla.clear();
            tabla.row.add(["", "Error al cargar solicitudes", "", ""]);
            tabla.draw();
        }
    });
}
