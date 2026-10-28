<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nombre = strtoupper(trim($_POST['nombre'] ?? ''));
$stock = filter_input(INPUT_POST, 'stock_nuevo', FILTER_VALIDATE_INT);
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$correo = trim($_POST['correo_proveedor'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');

if (!$id || $id <= 0 || $nombre === '' || !$stock || $stock <= 0 || !$precio || $precio <= 0 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $categoria === '') {
    header('Location: index.php?tipo=error&msg=' . urlencode('Datos inválidos para actualizar el producto.'));
    exit;
}

$sql = "UPDATE productos SET nombre = ?, stock = ?, precio = ?, correo_proveedor = ?, categoria = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('sidssi', $nombre, $stock, $precio, $correo, $categoria, $id);
$stmt->execute();
$stmt->close();

header('Location: index.php?tipo=exito&msg=' . urlencode('Producto actualizado correctamente.'));
exit;
