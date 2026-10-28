<?php
/**
 * Endpoint JSON para consultar y filtrar productos vía Fetch API (Semanas 10, 11, 12 y 13).
 * No incluye plantillas HTML para garantizar una respuesta JSON limpia.
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'No autenticado. Inicia sesión para consultar el inventario.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';

try {
    $modelo = new Producto($conexion);
    $bitacora = new Bitacora($conexion);
    $controlador = new ProductoController($modelo, $bitacora);

    $resultado = $controlador->index($_GET);

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Inventario consultado correctamente.',
        'datos' => $resultado['registros'],
        'resumen' => [
            'total_registros' => $resultado['total_registros'],
            'valor_total' => $resultado['valor_total'],
            'pagina_actual' => $resultado['pagina_actual'],
            'total_paginas' => $resultado['total_paginas'],
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('Error en api/listar.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error interno al consultar los registros.'
    ], JSON_UNESCAPED_UNICODE);
}
