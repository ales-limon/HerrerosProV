# HerrerosPro - Estructura del Sistema

## 1. Sitio Web Público (marketing y acceso)
- **Estado**: FINALIZADO
- **Ubicación**: `/public/`
- **Archivos**: Protegidos en .gitignore
- **Acceso**: `http://localhost/HerrerosPro/public/`
- ⚠️ NO MODIFICAR estos archivos, ya están finalizados

## 2. Plataforma Admin (gestión de talleres y suscripciones)
- **Estado**: En desarrollo
- **Ubicación**: `/plataforma/`
- **Acceso**: `http://localhost/HerrerosPro/plataforma/`
- **Estructura MVC**:
  - `/views/` - Interfaces de usuario
  - `/controllers/` - Lógica de negocio
  - `/models/` - Acceso a datos
- **UI**: AdminLTE

## 3. Panel de Talleres (gestión operativa)
- **Estado**: Pendiente
- **Ubicación**: `/talleres/`
- **Acceso**: `http://localhost/HerrerosPro/talleres/`
- **Estructura**: Similar a plataforma admin

## Reglas Importantes
1. NO modificar archivos del sitio público
2. NO modificar common.php sin hacer respaldo
3. Mantener separación entre los tres ámbitos
4. Seguir patrón MVC en plataforma y talleres
5. Usar AdminLTE solo en plataforma admin
6. Acceder siempre por puerto 80 (http://localhost/...)

## Configuración Base
- **Base de datos**: MySQL/MariaDB
  - Host: localhost
  - DB: herrerospro
  - Usuario: root
  - Charset: utf8mb4
