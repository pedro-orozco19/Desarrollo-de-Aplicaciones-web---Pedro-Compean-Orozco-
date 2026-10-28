<?php
require_once __DIR__ . '/proteger.php';
require_once __DIR__ . '/conexion.php';
$mensaje = $_SESSION['flash_mensaje'] ?? ($_GET['msg'] ?? '');
$tipoMsg = $_SESSION['flash_tipo'] ?? ($_GET['tipo'] ?? 'exito');
unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal Protegido — Sistema de Inventario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header class="encabezado">
        <div class="encabezado-contenido">
            <div>
                <h1>📦 Gestor de Inventario</h1>
                <p class="subtitulo-encabezado">Panel Protegido con Autenticación y Sesiones de PHP</p>
            </div>
            <div class="info-usuario-header">
                <span class="badge-usuario">
                    👤 <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <a href="logout.php" class="menu-item btn-salir" style="text-decoration:none;">🚪 Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="contenedor-principal">
        <div class="columna-principal">
            <?php if ($mensaje !== ''): ?>
                <div class="<?= $tipoMsg === 'error' ? 'mensaje-error' : 'mensaje-exito' ?>">
                    <?= htmlspecialchars((string)$mensaje, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <div id="mensaje-sistema"></div>

            <section class="caja caja-ancha">
                <h2>Inventario Registrado (Acceso Autenticado)</h2>
                <div class="tabla-responsive">
                    <table class="tabla-inventario">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Proveedor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, nombre, stock, precio, correo_proveedor, categoria FROM productos WHERE activo = 1 ORDER BY id DESC";
                            $resultado = $conexion->query($sql);
                            $valor_total = 0;

                            if ($resultado && $resultado->num_rows > 0):
                                while ($fila = $resultado->fetch_assoc()):
                                    $valor_total += ((int)$fila['stock'] * (float)$fila['precio']);
                            ?>
                                <tr>
                                    <td class="celda-centro">#<?= (int)$fila['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><span class="badge-categoria"><?= htmlspecialchars($fila['categoria'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                    <td class="celda-centro"><?= (int)$fila['stock'] ?></td>
                                    <td class="celda-derecha">$<?= number_format((float)$fila['precio'], 2) ?></td>
                                    <td><?= htmlspecialchars($fila['correo_proveedor'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celda-centro">
                                        <div class="acciones-fila">
                                            <a href="editar.php?id=<?= (int)$fila['id'] ?>" class="btn-accion btn-editar">✏️ Editar</a>
                                            <form action="eliminar.php" method="POST" class="form-eliminar-inline" onsubmit="return confirm('¿Estás seguro de eliminar este producto de prueba?');">
                                                <input type="hidden" name="id" value="<?= (int)$fila['id'] ?>">
                                                <button type="submit" class="btn-accion btn-eliminar">🗑️ Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr><td colspan="7" class="celda-vacia">No hay productos registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <h3 class="total">Valor total en almacén: $<?= number_format($valor_total, 2) ?></h3>
            </section>
        </div>

        <section class="caja seccion-formulario">
            <h2>➕ Registrar Nuevo Producto</h2>
            <form action="crear.php" method="POST" id="formulario-inventario">
                <div class="grupo-input">
                    <label for="nombre">Nombre del Producto: *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Teclado Mecánico" required>
                </div>
                <div class="grupo-input">
                    <label for="stock_nuevo">Cantidad a ingresar: *</label>
                    <input type="number" id="stock_nuevo" name="stock_nuevo" placeholder="Ej. 10" min="1" required>
                </div>
                <div class="grupo-input">
                    <label for="precio">Precio del Producto ($): *</label>
                    <input type="number" step="0.01" id="precio" name="precio" placeholder="Ej. 250.50" min="0.01" required>
                </div>
                <div class="grupo-input">
                    <label for="correo_proveedor">Correo del Proveedor: *</label>
                    <input type="email" id="correo_proveedor" name="correo_proveedor" placeholder="contacto@proveedor.com" required>
                </div>
                <div class="grupo-input">
                    <label for="categoria">Categoría del Producto: *</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">-- Selecciona una categoría --</option>
                        <option value="Electronica">Electrónica</option>
                        <option value="Mobiliario">Mobiliario</option>
                        <option value="Limpieza">Limpieza</option>
                    </select>
                </div>
                <button type="submit" class="btn-guardar" id="btnGuardar">Registrar Producto</button>
            </form>

            <br>
            <button id="btnMostrarInfo" class="btn-guardar btn-gris" type="button">Mostrar Ayuda</button>
            <div id="info-extra" class="caja-ayuda" style="display: none;">
                <p><strong>Tip:</strong> Todos los campos son obligatorios. El precio y el stock deben ser mayores a 0.</p>
            </div>
        </section>
    </main>

    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web — Proyecto Integrador · Pedro Compean Orozco</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
