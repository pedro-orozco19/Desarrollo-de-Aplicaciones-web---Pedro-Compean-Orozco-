<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/conexion.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: panel.php');
    exit;
}

$mensaje = $_SESSION['flash_mensaje'] ?? '';
$tipoMsg = $_SESSION['flash_tipo'] ?? 'error';
unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Gestor de Inventario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header class="encabezado">
        <h1>📦 Gestor de Inventario</h1>
    </header>
    <main class="contenedor-principal">
        <section class="caja seccion-formulario" style="max-width: 440px;">
            <h2>🔐 Iniciar Sesión</h2>
            <?php if ($mensaje !== ''): ?>
                <div class="<?= $tipoMsg === 'error' ? 'mensaje-error' : 'mensaje-exito' ?>">
                    <?= htmlspecialchars((string)$mensaje, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST" autocomplete="off">
                <div class="grupo-input">
                    <label for="usuario">Usuario</label>
                    <input id="usuario" name="usuario" type="text" required autofocus>
                </div>
                <div class="grupo-input">
                    <label for="contrasena">Contraseña</label>
                    <input id="contrasena" name="contrasena" type="password" required>
                </div>
                <button type="submit" class="btn-guardar">Iniciar sesión</button>
            </form>
            <div class="caja-ayuda" style="margin-top: 18px;">
                <p style="margin:0 0 6px 0;"><strong>🧪 Cuentas de demostración:</strong></p>
                <ul style="margin:0; padding-left:18px; font-size:0.9em;">
                    <li><code>admin_prueba</code> / <code>Admin_Demo2026!</code></li>
                    <li><code>operador_prueba</code> / <code>Operador_Demo2026!</code></li>
                    <li><code>consulta_prueba</code> / <code>Consulta_Demo2026!</code></li>
                </ul>
            </div>
        </section>
    </main>
    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web — Proyecto Integrador · Pedro Compean Orozco</p>
    </footer>
</body>
</html>
