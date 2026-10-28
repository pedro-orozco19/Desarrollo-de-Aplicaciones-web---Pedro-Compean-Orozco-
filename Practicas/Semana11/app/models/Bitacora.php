<?php
/**
 * Modelo Bitacora (Función Avanzada — Semana 13).
 * Registra acciones relevantes del sistema sin almacenar contraseñas ni datos sensibles.
 */
class Bitacora
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrar(?int $usuarioId, string $usuarioNombre, string $accion, string $modulo, ?int $registroId, string $detalle): void
    {
        $sql = "INSERT INTO bitacora (usuario_id, usuario_nombre, accion, modulo, registro_id, detalle)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('isssis', $usuarioId, $usuarioNombre, $accion, $modulo, $registroId, $detalle);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function obtenerRecientes(int $limite = 50): array
    {
        $limite = max(1, min(200, $limite));
        $sql = "SELECT id, usuario_id, usuario_nombre, accion, modulo, registro_id, detalle, creado_en
                FROM bitacora
                ORDER BY id DESC
                LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $limite);
        $stmt->execute();
        $res = $stmt->get_result();
        $filas = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $filas;
    }
}
