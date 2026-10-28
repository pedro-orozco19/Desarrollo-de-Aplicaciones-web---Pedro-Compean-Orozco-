<?php
/**
 * Modelo Producto (Arquitectura MVC — Semanas 11, 12 y 13).
 * Concentra todas las operaciones CRUD, búsqueda, filtros, ordenamiento seguro,
 * paginación y estadísticas sobre la tabla `productos`.
 */
class Producto
{
    private mysqli $conexion;

    /** Lista permitida de categorías válidas en las reglas de negocio */
    public const CATEGORIAS_PERMITIDAS = ['Electronica', 'Mobiliario', 'Limpieza'];

    /** Lista blanca de columnas permitidas para ordenamiento SQL (Semana 13) */
    public const COLUMNAS_ORDEN = ['id', 'nombre', 'stock', 'precio', 'categoria'];

    public function __construct(mysqli $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Obtiene todos los productos activos ordenados por ID descendente.
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT id, nombre, stock, precio, correo_proveedor, categoria
                FROM productos
                WHERE activo = 1
                ORDER BY id DESC";
        $resultado = $this->conexion->query($sql);
        if (!$resultado) {
            return [];
        }
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Consulta un producto por su clave primaria usando consulta preparada.
     */
    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT id, nombre, stock, precio, correo_proveedor, categoria
                FROM productos
                WHERE id = ? AND activo = 1
                LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $fila ?: null;
    }

    /**
     * Búsqueda, filtrado, ordenamiento con lista permitida y paginación (Semana 13).
     */
    public function listarAvanzado(
        string $termino = '',
        string $categoria = '',
        string $estadoStock = '',
        string $orden = 'id',
        string $direccion = 'DESC',
        int $pagina = 1,
        int $porPagina = 6
    ): array {
        // 1. Validar ordenamiento contra lista blanca (NUNCA concatenar entrada directa)
        $columnaOrden = in_array($orden, self::COLUMNAS_ORDEN, true) ? $orden : 'id';
        $dirOrden = strtoupper($direccion) === 'ASC' ? 'ASC' : 'DESC';

        // 2. Construir condiciones dinámicas con parámetros preparados
        $condiciones = ["activo = 1"];
        $tipos = '';
        $parametros = [];

        $termino = trim($termino);
        if ($termino !== '') {
            $condiciones[] = "(nombre LIKE ? OR correo_proveedor LIKE ?)";
            $like = '%' . $termino . '%';
            $tipos .= 'ss';
            $parametros[] = $like;
            $parametros[] = $like;
        }

        if ($categoria !== '' && in_array($categoria, self::CATEGORIAS_PERMITIDAS, true)) {
            $condiciones[] = "categoria = ?";
            $tipos .= 's';
            $parametros[] = $categoria;
        }

        if ($estadoStock === 'bajo') {
            $condiciones[] = "stock <= 15";
        } elseif ($estadoStock === 'suficiente') {
            $condiciones[] = "stock > 15";
        }

        $whereSql = implode(' AND ', $condiciones);

        // 3. Contar total de registros coincidentes para la paginación
        $sqlTotal = "SELECT COUNT(*) AS total, COALESCE(SUM(stock * precio), 0) AS valor_total
                     FROM productos
                     WHERE {$whereSql}";
        $stmtTotal = $this->conexion->prepare($sqlTotal);
        if ($tipos !== '') {
            $stmtTotal->bind_param($tipos, ...$parametros);
        }
        $stmtTotal->execute();
        $resumen = $stmtTotal->get_result()->fetch_assoc();
        $stmtTotal->close();

        $totalRegistros = (int)($resumen['total'] ?? 0);
        $valorTotal = (float)($resumen['valor_total'] ?? 0.0);

        $porPagina = max(1, min(50, $porPagina));
        $totalPaginas = max(1, (int)ceil($totalRegistros / $porPagina));
        $paginaActual = max(1, min($pagina, $totalPaginas));
        $offset = ($paginaActual - 1) * $porPagina;

        // 4. Obtener los registros de la página solicitada
        $sqlDatos = "SELECT id, nombre, stock, precio, correo_proveedor, categoria
                     FROM productos
                     WHERE {$whereSql}
                     ORDER BY {$columnaOrden} {$dirOrden}
                     LIMIT ? OFFSET ?";
        $stmtDatos = $this->conexion->prepare($sqlDatos);
        $tiposDatos = $tipos . 'ii';
        $parametrosDatos = array_merge($parametros, [$porPagina, $offset]);
        $stmtDatos->bind_param($tiposDatos, ...$parametrosDatos);
        $stmtDatos->execute();
        $registros = $stmtDatos->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtDatos->close();

        return [
            'registros' => $registros,
            'total_registros' => $totalRegistros,
            'valor_total' => $valorTotal,
            'pagina_actual' => $paginaActual,
            'total_paginas' => $totalPaginas,
            'por_pagina' => $porPagina,
            'orden' => $columnaOrden,
            'direccion' => $dirOrden,
        ];
    }

    /**
     * Registra un producto nuevo con consulta preparada (CREATE).
     */
    public function crear(string $nombre, int $stock, float $precio, string $correo, string $categoria): int
    {
        $sql = "INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria, activo)
                VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('sidss', $nombre, $stock, $precio, $correo, $categoria);
        $ok = $stmt->execute();
        $nuevoId = $ok ? (int)$this->conexion->insert_id : 0;
        $stmt->close();
        return $nuevoId;
    }

    /**
     * Actualiza un producto existente por su ID (UPDATE).
     */
    public function actualizar(int $id, string $nombre, int $stock, float $precio, string $correo, string $categoria): bool
    {
        $sql = "UPDATE productos
                SET nombre = ?, stock = ?, precio = ?, correo_proveedor = ?, categoria = ?
                WHERE id = ? AND activo = 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('sidssi', $nombre, $stock, $precio, $correo, $categoria, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Elimina un producto por su clave primaria (DELETE).
     */
    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $afectados = $stmt->affected_rows;
        $stmt->close();
        return $ok && $afectados > 0;
    }

    /**
     * Obtiene estadísticas resumidas por categoría y alertas de stock bajo (Semana 13).
     */
    public function obtenerEstadisticas(): array
    {
        $porCategoria = [];
        $sqlCat = "SELECT categoria,
                          COUNT(*) AS cantidad_productos,
                          SUM(stock) AS unidades_totales,
                          SUM(stock * precio) AS valor_inventario,
                          AVG(precio) AS precio_promedio
                   FROM productos
                   WHERE activo = 1
                   GROUP BY categoria
                   ORDER BY valor_inventario DESC";
        if ($res = $this->conexion->query($sqlCat)) {
            $porCategoria = $res->fetch_all(MYSQLI_ASSOC);
        }

        $stockBajo = [];
        $sqlBajo = "SELECT id, nombre, stock, precio, categoria, correo_proveedor
                    FROM productos
                    WHERE activo = 1 AND stock <= 15
                    ORDER BY stock ASC";
        if ($resB = $this->conexion->query($sqlBajo)) {
            $stockBajo = $resB->fetch_all(MYSQLI_ASSOC);
        }

        $totales = [
            'productos' => 0,
            'unidades' => 0,
            'valor_global' => 0.0,
        ];
        foreach ($porCategoria as $fila) {
            $totales['productos'] += (int)$fila['cantidad_productos'];
            $totales['unidades'] += (int)$fila['unidades_totales'];
            $totales['valor_global'] += (float)$fila['valor_inventario'];
        }

        return [
            'por_categoria' => $porCategoria,
            'stock_bajo' => $stockBajo,
            'totales' => $totales,
        ];
    }
}
