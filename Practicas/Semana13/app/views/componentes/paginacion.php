<?php
/**
 * Componente reutilizable: Paginación conservando criterios de búsqueda, filtros y orden (Semana 13).
 */
$paginaActual = (int)($paginaActual ?? 1);
$totalPaginas = (int)($totalPaginas ?? 1);
$totalRegistros = (int)($totalRegistros ?? 0);

$parametrosBase = [
    'q' => $_GET['q'] ?? '',
    'categoria' => $_GET['categoria'] ?? '',
    'estado_stock' => $_GET['estado_stock'] ?? '',
    'orden' => $_GET['orden'] ?? 'id',
    'dir' => $_GET['dir'] ?? 'DESC',
];

$construirUrlPagina = function (int $numPagina) use ($parametrosBase): string {
    $params = array_filter($parametrosBase, fn($v) => $v !== '');
    $params['pagina'] = $numPagina;
    return 'index.php?' . http_build_query($params);
};
?>
<div class="barra-paginacion" id="contenedor-paginacion">
    <span class="info-paginacion">
        Mostrando página <strong><?= $paginaActual ?></strong> de <strong><?= $totalPaginas ?></strong>
        (<?= $totalRegistros ?> registros en total)
    </span>

    <?php if ($totalPaginas > 1): ?>
        <div class="controles-paginacion">
            <?php if ($paginaActual > 1): ?>
                <a href="<?= htmlspecialchars($construirUrlPagina(1), ENT_QUOTES, 'UTF-8') ?>" class="btn-paginacion">« Primera</a>
                <a href="<?= htmlspecialchars($construirUrlPagina($paginaActual - 1), ENT_QUOTES, 'UTF-8') ?>" class="btn-paginacion">‹ Anterior</a>
            <?php else: ?>
                <span class="btn-paginacion deshabilitado">‹ Anterior</span>
            <?php endif; ?>

            <?php for ($p = max(1, $paginaActual - 2); $p <= min($totalPaginas, $paginaActual + 2); $p++): ?>
                <a href="<?= htmlspecialchars($construirUrlPagina($p), ENT_QUOTES, 'UTF-8') ?>"
                   class="btn-paginacion <?= $p === $paginaActual ? 'activa' : '' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="<?= htmlspecialchars($construirUrlPagina($paginaActual + 1), ENT_QUOTES, 'UTF-8') ?>" class="btn-paginacion">Siguiente ›</a>
                <a href="<?= htmlspecialchars($construirUrlPagina($totalPaginas), ENT_QUOTES, 'UTF-8') ?>" class="btn-paginacion">Última »</a>
            <?php else: ?>
                <span class="btn-paginacion deshabilitado">Siguiente ›</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
