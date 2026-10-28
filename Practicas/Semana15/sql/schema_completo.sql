-- =========================================================================
-- ESQUEMA COMPLETO DEL PROYECTO INTEGRADOR: GESTOR DE INVENTARIO
-- Alumno: Pedro Compean Orozco
-- Materia: Desarrollo de Aplicaciones Web (Semanas 01 a 15)
-- =========================================================================

CREATE DATABASE IF NOT EXISTS gestor_inventario
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gestor_inventario;

-- 1. Tabla principal del CRUD: productos (Semanas 05, 06, 08, 10, 11, 13)
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    stock INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    correo_proveedor VARCHAR(150) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla de autenticación y control de acceso: usuarios (Semanas 07, 08, 09)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(30) NOT NULL DEFAULT 'consulta',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabla de auditoría para Función Avanzada: bitacora (Semana 13)
CREATE TABLE IF NOT EXISTS bitacora (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    usuario_nombre VARCHAR(100) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    registro_id INT NULL,
    detalle VARCHAR(255) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Productos iniciales de demostración
INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria) VALUES
('MONITOR DELL 24 PULGADAS', 45, 3200.00, 'contacto@dell-proveedor.com', 'Electronica'),
('TECLADO MECANICO RGB LOGITECH', 30, 1450.50, 'ventas@logitech-distribuidor.com', 'Electronica'),
('SILLA ERGONOMICA EJECUTIVA', 15, 4100.00, 'mobiliario@oficinas-norte.com', 'Mobiliario'),
('ESCRITORIO ELEVABLE MODULAR', 8, 6850.00, 'mobiliario@oficinas-norte.com', 'Mobiliario'),
('KIT LIMPIEZA PANTALLAS ANTIESTATICO', 120, 185.00, 'insumos@limpiezapro.com', 'Limpieza');
