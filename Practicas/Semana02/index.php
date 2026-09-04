<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Inventario</title>
</head>
<body>
    <h1>Gestor de Inventario</h1>
    <h2>Resumen del almacén</h2>
    <?php
        // 1. dato de texto (nombre del producto)
        $articulo = "Monitor Dell 24 pulgadas";

        // 2. dato numérico (Cantidad en stock)
        $stock = 45;

        // 3. dato numérico (Costo unitario)
        $costo_unitario = 3200;

        // 4. Una operación utilizando los datos anteriores
        $valor_total = $stock * $costo_unitario;
    ?>

    <p><strong>Producto actual:</strong> <?php echo $articulo; ?></p>
    <p><strong>Unidades disponibles:</strong> <?php echo $stock; ?> piezas</p>
    <p><strong>Costo por unidad:</strong> $<?php echo $costo_unitario; ?></p>
    <h3>Valor total en almacén: $<?php echo $valor_total; ?></h3>

    <hr>

    <h2>Registrar Nuevo Producto</h2>
    
    <!-- Formulario enviando datos por post hacia procesar.php -->
    <form action="procesar.php" method="POST"></form>
        
        <p>
            <label for="nombre">Nombre del Producto:</label><br>
            <input type="text" id="nombre" name="nombre" placeholder="Ej. Teclado Mecánico">
        </p>

        <p>
            <label for="stock_nuevo">Cantidad a ingresar:</label><br>
            <input type="number" id="stock_nuevo" name="stock_nuevo" placeholder="Ej. 10">
        </p>

        <p>
            <label for="correo_proveedor">Correo del Proveedor:</label><br>
            <input type="email" id="correo_proveedor" name="correo_proveedor" placeholder="contacto@proveedor.com">
        </p>

        <p>
            <label for="categoria">Categoría del Producto:</label><br>
            <select id="categoria" name="categoria">
                <option value="">-- Selecciona una categoría --</option>
                <option value="Electronica">Electrónica</option>
                <option value="Mobiliario">Mobiliario</option>
                <option value="Limpieza">Limpieza</option>
            </select>
        </p>

        <button type="submit">Registrar Producto</button>

    </form>
</body>
</html>