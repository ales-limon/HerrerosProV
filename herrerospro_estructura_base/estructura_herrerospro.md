# Estructura de Carpetas - HerrerosPro

```plaintext

/herrerospro/
├── public/
│   ├── index.php
│   └── assets/
│       ├── adminlte/
│       ├── css/
│       ├── js/
│       └── img/
│
├── config/
│   ├── config.php
│   └── database.php
│
├── includes/
│   ├── shared/
│   │   ├── common.php
│   │   ├── auth.php
│   │   ├── flash.php
│   │   └── funciones.php
│   └── helpers/
│       ├── format.php
│       ├── medidas.php
│       ├── textos.php
│       └── seguridad.php
│
├── modules/
│   ├── ambito_publico/
│   │   ├── login.php
│   │   ├── home.php
│   │   ├── planes.php
│   │   └── contacto.php
│
│   ├── ambito_administracion/
│   │   ├── index.php
│   │   ├── controller_admin.php
│   │   ├── model_admin.php
│   │   ├── content_admin.php
│   │   ├── scripts_admin.php
│   │   ├── includes/
│   │   │   ├── header.php
│   │   │   ├── navbar.php
│   │   │   └── footer.php
│   │   └── modulos/
│   │       ├── talleres/
│   │       ├── usuarios/
│   │       └── reportes/
│
│   ├── ambito_talleres/
│   │   ├── index.php
│   │   ├── controller_taller.php
│   │   ├── model_taller.php
│   │   ├── content_taller.php
│   │   ├── scripts_taller.php
│   │   ├── includes/
│   │   │   ├── header.php
│   │   │   ├── navbar.php
│   │   │   └── footer.php
│   │   └── modulos/
│   │       ├── proyectos/
│   │       │   ├── index.php
│   │       │   ├── controller_proyectos.php
│   │       │   ├── model_proyectos.php
│   │       │   ├── content_proyectos.php
│   │       │   └── scripts_proyectos.php
│   │       ├── cotizaciones/
│   │       ├── materiales/
│   │       └── empleados/
```