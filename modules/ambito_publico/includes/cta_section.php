<?php
/**
 * Sección Reutilizable: Llamada a la Acción (CTA)
 *
 * Muestra un banner motivando al usuario a registrarse.
 */
?>
<section class="py-5 cta" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8 text-white">
                <h2 class="mb-4">¿Listo para transformar tu taller?</h2>
                <p class="lead mb-4">Elige el plan que mejor se adapte a tus necesidades y comienza a optimizar la gestión de tu taller de herrería hoy mismo.</p>
                <a href="<?php echo PUBLIC_URL; ?>?route=registro" class="btn btn-lg" style="background-color: white; color: var(--primary-color); border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                    <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                </a>
            </div>
        </div>
    </div>
</section>
