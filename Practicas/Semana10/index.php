<?php
require_once __DIR__ . '/autorizacion.php';
require_once __DIR__ . '/conexion.php';

$rolActual = $_SESSION['rol'] ?? 'consulta';
$puedeEscribir = in_array($rolActual, ['administrador', 'operador'], true);
$esAdmin = ($rolActual === 'administrador');

$mensaje = $_SESSION['flash_mensaje'] ?? ($_GET['msg'] ?? '');
$tipoMsg = $_SESSION['flash_tipo'] ?? ($_GET['tipo'] ?? 'exito');
unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Inventario — Roles y Permisos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header class="encabezado">
        <div class="encabezado-contenido">
            <div>
                <h1>📦 Gestor de Inventario</h1>
                <p class="subtitulo-encabezado">Control de Acceso Basado en Roles y Permisos</p>
            </div>
            <div class="info-usuario-header">
                <span class="badge-usuario">
                    👤 <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <span class="badge-rol rol-<?= htmlspecialchars($rolActual, ENT_QUOTES, 'UTF-8') ?>">
                    🛡️ Rol: <?= htmlspecialchars(ucfirst($rolActual), ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>
        </div>
    </header>

    <nav class="menu-navegacion">
        <div class="menu-enlaces">
            <a href="index.php" class="menu-item activo">📋 Consultar Inventario</a>
            <?php if ($puedeEscribir): ?>
                <a href="index.php#seccion-alta" class="menu-item">➕ Crear Registro</a>
            <?php endif; ?>
            <?php if ($esAdmin): ?>
                <a href="usuarios.php" class="menu-item">👥 Gestionar Usuarios</a>
            <?php endif; ?>
        </div>
        <div class="menu-derecha">
            <a href="logout.php" class="menu-item btn-salir">🚪 Cerrar sesión</a>
        </div>
    </nav>

    <main class="contenedor-principal" data-rol="<?= htmlspecialchars($rolActual, ENT_QUOTES, 'UTF-8') ?>" data-csrf="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <div class="columna-principal">
            <?php if ($mensaje !== ''): ?>
                <div class="<?= $tipoMsg === 'error' ? 'mensaje-error' : 'mensaje-exito' ?>">
                    <?= htmlspecialchars((string)$mensaje, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <div id="mensaje-sistema"></div>

            <section class="caja caja-ancha">
                <div class="cabecera-seccion">
                    <h2>Inventario Registrado</h2>
                    <?php if (file_exists(__DIR__ . '/api/listar.php')): ?>
                        <button type="button" id="btnRefrescarFetch" class="btn-secundario">⚡ Actualizar con Fetch API</button>
                    <?php endif; ?>
                </div>
                <div class="tabla-responsive">
                    <table class="tabla-inventario" id="tabla-productos">
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
                        <tbody id="cuerpo-tabla-productos">
                            <?php
                            $sql = "SELECT id, nombre, stock, precio, correo_proveedor, categoria FROM productos WHERE activo = 1 ORDER BY id DESC";
                            $resultado = $conexion->query($sql);
                            $valor_total = 0;

                            if ($resultado && $resultado->num_rows > 0):
                                while ($fila = $resultado->fetch_assoc()):
                                    $valor_total += ((int)$fila['stock'] * (float)$fila['precio']);
                            ?>
                                <tr id="fila-producto-<?= (int)$fila['id'] ?>">
                                    <td class="celda-centro">#<?= (int)$fila['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><span class="badge-categoria"><?= htmlspecialchars($fila['categoria'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                    <td class="celda-centro"><?= (int)$fila['stock'] ?> u.</td>
                                    <td class="celda-derecha">$<?= number_format((float)$fila['precio'], 2) ?></td>
                                    <td><?= htmlspecialchars($fila['correo_proveedor'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celda-centro">
                                        <?php if ($puedeEscribir || $esAdmin): ?>
                                            <div class="acciones-fila">
                                                <?php if ($puedeEscribir): ?>
                                                    <a href="editar.php?id=<?= (int)$fila['id'] ?>" class="btn-accion btn-editar">✏️ Editar</a>
                                                <?php endif; ?>
                                                <?php if ($esAdmin): ?>
                                                    <form action="eliminar.php" method="POST" class="form-eliminar-inline" data-id="<?= (int)$fila['id'] ?>" data-nombre="<?= htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                                                        <input type="hidden" name="id" value="<?= (int)$fila['id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        <button type="submit" class="btn-accion btn-eliminar">🗑️ Eliminar</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="texto-solo-lectura">Solo lectura</span>
                                        <?php endif; ?>
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
                <h3 class="total">Valor total en almacén: <span id="valor-total-almacen">$<?= number_format($valor_total, 2) ?></span></h3>
            </section>
        </div>

        <?php if ($puedeEscribir): ?>
        <section class="caja seccion-formulario" id="seccion-alta">
            <h2>➕ Registrar Nuevo Producto</h2>
            <form action="crear.php" method="POST" id="formulario-inventario">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
                <?php if (file_exists(__DIR__ . '/api/guardar.php')): ?>
                <div class="grupo-checkbox">
                    <label>
                        <input type="checkbox" id="usarFetchGuardar" checked>
                        Guardar sin recargar usando <strong>Fetch API (JSON)</strong>
                    </label>
                </div>
                <?php endif; ?>
                <button type="submit" class="btn-guardar" id="btnGuardar">Registrar Producto</button>
            </form>

            <br>
            <button id="btnMostrarInfo" class="btn-guardar btn-gris" type="button">Mostrar Ayuda</button>
            <div id="info-extra" class="caja-ayuda" style="display: none;">
                <p><strong>Tip:</strong> Todos los campos son obligatorios. El precio y el stock deben ser mayores a 0.</p>
            </div>
        </section>
        <?php else: ?>
        <section class="caja seccion-formulario">
            <h2>🔒 Modo Consulta</h2>
            <p>Tu cuenta tiene el rol <strong><?= htmlspecialchars(ucfirst($rolActual), ENT_QUOTES, 'UTF-8') ?></strong> (solo lectura).</p>
        </section>
        <?php endif; ?>
    </main>

    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web — Proyecto Integrador · Pedro Compean Orozco</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
