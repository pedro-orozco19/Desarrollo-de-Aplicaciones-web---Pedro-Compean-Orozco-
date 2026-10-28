<?php
/**
 * Controlador UsuarioController (Semana 09 y Semana 11).
 * Permite al administrador gestionar usuarios y roles con validaciones en el servidor.
 */
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Bitacora.php';

class UsuarioController
{
    private Usuario $modelo;
    private Bitacora $bitacora;

    public function __construct(Usuario $modelo, Bitacora $bitacora)
    {
        $this->modelo = $modelo;
        $this->bitacora = $bitacora;
    }

    public function crearUsuario(array $entrada, int $adminId, string $adminNombre): array
    {
        $nombre = trim((string)($entrada['nombre'] ?? ''));
        $usuario = trim((string)($entrada['usuario'] ?? ''));
        $correo = trim((string)($entrada['correo'] ?? ''));
        $contrasena = (string)($entrada['contrasena'] ?? '');
        $rol = trim((string)($entrada['rol'] ?? 'consulta'));

        if ($nombre === '' || $usuario === '' || $correo === '' || $contrasena === '') {
            return ['ok' => false, 'mensaje' => 'Todos los campos del usuario son obligatorios.'];
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'mensaje' => 'El correo electrónico no tiene un formato válido.'];
        }
        if (mb_strlen($contrasena) < 8) {
            return ['ok' => false, 'mensaje' => 'La contraseña debe tener al menos 8 caracteres.'];
        }
        if (!in_array($rol, Usuario::ROLES_PERMITIDOS, true)) {
            return ['ok' => false, 'mensaje' => 'El rol seleccionado no es válido.'];
        }
        if ($this->modelo->buscarPorUsuario($usuario)) {
            return ['ok' => false, 'mensaje' => 'El nombre de usuario ya está registrado.'];
        }

        $nuevoId = $this->modelo->crear($nombre, $usuario, $correo, $contrasena, $rol);
        if ($nuevoId <= 0) {
            return ['ok' => false, 'mensaje' => 'No fue posible crear el usuario (verifica que el correo no esté duplicado).'];
        }

        $this->bitacora->registrar(
            $adminId,
            $adminNombre,
            'CREAR_USUARIO',
            'usuarios',
            $nuevoId,
            "Alta de cuenta '{$usuario}' con rol '{$rol}'"
        );

        return ['ok' => true, 'mensaje' => "Usuario '{$usuario}' creado con rol '{$rol}'."];
    }

    public function cambiarRol(int $idObjetivo, string $nuevoRol, int $activo, int $adminId, string $adminNombre): array
    {
        if ($idObjetivo <= 0) {
            return ['ok' => false, 'mensaje' => 'Identificador de usuario inválido.'];
        }
        // Evitar que el administrador actual se degrade o desactive a sí mismo por accidente
        if ($idObjetivo === $adminId && ($nuevoRol !== 'administrador' || $activo !== 1)) {
            return ['ok' => false, 'mensaje' => 'Por seguridad no puedes degradar ni desactivar tu propia cuenta administradora en sesión.'];
        }

        if (!$this->modelo->actualizarRolYEstado($idObjetivo, $nuevoRol, $activo)) {
            return ['ok' => false, 'mensaje' => 'No fue posible actualizar el rol o estado del usuario.'];
        }

        $this->bitacora->registrar(
            $adminId,
            $adminNombre,
            'CAMBIO_ROL',
            'usuarios',
            $idObjetivo,
            "Actualización de usuario ID #{$idObjetivo} a rol '{$nuevoRol}' (activo={$activo})"
        );

        return ['ok' => true, 'mensaje' => 'Permisos del usuario actualizados correctamente.'];
    }
}
