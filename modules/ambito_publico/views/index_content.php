<?php
/**
 * Contenido de la página principal
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

/* Estilos para los iconos de características */
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

/* Estilos para los botones en hover */
.hero a.btn:hover, .cta a.btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
}

.hero a.btn[style*="background-color: white"]:hover, .cta a.btn[style*="background-color: white"]:hover {
    background-color: #f8f9fa !important;
}

.hero a.btn[style*="background-color: transparent"]:hover {
    background-color: white !important;
    color: var(--primary-color) !important;
}

/* Estilo para la cinta "Popular" */
.ribbon {
    position: absolute;
    top: -10px;
    right: 10px;
    padding: 10px 15px;
    background-color: var(--primary-color);
    color: white;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 2;
    border-radius: 3px;
}

/* Estilo para el plan destacado */
.card-popular {
    transform: scale(1.05);
    border: 2px solid var(--primary-color) !important;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    z-index: 1;
    position: relative;
}

/* Estilos para los botones */
.btn-lg {
    padding: 12px 24px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-outline-primary:hover {
    background-color: var(--primary-color);
    color: white;
}
</style>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInLeft">
                    Gestiona tu Taller de Herrería de Manera Profesional
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                    HerrerosPro es el software integral que necesitas para administrar eficientemente tu taller. 
                    Optimiza tus procesos, mejora tu productividad y haz crecer tu negocio.
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
                <img src="<?php echo ASSETS_URL; ?>img/logo.png" alt="HerrerosPro Dashboard" class="img-fluid rounded shadow" style="max-width: 55%; margin: 0 auto; display: block; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important; border: 3px solid rgba(255, 255, 255, 0.3); padding: 10px; background-color: rgba(255, 255, 255, 0.1);">
            </div>
        </div>
    </div>
</section>

<!-- Características -->
<section class="features py-5">
    <div class="container">
        <h2 class="text-center mb-5">¿Por qué elegir HerrerosPro?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon rounded-circle mb-3">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h5 class="card-title">Gestión Integral</h5>
                        <p class="card-text">Administra clientes, proyectos, inventario y más desde una sola plataforma.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon rounded-circle mb-3">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h5 class="card-title">Cotizaciones Profesionales</h5>
                        <p class="card-text">Genera cotizaciones detalladas y precisas en minutos con nuestro sistema automatizado.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon rounded-circle mb-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="card-title">Control Financiero</h5>
                        <p class="card-text">Mantén el control de tus ingresos, gastos y genera reportes detallados.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Planes -->
<section class="pricing bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Planes diseñados para tu negocio</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center mb-4">Plan Básico</h5>
                        <h1 class="text-center mb-4">$499<small class="text-muted">/mes</small></h1>
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Gestión de clientes</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Cotizaciones básicas</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Control de inventario</li>
                        </ul>
                        <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php?plan=basico" class="btn btn-outline-primary d-block">Elegir Plan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg card-popular">
                    <div class="card-body p-4">
                        <div class="ribbon">Popular</div>
                        <h5 class="card-title text-center mb-4">Plan Profesional</h5>
                        <h1 class="text-center mb-4">$999<small class="text-muted">/mes</small></h1>
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Todo del Plan Básico</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Gestión de proyectos</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Reportes avanzados</li>
                        </ul>
                        <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php?plan=profesional" class="btn btn-primary d-block">Elegir Plan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center mb-4">Plan Enterprise</h5>
                        <h1 class="text-center mb-4">$1,999<small class="text-muted">/mes</small></h1>
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Todo del Plan Profesional</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Múltiples sucursales</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Soporte prioritario</li>
                        </ul>
                        <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php?plan=enterprise" class="btn btn-outline-primary d-block">Elegir Plan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="text-white mb-4">¿Listo para llevar tu negocio al siguiente nivel?</h2>
                <p class="text-white mb-4">Únete a miles de empresas que confían en nuestra plataforma para gestionar sus proyectos de construcción.</p>
                <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="btn btn-lg cta-button" style="background-color: white; color: var(--primary-color); border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">Comenzar Ahora</a>
            </div>
        </div>
    </div>
</section> 