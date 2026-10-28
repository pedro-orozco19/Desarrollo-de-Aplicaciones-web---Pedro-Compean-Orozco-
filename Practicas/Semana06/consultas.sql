-- =========================================================
-- SEMANA 05 Y SEMANA 06: PERSISTENCIA Y CRUD COMPLETO
-- Base de datos: gestor_inventario
-- =========================================================

CREATE DATABASE IF NOT EXISTS gestor_inventario;
USE gestor_inventario;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    stock INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    correo_proveedor VARCHAR(150),
    categoria VARCHAR(50),
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CREATE: Insertar un registro de prueba
INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria)
VALUES ('MONITOR DELL 24 PULGADAS', 45, 3200.00, 'contacto@proveedor.com', 'Electronica');

-- READ: Consultar registros
SELECT id, nombre, stock, precio, correo_proveedor, categoria FROM productos ORDER BY id DESC;

-- UPDATE: Actualizar un registro por su clave primaria
UPDATE productos SET stock = 50, precio = 3150.00 WHERE id = 1;

-- DELETE: Eliminar un registro de prueba por su clave primaria
-- DELETE FROM productos WHERE id = 1;
