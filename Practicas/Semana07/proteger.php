<?php
/**
 * Middleware de protección de páginas internas (Semana 07 y Semana 08).
 * Verifica en el servidor que exista una sesión autenticada válida.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['flash_mensaje'] = 'Debes iniciar sesión para acceder a este módulo.';
    $_SESSION['flash_tipo'] = 'error';
    header('Location: login_form.php');
    exit;
}

// Generar token CSRF de sesión si aún no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
