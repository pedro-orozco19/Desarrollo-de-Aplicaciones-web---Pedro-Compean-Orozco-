<?php
/**
 * Vista de edición de un producto existente (Semanas 06, 11 y 12).
 * Utiliza la plantilla general y escapa todos los valores dinámicos con htmlspecialchars().
 */
$tituloPagina = 'Editar Producto #' . (int)($producto['id'] ?? 0);
$paginaActiva = 'inventario';

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/menu.php';
?>

<main class="contenedor-principal">
    <section class="caja seccion-formulario" style="max-width: 550px;">
        <?php require __DIR__ . '/../layouts/mensajes.php'; ?>

        <h2>✏️ Editar Producto #<?= (int)$producto['id'] ?></h2>

        <form action="actualizar.php" method="POST" id="formulario-editar">
            <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <div class="grupo-input">
                <label for="nombre">Nombre del Producto: *</label>
                <input type="text" id="nombre" name="nombre"
                       value="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                       maxlength="100" required>
            </div>

            <div class="grupo-input">
                <label for="stock_nuevo">Cantidad en Stock: *</label>
                <input type="number" id="stock_nuevo" name="stock_nuevo"
                       value="<?= (int)$producto['stock'] ?>"
                       min="1" required>
            </div>

            <div class="grupo-input">
                <label for="precio">Precio Unitario ($): *</label>
                <input type="number" step="0.01" id="precio" name="precio"
                       value="<?= htmlspecialchars((string)$producto['precio'], ENT_QUOTES, 'UTF-8') ?>"
                       min="0.01" required>
            </div>

            <div class="grupo-input">
                <label for="correo_proveedor">Correo del Proveedor: *</label>
                <input type="email" id="correo_proveedor" name="correo_proveedor"
                       value="<?= htmlspecialchars($producto['correo_proveedor'], ENT_QUOTES, 'UTF-8') ?>"
                       required>
            </div>

            <div class="grupo-input">
                <label for="categoria">Categoría del Producto: *</label>
                <select id="categoria" name="categoria" required>
                    <?php foreach (['Electronica' => 'Electrónica', 'Mobiliario' => 'Mobiliario', 'Limpieza' => 'Limpieza'] as $valor => $etiqueta): ?>
                        <option value="<?= htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $producto['categoria'] === $valor ? 'selected' : '' ?>>
                            <?= htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-guardar">💾 Guardar Cambios</button>
            <a href="index.php" class="btn-secundario btn-bloque" style="margin-top: 10px; text-align: center; display: block; text-decoration: none;">
                ⬅️ Cancelar y volver al listado
            </a>
        </form>
    </section>
</main>

<?php
$incluirScriptJs = false;
require __DIR__ . '/../layouts/footer.php';
?>
