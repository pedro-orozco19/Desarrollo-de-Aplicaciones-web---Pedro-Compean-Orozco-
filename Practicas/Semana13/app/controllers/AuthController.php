<?php
/**
 * Controlador AuthController (Semanas 07, 08, 09 y 11).
 * Procesa el inicio y cierre de sesión con password_verify(), regeneración de ID de sesión
 * y registro de auditoría.
 */
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Bitacora.php';

class AuthController
{
    private Usuario $usuarioModel;
    private Bitacora $bitacora;

    public function __construct(Usuario $usuarioModel, Bitacora $bitacora)
    {
        $this->usuarioModel = $usuarioModel;
        $this->bitacora = $bitacora;
    }

    public function iniciarSesion(string $usuario, string $contrasena): array
    {
        $usuario = trim($usuario);

        if ($usuario === '' || $contrasena === '') {
            return [
                'ok' => false,
                'mensaje' => 'Por favor ingresa tu usuario y contraseña.'
            ];
        }

        $fila = $this->usuarioModel->buscarPorUsuario($usuario);

        // Mensaje genérico para no revelar si falló el usuario o la contraseña (Semana 08)
        if (!$fila || (int)$fila['activo'] !== 1 || !password_verify($contrasena, $fila['password_hash'])) {
            return [
                'ok' => false,
                'mensaje' => 'No fue posible iniciar sesión con esas credenciales.'
            ];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int)$fila['id'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['rol'] = $fila['rol'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->bitacora->registrar(
            (int)$fila['id'],
            $fila['nombre'],
            'LOGIN',
            'autenticacion',
            (int)$fila['id'],
            "Inicio de sesión exitoso con rol '{$fila['rol']}'"
        );

        return [
            'ok' => true,
            'mensaje' => 'Bienvenido de nuevo, ' . $fila['nombre'] . '.'
        ];
    }
}
