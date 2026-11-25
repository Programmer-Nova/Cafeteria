-- Migración de la base de datos de SQLite a MySQL/MariaDB (XAMPP)

CREATE DATABASE IF NOT EXISTS `cafeteria_escolar`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `cafeteria_escolar`;

CREATE TABLE IF NOT EXISTS `productos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `precio` DECIMAL(10, 2) NOT NULL,
  `disponible` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `productos` (`id`, `nombre`, `precio`, `disponible`) VALUES
  (1, 'Agua', 10.00, 1),
  (2, 'Torta', 30.00, 1),
  (6, 'Cafe', 25.00, 1),
  (7, 'Chocomilk', 35.00, 1);

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(50) NOT NULL UNIQUE,
  `nombre` VARCHAR(100) NOT NULL,
  `contrasena` VARCHAR(255) NOT NULL,
  `rol` ENUM('alumno', 'admin') NOT NULL DEFAULT 'alumno',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
