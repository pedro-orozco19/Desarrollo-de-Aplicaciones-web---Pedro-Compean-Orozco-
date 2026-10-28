<?php
/**
 * Script de creación segura de usuario de demostración con password_hash() (Semana 07).
 */
require_once __DIR__ . '/conexion.php';

$nombre = "Administrador de prueba";
$usuario = "admin_prueba";
$correo = "admin.demo@inventario.local";
$contrasena = "Admin_Demo2026!";

$stmtCheck = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
$stmtCheck->bind_param("s", $usuario);
$stmtCheck->execute();
$existe = $stmtCheck->get_result()->fetch_assoc();
$stmtCheck->close();

if ($existe) {
    echo "El usuario de demostración 'admin_prueba' ya se encuentra registrado.";
    exit;
}

$hash = password_hash($contrasena, PASSWORD_DEFAULT);
$sql = "INSERT INTO usuarios (nombre, usuario, correo, password_hash, rol, activo) VALUES (?, ?, ?, ?, 'administrador', 1)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssss", $nombre, $usuario, $correo, $hash);
$stmt->execute();
$stmt->close();

echo "Usuario de prueba creado correctamente con hash seguro.";
