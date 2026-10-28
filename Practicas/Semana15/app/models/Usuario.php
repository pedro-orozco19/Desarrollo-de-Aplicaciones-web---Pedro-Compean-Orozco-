<?php
/**
 * Modelo Usuario (Semanas 07, 08, 09 y 11).
 * Gestiona la consulta segura de usuarios, verificación de contraseñas con password_verify()
 * y administración de roles.
 */
class Usuario
{
    private mysqli $conexion;

    public const ROLES_PERMITIDOS = ['administrador', 'operador', 'consulta'];

    public function __construct(mysqli $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Busca un usuario por su nombre de usuario mediante consulta preparada.
     */
    public function buscarPorUsuario(string $usuario): ?array
    {
        $sql = "SELECT id, nombre, usuario, correo, password_hash, rol, activo
                FROM usuarios
                WHERE usuario = ?
                LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $fila ?: null;
    }

    /**
     * Lista todos los usuarios registrados (sin exponer password_hash).
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT id, nombre, usuario, correo, rol, activo, creado_en
                FROM usuarios
                ORDER BY id ASC";
        $res = $this->conexion->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Crea un usuario nuevo aplicando password_hash() (Semana 07, 08 y 09).
     */
    public function crear(string $nombre, string $usuario, string $correo, string $contrasenaPlana, string $rol = 'consulta'): int
    {
        if (!in_array($rol, self::ROLES_PERMITIDOS, true)) {
            $rol = 'consulta';
        }
        $hash = password_hash($contrasenaPlana, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, usuario, correo, password_hash, rol, activo)
                VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('sssss', $nombre, $usuario, $correo, $hash, $rol);
        $ok = $stmt->execute();
        $id = $ok ? (int)$this->conexion->insert_id : 0;
        $stmt->close();
        return $id;
    }

    /**
     * Cambia el rol y estado activo de un usuario de forma controlada (Semana 09).
     */
    public function actualizarRolYEstado(int $id, string $rol, int $activo): bool
    {
        if (!in_array($rol, self::ROLES_PERMITIDOS, true)) {
            return false;
        }
        $activo = $activo === 1 ? 1 : 0;

        $sql = "UPDATE usuarios SET rol = ?, activo = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('sii', $rol, $activo, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
