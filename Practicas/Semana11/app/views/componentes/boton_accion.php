<?php
/**
 * Componente reutilizable: Botones de acción y etiquetas de estado (Semana 12).
 * Centraliza la creación de etiquetas de stock y controles de acción con escape seguro.
 */
if (!function_exists('renderizarEtiquetaStock')) {
    function renderizarEtiquetaStock(int $stock): string
    {
        if ($stock <= 15) {
            return '<span class="badge-stock stock-bajo" title="Requiere reabastecimiento">' . $stock . ' u. (Bajo)</span>';
        }
        return '<span class="badge-stock stock-ok">' . $stock . ' u.</span>';
    }
}

if (!function_exists('renderizarAccionesProducto')) {
    function renderizarAccionesProducto(int $id, string $nombre, string $rolActual, string $csrfToken): string
    {
        $puedeEditar = in_array($rolActual, ['administrador', 'operador'], true);
        $puedeEliminar = ($rolActual === 'administrador');

        if (!$puedeEditar && !$puedeEliminar) {
            return '<span class="texto-solo-lectura">Solo lectura</span>';
        }

        $nombreSeguro = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $csrfSeguro = htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8');
        $html = '<div class="acciones-fila">';

        if ($puedeEditar) {
            $html .= '<a href="editar.php?id=' . $id . '" class="btn-accion btn-editar" title="Editar producto">✏️ Editar</a>';
        }

        if ($puedeEliminar) {
            $html .= '<form action="eliminar.php" method="POST" class="form-eliminar-inline" data-id="' . $id . '" data-nombre="' . $nombreSeguro . '">'
                   . '<input type="hidden" name="id" value="' . $id . '">'
                   . '<input type="hidden" name="csrf_token" value="' . $csrfSeguro . '">'
                   . '<button type="submit" class="btn-accion btn-eliminar" title="Eliminar producto">🗑️ Eliminar</button>'
                   . '</form>';
        }

        $html .= '</div>';
        return $html;
    }
}
