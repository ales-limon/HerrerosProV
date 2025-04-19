<?php
/**
 * Footer de la plataforma administrativa
 * Contiene la información del copyright y scripts adicionales
 */
?>
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Versión</b> 1.0.0
    </div>
    <strong>Copyright &copy; <?= date('Y') ?> <a href="https://herrerospro.com">HerrerosPro</a>.</strong> 
    Todos los derechos reservados.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

<!-- Scripts adicionales específicos de la página -->
<?php if (isset($extraScripts)) echo $extraScripts; ?>

<script>
    // Mostrar mensajes de error/éxito usando SweetAlert si están definidos
    document.addEventListener('DOMContentLoaded', function() {
        // Ocultar overlay de carga
        document.querySelector('.loading-overlay').style.display = 'none';
        
        // Activar tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Activar popovers
        $('[data-toggle="popover"]').popover();
    });
</script>
</body>
</html>
