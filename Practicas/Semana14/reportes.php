<?php
/**
 * Módulo de Reportes y Estadísticas del Inventario (Función Avanzada — Semana 13).
 * Presenta indicadores globales, desglose por categoría y alertas de reabastecimiento.
 */
require_once __DIR__ . '/proteger.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Producto.php';

$modeloProducto = new Producto($conexion);
$estadisticas = $modeloProducto->obtenerEstadisticas();

$totales = $estadisticas['totales'];
$porCategoria = $estadisticas['por_categoria'];
$stockBajo = $estadisticas['stock_bajo'];

$tituloPagina = 'Reportes y Estadísticas';
$paginaActiva = 'reportes';
$incluirScriptJs = false;

require __DIR__ . '/app/views/layouts/header.php';
require __DIR__ . '/app/views/layouts/menu.php';
?>

<main class="contenedor-principal">
    <div class="columna-principal">
        <?php require __DIR__ . '/app/views/layouts/mensajes.php'; ?>

        <!-- Tarjetas de resumen ejecutivo -->
        <div class="grid-tarjetas-resumen">
            <div class="tarjeta-kpi">
                <span class="kpi-titulo">📦 Productos Distintos</span>
                <strong class="kpi-valor"><?= (int)$totales['productos'] ?></strong>
            </div>
            <div class="tarjeta-kpi">
                <span class="kpi-titulo">🔢 Unidades Totales en Almacén</span>
                <strong class="kpi-valor"><?= number_format((int)$totales['unidades']) ?> u.</strong>
            </div>
            <div class="tarjeta-kpi">
                <span class="kpi-titulo">💰 Valor Global del Inventario</span>
                <strong class="kpi-valor kpi-verde">$<?= number_format((float)$totales['valor_global'], 2) ?></strong>
            </div>
            <div class="tarjeta-kpi">
                <span class="kpi-titulo">⚠️ Alertas de Stock Bajo (≤ 15)</span>
                <strong class="kpi-valor kpi-alerta"><?= count($stockBajo) ?></strong>
            </div>
        </div>

        <section class="caja caja-ancha" style="margin-bottom: 25px;">
            <div class="cabecera-seccion">
                <h2>📊 Distribución y Valor por Categoría</h2>
                <a href="exportar_csv.php" class="btn-secundario" style="text-decoration: none;">📤 Descargar Inventario CSV</a>
            </div>

            <div class="tabla-responsive">
                <table class="tabla-inventario">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Productos Registrados</th>
                            <th>Unidades en Stock</th>
                            <th>Precio Promedio</th>
                            <th>Valor Total en Categoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($porCategoria) > 0): ?>
                            <?php foreach ($porCategoria as $cat): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($cat['categoria'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td class="celda-centro"><?= (int)$cat['cantidad_productos'] ?></td>
                                    <td class="celda-centro"><?= number_format((int)$cat['unidades_totales']) ?> u.</td>
                                    <td class="celda-derecha">$<?= number_format((float)$cat['precio_promedio'], 2) ?></td>
                                    <td class="celda-derecha"><strong>$<?= number_format((float)$cat['valor_inventario'], 2) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="celda-vacia">No hay datos suficientes para generar el reporte.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="caja caja-ancha">
            <h2>⚠️ Productos con Stock Crítico o Bajo (≤ 15 unidades)</h2>
            <div class="tabla-responsive">
                <table class="tabla-inventario">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Stock Actual</th>
                            <th>Correo del Proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($stockBajo) > 0): ?>
                            <?php foreach ($stockBajo as $prod): ?>
                                <tr>
                                    <td class="celda-centro">#<?= (int)$prod['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($prod['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><?= htmlspecialchars($prod['categoria'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celda-centro">
                                        <span class="badge-stock stock-bajo"><?= (int)$prod['stock'] ?> u.</span>
                                    </td>
                                    <td><?= htmlspecialchars($prod['correo_proveedor'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="celda-vacia">¡Excelente! Ningún producto se encuentra en nivel crítico de stock.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<?php require __DIR__ . '/app/views/layouts/footer.php'; ?>
