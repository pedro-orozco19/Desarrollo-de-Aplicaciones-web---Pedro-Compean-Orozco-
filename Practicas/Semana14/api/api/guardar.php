<?php
/**
 * Endpoint JSON para registrar productos vía Fetch API (Semanas 10, 11 y 12).
 * Comprueba sesión, rol autorizado, protección CSRF y validación en PHP.
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Sesión no válida. Inicia sesión nuevamente.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Método HTTP no permitido. Usa POST.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$rolActual = $_SESSION['rol'] ?? '';
if (!in_array($rolActual, ['administrador', 'operador'], true)) {
    http_response_code(403);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No tienes permiso para registrar productos.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$csrfRecibido = $_POST['csrf_token'] ?? '';
if (!empty($_SESSION['csrf_token']) && $csrfRecibido !== '' && !hash_equals($_SESSION['csrf_token'], $csrfRecibido)) {
    http_response_code(403);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Token de seguridad inválido. Recarga la página.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';

try {
    $modelo = new Producto($conexion);
    $bitacora = new Bitacora($conexion);
    $controlador = new ProductoController($modelo, $bitacora);

    $respuesta = $controlador->guardar(
        $_POST,
        (int)$_SESSION['usuario_id'],
        (string)($_SESSION['nombre'] ?? $_SESSION['usuario'])
    );

    if (!$respuesta['ok']) {
        http_response_code(400);
    }

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('Error en api/guardar.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error interno al procesar el registro.'
    ], JSON_UNESCAPED_UNICODE);
}
