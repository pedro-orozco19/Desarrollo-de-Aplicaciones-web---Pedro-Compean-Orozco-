<?php
/**
 * Componente reutilizable: Pie de página general (Semana 12).
 */
$incluirScriptJs = $incluirScriptJs ?? true;
$rutaJs = $rutaJs ?? 'script.js';
?>
    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web — Proyecto Integrador · Pedro Compean Orozco</p>
    </footer>

    <?php if ($incluirScriptJs): ?>
        <script src="<?= htmlspecialchars($rutaJs, ENT_QUOTES, 'UTF-8') ?>"></script>
    <?php endif; ?>
</body>
</html>
