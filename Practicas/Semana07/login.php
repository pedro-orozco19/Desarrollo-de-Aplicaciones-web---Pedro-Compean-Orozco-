<?php
session_start();
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_form.php');
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if ($usuario === '' || $contrasena === '') {
    $_SESSION['flash_mensaje'] = 'Escribe tu usuario y contraseña.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: login_form.php');
    exit;
}

$sql = "SELECT id, nombre, usuario, password_hash, rol, activo FROM usuarios WHERE usuario = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();
$stmt->close();

if ($fila && (int)$fila['activo'] === 1 && password_verify($contrasena, $fila['password_hash'])) {
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int)$fila['id'];
    $_SESSION['nombre'] = $fila['nombre'];
    $_SESSION['usuario'] = $fila['usuario'];
    $_SESSION['rol'] = $fila['rol'] ?? 'administrador';

    header('Location: panel.php');
    exit;
}

$_SESSION['flash_mensaje'] = 'No fue posible iniciar sesión con esas credenciales.';
$_SESSION['flash_tipo'] = 'error';
header('Location: login_form.php');
exit;
