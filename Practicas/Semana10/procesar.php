<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = strtoupper(trim($_POST['nombre'] ?? ''));
    $stock = filter_input(INPUT_POST, 'stock_nuevo', FILTER_VALIDATE_INT);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $correo = trim($_POST['correo_proveedor'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');

    $errores = [];
    if ($nombre === '') $errores[] = "⚠️ El nombre del producto es obligatorio.";
    if (!$stock || $stock <= 0) $errores[] = "⚠️ El stock debe ser mayor a 0.";
    if (!$precio || $precio <= 0) $errores[] = "⚠️ El precio debe ser mayor a 0.";
    if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "⚠️ Correo no válido.";
    if ($categoria === '') $errores[] = "⚠️ Debes seleccionar una categoría.";

    if (count($errores) > 0) {
        header("Location: index.php?tipo=error&msg=" . urlencode(implode(" ", $errores)));
        exit;
    }

    $sql = "INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sidss", $nombre, $stock, $precio, $correo, $categoria);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?tipo=exito&msg=" . urlencode("✔️ Producto guardado en la Base de Datos."));
    exit;
}

header("Location: index.php");
exit;
