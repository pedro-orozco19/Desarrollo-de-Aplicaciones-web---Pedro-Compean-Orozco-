<?php
require_once __DIR__ . '/autorizacion.php';
exigirRol(['administrador']);
require_once __DIR__ . '/conexion.php';

$rolesPermitidos = ['administrador', 'operador', 'consulta'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear_usuario') {
        $nombre = trim($_POST['nombre'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $rol = trim($_POST['rol'] ?? 'consulta');

        if ($nombre === '' || $usuario === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($contrasena) < 8 || !in_array($rol, $rolesPermitidos, true)) {
            $_SESSION['flash_mensaje'] = 'Verifica todos los campos del usuario (contraseña mínimo 8 caracteres).';
            $_SESSION['flash_tipo'] = 'error';
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, correo, password_hash, rol, activo) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->bind_param('sssss', $nombre, $usuario, $correo, $hash, $rol);
            if ($stmt->execute()) {
                $_SESSION['flash_mensaje'] = "Usuario '$usuario' creado con rol '$rol'.";
                $_SESSION['flash_tipo'] = 'exito';
            } else {
                $_SESSION['flash_mensaje'] = 'El usuario o correo ya existe.';
                $_SESSION['flash_tipo'] = 'error';
            }
            $stmt->close();
        }
        header('Location: usuarios.php');
        exit;
    }

    if ($accion === 'cambiar_rol') {
        $idObjetivo = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT) ?: 0;
        $nuevoRol = trim($_POST['rol'] ?? '');
        $activo = (int)($_POST['activo'] ?? 1) === 0 ? 0 : 1;

        if ($idObjetivo === (int)$_SESSION['usuario_id'] && ($nuevoRol !== 'administrador' || $activo !== 1)) {
            $_SESSION['flash_mensaje'] = 'No puedes degradar ni desactivar tu propia cuenta administradora en sesión.';
            $_SESSION['flash_tipo'] = 'error';
        } elseif ($idObjetivo > 0 && in_array($nuevoRol, $rolesPermitidos, true)) {
            $stmt = $conexion->prepare("UPDATE usuarios SET rol = ?, activo = ? WHERE id = ?");
            $stmt->bind_param('sii', $nuevoRol, $activo, $idObjetivo);
            $stmt->execute();
            $stmt->close();
            $_SESSION['flash_mensaje'] = 'Permisos actualizados correctamente.';
            $_SESSION['flash_tipo'] = 'exito';
        }
        header('Location: usuarios.php');
        exit;
    }
}

$resUsuarios = $conexion->query("SELECT id, nombre, usuario, correo, rol, activo FROM usuarios ORDER BY id ASC");
$listaUsuarios = $resUsuarios ? $resUsuarios->fetch_all(MYSQLI_ASSOC) : [];

$mensaje = $_SESSION['flash_mensaje'] ?? '';
$tipoMsg = $_SESSION['flash_tipo'] ?? 'exito';
unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios y Roles</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header class="encabezado">
        <div class="encabezado-contenido">
            <div>
                <h1>👥 Gestión de Usuarios y Roles</h1>
            </div>
            <div class="info-usuario-header">
                <a href="index.php" class="menu-item" style="text-decoration:none;">⬅️ Volver al Inventario</a>
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
            <section class="caja caja-ancha">
                <h2>Cuentas Registradas</h2>
                <div class="tabla-responsive">
                    <table class="tabla-inventario">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Actualizar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaUsuarios as $u): ?>
                                <tr>
                                    <td class="celda-centro">#<?= (int)$u['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($u['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><code><?= htmlspecialchars($u['usuario'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                    <td><?= htmlspecialchars($u['correo'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celda-centro"><?= htmlspecialchars(ucfirst($u['rol']), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celda-centro"><?= (int)$u['activo'] === 1 ? 'Activo' : 'Inactivo' ?></td>
                                    <td>
                                        <form action="usuarios.php" method="POST" class="form-rol-inline">
                                            <input type="hidden" name="accion" value="cambiar_rol">
                                            <input type="hidden" name="usuario_id" value="<?= (int)$u['id'] ?>">
                                            <select name="rol">
                                                <option value="administrador" <?= $u['rol'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                                                <option value="operador" <?= $u['rol'] === 'operador' ? 'selected' : '' ?>>Operador</option>
                                                <option value="consulta" <?= $u['rol'] === 'consulta' ? 'selected' : '' ?>>Consulta</option>
                                            </select>
                                            <select name="activo">
                                                <option value="1" <?= (int)$u['activo'] === 1 ? 'selected' : '' ?>>Activo</option>
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
            <h2>➕ Nuevo Usuario</h2>
            <form action="usuarios.php" method="POST" autocomplete="off">
                <input type="hidden" name="accion" value="crear_usuario">
                <div class="grupo-input">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="grupo-input">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="grupo-input">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="grupo-input">
                    <label for="contrasena">Contraseña (mín. 8 caracteres):</label>
                    <input type="password" id="contrasena" name="contrasena" minlength="8" required>
                </div>
                <div class="grupo-input">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" required>
                        <option value="consulta">Consulta</option>
                        <option value="operador">Operador</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn-guardar">Crear Usuario</button>
            </form>
        </section>
    </main>
    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web — Proyecto Integrador · Pedro Compean Orozco</p>
    </footer>
</body>
</html>
