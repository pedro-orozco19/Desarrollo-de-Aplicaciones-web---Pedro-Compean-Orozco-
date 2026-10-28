<?php
/**
 * Exportación segura de resultados autorizados a CSV (Función Avanzada — Semana 13).
 * - Respeta la autenticación y aplica los mismos filtros de consulta.
 * - No exporta campos sensibles.
 * - Previene la inyección de fórmulas en hojas de cálculo (CSV Injection).
 */
require_once __DIR__ . '/proteger.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Producto.php';

$modeloProducto = new Producto($conexion);

$termino = trim((string)($_GET['q'] ?? ''));
$categoria = trim((string)($_GET['categoria'] ?? ''));
$estadoStock = trim((string)($_GET['estado_stock'] ?? ''));
$orden = trim((string)($_GET['orden'] ?? 'id'));
$direccion = trim((string)($_GET['dir'] ?? 'DESC'));

$resultado = $modeloProducto->listarAvanzado($termino, $categoria, $estadoStock, $orden, $direccion, 1, 500);
$registros = $resultado['registros'];

/**
 * Sanitiza un valor de texto para evitar que Excel o LibreOffice Calc lo interpreten como fórmula.
 */
function sanitizarCeldaCsv(string $valor): string
{
    $peligrosos = ['=', '+', '-', '@', "\t", "\r"];
    if ($valor !== '' && in_array($valor[0], $peligrosos, true)) {
        return "'" . $valor;
    }
    return $valor;
}

$nombreArchivo = 'inventario_productos_' . date('Ymd_His') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');

$salida = fopen('php://output', 'w');

// BOM UTF-8 para correcta visualización de acentos y caracteres especiales en Excel
fwrite($salida, "\xEF\xBB\xBF");

fputcsv($salida, ['ID', 'Producto', 'Categoria', 'Stock', 'Precio Unitario', 'Valor Subtotal', 'Correo Proveedor']);

foreach ($registros as $fila) {
    $subtotal = (int)$fila['stock'] * (float)$fila['precio'];
    fputcsv($salida, [
        (int)$fila['id'],
        sanitizarCeldaCsv((string)$fila['nombre']),
        sanitizarCeldaCsv((string)$fila['categoria']),
        (int)$fila['stock'],
        number_format((float)$fila['precio'], 2, '.', ''),
        number_format($subtotal, 2, '.', ''),
        sanitizarCeldaCsv((string)$fila['correo_proveedor']),
    ]);
}

fclose($salida);
exit;
