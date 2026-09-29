<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $stock = $_POST['stock_nuevo'];
    $precio = $_POST['precio'];
    $correo = $_POST['correo_proveedor'];
    $categoria = $_POST['categoria'];

    $errores = [];

    // Validaciones
    if (empty($nombre)) $errores[] = "⚠️ El nombre del producto es obligatorio.";
    if (empty($stock) || !is_numeric($stock) || $stock <= 0) $errores[] = "⚠️ El stock debe ser mayor a 0.";
    if (empty($precio) || !is_numeric($precio) || $precio <= 0) $errores[] = "⚠️ El precio debe ser mayor a 0.";
    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "⚠️ Correo no válido.";
    if (empty($categoria)) $errores[] = "⚠️ Debes seleccionar una categoría.";

    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Procesando...</title><link rel="stylesheet" href="estilos.css"></head><body>';
    echo '<header class="encabezado"><h1>Gestor de Inventario</h1></header>';
    echo '<main class="contenedor-principal"><section class="caja" style="margin: 0 auto;">';

    if (count($errores) > 0) {
        echo "<h2 style='color: #e74c3c; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;'>Se encontraron problemas:</h2><ul>";
        foreach ($errores as $error) { echo "<li><strong>$error</strong></li>"; }
        echo "</ul><br><a href='index.php' class='btn-guardar' style='display: block; text-align: center; text-decoration: none;'>⬅️ Volver al formulario</a>";
    } else {
        // AQUÍ ESTÁ LA MAGIA: Guardar en MySQL
        $sql = "INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria) 
                VALUES ('$nombre', '$stock', '$precio', '$correo', '$categoria')";
        
        if ($conexion->query($sql) === TRUE) {
            echo "<h2 style='color: #27ae60; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;'>✔️ Guardado en la Base de Datos.</h2>";
            echo "<p><strong>Producto:</strong> $nombre</p>";
            echo "<p><strong>Stock:</strong> $stock</p>";
            echo "<p><strong>Precio:</strong> $$precio</p>";
            echo "<p><strong>Categoría:</strong> $categoria</p>";
            echo "<br><a href='index.php' class='btn-guardar' style='display: block; text-align: center; text-decoration: none;'>⬅️ Registrar otro</a>";
        } else {
            echo "<h2 style='color: #e74c3c;'>Error en la Base de Datos: " . $conexion->error . "</h2>";
        }
    }
    echo '</section></main><footer class="pie"><p>Proyecto Integrador</p></footer></body></html>';
} else {
    header("Location: index.php");
}
?>