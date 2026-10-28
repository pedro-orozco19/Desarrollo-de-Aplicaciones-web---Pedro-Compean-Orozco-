<?php
/**
 * Vista principal del módulo de productos (Semanas 11, 12 y 13).
 * Utiliza la plantilla general y los componentes reutilizables.
 */
$tituloPagina = 'Inventario de Productos';
$paginaActiva = 'inventario';

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/menu.php';

$qActual = trim((string)($_GET['q'] ?? ''));
$catActual = trim((string)($_GET['categoria'] ?? ''));
$stockActual = trim((string)($_GET['estado_stock'] ?? ''));
$ordenActual = trim((string)($_GET['orden'] ?? 'id'));
$dirActual = strtoupper(trim((string)($_GET['dir'] ?? 'DESC')));
$rolActual = $_SESSION['rol'] ?? 'consulta';
$puedeRegistrar = in_array($rolActual, ['administrador', 'operador'], true);
?>

<main class="contenedor-principal" data-rol="<?= htmlspecialchars($rolActual, ENT_QUOTES, 'UTF-8') ?>" data-csrf="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <div class="columna-principal">
        <?php require __DIR__ . '/../layouts/mensajes.php'; ?>

        <!-- SECCIÓN 1: BÚSQUEDA, FILTROS Y ORDENAMIENTO (Función Avanzada - Semana 13 + Fetch API - Semana 10) -->
        <section class="caja caja-ancha">
            <div class="cabecera-seccion">
                <h2>🔎 Búsqueda, Filtros y Ordenamiento de Inventario</h2>
                <button type="button" id="btnRefrescarFetch" class="btn-secundario" title="Actualizar tabla con Fetch API sin recargar">
                    ⚡ Actualizar con Fetch API
                </button>
            </div>

            <form method="GET" action="index.php" id="form-filtros" class="barra-filtros">
                <div class="filtro-item">
                    <label for="filtro-q">Buscar producto o proveedor:</label>
                    <input type="text" id="filtro-q" name="q" value="<?= htmlspecialchars($qActual, ENT_QUOTES, 'UTF-8') ?>" placeholder="Ej. Monitor, Dell, contacto@...">
                </div>

                <div class="filtro-item">
                    <label for="filtro-categoria">Categoría:</label>
                    <select id="filtro-categoria" name="categoria">
                        <option value="">Todas las categorías</option>
                        <option value="Electronica" <?= $catActual === 'Electronica' ? 'selected' : '' ?>>Electrónica</option>
                        <option value="Mobiliario" <?= $catActual === 'Mobiliario' ? 'selected' : '' ?>>Mobiliario</option>
                        <option value="Limpieza" <?= $catActual === 'Limpieza' ? 'selected' : '' ?>>Limpieza</option>
                    </select>
                </div>

                <div class="filtro-item">
                    <label for="filtro-stock">Nivel de Stock:</label>
                    <select id="filtro-stock" name="estado_stock">
                        <option value="">Todos los niveles</option>
                        <option value="bajo" <?= $stockActual === 'bajo' ? 'selected' : '' ?>>⚠️ Stock bajo (≤ 15 u.)</option>
                        <option value="suficiente" <?= $stockActual === 'suficiente' ? 'selected' : '' ?>>✅ Stock suficiente (> 15 u.)</option>
                    </select>
                </div>

                <div class="filtro-item">
                    <label for="filtro-orden">Ordenar por:</label>
                    <select id="filtro-orden" name="orden">
                        <option value="id" <?= $ordenActual === 'id' ? 'selected' : '' ?>>ID de registro</option>
                        <option value="nombre" <?= $ordenActual === 'nombre' ? 'selected' : '' ?>>Nombre del producto</option>
                        <option value="stock" <?= $ordenActual === 'stock' ? 'selected' : '' ?>>Cantidad en stock</option>
                        <option value="precio" <?= $ordenActual === 'precio' ? 'selected' : '' ?>>Precio unitario</option>
                        <option value="categoria" <?= $ordenActual === 'categoria' ? 'selected' : '' ?>>Categoría</option>
                    </select>
                </div>

                <div class="filtro-item">
                    <label for="filtro-dir">Dirección:</label>
                    <select id="filtro-dir" name="dir">
                        <option value="DESC" <?= $dirActual === 'DESC' ? 'selected' : '' ?>>Descendente (↓)</option>
                        <option value="ASC" <?= $dirActual === 'ASC' ? 'selected' : '' ?>>Ascendente (↑)</option>
                    </select>
                </div>

                <div class="filtro-acciones">
                    <button type="submit" class="btn-filtrar">Aplicar Filtros</button>
                    <a href="index.php" class="btn-limpiar">Limpiar</a>
                </div>
            </form>

            <?php if ($qActual !== '' || $catActual !== '' || $stockActual !== ''): ?>
                <div class="filtros-activos-banner">
                    <span>🎯 <strong>Filtros activos:</strong></span>
                    <?php if ($qActual !== ''): ?>
                        <span class="chip-filtro">Búsqueda: "<?= htmlspecialchars($qActual, ENT_QUOTES, 'UTF-8') ?>"</span>
                    <?php endif; ?>
                    <?php if ($catActual !== ''): ?>
                        <span class="chip-filtro">Categoría: <?= htmlspecialchars($catActual, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if ($stockActual !== ''): ?>
                        <span class="chip-filtro">Stock: <?= htmlspecialchars($stockActual, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <a href="exportar_csv.php?<?= htmlspecialchars(http_build_query($_GET), ENT_QUOTES, 'UTF-8') ?>" class="enlace-exportar-filtro">
                        📤 Exportar estos resultados a CSV
                    </a>
                </div>
            <?php endif; ?>

            <!-- Tabla reutilizable de productos (Semana 12) -->
            <?php require __DIR__ . '/../componentes/tabla_productos.php'; ?>

            <!-- Paginación reutilizable (Semana 13) -->
            <?php require __DIR__ . '/../componentes/paginacion.php'; ?>
        </section>
    </div>

    <!-- SECCIÓN 2: FORMULARIO DE ALTA (Visible según permisos del rol - Semana 09) -->
    <?php if ($puedeRegistrar): ?>
    <section class="caja seccion-formulario" id="seccion-alta">
        <h2>➕ Registrar Nuevo Producto</h2>

        <form action="crear.php" method="POST" id="formulario-inventario">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <div class="grupo-input">
                <label for="nombre">Nombre del Producto: *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej. Teclado Mecánico" maxlength="100" required>
            </div>

            <div class="grupo-input">
                <label for="stock_nuevo">Cantidad a ingresar (Stock): *</label>
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

            <div class="grupo-checkbox">
                <label>
                    <input type="checkbox" id="usarFetchGuardar" checked>
                    Guardar sin recargar página usando <strong>Fetch API (JSON)</strong>
                </label>
            </div>

            <button type="submit" class="btn-guardar" id="btnGuardar">Registrar Producto</button>
        </form>

        <br>
        <button id="btnMostrarInfo" class="btn-guardar btn-gris" type="button">Mostrar Ayuda</button>
        <div id="info-extra" class="caja-ayuda" style="display: none;">
            <p><strong>Tip de validación:</strong> Todos los campos son obligatorios. El nombre se convierte automáticamente a mayúsculas y tanto el precio como el stock se validan en JavaScript y en el servidor con PHP.</p>
        </div>
    </section>
    <?php else: ?>
    <section class="caja seccion-formulario">
        <h2>🔒 Modo Consulta</h2>
        <p>Has iniciado sesión con el rol <strong><?= htmlspecialchars(ucfirst($rolActual), ENT_QUOTES, 'UTF-8') ?></strong>.</p>
        <p>Tu cuenta tiene permiso para consultar el inventario, aplicar filtros, ordenar resultados, ver estadísticas y exportar reportes CSV, pero las operaciones de creación, edición y baja están restringidas a cuentas autorizadas.</p>
    </section>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
