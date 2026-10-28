<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nombre = strtoupper(trim($_POST['nombre'] ?? ''));
$stock = filter_input(INPUT_POST, 'stock_nuevo', FILTER_VALIDATE_INT);
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$correo = trim($_POST['correo_proveedor'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');

if ($nombre === '' || !$stock || $stock <= 0 || !$precio || $precio <= 0 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $categoria === '') {
    header('Location: index.php?tipo=error&msg=' . urlencode('Todos los campos son obligatorios y deben tener formato válido.'));
    exit;
}

$sql = "INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('sidss', $nombre, $stock, $precio, $correo, $categoria);
$stmt->execute();
$stmt->close();

header('Location: index.php?tipo=exito&msg=' . urlencode('Producto registrado correctamente.'));
exit;
