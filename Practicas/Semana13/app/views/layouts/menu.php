<?php
/**
 * Componente reutilizable: Menú de navegación dinámico según el rol (Semanas 09 y 12).
 * Adapta las opciones visibles a los permisos del usuario autenticado y a los módulos presentes.
 */
$paginaActiva = $paginaActiva ?? 'inventario';
$rolMenu = $_SESSION['rol'] ?? '';
$raizModulo = dirname(__DIR__, 3);
?>
<?php if (!empty($_SESSION['usuario_id'])): ?>
<nav class="menu-navegacion" aria-label="Menú principal">
    <div class="menu-enlaces">
        <a href="index.php" class="menu-item <?= $paginaActiva === 'inventario' ? 'activo' : '' ?>">
            📋 Inventario y Búsqueda
        </a>

        <?php if (in_array($rolMenu, ['administrador', 'operador'], true)): ?>
            <a href="index.php#seccion-alta" class="menu-item">
                ➕ Registrar Producto
            </a>
        <?php endif; ?>

        <?php if (file_exists($raizModulo . '/reportes.php')): ?>
            <a href="reportes.php" class="menu-item <?= $paginaActiva === 'reportes' ? 'activo' : '' ?>">
                📊 Estadísticas y Reportes
            </a>
        <?php endif; ?>

        <?php if (file_exists($raizModulo . '/exportar_csv.php')): ?>
            <a href="exportar_csv.php" class="menu-item">
                📤 Exportar CSV
            </a>
        <?php endif; ?>

        <?php if ($rolMenu === 'administrador'): ?>
            <a href="usuarios.php" class="menu-item <?= $paginaActiva === 'usuarios' ? 'activo' : '' ?>">
                👥 Usuarios y Roles
            </a>
            <?php if (file_exists($raizModulo . '/bitacora.php')): ?>
                <a href="bitacora.php" class="menu-item <?= $paginaActiva === 'bitacora' ? 'activo' : '' ?>">
                    📝 Bitácora de Auditoría
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="menu-derecha">
        <a href="logout.php" class="menu-item btn-salir">🚪 Cerrar sesión</a>
    </div>
</nav>
<?php endif; ?>
