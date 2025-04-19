<?php
/**
 * Contenido de la página de demo
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

/* Estilos específicos de la página de demo */
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

/* Estilos para la sección de características */
.feature-card {
    border: none;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background-color: rgba(23, 162, 184, 0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--primary-color);
    border-radius: 50%;
    margin-bottom: 1.5rem;
}

/* Estilos para la sección de video */
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
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
                    Demo de HerrerosPro
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                    Descubre cómo nuestra plataforma puede transformar la gestión de tu taller de herrería.
                </p>
                <div class="animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="#video-demo" class="btn btn-lg me-3" style="background-color: white; color: var(--primary-color); border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                        <i class="fas fa-play-circle me-2"></i>Ver Video Demo
                    </a>
                    <a href="<?php echo PUBLIC_URL; ?>public/views/registro.php" class="btn btn-lg" style="background-color: transparent; color: white; border: 3px solid white; font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                        <i class="fas fa-user-plus me-2"></i>Registrarse
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block animate__animated animate__fadeInRight">
                <img src="<?php echo ASSETS_URL; ?>img/logo.png" alt="HerrerosPro" class="img-fluid rounded shadow" style="max-width: 55%; margin: 0 auto; display: block; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important; border: 3px solid rgba(255, 255, 255, 0.3); padding: 10px; background-color: rgba(255, 255, 255, 0.1);">
            </div>
        </div>
    </div>
</section>

<!-- Características Destacadas -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Características Destacadas</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 feature-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="card-title">Gestión de Clientes</h4>
                        <p class="card-text">Administra toda la información de tus clientes, historial de proyectos y comunicaciones en un solo lugar.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h4 class="card-title">Cotizaciones Profesionales</h4>
                        <p class="card-text">Crea cotizaciones detalladas y personalizadas en minutos, con cálculos automáticos de materiales y mano de obra.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4 class="card-title">Seguimiento de Proyectos</h4>
                        <p class="card-text">Controla el avance de tus proyectos, asigna tareas y mantén a tu equipo y clientes informados en tiempo real.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Demo -->
<section id="video-demo" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Video Demostración</h2>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="video-container">
                    <!-- Reemplazar con el ID de video real -->
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Demo de HerrerosPro" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted">Este video muestra las principales funcionalidades de HerrerosPro y cómo pueden ayudarte a gestionar tu taller de manera más eficiente.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Solicitar Demo Personalizada -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-4">¿Quieres una demostración personalizada?</h2>
                <p class="lead mb-4">Agenda una sesión con uno de nuestros especialistas y descubre cómo HerrerosPro puede adaptarse a las necesidades específicas de tu taller.</p>
                <a href="<?php echo PUBLIC_URL; ?>public/views/contacto.php" class="btn btn-lg" style="background-color: var(--primary-color); color: white; border: 3px solid var(--primary-color); font-weight: bold; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); padding: 12px 24px; transition: all 0.3s ease;">
                    <i class="fas fa-calendar-alt me-2"></i>Solicitar Demo Personalizada
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
    
    // Scroll suave para anclas
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script> 