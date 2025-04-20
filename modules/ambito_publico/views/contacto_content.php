<?php
/**
 * Contenido de la página de contacto
 */

// Definir variables de estilo
$primary_color = '#17a2b8';
$primary_dark = '#343a40';

// Agregar estilos CSS personalizados
?>
<style>
:root {
    --primary-color: <?php echo $primary_color; ?>;
    --primary-dark: <?php echo $primary_dark; ?>;
}

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
}

.text-primary {
    color: var(--primary-color) !important;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(0, 102, 204, 0.25);
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
}

/* Estilos para los iconos sociales */
.social-icon {
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.2);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background-color: #fff;
    color: var(--primary-color);
    transform: translateY(-3px);
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

/* Estilos para el footer/CTA */
.cta {
    background-color: var(--primary-color);
    color: white;
}
</style>

<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verificar si hay mensajes de éxito o error
$mensajeEnviado = isset($_GET['enviado']) && $_GET['enviado'] == 1;
$hayError = isset($_GET['error']) && $_GET['error'] == 1;
$errorCSRF = isset($_GET['error']) && $_GET['error'] == 2;

// Recuperar datos del formulario en caso de error
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [
    'nombre' => '',
    'email' => '',
    'telefono' => '',
    'asunto' => '',
    'mensaje' => ''
];

// Recuperar errores
$formErrors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];

// Limpiar datos de sesión después de usarlos
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInLeft">
                    Contáctanos
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                    Estamos aquí para responder tus preguntas y ayudarte a comenzar con HerrerosPro.
                    Nuestro equipo de soporte está listo para asistirte.
                </p>
            </div>
            <div class="col-lg-6 d-none d-lg-block animate__animated animate__fadeInRight">
                <img src="<?php echo ASSETS_URL; ?>img/contact.svg" alt="Contacto" class="img-fluid" style="max-height: 300px; margin: 0 auto; display: block;">
            </div>
        </div>
    </div>
</section>

<?php if ($mensajeEnviado): ?>
<!-- Mensaje de éxito -->
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>¡Mensaje enviado correctamente!</strong> Gracias por contactarnos. Te responderemos a la brevedad.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>

<?php if ($hayError): ?>
<!-- Mensaje de error -->
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Error al enviar el mensaje!</strong> Por favor, verifica los datos e intenta nuevamente.
        <?php if (!empty($formErrors)): ?>
            <ul class="mt-2 mb-0">
                <?php foreach ($formErrors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>

<?php if ($errorCSRF): ?>
<!-- Mensaje de error CSRF -->
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Error de seguridad!</strong> Ha ocurrido un problema con la validación del formulario. Por favor, intenta nuevamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>

<!-- Información de Contacto y Formulario -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Información de Contacto -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4 text-primary">Información de Contacto</h3>
                        
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="feature-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5>Email</h5>
                                <p class="text-muted mb-0">info@herrerospro.com</p>
                                <p class="text-muted mb-0">soporte@herrerospro.com</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5>Síguenos</h5>
                            <div class="d-flex mt-3">
                                <a href="#" class="social-icon me-2"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon me-2"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon me-2"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-icon me-2"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Formulario de Contacto -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4 text-primary">Envíanos un Mensaje</h3>
                        
                        <?php 
                        // Mostrar mensajes flash (éxito/error del formulario)
                        if (isset($_SESSION['flash_message'])): 
                            $message = $_SESSION['flash_message'];
                            unset($_SESSION['flash_message']); // Limpiar el mensaje para que no se muestre de nuevo
                        ?>
                        <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?> alert-dismissible fade show" role="alert">
                            <?php echo $message['text']; // Usar echo directamente ya que el texto viene de nuestro controlador ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form id="contactForm" method="POST" action="<?php echo PUBLIC_URL; ?>?route=procesar_contacto" class="contact-form needs-validation" novalidate>
                            <!-- Token CSRF para prevenir ataques CSRF -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <!-- Campo honeypot para detectar bots - este campo debe estar oculto con CSS -->
                            <div style="display:none;">
                                <input type="text" name="honeypot" id="honeypot" autocomplete="off">
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control <?php echo isset($formErrors['nombre']) ? 'is-invalid' : ''; ?>" 
                                               id="nombre" name="nombre" placeholder="Tu nombre" required 
                                               pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-\']{2,50}"
                                               value="<?php echo htmlspecialchars($formData['nombre'] ?? ''); ?>"
                                               maxlength="50">
                                        <label for="nombre">Nombre completo</label>
                                        <div class="invalid-feedback">
                                            Por favor ingresa un nombre válido (solo letras y espacios).
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control <?php echo isset($formErrors['email']) ? 'is-invalid' : ''; ?>" 
                                               id="email" name="email" placeholder="Tu email" required
                                               value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                                               maxlength="100">
                                        <label for="email">Correo electrónico</label>
                                        <div class="invalid-feedback">
                                            Por favor ingresa un correo electrónico válido.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control <?php echo isset($formErrors['telefono']) ? 'is-invalid' : ''; ?>" 
                                               id="telefono" name="telefono" placeholder="Tu teléfono"
                                               pattern="[0-9\+\-\(\)\s]{5,20}"
                                               value="<?php echo htmlspecialchars($formData['telefono'] ?? ''); ?>"
                                               maxlength="20">
                                        <label for="telefono">Teléfono (opcional)</label>
                                        <div class="invalid-feedback">
                                            Por favor ingresa un número de teléfono válido.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select <?php echo isset($formErrors['asunto']) ? 'is-invalid' : ''; ?>" 
                                                id="asunto" name="asunto" required>
                                            <option value="" selected disabled>Selecciona una opción</option>
                                            <option value="Información general" <?php echo ($formData['asunto'] ?? '') === 'Información general' ? 'selected' : ''; ?>>Información general</option>
                                            <option value="Soporte técnico" <?php echo ($formData['asunto'] ?? '') === 'Soporte técnico' ? 'selected' : ''; ?>>Soporte técnico</option>
                                            <option value="Ventas" <?php echo ($formData['asunto'] ?? '') === 'Ventas' ? 'selected' : ''; ?>>Ventas</option>
                                            <option value="Otro" <?php echo ($formData['asunto'] ?? '') === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                                        </select>
                                        <label for="asunto">Asunto</label>
                                        <div class="invalid-feedback">
                                            Por favor selecciona un asunto.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control <?php echo isset($formErrors['mensaje']) ? 'is-invalid' : ''; ?>" 
                                                  id="mensaje" name="mensaje" placeholder="Tu mensaje" 
                                                  style="height: 150px" required
                                                  maxlength="2000"><?php echo htmlspecialchars($formData['mensaje'] ?? ''); ?></textarea>
                                        <label for="mensaje">Mensaje</label>
                                        <div class="invalid-feedback">
                                            Por favor ingresa un mensaje (máximo 2000 caracteres).
                                        </div>
                                        <div class="form-text text-end">
                                            <span id="charCount">0</span>/2000 caracteres
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input <?php echo isset($formErrors['privacidad']) ? 'is-invalid' : ''; ?>" 
                                               type="checkbox" id="privacidad" name="privacidad" required>
                                        <label class="form-check-label" for="privacidad">
                                            He leído y acepto la <a href="#" class="text-primary">política de privacidad</a>
                                        </label>
                                        <div class="invalid-feedback">
                                            Debes aceptar la política de privacidad para continuar.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Preguntas Frecuentes</h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="accordionFAQ">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                ¿Cómo puedo comenzar a usar HerrerosPro?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Para comenzar a usar HerrerosPro, simplemente regístrate en nuestra plataforma eligiendo el plan que mejor se adapte a tus necesidades. Una vez completado el registro, recibirás un correo electrónico con tus credenciales de acceso y podrás comenzar a configurar tu taller en el sistema.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                ¿Ofrecen capacitación para usar el sistema?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Sí, ofrecemos capacitación completa para todos nuestros clientes. Dependiendo del plan que elijas, puedes acceder a tutoriales en video, documentación detallada, webinars en vivo y sesiones de capacitación personalizadas para ti y tu equipo.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                ¿Puedo migrar mis datos desde otro sistema?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Sí, ofrecemos servicios de migración de datos desde otros sistemas. Nuestro equipo técnico te ayudará a transferir tu información de clientes, inventario, proyectos y más a HerrerosPro de manera segura y eficiente. Contáctanos para obtener más detalles sobre este servicio.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                ¿Qué soporte técnico ofrecen?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Ofrecemos soporte técnico por correo electrónico, chat en vivo y teléfono durante horario laboral. Los clientes con planes Premium y Enterprise tienen acceso a soporte prioritario y asistencia extendida. Nuestro objetivo es resolver cualquier problema técnico en el menor tiempo posible.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 shadow-sm">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                ¿Mis datos están seguros en HerrerosPro?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Absolutamente. La seguridad de tus datos es nuestra prioridad. Utilizamos encriptación SSL/TLS para todas las comunicaciones, realizamos copias de seguridad diarias y almacenamos tu información en servidores seguros con múltiples capas de protección. Cumplimos con todas las regulaciones de protección de datos aplicables.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<?php include __DIR__ . '/../includes/cta_section.php'; ?>

<!-- Script para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const mensaje = document.getElementById('mensaje');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    
    // Actualizar contador de caracteres
    if (mensaje && charCount) {
        charCount.textContent = mensaje.value.length;
        
        mensaje.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            
            // Cambiar color si se acerca al límite
            if (this.value.length > 1800) {
                charCount.style.color = 'red';
            } else if (this.value.length > 1500) {
                charCount.style.color = 'orange';
            } else {
                charCount.style.color = '';
            }
        });
    }
    
    // Validación del formulario
    if (form) {
        form.addEventListener('submit', function(event) {
            // Añadir console.log para ver la URL y los datos
            console.log('Enviando formulario a:', form.action);
            const formData = new FormData(form);
            const formDataObj = {};
            formData.forEach((value, key) => formDataObj[key] = value);
            console.log('Datos del formulario:', formDataObj);
            
            let isValid = true;
            
            // Validar campos requeridos y patrones
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            });
            
            // Validar email con una expresión regular más estricta
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value) {
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailRegex.test(emailInput.value)) {
                    emailInput.classList.add('is-invalid');
                    isValid = false;
                }
            }
            
            // Si no es válido, prevenir envío
            if (!isValid) {
                event.preventDefault();
                return;
            }
            
            // Deshabilitar botón para prevenir múltiples envíos
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Enviando...';
            
            // Permitir que el formulario se envíe
        });
        
        // Validación en tiempo real
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.required && !this.checkValidity()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    if (this.value) {
                        this.classList.add('is-valid');
                    }
                }
            });
        });
    }
    
    // Efecto hover para botones en la sección hero y CTA
    const heroButtons = document.querySelectorAll('.hero a.btn, .cta a.btn');
    heroButtons.forEach(button => {
        // Guardar el estilo original
        const computedStyle = window.getComputedStyle(button);
        const originalBg = computedStyle.backgroundColor;
        button.setAttribute('data-original-bg', originalBg);
        
        button.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.4)';
            
            const currentBg = window.getComputedStyle(this).backgroundColor;
            if (currentBg === 'rgb(255, 255, 255)' || currentBg === 'rgba(255, 255, 255, 1)') {
                this.style.backgroundColor = '#f8f9fa';
            }
            
            if (currentBg === 'rgba(0, 0, 0, 0)' || currentBg === 'transparent') {
                this.style.backgroundColor = 'white';
                this.style.color = 'var(--primary-color)';
            }
        });
        
        button.addEventListener('mouseout', function() {
            this.style.transform = '';
            this.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.3)';
            
            const originalBg = this.getAttribute('data-original-bg');
            this.style.backgroundColor = originalBg;
            
            if (originalBg === 'rgba(0, 0, 0, 0)' || originalBg === 'transparent') {
                this.style.backgroundColor = 'transparent';
                this.style.color = 'white';
            }
        });
    });
});
</script>