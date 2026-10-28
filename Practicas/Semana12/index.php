<?php
/**
 * Punto de entrada principal del Gestor de Inventario (Semanas 01 a 15).
 * Coordina la sesión, el controlador ProductoController y la vista principal con plantilla.
 */
require_once __DIR__ . '/proteger.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/ProductoController.php';

$modeloProducto = new Producto($conexion);
$modeloBitacora = new Bitacora($conexion);
$controlador = new ProductoController($modeloProducto, $modeloBitacora);

$resultadoListado = $controlador->index($_GET);

$registros = $resultadoListado['registros'];
$valorTotal = $resultadoListado['valor_total'];
$paginaActual = $resultadoListado['pagina_actual'];
$totalPaginas = $resultadoListado['total_paginas'];
$totalRegistros = $resultadoListado['total_registros'];

require __DIR__ . '/app/views/productos/index.php';
