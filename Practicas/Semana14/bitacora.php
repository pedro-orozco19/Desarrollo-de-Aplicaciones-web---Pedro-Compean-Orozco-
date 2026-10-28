<?php
/**
 * Módulo de Bitácora de Acciones (Función Avanzada — Semana 13).
 * Exclusivo para el rol 'administrador'. No almacena contraseñas ni datos sensibles.
 */
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador']);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Bitacora.php';

$modeloBitacora = new Bitacora($conexion);
$eventos = $modeloBitacora->obtenerRecientes(60);

$tituloPagina = 'Bitácora de Auditoría';
$paginaActiva = 'bitacora';
$incluirScriptJs = false;

require __DIR__ . '/app/views/layouts/header.php';
require __DIR__ . '/app/views/layouts/menu.php';
?>

<main class="contenedor-principal">
    <div class="columna-principal">
        <?php require __DIR__ . '/app/views/layouts/mensajes.php'; ?>

        <section class="caja caja-ancha">
            <h2>📝 Bitácora de Acciones del Sistema</h2>
            <p style="color: #555; margin-bottom: 15px;">
                Registro cronológico de inicios de sesión, altas, ediciones, bajas y cambios administrativos. Por seguridad, nunca se almacenan contraseñas ni tokens en esta bitácora.
            </p>

            <div class="tabla-responsive">
                <table class="tabla-inventario">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha y Hora</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Módulo</th>
                            <th>ID Reg.</th>
                            <th>Detalle de la Operación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($eventos) > 0): ?>
                            <?php foreach ($eventos as $ev): ?>
                                <tr>
                                    <td class="celda-centro">#<?= (int)$ev['id'] ?></td>
                                    <td><?= htmlspecialchars((string)$ev['creado_en'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><strong><?= htmlspecialchars((string)$ev['usuario_nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td class="celda-centro">
                                        <span class="badge-categoria"><?= htmlspecialchars((string)$ev['accion'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </td>
                                    <td class="celda-centro"><?= htmlspecialchars((string)$ev['modulo'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celda-centro"><?= $ev['registro_id'] !== null ? '#' . (int)$ev['registro_id'] : '-' ?></td>
                                    <td><?= htmlspecialchars((string)$ev['detalle'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="celda-vacia">Aún no hay eventos registrados en la bitácora.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<?php require __DIR__ . '/app/views/layouts/footer.php'; ?>
