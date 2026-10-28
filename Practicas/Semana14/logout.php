<?php
/**
 * Cierre seguro de sesión (Semana 07 y Semana 08).
 * Limpia $_SESSION, invalida la cookie de sesión y destruye la sesión en el servidor.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $parametros = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $parametros['path'],
        $parametros['domain'],
        $parametros['secure'],
        $parametros['httponly']
    );
}

session_destroy();

session_start();
$_SESSION['flash_mensaje'] = 'Has cerrado sesión correctamente.';
$_SESSION['flash_tipo'] = 'exito';

header('Location: login_form.php');
exit;
