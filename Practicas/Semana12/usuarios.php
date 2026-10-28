<?php
/**
 * Módulo de Administración de Usuarios y Roles (Semana 09 y Semana 12).
 * Restringido en el servidor exclusivamente al rol 'administrador'.
 */
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador']);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/UsuarioController.php';

$modeloUsuario = new Usuario($conexion);
$modeloBitacora = new Bitacora($conexion);
$controlador = new UsuarioController($modeloUsuario, $modeloBitacora);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear_usuario') {
        $res = $controlador->crearUsuario(
            $_POST,
            (int)$_SESSION['usuario_id'],
            (string)($_SESSION['nombre'] ?? $_SESSION['usuario'])
        );
        $_SESSION['flash_mensaje'] = $res['mensaje'];
        $_SESSION['flash_tipo'] = $res['ok'] ? 'exito' : 'error';
        header('Location: usuarios.php');
        exit;
    }

    if ($accion === 'cambiar_rol') {
        $idObjetivo = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT) ?: 0;
        $nuevoRol = trim((string)($_POST['rol'] ?? ''));
        $activo = filter_input(INPUT_POST, 'activo', FILTER_VALIDATE_INT);
        $activoVal = ($activo === 0) ? 0 : 1;

        $res = $controlador->cambiarRol(
            $idObjetivo,
            $nuevoRol,
            $activoVal,
            (int)$_SESSION['usuario_id'],
            (string)($_SESSION['nombre'] ?? $_SESSION['usuario'])
        );
        $_SESSION['flash_mensaje'] = $res['mensaje'];
        $_SESSION['flash_tipo'] = $res['ok'] ? 'exito' : 'error';
        header('Location: usuarios.php');
        exit;
    }
}

$listaUsuarios = $modeloUsuario->obtenerTodos();

$tituloPagina = 'Gestión de Usuarios y Roles';
$paginaActiva = 'usuarios';
$incluirScriptJs = false;

require __DIR__ . '/app/views/layouts/header.php';
require __DIR__ . '/app/views/layouts/menu.php';
?>

<main class="contenedor-principal">
    <div class="columna-principal">
        <?php require __DIR__ . '/app/views/layouts/mensajes.php'; ?>

        <section class="caja caja-ancha">
            <h2>👥 Cuentas Registradas y Asignación de Roles</h2>
            <p style="color: #555; margin-bottom: 15px;">
                Desde este panel exclusivo para administradores puedes consultar las cuentas y gestionar sus roles y estado de acceso. Las contraseñas se almacenan cifradas con <code>password_hash()</code>.
            </p>

            <div class="tabla-responsive">
                <table class="tabla-inventario">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol Actual</th>
                            <th>Estado</th>
                            <th>Actualizar Permisos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaUsuarios as $u): ?>
                            <tr>
                                <td class="celda-centro">#<?= (int)$u['id'] ?></td>
                                <td><strong><?= htmlspecialchars($u['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                <td><code><?= htmlspecialchars($u['usuario'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                <td><?= htmlspecialchars($u['correo'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="celda-centro">
                                    <span class="badge-rol rol-<?= htmlspecialchars($u['rol'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars(ucfirst($u['rol']), ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="celda-centro">
                                    <?= (int)$u['activo'] === 1
                                        ? '<span class="badge-stock stock-ok">Activo</span>'
                                        : '<span class="badge-stock stock-bajo">Inactivo</span>' ?>
                                </td>
                                <td>
                                    <form action="usuarios.php" method="POST" class="form-rol-inline">
                                        <input type="hidden" name="accion" value="cambiar_rol">
                                        <input type="hidden" name="usuario_id" value="<?= (int)$u['id'] ?>">

                                        <select name="rol" aria-label="Rol">
                                            <option value="administrador" <?= $u['rol'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                                            <option value="operador" <?= $u['rol'] === 'operador' ? 'selected' : '' ?>>Operador</option>
                                            <option value="consulta" <?= $u['rol'] === 'consulta' ? 'selected' : '' ?>>Consulta</option>
                                        </select>

                                        <select name="activo" aria-label="Estado">
                                            <option value="1" <?= (int)$u['activo'] === 1 ? 'selected' : '' ?>>Activo</option>
                                            <option value="0" <?= (int)$u['activo'] === 0 ? 'selected' : '' ?>>Inactivo</option>
                                        </select>

                                        <button type="submit" class="btn-accion btn-editar">Guardar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <section class="caja seccion-formulario">
        <h2>➕ Crear Usuario Nuevo</h2>
        <form action="usuarios.php" method="POST" autocomplete="off">
            <input type="hidden" name="accion" value="crear_usuario">

            <div class="grupo-input">
                <label for="nombre">Nombre completo: *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej. Ana López" required>
            </div>

            <div class="grupo-input">
                <label for="usuario">Nombre de usuario: *</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ej. ana_consulta" required>
            </div>

            <div class="grupo-input">
                <label for="correo">Correo electrónico: *</label>
                <input type="email" id="correo" name="correo" placeholder="ana@inventario.local" required>
            </div>

            <div class="grupo-input">
                <label for="contrasena">Contraseña (mín. 8 caracteres): *</label>
                <input type="password" id="contrasena" name="contrasena" minlength="8" required>
            </div>

            <div class="grupo-input">
                <label for="rol">Rol asignado: *</label>
                <select id="rol" name="rol" required>
                    <option value="consulta">Usuario de Consulta (Solo lectura)</option>
                    <option value="operador">Operador de Almacén (Crear y Editar)</option>
                    <option value="administrador">Administrador (Acceso completo)</option>
                </select>
            </div>

            <button type="submit" class="btn-guardar">Registrar Usuario</button>
        </form>
    </section>
</main>

<?php require __DIR__ . '/app/views/layouts/footer.php'; ?>
