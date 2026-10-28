<?php
/**
 * Formulario de edición de un producto (UPDATE — Semanas 06, 08, 09, 11 y 12).
 * Valida la clave primaria recibida y comprueba el rol en el servidor.
 */
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador', 'operador']);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Producto.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    $_SESSION['flash_mensaje'] = 'El identificador del producto no es válido.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit;
}

$modeloProducto = new Producto($conexion);
$producto = $modeloProducto->obtenerPorId($id);

if (!$producto) {
    $_SESSION['flash_mensaje'] = 'El producto solicitado no existe en la base de datos.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit;
}

require __DIR__ . '/app/views/productos/editar.php';
