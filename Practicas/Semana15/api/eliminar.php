<?php
header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'No autenticado.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (($_SESSION['rol'] ?? '') !== 'administrador') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'Solo el administrador tiene permiso para eliminar registros.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'mensaje' => 'El identificador del producto no es válido.'], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../conexion.php';

$stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$afectados = $stmt->affected_rows;
$stmt->close();

if ($afectados <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'mensaje' => 'El producto solicitado no existe.'], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'ok' => true,
    'mensaje' => 'Producto eliminado correctamente del inventario.'
], JSON_UNESCAPED_UNICODE);
