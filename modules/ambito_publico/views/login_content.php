<?php
/**
 * Contenido de la página de login
 * Este archivo SOLO contiene el contenido específico de la página
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

// Recuperar datos del formulario en caso de error (para no perder la información)
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']); // Limpiar después de usar
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
    box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
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

/* Estilos para los botones en hover */
.hero a.btn:hover, .cta a.btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
}

.hero a.btn[style*="background-color: white"]:hover {
    background-color: #f8f9fa !important;
}

/* Estilos para los campos con validación */
.form-control.is-valid {
    border-color: #28a745;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInLeft">
                    Iniciar Sesión
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                    Accede a tu cuenta de HerrerosPro y gestiona tu taller de manera eficiente.
                </p>
                <div class="animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="#login-form" class="btn btn-lg me-3" style="background-color: white; color: var(--primary-color); font-weight: bold; padding: 12px 24px;">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                    <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="btn btn-lg" style="background-color: transparent; color: white; border: 2px solid white; font-weight: bold; padding: 12px 24px;">
                        <i class="fas fa-user-plus me-2"></i>Solicitar Cuenta
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block animate__animated animate__fadeInRight">
                <img src="<?php echo ASSETS_URL; ?>img/logo.png" alt="HerrerosPro" class="img-fluid rounded shadow" style="max-width: 55%; margin: 0 auto; display: block; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important; border: 3px solid rgba(255, 255, 255, 0.3); padding: 10px; background-color: rgba(255, 255, 255, 0.1);">
            </div>
        </div>
    </div>
</section>

<!-- Formulario de Login -->
<section id="login-form" class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Accede a tu cuenta</h2>
                        
                        <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <form id="loginForm" method="post" action="<?php echo PUBLIC_URL; ?>controllers/login_controller.php" class="needs-validation" novalidate>
                            <!-- Token CSRF -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                                           title="Ingrese un correo electrónico válido"
                                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                                           maxlength="100">
                                    <div class="invalid-feedback">
                                        Por favor ingresa un correo electrónico válido.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           required pattern=".{8,}" 
                                           title="La contraseña debe tener al menos 8 caracteres"
                                           maxlength="100">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu contraseña (mínimo 8 caracteres).
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                            
                            <!-- Campo Honeypot para detectar bots -->
                            <div class="d-none">
                                <label for="website">Website</label>
                                <input type="text" name="website" id="website" autocomplete="off">
                            </div>
                            
                            <!-- Campo de tiempo para prevenir envíos automatizados -->
                            <input type="hidden" name="form_time" value="<?php echo time(); ?>">
                            
                            <!-- Botón de Envío -->
                            <div class="d-grid gap-2">
                                <button type="submit" id="submitBtn" class="btn btn-lg" style="background-color: var(--primary-color); color: white; border: 3px solid var(--primary-color); font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </button>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="mb-0">¿No tienes una cuenta? <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="text-primary">Regístrate aquí</a></p>
                                <p class="mt-2 text-muted">¿Eres administrador? <a href="<?php echo PLATAFORMA_URL; ?>login.php" class="text-primary">Accede a la plataforma</a></p>
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
    
    // Función para sanitizar inputs
    function sanitizarInput(input) {
        // Eliminar etiquetas HTML y caracteres especiales
        return input.value.replace(/<[^>]*>/g, '').trim();
    }
    
    // Mostrar/ocultar contraseña
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Validación del formulario
    if (form) {
        // Validación en tiempo real
        const inputs = form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
        
        form.addEventListener('submit', function(event) {
            // Sanitizar todos los inputs antes de enviar
            const allInputs = form.querySelectorAll('input:not([type=hidden]):not([type=checkbox])');
            allInputs.forEach(input => {
                input.value = sanitizarInput(input);
            });
            
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Mostrar mensaje de error
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
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
