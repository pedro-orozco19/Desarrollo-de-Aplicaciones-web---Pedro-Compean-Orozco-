<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando Registro...</title>
</head>
<body>
    <h1>Resultado del Registro</h1>

    <?php
    // Verificar post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
       // recoleccion de datos del formulario
        $nombre = $_POST['nombre'];
        $stock = $_POST['stock_nuevo'];
        $correo = $_POST['correo_proveedor'];
        $categoria = $_POST['categoria'];

        // imprimir los datos recibidos
        echo "<h2>Datos recibidos en crudo:</h2>";
        echo "<p>Producto: $nombre</p>";
        echo "<p>Stock: $stock</p>";
        echo "<p>Correo: $correo</p>";
        echo "<p>Categoría: $categoria</p>";

    } else {
        // seguridad url 
        echo "<p>⚠️ Acceso denegado. Por favor, utiliza el formulario.</p>";
        echo '<a href="index.php">⬅️ Ir al formulario</a>';
    }
    ?>
</body>
</html>
