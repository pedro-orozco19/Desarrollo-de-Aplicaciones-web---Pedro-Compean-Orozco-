<?php
header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'Sesión no válida.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (!in_array($_SESSION['rol'] ?? '', ['administrador', 'operador'], true)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No tienes permiso para registrar productos.'], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../conexion.php';

$nombre = strtoupper(trim($_POST['nombre'] ?? ''));
$stock = filter_input(INPUT_POST, 'stock_nuevo', FILTER_VALIDATE_INT);
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$correo = trim($_POST['correo_proveedor'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$categoriasPermitidas = ['Electronica', 'Mobiliario', 'Limpieza'];

if ($nombre === '' || !$stock || $stock <= 0 || !$precio || $precio <= 0 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || !in_array($categoria, $categoriasPermitidas, true)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'mensaje' => 'Todos los campos son obligatorios y deben tener formato válido.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $conexion->prepare("INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria, activo) VALUES (?, ?, ?, ?, ?, 1)");
$stmt->bind_param('sidss', $nombre, $stock, $precio, $correo, $categoria);
$ok = $stmt->execute();
$nuevoId = $ok ? (int)$conexion->insert_id : 0;
$stmt->close();

echo json_encode([
    'ok' => true,
    'mensaje' => 'Producto registrado correctamente en el inventario.',
    'id' => $nuevoId
], JSON_UNESCAPED_UNICODE);
