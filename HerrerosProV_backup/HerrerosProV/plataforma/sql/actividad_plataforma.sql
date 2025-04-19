-- Tabla de Actividad de la Plataforma
-- Registra todas las acciones importantes realizadas por los usuarios

CREATE TABLE IF NOT EXISTS `actividad_plataforma` (
  `id_actividad` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `tipo_actividad` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `entidad` varchar(50) DEFAULT NULL,
  `id_entidad` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_actividad`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_tipo` (`tipo_actividad`),
  KEY `idx_fecha` (`fecha_creacion`),
  KEY `idx_entidad` (`entidad`, `id_entidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos registros de ejemplo
INSERT INTO `actividad_plataforma` 
(`id_usuario`, `tipo_actividad`, `descripcion`, `entidad`, `id_entidad`, `fecha_creacion`) 
VALUES
(1, 'login', 'Inicio de sesión con email: admin@herrerospro.com', 'usuario', 1, NOW() - INTERVAL 1 HOUR),
(1, 'aprobar', 'Aprobación de solicitud para taller: Herrería El Arte', 'solicitud', 1, NOW() - INTERVAL 45 MINUTE),
(1, 'crear', 'Creación de nuevo usuario supervisor', 'usuario', 2, NOW() - INTERVAL 30 MINUTE),
(1, 'editar', 'Actualización de información del taller: Taller Metálico ABC', 'taller', 2, NOW() - INTERVAL 15 MINUTE);
