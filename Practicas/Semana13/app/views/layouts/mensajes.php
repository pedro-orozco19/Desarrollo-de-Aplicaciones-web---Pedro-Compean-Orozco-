<?php
/**
 * Componente reutilizable: Mensajes de estado (Semana 12).
 * Muestra avisos uniformes de éxito, advertencia o error escapando siempre la salida HTML.
 * Elimina el mensaje de sesión tras mostrarlo para que no se repita indefinidamente.
 */
$tiposPermitidos = [
    'exito' => 'mensaje-exito',
    'advertencia' => 'mensaje-advertencia',
    'error' => 'mensaje-error',
];

$mensajeFlash = $_SESSION['flash_mensaje'] ?? null;
$tipoFlash = $_SESSION['flash_tipo'] ?? 'exito';
unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);

$claseFlash = $tiposPermitidos[$tipoFlash] ?? 'mensaje-exito';
?>
<div class="contenedor-mensajes">
    <?php if (!empty($mensajeFlash)): ?>
        <div class="<?= htmlspecialchars($claseFlash, ENT_QUOTES, 'UTF-8') ?>" role="alert">
            <?= htmlspecialchars((string)$mensajeFlash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- Contenedor para mensajes dinámicos de JavaScript / Fetch API -->
    <div id="mensaje-sistema" aria-live="polite"></div>
</div>
