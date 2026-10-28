<?php
/**
 * Formulario de inicio de sesión (Semana 07, 08 y 12).
 * Si el usuario ya tiene sesión activa, lo dirige al panel principal.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$tituloPagina = 'Iniciar Sesión';
$incluirScriptJs = false;
require __DIR__ . '/app/views/layouts/header.php';
?>

<main class="contenedor-principal">
    <section class="caja seccion-formulario" style="max-width: 440px;">
        <h2>🔐 Acceso al Sistema</h2>
        <p style="color: #555; margin-bottom: 15px;">Ingresa tus credenciales para acceder al Gestor de Inventario.</p>

        <?php require __DIR__ . '/app/views/layouts/mensajes.php'; ?>

        <form action="login.php" method="POST" autocomplete="off">
            <div class="grupo-input">
                <label for="usuario">Usuario:</label>
                <input id="usuario" name="usuario" type="text" placeholder="Ej. admin_prueba" required autofocus>
            </div>

            <div class="grupo-input">
                <label for="contrasena">Contraseña:</label>
                <input id="contrasena" name="contrasena" type="password" placeholder="Ingresa tu contraseña" required>
            </div>

            <button type="submit" class="btn-guardar">Iniciar sesión</button>
        </form>

        <div class="caja-ayuda" style="margin-top: 20px;">
            <p style="margin: 0 0 8px 0;"><strong>🧪 Cuentas de demostración (Entorno de Pruebas):</strong></p>
            <ul style="margin: 0; padding-left: 18px; font-size: 0.9em; line-height: 1.6;">
                <li><strong>Administrador:</strong> <code>admin_prueba</code> / <code>Admin_Demo2026!</code></li>
                <li><strong>Operador:</strong> <code>operador_prueba</code> / <code>Operador_Demo2026!</code></li>
                <li><strong>Consulta:</strong> <code>consulta_prueba</code> / <code>Consulta_Demo2026!</code></li>
            </ul>
        </div>
    </section>
</main>

<?php require __DIR__ . '/app/views/layouts/footer.php'; ?>
