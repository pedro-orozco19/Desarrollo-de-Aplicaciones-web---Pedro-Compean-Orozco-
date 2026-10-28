<?php
/**
 * Eliminación segura de un producto (DELETE — Semanas 06, 08, 09 y 11).
 * Solo acepta método POST y exige rol 'administrador'.
 */
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_mensaje'] = 'La eliminación debe enviarse mediante una solicitud POST válida.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    $_SESSION['flash_mensaje'] = 'El identificador del producto no es válido.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/ProductoController.php';

$modelo = new Producto($conexion);
$bitacora = new Bitacora($conexion);
$controlador = new ProductoController($modelo, $bitacora);

$resultado = $controlador->eliminar(
    $id,
    (int)$_SESSION['usuario_id'],
    (string)($_SESSION['nombre'] ?? $_SESSION['usuario'])
);

$_SESSION['flash_mensaje'] = $resultado['mensaje'];
$_SESSION['flash_tipo'] = $resultado['ok'] ? 'exito' : 'error';

header('Location: index.php');
exit;
