/**
 * Archivo JavaScript principal para HerrerosPro
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('HerrerosPro JS loaded');
    
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Manejar el formulario de contacto
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            // La validación básica la maneja HTML5 con los atributos required
            
            // Si se quiere hacer una validación personalizada adicional, se puede hacer aquí
            
            // Para una experiencia de usuario mejorada, podríamos usar AJAX para enviar el formulario
            // sin recargar la página, pero eso requeriría más código JavaScript
        });
    }
    
    // Efecto de desplazamiento suave para los enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
