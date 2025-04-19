// Configuración global de Toastr
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Función para mostrar mensajes de error
function showError(message) {
    toastr.error(message);
}

// Función para mostrar mensajes de éxito
function showSuccess(message) {
    toastr.success(message);
}

// Función para mostrar mensajes de información
function showInfo(message) {
    toastr.info(message);
}

// Función para mostrar advertencias
function showWarning(message) {
    toastr.warning(message);
}

// Inicialización de componentes AdminLTE
$(document).ready(function() {
    // Activar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Activar popovers
    $('[data-toggle="popover"]').popover();

    // Manejar mensajes de error en el dashboard
    if (typeof dashboardErrors !== 'undefined' && dashboardErrors.length > 0) {
        dashboardErrors.forEach(function(error) {
            showError(error);
        });
    }

    // Manejar mensajes de éxito en el dashboard
    if (typeof dashboardMessages !== 'undefined' && dashboardMessages.length > 0) {
        dashboardMessages.forEach(function(message) {
            showSuccess(message);
        });
    }
});
