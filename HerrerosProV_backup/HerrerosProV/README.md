# HerrerosPro

## Archivos Protegidos (Versiones Finales)

Los siguientes archivos están completamente optimizados y no deben modificarse:

### Vistas Principales (Finalizadas)
- `public/views/contacto_content.php`
- `public/views/index_content.php`

⚠️ **IMPORTANTE:**
- Estos archivos contienen código optimizado y probado.
- Cualquier modificación podría afectar la funcionalidad y el rendimiento.
- Si se requieren cambios, primero crear un ticket y discutirlo con el equipo.

### Archivos en Desarrollo
Los siguientes archivos aún están en desarrollo y pueden ser modificados:
- `public/views/login_content.php`
- `public/views/registro_content.php`

## Proceso para Modificaciones de Archivos Protegidos

Si es absolutamente necesario modificar alguno de los archivos protegidos:

1. Crear una rama específica para los cambios
2. Comentar las líneas correspondientes en `.gitignore`
3. Realizar los cambios necesarios
4. Probar exhaustivamente en un entorno de desarrollo
5. Solicitar revisión del equipo
6. Después de la aprobación, descomentar las líneas en `.gitignore`

## Estructura del Proyecto

```
HerrerosPro/
├── public/
│   ├── views/         # Vistas principales
│   │   ├── *.php     # Archivos protegidos (finalizados)
│   │   └── *.php     # Archivos en desarrollo
│   ├── assets/        # Recursos estáticos
│   └── controllers/   # Controladores
└── config/           # Configuración del sistema
``` 