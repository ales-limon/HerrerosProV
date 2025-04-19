<?php
/**
 * Contenido de la página de registro
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

/* Estilos específicos de la página de registro */
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

/* Estilos para los iconos */
.feature-icon {
    width: 60px;
    height: 60px;
    background-color: rgba(23, 162, 184, 0.1);
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

/* Estilos para los botones en hover */
.hero a.btn:hover, .cta a.btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
}

.hero a.btn[style*="background-color: white"]:hover {
    background-color: #f8f9fa !important;
}

/* Estilos para el selector de plan */
.plan-selector {
    border-left: 4px solid var(--primary-color);
    padding: 10px 15px;
    background-color: rgba(23, 162, 184, 0.05);
    margin-bottom: 20px;
}

.plan-selector .form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
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

/* Estilos para el marco elegante del formulario */
.elegant-card {
    position: relative;
    border: 2px solid var(--primary-color);
    border-radius: 15px;
    padding: 2.5rem;
    background-color: white;
    transition: all 0.3s ease;
}

.elegant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.elegant-label {
    position: absolute;
    top: -15px;
    right: 20px;
    background-color: var(--primary-color);
    color: white;
    padding: 5px 20px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInLeft">
                    Solicita tu cuenta en HerrerosPro
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                    Envía tu solicitud para unirte a nuestra comunidad y comenzar a gestionar tu taller de manera profesional.
                </p>
                <div class="animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="#registro-form" class="btn btn-lg me-3" style="background-color: white; color: var(--primary-color); border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                        <i class="fas fa-paper-plane me-2"></i>Solicitar Cuenta
                    </a>
                    <a href="<?php echo PUBLIC_URL; ?>public/views/login.php" class="btn btn-lg" style="background-color: transparent; color: white; border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block animate__animated animate__fadeInRight">
                <img src="<?php echo ASSETS_URL; ?>img/logo.png" alt="HerrerosPro" class="img-fluid rounded shadow" style="max-width: 55%; margin: 0 auto; display: block; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important; border: 3px solid rgba(255, 255, 255, 0.3); padding: 10px; background-color: rgba(255, 255, 255, 0.1);">
            </div>
        </div>
    </div>
</section>

<!-- Formulario de Registro -->
<section id="registro-form" class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="elegant-card">
                    <div class="elegant-label">Solicita tu cuenta</div>
                    <h2 class="text-center mb-4">Solicitud de Cuenta</h2>
                    
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
                    
                    <form id="registroForm" method="post" action="<?php echo BASE_URL; ?>public/controllers/registro_controller.php" class="needs-validation" novalidate>
                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Completa este formulario para solicitar una cuenta en HerrerosPro. Tu solicitud será revisada por nuestro equipo y te contactaremos a la brevedad.
                        </div>
                        
                        <h5 class="mb-3 mt-4">Información del Propietario</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]{2,50}" 
                                           title="Solo se permiten letras y espacios (2-50 caracteres)"
                                           value="<?php echo htmlspecialchars($formData['nombre'] ?? ''); ?>"
                                           maxlength="50">
                                    <div class="invalid-feedback">
                                        Por favor ingresa un nombre válido (solo letras y espacios).
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                           required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]{2,50}" 
                                           title="Solo se permiten letras y espacios (2-50 caracteres)"
                                           value="<?php echo htmlspecialchars($formData['apellidos'] ?? ''); ?>"
                                           maxlength="50">
                                    <div class="invalid-feedback">
                                        Por favor ingresa apellidos válidos (solo letras y espacios).
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3 mt-4">Información del Taller</h5>
                        
                        <div class="mb-4">
                            <label for="nombre_taller" class="form-label">Nombre del Taller <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-store"></i></span>
                                <input type="text" class="form-control" id="nombre_taller" name="nombre_taller" 
                                       required pattern="[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s\.\-\&]{2,100}" 
                                       title="Ingrese un nombre válido (2-100 caracteres)"
                                       value="<?php echo htmlspecialchars($formData['nombre_taller'] ?? ''); ?>"
                                       maxlength="100">
                                <div class="invalid-feedback">
                                    Por favor ingresa un nombre de taller válido.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="rfc" class="form-label">RFC <small class="text-muted">(opcional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control" id="rfc" name="rfc" 
                                       pattern="^([A-ZÑ&]{3,4})?([0-9]{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12][0-9]|3[01]))?([A-Z\d]{3})?$" 
                                       title="Ingrese un RFC válido (13 caracteres para personas físicas, 12 para morales)"
                                       value="<?php echo htmlspecialchars($formData['rfc'] ?? ''); ?>"
                                       maxlength="13">
                                <div class="invalid-feedback">
                                    El RFC no tiene un formato válido.
                                </div>
                                <div class="form-text">
                                    Si aún no cuentas con RFC, puedes dejarlo en blanco.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="direccion" class="form-label">Dirección del Taller <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" id="direccion" name="direccion" 
                                       required pattern=".{5,200}" 
                                       title="Ingrese una dirección válida (5-200 caracteres)"
                                       value="<?php echo htmlspecialchars($formData['direccion'] ?? ''); ?>"
                                       maxlength="200">
                                <div class="invalid-feedback">
                                    Por favor ingresa la dirección de tu taller.
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3 mt-4">Información de Contacto</h5>
                        
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
                            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       required pattern="[0-9]{10}" 
                                       title="Ingrese un número de teléfono de 10 dígitos"
                                       value="<?php echo htmlspecialchars($formData['telefono'] ?? ''); ?>"
                                       maxlength="10">
                                <div class="invalid-feedback">
                                    Por favor ingresa un número de teléfono válido (10 dígitos).
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3 mt-4">Plan Seleccionado</h5>
                        
                        <div class="plan-selector p-3 mb-4">
                            <?php
                            // Obtener el plan de la URL si existe
                            $plan_seleccionado = isset($_GET['plan']) ? htmlspecialchars($_GET['plan']) : ($formData['plan'] ?? '');
                            ?>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="plan" id="plan_basico" value="basico" <?php echo ($plan_seleccionado == 'basico') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="plan_basico">
                                    <strong>Plan Básico</strong> - $499/mes
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="plan" id="plan_profesional" value="profesional" <?php echo ($plan_seleccionado == 'profesional') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="plan_profesional">
                                    <strong>Plan Profesional</strong> - $999/mes
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="plan" id="plan_enterprise" value="enterprise" <?php echo ($plan_seleccionado == 'enterprise') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="plan_enterprise">
                                    <strong>Plan Enterprise</strong> - $1,999/mes
                                </label>
                            </div>
                            
                            <div class="invalid-feedback">
                                Por favor selecciona un plan.
                            </div>
                            
                            <div class="mt-2">
                                <a href="<?php echo PUBLIC_URL; ?>public/views/planes.php" class="text-decoration-none">
                                    <i class="fas fa-info-circle me-1"></i>Ver detalles de los planes
                                </a>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="terminos" name="terminos" required>
                            <label class="form-check-label" for="terminos">
                                Acepto los <a href="#" class="text-decoration-none">Términos y Condiciones</a> y la <a href="#" class="text-decoration-none">Política de Privacidad</a>
                            </label>
                            <div class="invalid-feedback">
                                Debes aceptar los términos y condiciones para continuar.
                            </div>
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
                                <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">¿Ya tienes una cuenta? <a href="<?php echo PUBLIC_URL; ?>public/views/login.php" class="text-decoration-none" style="color: var(--primary-color); font-weight: bold;">Inicia Sesión</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registroForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Función para sanitizar inputs
    function sanitizarInput(input) {
        // Eliminar etiquetas HTML y caracteres especiales
        return input.value.replace(/<[^>]*>/g, '').trim();
    }
    
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
    
    // Validación específica para RFC
    const rfcInput = document.getElementById('rfc');
    if (rfcInput) {
        rfcInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
});
</script> 