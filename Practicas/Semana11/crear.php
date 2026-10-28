<?php
/**
 * Creación de registro en el servidor (CREATE — Semanas 06, 08, 09 y 11).
 * Exige autenticación y rol autorizado ('administrador' u 'operador').
 */
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador', 'operador']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/ProductoController.php';

$modelo = new Producto($conexion);
$bitacora = new Bitacora($conexion);
$controlador = new ProductoController($modelo, $bitacora);

$resultado = $controlador->guardar(
    $_POST,
    (int)$_SESSION['usuario_id'],
    (string)($_SESSION['nombre'] ?? $_SESSION['usuario'])
);

$_SESSION['flash_mensaje'] = $resultado['mensaje'];
$_SESSION['flash_tipo'] = $resultado['ok'] ? 'exito' : 'error';

header('Location: index.php');
exit;
