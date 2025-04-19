<?php
/**
 * Contenido de la página de planes y precios
 */

// Definir variables de estilo
$primary_color = '#17a2b8';
$primary_dark = '#343a40';
?>
<style>
:root {
    --primary-color: <?php echo $primary_color; ?>;
    --primary-dark: <?php echo $primary_dark; ?>;
}

/* Estilos específicos de la página de planes */
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

/* Estilos para los planes */
.pricing-card {
    border: none;
    transition: all 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
}

.pricing-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.pricing-header {
    background-color: var(--primary-color);
    color: white;
    padding: 20px;
    text-align: center;
}

.pricing-popular {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    z-index: 1;
    position: relative;
}

.pricing-popular .pricing-header {
    background-color: var(--primary-dark);
}

.pricing-price {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 20px 0;
}

.pricing-price small {
    font-size: 1rem;
    font-weight: normal;
}

.pricing-features {
    padding: 0;
    list-style: none;
}

.pricing-features li {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.pricing-features li:last-child {
    border-bottom: none;
}

.pricing-features i {
    margin-right: 10px;
    color: var(--primary-color);
}

.ribbon {
    position: absolute;
    top: 20px;
    right: -30px;
    transform: rotate(45deg);
    background-color: #ffc107;
    color: #343a40;
    padding: 5px 40px;
    font-weight: bold;
    z-index: 2;
}

/* Estilos para la sección de FAQ */
.accordion-button:not(.collapsed) {
    background-color: rgba(23, 162, 184, 0.1);
    color: var(--primary-color);
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
    border-color: var(--primary-color);
}

/* Estilos para los botones en hover */
.hero a.btn:hover, .cta a.btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
}

.hero a.btn[style*="background-color: white"]:hover {
    background-color: #f8f9fa !important;
}
</style>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInLeft">
                    Planes y Precios
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                    Elige el plan que mejor se adapte a las necesidades de tu taller de herrería.
                </p>
                <div class="animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="btn btn-lg me-3" style="background-color: white; color: var(--primary-color); border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                        <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                    </a>
                    <a href="<?php echo PUBLIC_URL; ?>public/views/demo.php" class="btn btn-lg" style="background-color: transparent; color: white; border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                        <i class="fas fa-play-circle me-2"></i>Ver Demo
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block animate__animated animate__fadeInRight">
                <img src="<?php echo ASSETS_URL; ?>img/logo.png" alt="HerrerosPro" class="img-fluid rounded shadow" style="max-width: 55%; margin: 0 auto; display: block; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important; border: 3px solid rgba(255, 255, 255, 0.3); padding: 10px; background-color: rgba(255, 255, 255, 0.1);">
            </div>
        </div>
    </div>
</section>

<!-- Planes de Precios -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Nuestros Planes</h2>
        <div class="row g-4">
            <!-- Plan Básico -->
            <div class="col-md-4">
                <div class="card pricing-card h-100 shadow-sm">
                    <div class="pricing-header">
                        <h3>Básico</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="pricing-price text-center">
                            $499<small>/mes</small>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check-circle"></i>Gestión de clientes</li>
                            <li><i class="fas fa-check-circle"></i>Cotizaciones básicas</li>
                            <li><i class="fas fa-check-circle"></i>Control de inventario</li>
                            <li><i class="fas fa-check-circle"></i>Facturación simple</li>
                            <li><i class="fas fa-check-circle"></i>Soporte por email</li>
                            <li><i class="fas fa-times-circle text-muted"></i>Gestión de proyectos</li>
                            <li><i class="fas fa-times-circle text-muted"></i>Reportes avanzados</li>
                            <li><i class="fas fa-times-circle text-muted"></i>Múltiples usuarios</li>
                        </ul>
                        <div class="d-grid gap-2 mt-4">
                            <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php?plan=basico" class="btn btn-outline-primary">Elegir Plan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Plan Profesional -->
            <div class="col-md-4">
                <div class="card pricing-card pricing-popular h-100 shadow">
                    <div class="ribbon">Popular</div>
                    <div class="pricing-header">
                        <h3>Profesional</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="pricing-price text-center">
                            $999<small>/mes</small>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check-circle"></i>Todo del Plan Básico</li>
                            <li><i class="fas fa-check-circle"></i>Gestión de proyectos</li>
                            <li><i class="fas fa-check-circle"></i>Reportes avanzados</li>
                            <li><i class="fas fa-check-circle"></i>Hasta 5 usuarios</li>
                            <li><i class="fas fa-check-circle"></i>Soporte prioritario</li>
                            <li><i class="fas fa-check-circle"></i>Aplicación móvil</li>
                            <li><i class="fas fa-check-circle"></i>Integración con contabilidad</li>
                            <li><i class="fas fa-times-circle text-muted"></i>Múltiples sucursales</li>
                        </ul>
                        <div class="d-grid gap-2 mt-4">
                            <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php?plan=profesional" class="btn btn-primary">Elegir Plan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Plan Enterprise -->
            <div class="col-md-4">
                <div class="card pricing-card h-100 shadow-sm">
                    <div class="pricing-header">
                        <h3>Enterprise</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="pricing-price text-center">
                            $1,999<small>/mes</small>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check-circle"></i>Todo del Plan Profesional</li>
                            <li><i class="fas fa-check-circle"></i>Usuarios ilimitados</li>
                            <li><i class="fas fa-check-circle"></i>Múltiples sucursales</li>
                            <li><i class="fas fa-check-circle"></i>API para integraciones</li>
                            <li><i class="fas fa-check-circle"></i>Personalización avanzada</li>
                            <li><i class="fas fa-check-circle"></i>Soporte 24/7</li>
                            <li><i class="fas fa-check-circle"></i>Capacitación personalizada</li>
                            <li><i class="fas fa-check-circle"></i>Consultoría de implementación</li>
                        </ul>
                        <div class="d-grid gap-2 mt-4">
                            <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php?plan=enterprise" class="btn btn-outline-primary">Elegir Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comparativa de Planes -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Comparativa de Planes</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Características</th>
                        <th class="text-center">Básico</th>
                        <th class="text-center">Profesional</th>
                        <th class="text-center">Enterprise</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gestión de clientes</td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                    </tr>
                    <tr>
                        <td>Cotizaciones</td>
                        <td class="text-center">Básicas</td>
                        <td class="text-center">Avanzadas</td>
                        <td class="text-center">Personalizables</td>
                    </tr>
                    <tr>
                        <td>Control de inventario</td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                    </tr>
                    <tr>
                        <td>Gestión de proyectos</td>
                        <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                    </tr>
                    <tr>
                        <td>Reportes</td>
                        <td class="text-center">Básicos</td>
                        <td class="text-center">Avanzados</td>
                        <td class="text-center">Personalizables</td>
                    </tr>
                    <tr>
                        <td>Usuarios</td>
                        <td class="text-center">1</td>
                        <td class="text-center">Hasta 5</td>
                        <td class="text-center">Ilimitados</td>
                    </tr>
                    <tr>
                        <td>Sucursales</td>
                        <td class="text-center">1</td>
                        <td class="text-center">1</td>
                        <td class="text-center">Múltiples</td>
                    </tr>
                    <tr>
                        <td>Soporte</td>
                        <td class="text-center">Email</td>
                        <td class="text-center">Prioritario</td>
                        <td class="text-center">24/7</td>
                    </tr>
                    <tr>
                        <td>Aplicación móvil</td>
                        <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                    </tr>
                    <tr>
                        <td>Personalización</td>
                        <td class="text-center">Limitada</td>
                        <td class="text-center">Estándar</td>
                        <td class="text-center">Avanzada</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Preguntas Frecuentes -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Preguntas Frecuentes</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="accordionFAQ">
                    <!-- Pregunta 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                ¿Puedo cambiar de plan en cualquier momento?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Sí, puedes actualizar o cambiar tu plan en cualquier momento. Los cambios se aplicarán al inicio del siguiente ciclo de facturación. Si actualizas a un plan superior, se te cobrará la diferencia prorrateada por el tiempo restante del ciclo actual.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pregunta 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                ¿Hay algún contrato de permanencia?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                No, todos nuestros planes son mensuales y puedes cancelar en cualquier momento. No hay contratos de permanencia ni penalizaciones por cancelación.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pregunta 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                ¿Ofrecen algún período de prueba?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Sí, ofrecemos un período de prueba gratuito de 14 días para todos nuestros planes. Durante este período podrás explorar todas las funcionalidades del plan seleccionado sin compromiso.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pregunta 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                ¿Qué métodos de pago aceptan?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Aceptamos tarjetas de crédito y débito (Visa, MasterCard, American Express), PayPal y transferencias bancarias para planes anuales.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pregunta 5 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                ¿Ofrecen descuentos para pagos anuales?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Sí, ofrecemos un descuento del 20% para pagos anuales en todos nuestros planes. Contacta con nuestro equipo de ventas para más información.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8 text-white">
                <h2 class="mb-4">¿Listo para transformar tu taller?</h2>
                <p class="lead mb-4">Elige el plan que mejor se adapte a tus necesidades y comienza a optimizar la gestión de tu taller de herrería hoy mismo.</p>
                <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="btn btn-lg" style="background-color: white; color: var(--primary-color); border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                    <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Script para efectos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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