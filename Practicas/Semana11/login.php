<?php
/**
 * Procesador de autenticación con PHP y MySQL (Semanas 07, 08 y 11).
 * Verifica credenciales con password_verify(), valida que el usuario esté activo
 * y regenera el identificador de sesión.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_form.php');
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

$usuario = trim((string)($_POST['usuario'] ?? ''));
$contrasena = (string)($_POST['contrasena'] ?? '');

$usuarioModel = new Usuario($conexion);
$bitacoraModel = new Bitacora($conexion);
$authController = new AuthController($usuarioModel, $bitacoraModel);

$resultado = $authController->iniciarSesion($usuario, $contrasena);

if ($resultado['ok']) {
    $_SESSION['flash_mensaje'] = $resultado['mensaje'];
    $_SESSION['flash_tipo'] = 'exito';
    header('Location: index.php');
    exit;
}

$_SESSION['flash_mensaje'] = $resultado['mensaje'];
$_SESSION['flash_tipo'] = 'error';
header('Location: login_form.php');
exit;
