<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando Registro...</title>
</head>
<body>
    <h1>Resultado del Registro</h1>

    <?php
    // Verificar si los datos llegaron 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Recoleccion de datos del formulario
        $nombre = $_POST['nombre'];
        $stock = $_POST['stock_nuevo'];
        $precio = $_POST['precio']; // <-- NUEVO CAMPO ATRAPADO
        $correo = $_POST['correo_proveedor'];
        $categoria = $_POST['categoria'];

        // Arreglo para guardar los errores que encontremos
        $errores = [];

        // VALIDACIONES BÁSICAS
        // Validar que el nombre no esté vacío
        if (empty($nombre)) {
            $errores[] = "⚠️ El nombre del producto es obligatorio.";
        }
        
        // Validar que el stock sea un número válido y mayor a 0
        if (empty($stock) || !is_numeric($stock) || $stock <= 0) {
            $errores[] = "⚠️ La cantidad en stock debe ser un número válido mayor a 0.";
        }

        // NUEVA VALIDACIÓN: Validar que el precio sea válido
        if (empty($precio) || !is_numeric($precio) || $precio <= 0) {
            $errores[] = "⚠️ El precio debe ser un número válido mayor a 0.";
        }
        
        // Validar el formato del correo
        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "⚠️ Debes proporcionar un correo electrónico con formato válido.";
        }
        
        // Validar que se haya elegido una categoría
        if (empty($categoria)) {
            $errores[] = "⚠️ Debes seleccionar una categoría para el producto.";
        }

        // MOSTRAR RESULTADOS
        if (count($errores) > 0) {
            // Si hay errores, mostramos la lista
            echo "<h2>Se encontraron los siguientes problemas:</h2>";
            echo "<ul>";
            foreach ($errores as $error) {
                echo "<li><strong>$error</strong></li>";
            }
            echo "</ul>";
            echo '<br><a href="index.php">⬅️ Volver al formulario</a>';
        } else {
            // Si todo está bien, mostramos el éxito
            echo "<h2>✔️ Registro recibido correctamente.</h2>";
            echo "<p><strong>Producto:</strong> $nombre</p>";
            echo "<p><strong>Stock Inicial:</strong> $stock piezas</p>";
            echo "<p><strong>Precio:</strong> $$precio</p>"; // <-- NUEVO CAMPO MOSTRADO
            echo "<p><strong>Contacto Proveedor:</strong> $correo</p>";
            echo "<p><strong>Categoría:</strong> $categoria</p>";
            echo '<br><a href="index.php">⬅️ Registrar otro producto</a>';
        }

    } else {
        // Seguridad URL 
        echo "<p>⚠️ Acceso denegado. Por favor, utiliza el formulario.</p>";
        echo '<a href="index.php">⬅️ Ir al formulario</a>';
    }
    ?>
</body>
</html>