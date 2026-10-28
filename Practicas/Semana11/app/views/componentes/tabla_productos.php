<?php
/**
 * Componente reutilizable: Tabla de productos del inventario (Semana 12).
 * No ejecuta consultas SQL: recibe únicamente datos preparados por el controlador.
 */
require_once __DIR__ . '/boton_accion.php';

$registros = $registros ?? [];
$valorTotal = $valorTotal ?? 0.0;
$rolUsuario = $_SESSION['rol'] ?? 'consulta';
$csrfToken = $_SESSION['csrf_token'] ?? '';
?>
<div class="tabla-responsive">
    <table class="tabla-inventario" id="tabla-productos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio Unit.</th>
                <th>Proveedor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="cuerpo-tabla-productos">
            <?php if (count($registros) > 0): ?>
                <?php foreach ($registros as $fila): ?>
                    <tr id="fila-producto-<?= (int)$fila['id'] ?>">
                        <td class="celda-centro">#<?= (int)$fila['id'] ?></td>
                        <td><strong><?= htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                        <td><span class="badge-categoria"><?= htmlspecialchars($fila['categoria'], ENT_QUOTES, 'UTF-8') ?></span></td>
                        <td class="celda-centro"><?= renderizarEtiquetaStock((int)$fila['stock']) ?></td>
                        <td class="celda-derecha">$<?= number_format((float)$fila['precio'], 2) ?></td>
                        <td><?= htmlspecialchars($fila['correo_proveedor'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="celda-centro">
                            <?= renderizarAccionesProducto((int)$fila['id'], (string)$fila['nombre'], $rolUsuario, $csrfToken) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="celda-vacia">
                        No se encontraron productos que coincidan con los criterios seleccionados.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="resumen-valor-pie">
    <h3 class="total">
        💰 Valor total de los productos filtrados:
        <span id="valor-total-almacen">$<?= number_format((float)$valorTotal, 2) ?></span>
    </h3>
</div>
