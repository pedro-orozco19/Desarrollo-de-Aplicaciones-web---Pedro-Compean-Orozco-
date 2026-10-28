<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?tipo=error&msg=' . urlencode('La eliminación requiere método POST.'));
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    header('Location: index.php?tipo=error&msg=' . urlencode('Identificador inválido.'));
    exit;
}

$stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header('Location: index.php?tipo=exito&msg=' . urlencode('Producto eliminado correctamente.'));
exit;
