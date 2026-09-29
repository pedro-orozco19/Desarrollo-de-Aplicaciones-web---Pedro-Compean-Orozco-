-- Crear la base de datos
CREATE DATABASE gestor_inventario;

-- Seleccionarla para trabajar en ella
USE gestor_inventario;

-- Crear la tabla principal
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    stock INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    correo_proveedor VARCHAR(150),
    categoria VARCHAR(50)
);

-- Insertar un registro de prueba
INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria)
VALUES ('Monitor Dell 24 pulgadas', 45, 3200.00, 'contacto@proveedor.com', 'Electronica');

-- Consultar para verificar que se guardó
SELECT * FROM productos;