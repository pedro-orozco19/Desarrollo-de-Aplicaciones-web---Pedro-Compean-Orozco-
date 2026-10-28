<?php
require_once __DIR__ . '/conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    header('Location: index.php?tipo=error&msg=' . urlencode('El identificador no es válido.'));
    exit;
}

$stmt = $conexion->prepare("SELECT id, nombre, stock, precio, correo_proveedor, categoria FROM productos WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$producto) {
    header('Location: index.php?tipo=error&msg=' . urlencode('El producto solicitado no existe.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto #<?= (int)$producto['id'] ?></title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header class="encabezado">
        <h1>✏️ Editar Producto #<?= (int)$producto['id'] ?></h1>
    </header>
    <main class="contenedor-principal">
        <section class="caja seccion-formulario">
            <h2>Modificar Datos del Producto</h2>
            <form action="actualizar.php" method="POST">
                <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
                <div class="grupo-input">
                    <label for="nombre">Nombre del Producto: *</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="grupo-input">
                    <label for="stock_nuevo">Stock: *</label>
                    <input type="number" id="stock_nuevo" name="stock_nuevo" value="<?= (int)$producto['stock'] ?>" min="1" required>
                </div>
                <div class="grupo-input">
                    <label for="precio">Precio ($): *</label>
                    <input type="number" step="0.01" id="precio" name="precio" value="<?= htmlspecialchars((string)$producto['precio'], ENT_QUOTES, 'UTF-8') ?>" min="0.01" required>
                </div>
                <div class="grupo-input">
                    <label for="correo_proveedor">Correo del Proveedor: *</label>
                    <input type="email" id="correo_proveedor" name="correo_proveedor" value="<?= htmlspecialchars($producto['correo_proveedor'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="grupo-input">
                    <label for="categoria">Categoría: *</label>
                    <select id="categoria" name="categoria" required>
                        <option value="Electronica" <?= $producto['categoria'] === 'Electronica' ? 'selected' : '' ?>>Electrónica</option>
                        <option value="Mobiliario" <?= $producto['categoria'] === 'Mobiliario' ? 'selected' : '' ?>>Mobiliario</option>
                        <option value="Limpieza" <?= $producto['categoria'] === 'Limpieza' ? 'selected' : '' ?>>Limpieza</option>
                    </select>
                </div>
                <button type="submit" class="btn-guardar">💾 Guardar Cambios</button>
                <a href="index.php" class="btn-secundario" style="display:block; text-align:center; margin-top:10px; text-decoration:none;">⬅️ Cancelar y volver</a>
            </form>
        </section>
    </main>
    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web — Proyecto Integrador · Pedro Compean Orozco</p>
    </footer>
</body>
</html>
