<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Inventario</title>
</head>
<body>
    <h1>Gestor de Inventario</h1>
    
    <?php
        // 1. dato de texto (nombre del producto)
        $articulo = "Monitor Dell 24 pulgadas";

        // 2. dato numérico (Cantidad en stock)
        $stock = 45;

        // 3. dato numérico (Costo unitario)
        $costo_unitario = 3200;

    ?>

    <p><strong>Producto:</strong> <?php echo $articulo; ?></p>

</body>
</html>