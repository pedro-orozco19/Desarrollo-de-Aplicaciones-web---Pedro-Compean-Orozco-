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

require_once __DIR__ . '/../conexion.php';

$sql = "SELECT id, nombre, stock, precio, correo_proveedor, categoria FROM productos WHERE activo = 1 ORDER BY id DESC";
$res = $conexion->query($sql);
$datos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

$valorTotal = 0.0;
foreach ($datos as $d) {
    $valorTotal += ((int)$d['stock'] * (float)$d['precio']);
}

echo json_encode([
    'ok' => true,
    'mensaje' => 'Inventario consultado correctamente.',
    'datos' => $datos,
    'resumen' => [
        'total_registros' => count($datos),
        'valor_total' => $valorTotal,
    ]
], JSON_UNESCAPED_UNICODE);
