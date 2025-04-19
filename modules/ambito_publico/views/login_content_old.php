<?php
/**
 * Contenido de la página de login
 */

// Definir variables de estilo
$primary_color = '#17a2b8';
$primary_dark = '#343a40';

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Agregar estilos CSS personalizados
?>
<style>
:root {
    --primary-color: <?php echo $primary_color; ?>;
    --primary-dark: <?php echo $primary_dark; ?>;
}

/* Estilos específicos de la página de login */
.hero {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
}

.text-primary {
    color: var(--primary-color) !important;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(0, 102, 204, 0.25);
}

/* Estilos para los botones */
.btn-lg {
    padding: 12px 24px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
}

/* Estilos para los iconos */
.feature-icon {
    width: 60px;
    height: 60px;
    background-color: rgba(0, 102, 204, 0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--primary-color);
    border-radius: 50%;
    margin-bottom: 1rem;
}

/* Estilos para el botón de mostrar/ocultar contraseña */
.btn-outline-secondary {
    border-color: #ced4da;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Estilos para los enlaces */
a {
    color: var(--primary-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}
</style>

<!-- Hero Section -->
<section class="hero py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-4">Iniciar Sesión</h1>
                <p class="lead mb-4">Accede a tu cuenta de HerrerosPro y gestiona tu taller de manera eficiente.</p>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo ASSETS_URL; ?>img/login-hero.svg" alt="Login HerrerosPro" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Formulario de Login -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Accede a tu cuenta</h2>
                        
                        <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <form id="loginForm" method="post" action="<?php echo BASE_URL; ?>public/controllers/login_controller.php" class="needs-validation" novalidate>
                            <!-- Token CSRF -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu correo electrónico.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu contraseña.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                            
                            <!-- Campo Honeypot -->
                            <div class="d-none">
                                <label for="website">Website</label>
                                <input type="text" name="website" id="website">
                            </div>
                            
                            <!-- Botón de Envío -->
                            <div class="d-grid gap-2">
                                <button type="submit" id="submitBtn" class="btn btn-lg" style="background-color: var(--primary-color); color: white; border: 3px solid var(--primary-color); font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p><a href="#" class="text-decoration-none" style="color: var(--primary-color);">¿Olvidaste tu contraseña?</a></p>
                                <p class="mb-0">¿No tienes una cuenta? <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="text-decoration-none" style="color: var(--primary-color); font-weight: bold;">Regístrate</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const submitBtn = document.getElementById('submitBtn');
    
    // Mostrar/ocultar contraseña
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Validación del formulario
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Deshabilitar botón para prevenir múltiples envíos
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Procesando...';
            }
            
            this.classList.add('was-validated');
        });
    }
    
    // Efecto hover para botones
    const buttons = document.querySelectorAll('.btn-lg');
    buttons.forEach(button => {
        button.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.4)';
        });
        
        button.addEventListener('mouseout', function() {
            this.style.transform = '';
            this.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.3)';
        });
    });
});
</script> 