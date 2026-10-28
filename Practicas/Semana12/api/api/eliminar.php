<?php
/**
 * Endpoint JSON para eliminar productos vía Fetch API (Semanas 10 y 11).
 * Restringido exclusivamente al rol administrador.
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No autenticado.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Método no permitido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SESSION['rol'] ?? '') !== 'administrador') {
    http_response_code(403);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Solo el administrador tiene permiso para eliminar registros.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'El identificador del producto no es válido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';

try {
    $modelo = new Producto($conexion);
    $bitacora = new Bitacora($conexion);
    $controlador = new ProductoController($modelo, $bitacora);

    $respuesta = $controlador->eliminar(
        $id,
        (int)$_SESSION['usuario_id'],
        (string)($_SESSION['nombre'] ?? $_SESSION['usuario'])
    );

    if (!$respuesta['ok']) {
        http_response_code(400);
    }

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('Error en api/eliminar.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No fue posible procesar la solicitud de eliminación.'
    ], JSON_UNESCAPED_UNICODE);
}
