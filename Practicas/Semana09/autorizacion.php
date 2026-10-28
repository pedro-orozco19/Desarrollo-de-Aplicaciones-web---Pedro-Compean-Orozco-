<?php
/**
 * Middleware reutilizable de autorización basada en roles (Semana 09).
 * Comprueba los permisos desde el servidor (PHP) antes de cualquier operación restringida.
 */
require_once __DIR__ . '/proteger.php';

/**
 * Exige que el usuario autenticado tenga uno de los roles permitidos.
 *
 * @param array $rolesPermitidos Lista de roles autorizados (ej. ['administrador', 'operador'])
 * @param bool  $respuestaJson   Si es true, devuelve JSON con código 403 (para endpoints Fetch API)
 */
function exigirRol(array $rolesPermitidos, bool $respuestaJson = false): void
{
    $rolActual = $_SESSION['rol'] ?? '';

    if (!in_array($rolActual, $rolesPermitidos, true)) {
        http_response_code(403);
        if ($respuestaJson) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => false,
                'mensaje' => 'No tienes permiso para realizar esta operación.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $_SESSION['flash_mensaje'] = 'Acceso denegado (403): Tu rol (' . htmlspecialchars($rolActual, ENT_QUOTES, 'UTF-8') . ') no tiene permiso para realizar esta operación.';
        $_SESSION['flash_tipo'] = 'error';
        header('Location: index.php');
        exit;
    }
}

/**
 * Comprueba si el usuario actual posee alguno de los roles indicados.
 */
function tieneRol(array $rolesPermitidos): bool
{
    $rolActual = $_SESSION['rol'] ?? '';
    return in_array($rolActual, $rolesPermitidos, true);
}
