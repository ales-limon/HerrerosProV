<?php
/**
 * Footer común para la parte pública de HerrerosPro
 * Contiene el pie de página y los scripts necesarios
 */
?>
<footer class="footer bg-primary text-white py-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);">
    <div class="container">
        <div class="row">
            <!-- Logo y descripción -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="<?php echo ASSETS_URL; ?>img/logo.png" alt="HerrerosPro Logo" class="img-fluid mb-3" style="max-width: 180px;">
                <p class="text-light mb-3">HerrerosPro es el software integral que necesitas para administrar eficientemente tu taller de herrería. Optimiza tus procesos y haz crecer tu negocio.</p>
            </div>
            
            <!-- Enlaces rápidos -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-4">Enlaces</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo PUBLIC_URL; ?>" class="text-light">Inicio</a></li>
                    <li class="mb-2"><a href="<?php echo PUBLIC_URL; ?>public/views/planes.php" class="text-light">Planes y Precios</a></li>
                    <li class="mb-2"><a href="<?php echo PUBLIC_URL; ?>public/views/demo.php" class="text-light">Demo</a></li>
                    <li class="mb-2"><a href="<?php echo PUBLIC_URL; ?>public/views/contacto.php" class="text-light">Contacto</a></li>
                </ul>
            </div>
            
            <!-- Contacto -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-4">Contacto</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@herrerospro.com</li>
                    <li class="mb-2"><i class="fas fa-clock me-2"></i> Lun - Vie: 9:00 - 18:00</li>
                </ul>
            </div>
            
            <!-- Redes sociales y newsletter -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase mb-4">Síguenos</h5>
                <div class="d-flex mb-4">
                    <a href="#" class="social-icon me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="social-icon me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="social-icon me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="social-icon me-3"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
                
                <h5 class="text-uppercase mb-3">Newsletter</h5>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Tu email" aria-label="Tu email">
                    <button class="btn btn-light" type="button">Suscribirse</button>
                </div>
            </div>
        </div>
        
        <!-- Línea divisoria -->
        <hr class="my-4 bg-light opacity-25">
        
        <!-- Copyright y términos -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-md-0">&copy; <?php echo date('Y'); ?> HerrerosPro. Todos los derechos reservados.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="#" class="text-light">Términos y Condiciones</a></li>
                    <li class="list-inline-item">·</li>
                    <li class="list-inline-item"><a href="#" class="text-light">Política de Privacidad</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 para alertas y notificaciones -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script src="<?php echo PUBLIC_URL; ?>assets/js/main.js"></script>

</body>
</html>