<?php
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador', 'operador']);
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
$categoriasPermitidas = ['Electronica', 'Mobiliario', 'Limpieza'];

if ($nombre === '' || !$stock || $stock <= 0 || !$precio || $precio <= 0 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || !in_array($categoria, $categoriasPermitidas, true)) {
    $_SESSION['flash_mensaje'] = 'Datos inválidos: verifica que todos los campos obligatorios sean correctos.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: index.php');
    exit;
}

$sql = "INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('sidss', $nombre, $stock, $precio, $correo, $categoria);
$stmt->execute();
$stmt->close();

$_SESSION['flash_mensaje'] = 'Producto registrado correctamente.';
$_SESSION['flash_tipo'] = 'exito';
header('Location: index.php');
exit;
