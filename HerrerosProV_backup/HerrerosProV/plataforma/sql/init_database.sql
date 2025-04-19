-- Inicialización de la base de datos según MEMORY[d8a38fe4]
-- Charset: utf8mb4 para soporte completo de Unicode

-- Eliminar base de datos si existe y crear una nueva
DROP DATABASE IF EXISTS herrerospro_plataforma;
CREATE DATABASE herrerospro_plataforma
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE herrerospro_plataforma;

-- Tabla de usuarios de la plataforma
CREATE TABLE usuarios_plataforma (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'supervisor', 'capturista') NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    ultimo_acceso DATETIME,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de talleres (debe crearse antes que suscripciones)
CREATE TABLE talleres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    propietario VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(20),
    direccion TEXT,
    estado ENUM('activo', 'inactivo', 'suspendido') NOT NULL DEFAULT 'activo',
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de solicitudes de talleres
CREATE TABLE solicitudes_talleres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_taller VARCHAR(100) NOT NULL,
    propietario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    fecha_solicitud DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta DATETIME,
    comentarios TEXT,
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de suscripciones (con referencia a talleres)
CREATE TABLE suscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_taller INT NOT NULL,
    plan VARCHAR(50) NOT NULL,
    estado ENUM('activa', 'inactiva', 'cancelada') NOT NULL DEFAULT 'activa',
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    CONSTRAINT fk_suscripcion_taller FOREIGN KEY (id_taller) 
        REFERENCES talleres(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de registro de actividad
CREATE TABLE registro_actividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    accion VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto
-- Contraseña: admin123 (hasheada con password_hash)
INSERT INTO usuarios_plataforma (nombre, email, password, rol)
VALUES (
    'Administrador',
    'admin@herrerospro.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);

-- Insertar algunos datos de ejemplo
INSERT INTO talleres (nombre, propietario, email, telefono, estado)
VALUES 
('Herrería Moderna', 'Juan Pérez', 'juan@herreria.com', '5551234567', 'activo'),
('Taller Industrial XYZ', 'María García', 'maria@xyz.com', '5559876543', 'activo');

INSERT INTO solicitudes_talleres (nombre_taller, propietario, email, telefono, estado)
VALUES 
('Herrería El Arte', 'Pedro López', 'pedro@elarte.com', '5552345678', 'pendiente'),
('Taller Metalúrgico ABC', 'Ana Martínez', 'ana@abc.com', '5558765432', 'pendiente');
