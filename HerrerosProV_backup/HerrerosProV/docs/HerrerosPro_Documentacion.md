# Documentación Completa de HerrerosPro

## Índice
1. [Estructura General del Sistema](#estructura-general-del-sistema)
2. [Arquitectura de Archivos](#arquitectura-de-archivos)
3. [Sistema de Plantillas](#sistema-de-plantillas)
4. [Gestión de Usuarios y Permisos](#gestión-de-usuarios-y-permisos)
5. [Componentes Principales](#componentes-principales)
6. [Guía de Implementación](#guía-de-implementación)
7. [Estándares de Codificación](#estándares-de-codificación)
8. [Seguridad en Formularios](#seguridad-en-formularios)
   - [Medidas de Seguridad Implementadas](#medidas-de-seguridad-implementadas)
   - [Prevención de Inyecciones SQL](#prevención-de-inyecciones-sql)
   - [Prevención de Ataques XSS](#prevención-de-ataques-xss)
   - [Implementación de CSRF Protection](#implementación-de-csrf-protection)
   - [Caso de Estudio: Seguridad en el Formulario de Contacto](#caso-de-estudio-seguridad-en-el-formulario-de-contacto)
   - [Conclusión y Recomendaciones de Seguridad](#conclusión-y-recomendaciones-de-seguridad)
9. [Diseño de Interfaces y Componentes Visuales](#diseño-de-interfaces-y-componentes-visuales)
10. [Implementación de DataTables](#implementación-de-datatables)
11. [Ejemplos de Código](#ejemplos-de-código)
    - [Ejemplo de Validación de Formularios con Seguridad Mejorada](#ejemplo-de-validación-de-formularios-con-seguridad-mejorada)
    - [Ejemplo de Controlador](#ejemplo-de-controlador-clientes_controllerphp)

## Estructura General del Sistema

HerrerosPro se divide en tres ámbitos principales, cada uno con su propia estructura y funcionalidad:

### 1. VISTAS PÚBLICO (Sitio Web Principal)
- Inicio (Presentación de HerrerosPro)
- Planes y Precios
- Solicita tu Membresía
- Cómo Funciona
- Demo Interactiva (Sandbox)
- Casos de Éxito / Testimonios
- Preguntas Frecuentes (FAQ)
- Soporte y Contacto
- Blog / Noticias del Sector
- Acceso
- Login (Talleres)
- Acceso Admin (Plataforma)

### 2. PLATAFORMA (Admin HerrerosPro)
- Dashboard (Vista general, métricas de ingresos y actividad)
- Gestión de Talleres (CRUD de talleres, historial de actividad, soporte)
- Gestión de Usuarios y Permisos (Roles: Admin, Supervisor, Capturista)
- Membresías y Suscripciones (Planes, pagos, facturación, reportes)
- Gestión de Solicitudes (Aprobación de nuevos talleres)
- Métricas y Reportes (Estadísticas de uso, registros de acceso)
- Categorías de Finanzas (Ingresos y Gastos)

### 3. TALLERES (Panel de cada taller)
- Dashboard (Vista de cotizaciones, proyectos en curso y finanzas)
- Gestión de Empresas (A donde pertenece cada cliente) (CRUD)
- Gestión de Clientes (Historial de pedidos, facturación, notas) (CRUD)
- Gestión de Proveedores (Órdenes de compra, trazabilidad de materiales) (CRUD)
- Gestión de Empleados (Roles, asistencia, horas extras, préstamos) (CRUD)
- Gestión de Almacén
  - Control de existencias y stock bajo (Inventario)
  - Herrajes (CRUD)
    - Categorías de herrajes (CRUD)
- Gestión de Materiales
  - Catálogo de materiales, definir actualización automática de precios (CRUD)
  - Categorías de materiales (herrajes, PTR, soleras, etc.)
- Cotizaciones (Generación, cálculo automático, PDF) (CRUD)
- Gestión de Proyectos (Estados de avance, órdenes de producción, planos) (CRUD)
  - Gestión de Piezas (Optimización de materiales, costos de fabricación)
- Finanzas (Cuentas por cobrar/pagar, reportes financieros PDF/Excel) (CRUD)
- Agenda y Planeación (Planeador semanal, Gantt, notificaciones, citas)
- Optimización de Materiales (Cálculo de cortes, minimización de desperdicio)
- Recursos Humanos (Asistencias, Horas extras, Nóminas, Préstamos, Faltas y justificaciones)
- Configuraciones
  - IVA
  - Porcentaje de gastos indirectos
  - Logo taller

## Arquitectura de Archivos

La arquitectura de HerrerosPro sigue un patrón MVC simplificado, con una clara separación de responsabilidades:

```
📂 herrerospro/
│── 📂 public/                # 1️⃣ Ámbito Público (Sitio web)
│   ├── views/                # Todas las vistas del sitio público
│   │   ├── index.php
│   │   ├── planes.php
│   │   ├── contacto.php
│   │   ├── login.php
│   ├── assets/
│
│── 📂 plataforma/            # 2️⃣ Ámbito Plataforma (Admin de HerrerosPro)
│   ├── views/                # Todas las vistas del admin
│   │   ├── dashboard.php
│   │   ├── talleres.php
│   │   ├── usuarios.php
│   │   ├── finanzas.php
│   │   ├── solicitudes.php
│
│── 📂 talleres/              # 3️⃣ Ámbito de Talleres (Panel de cada taller)
│   ├── views/                # Todas las vistas del panel de talleres
│   │   ├── dashboard.php
│   │   ├── clientes.php
│   │   ├── proyectos.php
│   │   ├── piezas.php
│   │   ├── ordenes.php
│   │   ├── materiales.php
│   │   ├── cotizaciones.php
│   │   ├── empleados.php
│   │   ├── finanzas.php
│
│── 📂 config/                # Configuración del sistema
│   ├── database.php
│   ├── auth.php
│   ├── common.php
│
│── .htaccess                 # Reglas de Apache
│── index.php                 # Redirección al ámbito correcto
```

### Ubicación de Librerías y Assets

Dentro de cada ámbito (`public/`, `plataforma/`, `talleres/`) debe haber una carpeta `assets/` que contenga los recursos estáticos:

```
📂 assets/
│   ├── 📂 adminlte/    # Librería AdminLTE
│   ├── 📂 fontawesome/ # Íconos
│   ├── 📂 sweetalert/  # Alertas
│   ├── 📂 js/          # Scripts personalizados
│   ├── 📂 css/         # Estilos personalizados
│   ├── 📂 img/         # Imágenes
```

## Sistema de Plantillas

HerrerosPro utiliza un sistema de plantillas simple pero efectivo, basado en la inclusión de archivos PHP. Este sistema permite mantener una estructura consistente en todas las vistas, evitando la duplicación de código.

### Estructura Básica

Cada vista se compone de:

1. **Header**: Contiene las etiquetas HTML de apertura, metadatos, CSS y scripts iniciales
2. **Navbar**: Barra de navegación superior
3. **Sidebar**: Menú lateral que se adapta según el rol del usuario
4. **Contenido**: Contenido específico de cada vista
5. **Footer**: Cierre de etiquetas HTML, scripts comunes

### Implementación

#### Archivo de Layout Principal (layout.php)

```php
<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?> <!-- Carga el header común -->
</head>
<body>
    <?php include 'includes/navbar.php'; ?>  <!-- Navbar separado -->

    <div class="wrapper">
        <?php include 'includes/sidebar.php'; ?> <!-- Sidebar dinámico según usuario -->
        
        <div class="content">
            <div class="container">
                <?php include $contenido; ?> <!-- Carga la vista específica -->
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?> <!-- Scripts comunes -->
</body>
</html>
```

#### Archivo de Vista Específica (ejemplo: empleados.php)

```php
<?php
$contenido = "views/empleados_content.php"; // Carga la vista específica de empleados
include 'layout.php'; // Usa la estructura del layout con header, sidebar y footer
?>
```

#### Contenido Específico (ejemplo: empleados_content.php)

```php
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestión de Empleados</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Contenido específico de empleados -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Empleados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarEmpleado">
                            <i class="fas fa-plus"></i> Agregar Empleado
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tablaEmpleados" class="table table-bordered table-striped">
                        <!-- Tabla de empleados -->
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modales para CRUD de empleados -->
<?php include 'modals/empleados/agregar.php'; ?>
<?php include 'modals/empleados/editar.php'; ?>
<?php include 'modals/empleados/eliminar.php'; ?>
```

## Gestión de Usuarios y Permisos

HerrerosPro implementa un sistema de roles y permisos para controlar el acceso a las diferentes funcionalidades del sistema.

### Roles Principales

1. **Admin (Dueño)**: Acceso total al sistema
2. **Jefe de Taller**: Acceso a producción y materiales
3. **Secretario**: Acceso a clientes, cotizaciones, proveedores y agenda

### Tabla de Permisos

| Módulo | Admin (Dueño) | Jefe de Taller | Secretaria |
| ----- | ----- | ----- | ----- |
| **Dashboard** | ✅ Sí | ✅ Sí | ❌ No |
| **Clientes** | ✅ Sí | ❌ No | ✅ Sí |
| **Cotizaciones** | ✅ Sí | ❌ No | ✅ Solo Envío |
| **Proveedores** | ✅ Sí | ❌ Solo Ver | ✅ Sí |
| **Proyectos** | ✅ Sí | ✅ Sí | ❌ No |
| **Piezas** | ✅ Sí | ✅ Sí | ❌ No |
| **Órdenes de Producción** | ✅ Sí | ✅ Sí | ❌ No |
| **Materiales y Almacén** | ✅ Sí | ✅ Sí | ❌ No |
| **Optimización de Materiales** | ✅ Sí | ✅ Sí | ❌ No |
| **Recursos Humanos** | ✅ Sí | ❌ Solo Ver | ✅ Sí |
| **Finanzas** | ✅ Sí | ❌ No | ❌ No |
| **Agenda y Planeación** | ✅ Sí | ❌ No | ✅ Sí |
| **Gestión de Usuarios** | ✅ Sí | ❌ No | ❌ No |

### Implementación en el Sidebar

```php
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="HerrerosPro Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">HerrerosPro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <?php if ($_SESSION['rol'] == 'admin'): ?>
                    <!-- El administrador ve TODO el sistema -->
                    <li class="nav-item">
                        <a href="clientes.php" class="nav-link <?php echo $currentPage == 'clientes' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Gestión de Clientes</p>
                        </a>
                    </li>
                    <!-- Más elementos del menú para admin -->
                <?php endif; ?>
                
                <?php if ($_SESSION['rol'] == 'jefe_taller'): ?>
                    <!-- Elementos específicos para jefe de taller -->
                <?php endif; ?>
                
                <?php if ($_SESSION['rol'] == 'secretaria'): ?>
                    <!-- Elementos específicos para secretaria -->
                <?php endif; ?>
                
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Cerrar Sesión</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
```

## Componentes Principales

### Header (header.php)

```php
<?php
require_once __DIR__ . "/../config/common.php"; // Carga variables globales
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? SISTEMA_NOMBRE; ?></title>
    
    <!-- AdminLTE -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>adminlte/css/adminlte.min.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>fontawesome/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>plugins/sweetalert2/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini">
```

### Footer (footer.php)

```php
<?php require_once __DIR__ . "/../config/common.php"; ?>

</div> <!-- Cierre de contenido principal -->

<footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
        Panel de Administración
    </div>
    <strong>&copy; 2024 <a href="#">HerrerosPro</a>.</strong> Todos los derechos reservados.
</footer>

<!-- Scripts generales -->
<script src="<?= ASSETS_URL ?>js/jquery.min.js"></script>
<script src="<?= ASSETS_URL ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= ASSETS_URL ?>adminlte/js/adminlte.min.js"></script>

<!-- DataTables -->
<script src="<?= ASSETS_URL ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= ASSETS_URL ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?= ASSETS_URL ?>plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Script para el botón de colapso -->
<script>
    $(document).ready(function() {
        // Inicializar el botón de colapso
        $('[data-widget="pushmenu"]').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-collapse');
        });
    });
</script>

</body>
</html>
```

### Configuración Global (common.php)

```php
<?php
// URL base para cargar archivos estáticos y rutas absolutas
define("BASE_URL", "http://localhost/herrerospro/");

// Ruta para assets (CSS, JS, imágenes)
define("ASSETS_URL", BASE_URL . "assets/");

// Ruta para las vistas de la plataforma y los talleres
define("PLATAFORMA_VIEWS", BASE_URL . "plataforma/views/");
define("TALLERES_VIEWS", BASE_URL . "talleres/views/");

// Ruta del directorio de controladores y modelos
define("PLATAFORMA_CONTROLLERS", BASE_URL . "plataforma/controllers/");
define("TALLERES_CONTROLLERS", BASE_URL . "talleres/controllers/");
define("PLATAFORMA_MODELS", BASE_URL . "plataforma/models/");
define("TALLERES_MODELS", BASE_URL . "talleres/models/");

// Variables globales del sistema
define("SISTEMA_NOMBRE", "HerrerosPro");
define("VERSION", "1.0.0");

// Función para depuración
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

// Función para redireccionar
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Función para mostrar mensajes de error/éxito
function setMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
}

// Función para obtener mensajes
function getMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return null;
}
```

## Guía de Implementación

Para implementar esta estructura en HerrerosPro, se recomienda seguir estos pasos:

### 1. Crear la Estructura de Carpetas

```bash
# Crear directorios principales
mkdir -p public/{views,assets}
mkdir -p plataforma/{views,includes,assets,controllers,models}
mkdir -p talleres/{views,includes,assets,controllers,models}
mkdir -p config

# Crear directorios de assets
mkdir -p public/assets/{css,js,img}
mkdir -p plataforma/assets/{adminlte,fontawesome,sweetalert,js,css,img}
mkdir -p talleres/assets/{adminlte,fontawesome,sweetalert,js,css,img}
```

### 2. Implementar Archivos de Configuración

Crear los archivos de configuración en la carpeta `config/`:
- `common.php`: Variables y funciones globales
- `database.php`: Configuración de la base de datos
- `auth.php`: Funciones de autenticación

### 3. Implementar Sistema de Plantillas

Crear los archivos base en `talleres/includes/`:
- `header.php`
- `navbar.php`
- `sidebar.php`
- `footer.php`
- `layout.php`

### 4. Migrar una Vista de Ejemplo

Migrar la vista de clientes como ejemplo:
1. Crear `talleres/views/clientes_content.php` con el contenido específico
2. Crear `talleres/clientes.php` que incluya el layout

### 5. Implementar Controlador y Modelo

Crear el controlador y modelo para clientes:
- `talleres/controllers/Clientes_Controller.php`
- `talleres/models/Cliente_Model.php`

### 6. Probar y Ajustar

Probar la vista migrada y ajustar según sea necesario antes de continuar con las demás vistas.

## Estándares de Codificación

Para mantener la consistencia en el código, se recomienda seguir estos estándares:

### Nomenclatura

- **Archivos**: Usar snake_case para vistas y PascalCase para controladores y modelos
- **Variables**: Usar camelCase
- **Constantes**: Usar UPPER_CASE
- **Funciones**: Usar camelCase

### Indentación y Formato

- Usar 4 espacios para la indentación
- Usar llaves en nueva línea para clases y funciones
- Usar comentarios descriptivos para secciones importantes

### Seguridad

- Escapar todas las salidas con `htmlspecialchars()`
- Usar consultas preparadas para interactuar con la base de datos
- Validar todas las entradas de usuario

## Seguridad en Formularios

Para garantizar la seguridad de HerrerosPro y proteger el sistema contra ataques comunes, se deben implementar las siguientes medidas de seguridad en todos los formularios:

### Medidas de Seguridad Implementadas

HerrerosPro implementa un conjunto completo de medidas de seguridad para proteger todos los formularios de la aplicación:

1. **Protección CSRF (Cross-Site Request Forgery)**
   - Generación de tokens únicos por sesión
   - Verificación obligatoria en cada envío de formulario
   - Regeneración de tokens después de cada envío exitoso

2. **Protección XSS (Cross-Site Scripting)**
   - Sanitización de todas las entradas con `htmlspecialchars()`
   - Validación estricta con expresiones regulares
   - Escape de salida en la presentación de datos

3. **Protección contra Inyección SQL**
   - Uso exclusivo de consultas preparadas
   - Validación de tipos de datos
   - Sanitización de entradas antes de procesamiento

4. **Protección contra Bots y Spam**
   - Campo "honeypot" oculto para detectar bots
   - Límites de intentos por IP (máximo 5 por hora)
   - Registro de intentos de contacto para monitoreo

5. **Validación Mejorada de Datos**
   - Validación del lado del cliente con JavaScript
   - Validación del lado del servidor con PHP
   - Patrones específicos para cada tipo de campo
   - Límites de longitud para prevenir ataques DoS

6. **Experiencia de Usuario Mejorada**
   - Feedback visual inmediato sobre validez de campos
   - Contadores de caracteres para campos con límite
   - Mensajes de error específicos y descriptivos
   - Persistencia de datos en caso de error

7. **Protección de Archivos Sensibles**
   - Directorio de logs protegido con .htaccess
   - Prevención de listado de directorios
   - Denegación de acceso web a archivos .log

### Prevención de Inyecciones SQL

Las inyecciones SQL son uno de los ataques más comunes en aplicaciones web. Para prevenirlas:

1. **Usar siempre consultas preparadas**:
   ```php
   // INCORRECTO - Vulnerable a inyección SQL
   $query = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
   
   // CORRECTO - Usando consultas preparadas
   $query = "SELECT * FROM usuarios WHERE username = :username AND password = :password";
   $params = [':username' => $username, ':password' => $password];
   $result = $db->query($query, $params);
   ```

2. **Validar el tipo de datos**:
   ```php
   // Validar que un ID sea numérico
   if (!is_numeric($id)) {
       // Manejar error
       mostrarError('Error', 'ID inválido');
       exit;
   }
   ```

3. **Implementar validación en el servidor**:
   ```php
   // Ejemplo de validación de email
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       // Email inválido
       mostrarError('Error', 'Email inválido');
       return false;
   }
   ```

4. **Implementar una función de sanitización**:
   ```php
   function sanitizarInput($data) {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
   }
   
   // Uso
   $nombre = sanitizarInput($_POST['nombre']);
   ```

### Prevención de Ataques XSS (Cross-Site Scripting)

Los ataques XSS permiten a los atacantes inyectar scripts maliciosos en páginas web. Para prevenirlos:

1. **Escapar siempre la salida de datos**:
   ```php
   // INCORRECTO - Vulnerable a XSS
   echo "Bienvenido, " . $_POST['nombre'];
   
   // CORRECTO - Escapando la salida
   echo "Bienvenido, " . htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
   ```

2. **Usar la política de Content Security Policy (CSP)**:
   ```php
   // En el header.php
   header("Content-Security-Policy: default-src 'self'; script-src 'self' https://trusted-cdn.com");
   ```

3. **Validar y sanitizar entradas de formularios con JavaScript**:
   ```javascript
   // Función para sanitizar input antes de enviar
   function sanitizarFormulario() {
       const inputs = document.querySelectorAll('input, textarea');
       inputs.forEach(input => {
           // Eliminar caracteres potencialmente peligrosos
           input.value = input.value.replace(/<script.*?>.*?<\/script>/gi, '');
       });
       return true;
   }
   
   // Aplicar a formularios
   document.getElementById('formCliente').onsubmit = function() {
       return sanitizarFormulario();
   };
   ```

4. **Implementar validación de formularios con clases de Bootstrap**:
   ```javascript
   // Validación de formulario con Bootstrap
   (function() {
       'use strict';
       window.addEventListener('load', function() {
           var forms = document.getElementsByClassName('needs-validation');
           Array.prototype.filter.call(forms, function(form) {
               form.addEventListener('submit', function(event) {
                   if (form.checkValidity() === false) {
                       event.preventDefault();
                       event.stopPropagation();
                   }
                   form.classList.add('was-validated');
               }, false);
           });
       }, false);
   })();
   ```

### Implementación de CSRF Protection

Para proteger contra ataques CSRF (Cross-Site Request Forgery):

1. **Generar y verificar tokens CSRF**:
   ```php
   // Generar token CSRF en session
   function generarCSRFToken() {
       if (!isset($_SESSION['csrf_token'])) {
           $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
       }
       return $_SESSION['csrf_token'];
   }
   
   // En el formulario
   <form method="POST" action="procesar.php">
       <input type="hidden" name="csrf_token" value="<?php echo generarCSRFToken(); ?>">
       <!-- Resto del formulario -->
   </form>
   
   // Verificar token en el procesamiento
   function verificarCSRFToken($token) {
       if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
           // Token inválido
           header('Location: error.php?msg=csrf_error');
           exit;
       }
       return true;
   }
   
   // Uso
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       verificarCSRFToken($_POST['csrf_token']);
       // Procesar formulario...
   }
   ```

### Ejemplo Completo de Formulario Seguro

```php
<!-- Formulario con medidas de seguridad -->
<form id="formCliente" method="POST" action="controllers/clientes_controller.php" class="needs-validation" novalidate>
    <!-- Token CSRF -->
    <input type="hidden" name="csrf_token" value="<?php echo generarCSRFToken(); ?>">
    <input type="hidden" name="action" value="create">
    
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required 
               pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" 
               title="Solo se permiten letras y espacios">
        <div class="invalid-feedback">
            Por favor ingrese un nombre válido.
        </div>
    </div>
    
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
        <div class="invalid-feedback">
            Por favor ingrese un email válido.
        </div>
    </div>
    
    <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="tel" class="form-control" id="telefono" name="telefono" 
               pattern="[0-9]{10}" title="Número de 10 dígitos">
        <div class="invalid-feedback">
            Por favor ingrese un teléfono válido (10 dígitos).
        </div>
    </div>
    
    <button type="submit" class="btn btn-info">
        <i class="fas fa-save"></i> Guardar
    </button>
</form>

<script>
// Validación del lado del cliente
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var form = document.getElementById('formCliente');
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                
                // Mostrar mensaje con SweetAlert2
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos Requeridos',
                    text: 'Por favor, complete todos los campos obligatorios correctamente',
                    confirmButtonColor: '#17a2b8'
                });
            } else {
                // Sanitizar inputs antes de enviar
                sanitizarFormulario();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    function sanitizarFormulario() {
        const inputs = document.querySelectorAll('#formCliente input:not([type=hidden])');
        inputs.forEach(input => {
            input.value = input.value.trim().replace(/<[^>]*>/g, '');
        });
        return true;
    }
})();
</script>
```

### Procesamiento Seguro en el Controlador

```php
// En el controlador (Clientes_Controller.php)
public function create()
{
    // Verificar método y token CSRF
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('error.php?msg=method_not_allowed');
        exit;
    }
    
    verificarCSRFToken($_POST['csrf_token']);
    
    // Sanitizar y validar entradas
    $nombre = sanitizarInput($_POST['nombre']);
    $email = sanitizarInput($_POST['email']);
    $telefono = sanitizarInput($_POST['telefono']);
    
    // Validaciones adicionales
    if (empty($nombre) || !preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/", $nombre)) {
        setMessage('error', 'Nombre inválido');
        redirect('clientes.php');
        exit;
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setMessage('error', 'Email inválido');
        redirect('clientes.php');
        exit;
    }
    
    if (!empty($telefono) && !preg_match("/^[0-9]{10}$/", $telefono)) {
        setMessage('error', 'Teléfono inválido');
        redirect('clientes.php');
        exit;
    }
    
    // Procesar datos validados
    $result = $this->model->create([
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => $telefono
    ]);
    
    if ($result) {
        setMessage('success', 'Cliente creado correctamente');
    } else {
        setMessage('error', 'Error al crear el cliente');
    }
    
    redirect('clientes.php');
}
```

### Caso de Estudio: Seguridad en el Formulario de Contacto

El formulario de contacto de HerrerosPro implementa todas las medidas de seguridad mencionadas anteriormente y sirve como ejemplo de referencia para otros formularios del sistema:

#### Vista del Formulario (`contacto_content.php`)

```php
<!-- Token CSRF para prevenir ataques CSRF -->
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

<!-- Campo honeypot para detectar bots - este campo debe estar oculto con CSS -->
<div style="display:none;">
    <input type="text" name="honeypot" id="honeypot" autocomplete="off">
</div>

<!-- Ejemplo de campo con validación estricta -->
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

<!-- Contador de caracteres para prevenir ataques DoS -->
<div class="form-text text-end">
    <span id="charCount">0</span>/2000 caracteres
</div>
```

#### Controlador (`contact_controller.php`)

```php
// Verificar token CSRF para prevenir ataques CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Token CSRF inválido, posible ataque
    error_log("Intento de ataque CSRF detectado desde IP: " . $_SERVER['REMOTE_ADDR']);
    header('Location: ' . PUBLIC_URL . 'views/contacto.php?error=2');
    exit;
}

// Sanitizar datos para prevenir XSS
$nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8') : '';

// Validación estricta con expresiones regulares
if (empty($nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-\']{2,50}$/', $nombre)) {
    $errores[] = 'El nombre es obligatorio y debe contener solo letras y espacios (2-50 caracteres)';
}

// Verificación anti-spam con honeypot
if (isset($_POST['honeypot']) && !empty($_POST['honeypot'])) {
    // Campo honeypot completado, probablemente es un bot
    error_log("Posible spam detectado desde IP: " . $_SERVER['REMOTE_ADDR']);
    // Simulamos éxito pero no procesamos el mensaje
    header('Location: ' . PUBLIC_URL . 'views/contacto.php?enviado=1');
    exit;
}

// Registro de actividad para monitoreo de seguridad
$log_message = date('Y-m-d H:i:s') . " | IP: " . $_SERVER['REMOTE_ADDR'] . " | Nombre: $nombre | Email: $email | Asunto: $asunto";
error_log($log_message, 3, __DIR__ . '/../../logs/contact_attempts.log');

// Límite de intentos por IP para prevenir spam
$max_attempts = 5; // Máximo 5 intentos por hora
$ip = $_SERVER['REMOTE_ADDR'];
$hour_key = date('YmdH');
$attempts_key = "contact_attempts_{$ip}_{$hour_key}";

if (!isset($_SESSION[$attempts_key])) {
    $_SESSION[$attempts_key] = 1;
} else {
    $_SESSION[$attempts_key]++;
}

if ($_SESSION[$attempts_key] > $max_attempts) {
    error_log("Límite de intentos de contacto excedido para IP: $ip");
    // Acción adicional como bloqueo temporal
}

// Regenerar token CSRF para la próxima solicitud
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
```

#### Protección de Logs con .htaccess

```apache
# Denegar acceso a todos los archivos en el directorio logs
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>

# Para versiones antiguas de Apache
<IfModule !mod_authz_core.c>
    Order deny,allow
    Deny from all
</IfModule>

# Prevenir listado de directorios
Options -Indexes

# Denegar acceso a archivos específicos
<FilesMatch "\.log$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order deny,allow
        Deny from all
    </IfModule>
</FilesMatch>
```

Estas implementaciones de seguridad deben aplicarse de manera consistente en todos los formularios de HerrerosPro para garantizar la protección integral del sistema y los datos de los usuarios.

### Conclusión y Recomendaciones de Seguridad

La implementación de estas medidas de seguridad no es opcional sino un requisito fundamental para todos los formularios en HerrerosPro. Cada desarrollador debe asegurarse de que:

1. **Todos los formularios incluyan protección CSRF** - Sin excepciones, cada formulario debe generar y validar tokens CSRF.

2. **Toda entrada de usuario sea sanitizada y validada** - Aplicar validación tanto en el cliente como en el servidor, con patrones específicos para cada tipo de dato.

3. **Se implementen límites y monitoreo** - Establecer límites de intentos, longitud de campos y registrar actividades sospechosas.

4. **Se protejan los archivos sensibles** - Utilizar .htaccess para proteger logs y archivos de configuración.

5. **Se realicen pruebas de seguridad periódicas** - Verificar regularmente la efectividad de las medidas implementadas.

La seguridad es un proceso continuo, no un estado final. Las medidas deben revisarse y actualizarse regularmente para adaptarse a nuevas amenazas y vulnerabilidades.

## Diseño de Interfaces y Componentes Visuales

Para mantener una interfaz de usuario consistente y profesional en todo HerrerosPro, se deben seguir estos lineamientos de diseño:

### Modales

Los modales son componentes clave para interacciones como crear, editar o eliminar registros. Deben seguir estas especificaciones:

1. **Cabecera**:
   - Color de fondo: `#17a2b8` (turquesa)
   - Color de texto: `#FFFFFF` (blanco)
   - Título alineado a la izquierda
   - Botón de cierre (X) alineado a la derecha

2. **Cuerpo**:
   - Organizado en secciones o "tarjetas" con títulos descriptivos
   - Cada sección debe tener un encabezado claro
   - Campos agrupados lógicamente (información personal, contacto, dirección, etc.)

3. **Pie**:
   - Botón "Cancelar": Gris (`#6c757d`)
   - Botón "Guardar": Turquesa (`#17a2b8`)
   - Alineados a la derecha

### Mensajes y Alertas

Los mensajes y alertas son esenciales para proporcionar retroalimentación al usuario. HerrerosPro utiliza SweetAlert2 para mostrar mensajes atractivos y personalizados:

1. **Mensajes de Validación**:
   - **Diseño**: Modal centrado con fondo blanco y borde sutil
   - **Icono**: Signo de exclamación en círculo naranja para advertencias
   - **Título**: Texto descriptivo en gris oscuro, centrado, fuente más grande
   - **Mensaje**: Texto explicativo en gris medio, centrado
   - **Botón**: Botón "OK" con fondo turquesa (`#17a2b8`), texto blanco, centrado

2. **Tipos de Mensajes**:
   - **Validación de Campos**: "Campos Requeridos" - Para formularios incompletos
   - **Confirmación**: "¿Está seguro?" - Para acciones destructivas
   - **Éxito**: "Operación Exitosa" - Para acciones completadas
   - **Error**: "Error en la Operación" - Para fallos del sistema

3. **Implementación con SweetAlert2**:
   ```javascript
   // Función para mostrar mensaje de validación
   function mostrarValidacion(titulo, mensaje) {
     Swal.fire({
       icon: 'warning',
       title: titulo,
       text: mensaje,
       confirmButtonColor: '#17a2b8',
       confirmButtonText: 'OK'
     });
   }
   
   // Función para mostrar confirmación
   function mostrarConfirmacion(titulo, mensaje, callback) {
     Swal.fire({
       icon: 'question',
       title: titulo,
       text: mensaje,
       showCancelButton: true,
       confirmButtonColor: '#17a2b8',
       cancelButtonColor: '#6c757d',
       confirmButtonText: 'Sí, continuar',
       cancelButtonText: 'Cancelar'
     }).then((result) => {
       if (result.isConfirmed) {
         callback();
       }
     });
   }
   
   // Función para mostrar éxito
   function mostrarExito(titulo, mensaje) {
     Swal.fire({
       icon: 'success',
       title: titulo,
       text: mensaje,
       confirmButtonColor: '#17a2b8'
     });
   }
   
   // Función para mostrar error
   function mostrarError(titulo, mensaje) {
     Swal.fire({
       icon: 'error',
       title: titulo,
       text: mensaje,
       confirmButtonColor: '#17a2b8'
     });
   }
   ```

4. **Ejemplos de Uso**:
   ```javascript
   // Validación de formulario
   $('#formCliente').on('submit', function(e) {
     // Verificar campos requeridos
     if ($('#nombre').val() === '') {
       e.preventDefault();
       mostrarValidacion('Campos Requeridos', 'Por favor, complete todos los campos obligatorios');
     }
   });
   
   // Confirmación para eliminar
   $('.btn-eliminar').on('click', function(e) {
     e.preventDefault();
     const id = $(this).data('id');
     
     mostrarConfirmacion('¿Está seguro?', '¿Realmente desea eliminar este registro? Esta acción no se puede deshacer.', function() {
       // Código para eliminar el registro
       $.ajax({
         url: 'controllers/eliminar.php',
         type: 'POST',
         data: { id: id },
         success: function(response) {
           if (response.success) {
             mostrarExito('¡Eliminado!', 'El registro ha sido eliminado correctamente.');
             // Actualizar la tabla o redireccionar
           } else {
             mostrarError('Error', 'No se pudo eliminar el registro.');
           }
         }
       });
     });
   });
   ```

5. **Personalización de SweetAlert2 para HerrerosPro**:
   ```javascript
   // Configuración global de SweetAlert2 para HerrerosPro
   const SwalHerrerosPro = Swal.mixin({
     customClass: {
       confirmButton: 'btn btn-info',
       cancelButton: 'btn btn-secondary'
     },
     buttonsStyling: false,
     confirmButtonColor: '#17a2b8',
     cancelButtonColor: '#6c757d'
   });
   
   // Uso de la configuración personalizada
   function mostrarMensajePersonalizado(titulo, mensaje) {
     SwalHerrerosPro.fire({
       icon: 'warning',
       title: titulo,
       text: mensaje
     });
   }
   ```

6. **Notificaciones Toast con SweetAlert2**:
   ```javascript
   // Función para mostrar notificaciones tipo toast
   function mostrarNotificacion(titulo, icono) {
     const Toast = Swal.mixin({
       toast: true,
       position: 'top-end',
       showConfirmButton: false,
       timer: 3000,
       timerProgressBar: true,
       didOpen: (toast) => {
         toast.addEventListener('mouseenter', Swal.stopTimer)
         toast.addEventListener('mouseleave', Swal.resumeTimer)
       }
     });
     
     Toast.fire({
       icon: icono, // 'success', 'error', 'warning', 'info'
       title: titulo
     });
   }
   
   // Ejemplos de uso
   function notificarExito(mensaje) {
     mostrarNotificacion(mensaje, 'success');
   }
   
   function notificarError(mensaje) {
     mostrarNotificacion(mensaje, 'error');
   }
   
   // Uso
   $('#btnGuardar').on('click', function() {
     // Código para guardar...
     notificarExito('Cliente guardado correctamente');
   });
   ```

7. **Estilos Personalizados para SweetAlert2**:
   ```css
   /* Personalización adicional de SweetAlert2 para HerrerosPro */
   .swal2-popup {
     border-radius: 10px;
   }
   
   .swal2-title {
     color: #5a5a5a !important;
   }
   
   .swal2-content {
     color: #6c757d !important;
   }
   
   .swal2-icon.swal2-warning {
     border-color: #f8a556 !important;
     color: #f8a556 !important;
   }
   ```

Esta implementación con SweetAlert2 proporciona una experiencia de usuario moderna y consistente para todos los mensajes y alertas en HerrerosPro, manteniendo la estética visual definida en las especificaciones de diseño.

### Iconos

Los iconos deben usarse de manera consistente para mejorar la experiencia del usuario:

1. **Secciones de formularios**:
   - Información Personal: `<i class="fas fa-id-card"></i>`
   - Contacto: `<i class="fas fa-phone-alt"></i>`
   - Dirección: `<i class="fas fa-map-marker-alt"></i>`
   - Notas: `<i class="fas fa-sticky-note"></i>`
   - Email: `<i class="fas fa-envelope"></i>`
   - Empresa: `<i class="fas fa-building"></i>`
   - Cumpleaños: `<i class="fas fa-birthday-cake"></i>`

2. **Acciones**:
   - Agregar: `<i class="fas fa-plus"></i>`
   - Editar: `<i class="fas fa-edit"></i>`
   - Eliminar: `<i class="fas fa-trash-alt"></i>`
   - Ver: `<i class="fas fa-eye"></i>`
   - Guardar: `<i class="fas fa-save"></i>`
   - Cancelar: `<i class="fas fa-times"></i>`

### Formularios

Los formularios deben seguir estas pautas:

1. **Campos**:
   - Etiquetas claras y descriptivas
   - Iconos relevantes junto a las etiquetas
   - Validación visual (bordes rojos para errores, verdes para válidos)
   - Mensajes de error específicos

2. **Agrupación**:
   - Agrupar campos relacionados en secciones con títulos descriptivos
   - Usar bordes sutiles para separar secciones

3. **Responsividad**:
   - Campos deben adaptarse a diferentes tamaños de pantalla
   - En móviles, los campos ocupan el ancho completo
   - En tablets/desktop, usar layout de múltiples columnas cuando sea apropiado

### Paleta de Colores

Para mantener consistencia visual en toda la aplicación, se debe usar la siguiente paleta de colores:

1. **Colores Principales**:
   - Turquesa (Primario): `#17a2b8` - Para cabeceras, botones principales y acentos
   - Gris Oscuro: `#343a40` - Para texto principal y sidebar
   - Blanco: `#FFFFFF` - Para fondos y texto sobre colores oscuros

2. **Colores de Estado**:
   - Éxito: `#28a745` - Para mensajes de éxito y estados positivos
   - Advertencia: `#ffc107` - Para advertencias y estados de precaución
   - Peligro: `#dc3545` - Para errores y acciones destructivas
   - Info: `#17a2b8` - Para información y estados neutrales

3. **Colores de Fondo**:
   - Fondo Principal: `#f8f9fa` - Para el fondo general de la aplicación
   - Fondo de Tarjetas: `#FFFFFF` - Para tarjetas y contenedores
   - Fondo de Sidebar: `#343a40` - Para el sidebar

## Implementación de DataTables

Las tablas de datos son un componente fundamental en HerrerosPro para mostrar listados de información. Todas las tablas deben implementarse usando la biblioteca DataTables y seguir estas especificaciones:

### Configuración Estándar

```javascript
$('#tablaClientes').DataTable({
    "responsive": true,
    "autoWidth": false,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
    },
    "pageLength": 10,
    "order": [[1, 'asc']], // Ordenar por la segunda columna (nombre) ascendente
    "columnDefs": [
        { "orderable": false, "targets": [0, -1] } // No ordenar primera y última columna
    ]
});
```

### Botones de Acción Estandarizados

Los botones de acción deben seguir un formato consistente en todas las tablas:

1. **Ubicación**: Siempre en la última columna
2. **Tamaño**: Usar la clase `btn-sm` para botones pequeños
3. **Agrupación**: Agrupar en un contenedor con clase `btn-group`
4. **Colores**:
   - Ver: Azul (`btn-info`) - `<i class="fas fa-eye"></i>`
   - Editar: Amarillo (`btn-warning`) - `<i class="fas fa-edit"></i>`
   - Eliminar: Rojo (`btn-danger`) - `<i class="fas fa-trash-alt"></i>`
   - Imprimir/PDF: Gris (`btn-secondary`) - `<i class="fas fa-print"></i>`
   - Otros: Turquesa (`btn-info`) - Icono apropiado

5. **Tooltips**: Todos los botones deben incluir tooltips para mejorar la usabilidad

### Estructura HTML Estándar

```html
<table id="tablaClientes" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th width="15%">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $index => $cliente): ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
            <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
            <td>
                <div class="btn-group">
                    <!-- Botón Ver -->
                    <button type="button" class="btn btn-info btn-sm" 
                            data-toggle="tooltip" title="Ver detalles"
                            onclick="verCliente(<?php echo $cliente['id']; ?>)">
                        <i class="fas fa-eye"></i>
                    </button>
                    
                    <!-- Botón Editar -->
                    <button type="button" class="btn btn-warning btn-sm" 
                            data-toggle="tooltip" title="Editar"
                            onclick="editarCliente(<?php echo $cliente['id']; ?>)">
                        <i class="fas fa-edit"></i>
                    </button>
                    
                    <!-- Botón Eliminar -->
                    <button type="button" class="btn btn-danger btn-sm" 
                            data-toggle="tooltip" title="Eliminar"
                            onclick="confirmarEliminar(<?php echo $cliente['id']; ?>)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

### Funciones JavaScript para Acciones

```javascript
// Ver detalles de cliente
function verCliente(id) {
    // Cargar datos del cliente mediante AJAX
    $.ajax({
        url: 'controllers/clientes_controller.php',
        type: 'GET',
        data: { action: 'get', id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Llenar el modal con los datos
                $('#modalVerCliente .modal-title').text('Detalles del Cliente: ' + response.data.nombre);
                $('#detalleNombre').text(response.data.nombre);
                $('#detalleEmail').text(response.data.email);
                $('#detalleTelefono').text(response.data.telefono);
                
                // Mostrar el modal
                $('#modalVerCliente').modal('show');
            } else {
                mostrarError('Error', 'No se pudo cargar la información del cliente');
            }
        },
        error: function() {
            mostrarError('Error', 'Error de conexión al servidor');
        }
    });
}

// Editar cliente
function editarCliente(id) {
    // Similar a verCliente, pero cargando datos en un formulario editable
    $.ajax({
        url: 'controllers/clientes_controller.php',
        type: 'GET',
        data: { action: 'get', id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Llenar el formulario con los datos
                $('#formEditarCliente #id').val(response.data.id);
                $('#formEditarCliente #nombre').val(response.data.nombre);
                $('#formEditarCliente #email').val(response.data.email);
                $('#formEditarCliente #telefono').val(response.data.telefono);
                
                // Mostrar el modal
                $('#modalEditarCliente').modal('show');
            } else {
                mostrarError('Error', 'No se pudo cargar la información del cliente');
            }
        },
        error: function() {
            mostrarError('Error', 'Error de conexión al servidor');
        }
    });
}

// Confirmar eliminación
function confirmarEliminar(id) {
    mostrarConfirmacion(
        '¿Está seguro?', 
        '¿Realmente desea eliminar este cliente? Esta acción no se puede deshacer.',
        function() {
            // Si el usuario confirma, proceder con la eliminación
            eliminarCliente(id);
        }
    );
}

// Eliminar cliente
function eliminarCliente(id) {
    $.ajax({
        url: 'controllers/clientes_controller.php',
        type: 'POST',
        data: { 
            action: 'delete', 
            id: id,
            csrf_token: $('#csrf_token').val()
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarExito('¡Eliminado!', 'El cliente ha sido eliminado correctamente');
                // Recargar la tabla
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            } else {
                mostrarError('Error', response.message || 'No se pudo eliminar el cliente');
            }
        },
        error: function() {
            mostrarError('Error', 'Error de conexión al servidor');
        }
    });
}
```

### Inicialización de Tooltips

```javascript
// Inicializar tooltips para los botones de acción
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    
    // Destruir tooltips cuando se cierra un modal para evitar duplicados
    $('.modal').on('hidden.bs.modal', function () {
        $('.tooltip').tooltip('dispose');
    });
});
```

### Botones de Exportación

Para tablas que requieran exportación de datos, se debe usar la siguiente configuración:

```javascript
$('#tablaClientes').DataTable({
    // Configuración básica...
    "dom": 'Bfrtip',
    "buttons": [
        {
            extend: 'excel',
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'btn btn-success btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3] // Excluir columna de acciones
            }
        },
        {
            extend: 'pdf',
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'btn btn-danger btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3] // Excluir columna de acciones
            }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Imprimir',
            className: 'btn btn-info btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3] // Excluir columna de acciones
            }
        }
    ]
});
```

### Estilos Personalizados

```css
/* Estilos para DataTables */
.dataTables_wrapper .btn-group .btn {
    margin-right: 2px;
}

.dataTables_wrapper .dataTables_filter input {
    border-radius: 4px;
    padding: 5px;
    border: 1px solid #ced4da;
}

.dataTables_wrapper .dataTables_length select {
    border-radius: 4px;
    padding: 5px;
    border: 1px solid #ced4da;
}

/* Estilos para botones de exportación */
.dt-buttons {
    margin-bottom: 15px;
}

.dt-buttons .btn {
    margin-right: 5px;
}
```

### Manejo de Datos Vacíos

```javascript
$('#tablaClientes').DataTable({
    // Configuración básica...
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json",
        "emptyTable": "No hay clientes registrados",
        "zeroRecords": "No se encontraron resultados"
    }
});
```

### Columnas Responsivas

```javascript
$('#tablaClientes').DataTable({
    // Configuración básica...
    "responsive": true,
    "columnDefs": [
        { "responsivePriority": 1, "targets": 1 }, // Nombre (siempre visible)
        { "responsivePriority": 2, "targets": -1 }, // Acciones (siempre visible)
        { "responsivePriority": 3, "targets": 0 } // ID
    ]
});
```

### Ubicación Correcta para la Inicialización

La inicialización de DataTables debe realizarse en un lugar específico para garantizar que funcione correctamente:

1. **Ubicación en el Archivo**: Al final de cada vista específica, justo antes del cierre del contenido principal.

2. **Estructura Recomendada**:
```php
<!-- Contenido de la vista (ejemplo: clientes_content.php) -->
<div class="content-wrapper">
    <section class="content-header">
        <!-- Encabezado de la página -->
    </section>
    
    <section class="content">
        <div class="container-fluid">
            <!-- Tabla y otros elementos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Clientes</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente">
                            <i class="fas fa-plus"></i> Agregar Cliente
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tablaClientes" class="table table-bordered table-striped">
                        <!-- Contenido de la tabla -->
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modales para CRUD -->
<?php include 'modals/clientes/agregar.php'; ?>
<?php include 'modals/clientes/editar.php'; ?>
<?php include 'modals/clientes/ver.php'; ?>

<!-- Scripts específicos para esta vista -->
<script>
$(function() {
    // Inicialización de DataTables
    $('#tablaClientes').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "pageLength": 10,
        "order": [[1, 'asc']]
    });
    
    // Inicialización de tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Otras inicializaciones específicas de la vista
});

// Funciones para acciones CRUD
function verCliente(id) {
    // Código para ver cliente
}

function editarCliente(id) {
    // Código para editar cliente
}

function confirmarEliminar(id) {
    // Código para confirmar eliminación
}
</script>
```

3. **Consideraciones Importantes**:
   - Asegurarse de que jQuery y la biblioteca DataTables estén cargados antes de la inicialización
   - Usar el evento `$(function() { ... })` o `$(document).ready(function() { ... })` para garantizar que el DOM esté completamente cargado
   - Mantener todas las inicializaciones y funciones relacionadas con la tabla en el mismo bloque de script
   - No mezclar la inicialización de DataTables con otro código JavaScript no relacionado

4. **Evitar Inicializaciones Duplicadas**:
```javascript
// Verificar si la tabla ya está inicializada
if ($.fn.DataTable.isDataTable('#tablaClientes')) {
    // Destruir la instancia existente
    $('#tablaClientes').DataTable().destroy();
}

// Inicializar la tabla
$('#tablaClientes').DataTable({
    // Configuración...
});
```

5. **Actualización Dinámica**:
```javascript
// Función para recargar datos sin recargar la página
function recargarTabla() {
    $.ajax({
        url: 'controllers/clientes_controller.php',
        type: 'GET',
        data: { action: 'list' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var tabla = $('#tablaClientes').DataTable();
                tabla.clear();
                tabla.rows.add(response.data);
                tabla.draw();
            }
        }
    });
}
```

Siguiendo estas especificaciones para todas las DataTables en HerrerosPro, se logrará una experiencia de usuario consistente y profesional en todas las vistas que muestren listados de datos.

## Ejemplos de Código

### Ejemplo de Validación de Formularios con Seguridad Mejorada

El siguiente ejemplo muestra cómo implementar un formulario con todas las medidas de seguridad recomendadas:

#### HTML del Formulario

```php
<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Recuperar datos del formulario en caso de error
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [
    'nombre' => '',
    'email' => '',
    'telefono' => '',
    'mensaje' => ''
];

// Recuperar errores
$formErrors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];

// Limpiar datos de sesión después de usarlos
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<form id="secureForm" action="procesar.php" method="POST" class="needs-validation" novalidate>
    <!-- Token CSRF -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    
    <!-- Campo honeypot para detectar bots -->
    <div style="display:none;">
        <input type="text" name="honeypot" id="honeypot" autocomplete="off">
    </div>
    
    <!-- Campo nombre con validación estricta -->
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
    
    <!-- Campo email con validación -->
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
    
    <!-- Campo mensaje con contador de caracteres -->
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
    
    <!-- Botón de envío con prevención de múltiples clics -->
    <button type="submit" class="btn btn-primary" id="submitBtn">
        <i class="fas fa-paper-plane me-2"></i>Enviar
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('secureForm');
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
            let isValid = true;
            
            // Validar campos requeridos y patrones
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            });
            
            // Si no es válido, prevenir envío
            if (!isValid) {
                event.preventDefault();
                return;
            }
            
            // Deshabilitar botón para prevenir múltiples envíos
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Enviando...';
        });
    }
});
</script>
```

#### Procesamiento del Formulario (procesar.php)

```php
<?php
// Incluir configuración común
require_once 'config/common.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Token CSRF inválido, posible ataque
        error_log("Intento de ataque CSRF detectado desde IP: " . $_SERVER['REMOTE_ADDR']);
        header('Location: formulario.php?error=2');
        exit;
    }
    
    // Verificación anti-spam con honeypot
    if (isset($_POST['honeypot']) && !empty($_POST['honeypot'])) {
        // Campo honeypot completado, probablemente es un bot
        error_log("Posible spam detectado desde IP: " . $_SERVER['REMOTE_ADDR']);
        // Simulamos éxito pero no procesamos
        header('Location: formulario.php?enviado=1');
        exit;
    }
    
    // Obtener y sanitizar los datos del formulario
    $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8') : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $mensaje = isset($_POST['mensaje']) ? htmlspecialchars(trim($_POST['mensaje']), ENT_QUOTES, 'UTF-8') : '';
    
    // Validar datos
    $errores = [];
    
    // Validación para nombre
    if (empty($nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-\']{2,50}$/', $nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio y debe contener solo letras y espacios (2-50 caracteres)';
    }
    
    // Validación para email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El email no es válido';
    }
    
    // Validación para mensaje
    if (empty($mensaje) || strlen($mensaje) > 2000) {
        $errores['mensaje'] = 'El mensaje es obligatorio y no debe exceder los 2000 caracteres';
    }
    
    // Si hay errores, redirigir de vuelta al formulario
    if (!empty($errores)) {
        $_SESSION['form_errors'] = $errores;
        $_SESSION['form_data'] = [
            'nombre' => $nombre,
            'email' => $email,
            'mensaje' => $mensaje
        ];
        
        header('Location: formulario.php?error=1');
        exit;
    }
    
    // Registrar el intento en un log para monitoreo
    $log_message = date('Y-m-d H:i:s') . " | IP: " . $_SERVER['REMOTE_ADDR'] . " | Nombre: $nombre | Email: $email";
    error_log($log_message, 3, __DIR__ . '/logs/form_submissions.log');
    
    // Límite de intentos por IP
    $max_attempts = 5; // Máximo 5 intentos por hora
    $ip = $_SERVER['REMOTE_ADDR'];
    $hour_key = date('YmdH');
    $attempts_key = "form_attempts_{$ip}_{$hour_key}";
    
    if (!isset($_SESSION[$attempts_key])) {
        $_SESSION[$attempts_key] = 1;
    } else {
        $_SESSION[$attempts_key]++;
    }
    
    if ($_SESSION[$attempts_key] > $max_attempts) {
        error_log("Límite de intentos excedido para IP: $ip");
        header('Location: formulario.php?error=limit');
        exit;
    }
    
    // Procesar los datos validados
    // ...
    
    // Generar nuevo token CSRF para la próxima solicitud
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    
    // Redirigir a una página de confirmación
    header('Location: formulario.php?enviado=1');
    exit;
} else {
    // Si no es una petición POST, redirigir
    header('Location: formulario.php');
    exit;
}
?>
```

Este ejemplo implementa todas las medidas de seguridad recomendadas y puede servir como plantilla para cualquier formulario en HerrerosPro.

### Ejemplo de Controlador (Clientes_Controller.php)

```php
<?php
require_once __DIR__ . '/../models/Cliente_Model.php';

class Clientes_Controller
{
    private $model;
    
    public function __construct()
    {
        $this->model = new Cliente_Model();
    }
    
    public function index()
    {
        $clientes = $this->model->getAll();
        include 'views/clientes_content.php';
    }
    
    public function create()
    {
        // Validar datos del formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = htmlspecialchars($_POST['nombre']);
            $email = htmlspecialchars($_POST['email']);
            $telefono = htmlspecialchars($_POST['telefono']);
            
            $result = $this->model->create([
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono
            ]);
            
            if ($result) {
                setMessage('success', 'Cliente creado correctamente');
            } else {
                setMessage('error', 'Error al crear el cliente');
            }
            
            redirect('clientes.php');
        }
    }
    
    public function update()
    {
        // Lógica para actualizar cliente
    }
    
    public function delete()
    {
        // Lógica para eliminar cliente
    }
}
```

### Ejemplo de Modelo (Cliente_Model.php)

```php
<?php
require_once __DIR__ . '/../../config/database.php';

class Cliente_Model
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function getAll()
    {
        $query = "SELECT * FROM clientes WHERE id_taller = :id_taller ORDER BY nombre";
        $params = [':id_taller' => $_SESSION['id_taller']];
        
        return $this->db->query($query, $params);
    }
    
    public function getById($id)
    {
        $query = "SELECT * FROM clientes WHERE id_cliente = :id AND id_taller = :id_taller";
        $params = [
            ':id' => $id,
            ':id_taller' => $_SESSION['id_taller']
        ];
        
        return $this->db->queryOne($query, $params);
    }
    
    public function create($data)
    {
        $query = "INSERT INTO clientes (nombre, email, telefono, id_taller) 
                  VALUES (:nombre, :email, :telefono, :id_taller)";
        
        $params = [
            ':nombre' => $data['nombre'],
            ':email' => $data['email'],
            ':telefono' => $data['telefono'],
            ':id_taller' => $_SESSION['id_taller']
        ];
        
        return $this->db->execute($query, $params);
    }
    
    public function update($id, $data)
    {
        // Lógica para actualizar
    }
    
    public function delete($id)
    {
        // Lógica para eliminar
    }
}
```

### Ejemplo de Clase Database (database.php)

```php
<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'herrerospro';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    public function query($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function queryOne($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function execute($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }
    
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }
}
```

---

Este documento servirá como guía completa para la implementación de la nueva estructura de HerrerosPro, asegurando consistencia y facilitando el mantenimiento del sistema. 