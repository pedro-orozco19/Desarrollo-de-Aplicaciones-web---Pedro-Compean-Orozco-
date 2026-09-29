<?php
$servidor = "localhost"; // Regresamos al original sin el :3308
$usuario = "root";
$password = "Sansonlino0305"; // Escríbela entre las comillas
$base_datos = "gestor_inventario";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión crítica: " . $conexion->connect_error);
}
?>