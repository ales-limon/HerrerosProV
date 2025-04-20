<?php
/**
 * Contenido de la página de registro
 */

// Definir variables de estilo
$primary_color = '#17a2b8';
$primary_dark = '#343a40';

// Iniciar sesión si no está iniciada (el layout ya debería hacerlo, pero por seguridad)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Recuperar errores y datos del formulario en caso de fallo de validación
$registroErrores = $_SESSION['registro_errores'] ?? [];
$formData = $_SESSION['registro_data'] ?? [];
// Limpiar después de usar para no mostrarlos de nuevo en recargas
unset($_SESSION['registro_errores'], $_SESSION['registro_data']);

// La variable $planSeleccionado viene del RegistroController
// No necesitamos leerla de $_GET aquí, ya está disponible.

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
                    <h2 class="text-center mb-4">Formulario de Registro</h2>
                    
                    <?php 
                    // Asegurarse de que la sesión esté iniciada para leer mensajes
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    ?>

                    <!-- Mostrar errores de validación directa (si los hay) -->
                    <?php if (!empty($registroErrores)): /* $registroErrores viene directo del controller, no de sesión */ ?>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Error en el Registro</h4>
                            <p>Por favor, corrige los siguientes errores:</p>
                            <ul>
                                <?php foreach ($registroErrores as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mostrar errores generales (después de redirección) -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger" role="alert">
                             <i class="fas fa-exclamation-triangle me-2"></i>
                             <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php 
                        // Obtener mensaje flash de éxito
                        $flashSuccessMessage = getFlash('success'); 
                    ?>

                    <!-- Mostrar mensaje de éxito (después de redirección) -->
                    <?php if ($flashSuccessMessage): // Mostrar si hay un mensaje flash ?>
                        <div id="success-message-alert" class="alert alert-success" role="alert">
                             <i class="fas fa-check-circle me-2"></i> 
                             <?php echo htmlspecialchars($flashSuccessMessage); // Mostrar el mensaje flash ?>
                         </div>
                         <!-- Barra de progreso para redirección -->
                         <div class="progress" style="height: 20px; margin-top: 15px; margin-bottom: 20px;">
                            <div id="redirect-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Redirigiendo...</div>
                        </div>
                          <div class="text-center mt-4 mb-4">
                              <!-- Ya no necesitamos este botón aquí si vamos a redirigir automáticamente -->
                          </div>
                    <?php else: // Solo mostrar formulario si no hay mensaje de éxito ?>
                    
                    <?php
                    // Recuperar datos y errores del formulario si existen en la sesión (tras redirección por error)
                    $formData = $_SESSION['form_data'] ?? [];
                    $formErrors = $_SESSION['registroErrores'] ?? []; // Usamos la clave correcta que puso el controller
                    unset($_SESSION['form_data'], $_SESSION['registroErrores']); // Limpiar después de usar
                    ?>

                    <!-- Mostrar resumen de errores de validación específicos del formulario -->
                    <?php if (!empty($formErrors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Error en el Formulario</h5>
                            <p>Por favor, corrige los siguientes errores:</p>
                            <ul class="mb-0">
                                <?php foreach ($formErrors as $field => $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form id="registroForm" method="post" action="<?php echo PUBLIC_URL; ?>?route=do_registro" class="needs-validation" novalidate>
                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        
                        <?php if (isset($plan_info) && $plan_info): ?>
                            <!-- Caso 1: Plan preseleccionado -->
                            <div class="alert alert-info" role="alert">
                                <h5 class="alert-heading">Plan Seleccionado: <?php echo htmlspecialchars(ucfirst($plan_info['nombre'])); ?></h5>
                                <p><?php echo htmlspecialchars($plan_info['descripcion']); ?></p>
                                <hr>
                                <p class="mb-0">Precio: <strong>$<?php echo htmlspecialchars(number_format($plan_info['precio'], 2)); ?> / mes</strong></p>
                            </div>
                            <input type="hidden" name="plan" value="<?php echo htmlspecialchars($plan_info['id']); ?>">
                        <?php elseif (isset($planes_disponibles) && !empty($planes_disponibles)): ?>
                            <!-- Caso 2: No hay plan preseleccionado, mostrar selector -->
                            <div class="mb-3">
                                <label for="plan" class="form-label">Selecciona un Plan <span class="text-danger">*</span></label>
                                <select class="form-select <?php echo isset($formErrors['plan']) ? 'is-invalid' : ''; ?>" id="plan" name="plan" required>
                                    <option value="" selected disabled>Elige tu plan...</option>
                                    <?php foreach ($planes_disponibles as $p): ?>
                                        <option value="<?php echo htmlspecialchars($p['id']); ?>" <?php echo (isset($formData['plan']) && $formData['plan'] == $p['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars(ucfirst($p['nombre'])); ?> ($<?php echo htmlspecialchars(number_format($p['precio'], 2)); ?>/mes)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($formErrors['plan'])): ?>
                                    <div class="invalid-feedback">
                                        Por favor, selecciona un plan de suscripción.
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                             <!-- Caso 3: Error - No hay planes disponibles -->
                             <div class="alert alert-warning" role="alert">
                                 No hay planes disponibles para seleccionar en este momento.
                             </div>
                             <!-- Podrías deshabilitar el botón de envío o mostrar un mensaje más prominente -->
                        <?php endif; ?>

                        <!-- Campos del formulario -->
                        <h4 class="mb-3">Datos del Propietario</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control <?php echo isset($formErrors['nombre']) ? 'is-invalid' : ''; ?>" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($formData['nombre'] ?? ''); ?>" required>
                                    <?php if (isset($formErrors['nombre'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($formErrors['nombre']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control <?php echo isset($formErrors['apellidos']) ? 'is-invalid' : ''; ?>" id="apellidos" name="apellidos" 
                                           value="<?php echo htmlspecialchars($formData['apellidos'] ?? ''); ?>" required>
                                    <?php if (isset($formErrors['apellidos'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($formErrors['apellidos']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control <?php echo isset($formErrors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                                <?php if (isset($formErrors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo htmlspecialchars($formErrors['email']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_taller" class="form-label">Nombre del Taller <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-store"></i></span>
                                <input type="text" class="form-control <?php echo isset($formErrors['nombre_taller']) ? 'is-invalid' : ''; ?>" id="nombre_taller" name="nombre_taller" 
                                       value="<?php echo htmlspecialchars($formData['nombre_taller'] ?? ''); ?>" required>
                                <?php if (isset($formErrors['nombre_taller'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo htmlspecialchars($formErrors['nombre_taller']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono de Contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control <?php echo isset($formErrors['telefono']) ? 'is-invalid' : ''; ?>" id="telefono" name="telefono" 
                                           value="<?php echo htmlspecialchars($formData['telefono'] ?? ''); ?>">
                                    <?php if (isset($formErrors['telefono'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($formErrors['telefono']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rfc" class="form-label">RFC <small class="text-muted">(opcional)</small></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="rfc" name="rfc" 
                                           value="<?php echo htmlspecialchars($formData['rfc'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección del Taller</label>
                            <textarea class="form-control <?php echo isset($formErrors['direccion']) ? 'is-invalid' : ''; ?>" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($formData['direccion'] ?? ''); ?></textarea>
                            <?php if (isset($formErrors['direccion'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars($formErrors['direccion']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="terminos" required>
                            <label class="form-check-label" for="terminos">
                                Acepto los <a href="#" target="_blank">Términos y Condiciones</a> y la <a href="#" target="_blank">Política de Privacidad</a>. <span class="text-danger">*</span>
                            </label>
                            <div class="invalid-feedback">
                                Debes aceptar los términos y condiciones.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Enviar Solicitud</button>
                        </div>
                        
                        <p class="mt-3 text-center">
                            ¿Ya tienes una cuenta? <a href="<?php echo PUBLIC_URL; ?>?route=login">Inicia Sesión aquí</a>
                        </p>
                    </form>
                  <?php endif; // Fin del else para no mostrar formulario si hay éxito ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 cta" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8 text-white">
                <h2 class="mb-4">¿Listo para transformar tu taller?</h2>
                <p class="lead mb-4">Completa tu registro y comienza a optimizar la gestión de tu taller de manera profesional.</p>
                <!-- Botón adaptado para la página de registro -->
                <a href="<?php echo PUBLIC_URL; ?>?route=planes" class="btn btn-lg" style="background-color: white; color: var(--primary-color); border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                    <i class="fas fa-list-alt me-2"></i>Ver Planes Disponibles
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Script para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registroForm');
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');
    
    // Sistema de prevención de duplicados con localStorage
    const STORAGE_KEY = 'herreros_pro_solicitudes';
    
    // Función para verificar si un email ya ha sido enviado
    function isEmailAlreadySubmitted(email) {
        if (!email) return false;
        
        // Normalizar email (minúsculas, sin espacios)
        email = email.toLowerCase().trim();
        
        // Obtener solicitudes guardadas
        const submittedEmails = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        return submittedEmails.includes(email);
    }
    
    // Función para guardar un email como enviado
    function saveSubmittedEmail(email) {
        if (!email) return;
        
        // Normalizar email
        email = email.toLowerCase().trim();
        
        // Obtener y actualizar lista
        const submittedEmails = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        if (!submittedEmails.includes(email)) {
            submittedEmails.push(email);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(submittedEmails));
        }
    }
    
    // Verificar al cargar la página
    if (emailInput && emailInput.value) {
        if (isEmailAlreadySubmitted(emailInput.value)) {
            // Crear alerta de advertencia
            const warningDiv = document.createElement('div');
            warningDiv.className = 'alert alert-warning mt-2';
            warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> Ya existe una solicitud con este correo electrónico.';
            
            // Insertar después del campo de email
            emailInput.parentNode.insertAdjacentElement('afterend', warningDiv);
        }
    }
    
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
            // Verificar duplicados antes de enviar
            if (emailInput && emailInput.value) {
                if (isEmailAlreadySubmitted(emailInput.value)) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Mostrar mensaje de error
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger mt-3';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> <strong>Error:</strong> Ya has enviado una solicitud con este correo electrónico anteriormente. Por favor, espera a que un administrador revise tu solicitud o utiliza otro correo.';
                    
                    // Insertar al principio del formulario
                    form.prepend(errorDiv);
                    
                    // Scroll hacia el mensaje
                    errorDiv.scrollIntoView({ behavior: 'smooth' });
                    return;
                }
            }
            
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
                // Guardar email como enviado
                if (emailInput && emailInput.value) {
                    saveSubmittedEmail(emailInput.value);
                }
                
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
    
    // Mostrar/Ocultar contraseña
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    if (passwordInput && togglePassword) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Mostrar/Ocultar confirmar contraseña
    const confirmPasswordInput = document.getElementById('confirm_password');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    if (confirmPasswordInput && toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Añadir lógica para la barra de progreso y redirección
    const successAlert = document.getElementById('success-message-alert');
    const progressBar = document.getElementById('redirect-progress-bar');
    const homeUrl = '<?php echo PUBLIC_URL; ?>'; // Obtener URL base desde PHP

    if (successAlert && progressBar) {
        // Hacer visible la barra (si estaba oculta)
        progressBar.parentElement.style.display = 'block'; 

        let progress = 0;
        const intervalTime = 30; // ms -> 3000ms / 100 = 30ms por paso
        const duration = 5000; // 5 segundos en ms
        const steps = duration / intervalTime;
        const increment = 100 / steps;

        const interval = setInterval(() => {
            progress += increment;
            if (progress >= 100) {
                progress = 100;
                progressBar.style.width = '100%';
                progressBar.setAttribute('aria-valuenow', Math.round(progress));
                clearInterval(interval);

                // Redirigir después de un breve instante (ej. 500ms después de completar)
                setTimeout(() => {
                    window.location.href = homeUrl; 
                }, 5500); 
            } else {
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', Math.round(progress));
            }
        }, intervalTime);
    }
});
</script>