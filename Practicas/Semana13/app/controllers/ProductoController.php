<?php
/**
 * Controlador ProductoController (Arquitectura MVC — Semanas 11, 12 y 13).
 * Coordina la validación de datos del lado del servidor, la autorización y la comunicación
 * con el modelo Producto y la Bitácora.
 */
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Bitacora.php';

class ProductoController
{
    private Producto $modelo;
    private Bitacora $bitacora;

    public function __construct(Producto $modelo, Bitacora $bitacora)
    {
        $this->modelo = $modelo;
        $this->bitacora = $bitacora;
    }

    /**
     * Valida los datos de un producto recibidos por formulario o API (Semana 08).
     */
    public function validarDatosProducto(array $entrada): array
    {
        $nombre = strtoupper(trim((string)($entrada['nombre'] ?? '')));
        $stockRaw = $entrada['stock_nuevo'] ?? ($entrada['stock'] ?? '');
        $precioRaw = $entrada['precio'] ?? '';
        $correo = trim((string)($entrada['correo_proveedor'] ?? ''));
        $categoria = trim((string)($entrada['categoria'] ?? ''));

        $errores = [];

        if ($nombre === '' || mb_strlen($nombre) > 100) {
            $errores[] = 'El nombre del producto es obligatorio (máximo 100 caracteres).';
        }

        if ($stockRaw === '' || !is_numeric($stockRaw) || (int)$stockRaw <= 0) {
            $errores[] = 'La cantidad de stock debe ser un número entero mayor a 0.';
        }

        if ($precioRaw === '' || !is_numeric($precioRaw) || (float)$precioRaw <= 0) {
            $errores[] = 'El precio del producto debe ser un número mayor a 0.';
        }

        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo del proveedor no tiene un formato válido.';
        }

        if (!in_array($categoria, Producto::CATEGORIAS_PERMITIDAS, true)) {
            $errores[] = 'La categoría seleccionada no es válida.';
        }

        return [
            'valido' => count($errores) === 0,
            'errores' => $errores,
            'datos' => [
                'nombre' => $nombre,
                'stock' => (int)$stockRaw,
                'precio' => round((float)$precioRaw, 2),
                'correo_proveedor' => $correo,
                'categoria' => $categoria,
            ]
        ];
    }

    /**
     * Lista productos con soporte de búsqueda, filtros, ordenamiento seguro y paginación.
     */
    public function index(array $queryParams): array
    {
        $termino = trim((string)($queryParams['q'] ?? ''));
        if (mb_strlen($termino) > 80) {
            $termino = mb_substr($termino, 0, 80);
        }
        $categoria = trim((string)($queryParams['categoria'] ?? ''));
        $estadoStock = trim((string)($queryParams['estado_stock'] ?? ''));
        $orden = trim((string)($queryParams['orden'] ?? 'id'));
        $direccion = trim((string)($queryParams['dir'] ?? 'DESC'));
        $pagina = filter_var($queryParams['pagina'] ?? 1, FILTER_VALIDATE_INT);
        if ($pagina === false || $pagina < 1) {
            $pagina = 1;
        }

        return $this->modelo->listarAvanzado(
            $termino,
            $categoria,
            $estadoStock,
            $orden,
            $direccion,
            $pagina,
            6
        );
    }

    /**
     * Crea un producto tras validar en el servidor y registra en la bitácora.
     */
    public function guardar(array $entrada, int $usuarioId, string $usuarioNombre): array
    {
        $validacion = $this->validarDatosProducto($entrada);
        if (!$validacion['valido']) {
            return [
                'ok' => false,
                'mensaje' => implode(' ', $validacion['errores']),
                'errores' => $validacion['errores'],
            ];
        }

        $d = $validacion['datos'];
        $nuevoId = $this->modelo->crear(
            $d['nombre'],
            $d['stock'],
            $d['precio'],
            $d['correo_proveedor'],
            $d['categoria']
        );

        if ($nuevoId <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'No fue posible registrar el producto en la base de datos.',
            ];
        }

        $this->bitacora->registrar(
            $usuarioId,
            $usuarioNombre,
            'CREAR',
            'productos',
            $nuevoId,
            "Alta de producto: {$d['nombre']} (Stock: {$d['stock']}, Precio: \${$d['precio']})"
        );

        return [
            'ok' => true,
            'mensaje' => 'Producto registrado correctamente en el inventario.',
            'id' => $nuevoId,
            'producto' => array_merge(['id' => $nuevoId], $d),
        ];
    }

    /**
     * Actualiza un producto existente validando identificador y reglas de negocio.
     */
    public function actualizar(int $id, array $entrada, int $usuarioId, string $usuarioNombre): array
    {
        if ($id <= 0) {
            return ['ok' => false, 'mensaje' => 'El identificador del producto no es válido.'];
        }

        $existente = $this->modelo->obtenerPorId($id);
        if (!$existente) {
            return ['ok' => false, 'mensaje' => 'El producto solicitado no existe en el inventario.'];
        }

        $validacion = $this->validarDatosProducto($entrada);
        if (!$validacion['valido']) {
            return [
                'ok' => false,
                'mensaje' => implode(' ', $validacion['errores']),
                'errores' => $validacion['errores'],
            ];
        }

        $d = $validacion['datos'];
        $exito = $this->modelo->actualizar(
            $id,
            $d['nombre'],
            $d['stock'],
            $d['precio'],
            $d['correo_proveedor'],
            $d['categoria']
        );

        if (!$exito) {
            return ['ok' => false, 'mensaje' => 'No se pudo actualizar el producto.'];
        }

        $this->bitacora->registrar(
            $usuarioId,
            $usuarioNombre,
            'ACTUALIZAR',
            'productos',
            $id,
            "Edición de producto ID #{$id}: {$d['nombre']} (Stock: {$d['stock']}, Precio: \${$d['precio']})"
        );

        return [
            'ok' => true,
            'mensaje' => 'Producto actualizado correctamente.',
        ];
    }

    /**
     * Elimina un producto verificando su existencia y registra en la bitácora.
     */
    public function eliminar(int $id, int $usuarioId, string $usuarioNombre): array
    {
        if ($id <= 0) {
            return ['ok' => false, 'mensaje' => 'El identificador del producto no es válido.'];
        }

        $existente = $this->modelo->obtenerPorId($id);
        if (!$existente) {
            return ['ok' => false, 'mensaje' => 'El producto que intentas eliminar no existe.'];
        }

        $exito = $this->modelo->eliminar($id);
        if (!$exito) {
            return ['ok' => false, 'mensaje' => 'No fue posible eliminar el producto.'];
        }

        $this->bitacora->registrar(
            $usuarioId,
            $usuarioNombre,
            'ELIMINAR',
            'productos',
            $id,
            "Baja de producto ID #{$id}: {$existente['nombre']}"
        );

        return [
            'ok' => true,
            'mensaje' => 'Producto eliminado correctamente del inventario.',
        ];
    }
}
