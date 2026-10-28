<?php
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador']);
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_mensaje'] = 'La eliminación requiere método POST.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    $_SESSION['flash_mensaje'] = 'Identificador inválido.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit;
}

$stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

$_SESSION['flash_mensaje'] = 'Producto eliminado correctamente.';
$_SESSION['flash_tipo'] = 'exito';
header('Location: index.php');
exit;
