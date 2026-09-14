<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Inventario</title>
    <!-- Vinculación de la hoja de estilos externa -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    
    <!-- ENCABEZADO -->
    <header class="encabezado">
        <h1>Gestor de Inventario</h1>
    </header>

    <!-- CONTENIDO PRINCIPAL (Flexbox) -->
    <main class="contenedor-principal">
        
        <!-- Caja 1: Resumen -->
        <section class="caja resumen">
            <h2>Resumen del almacén</h2>
            <?php
                $articulo = "Monitor Dell 24 pulgadas";
                $stock = 45;
                $costo_unitario = 3200;
                $valor_total = $stock * $costo_unitario;
            ?>
            <p><strong>Producto actual:</strong> <?php echo $articulo; ?></p>
            <p><strong>Unidades disponibles:</strong> <?php echo $stock; ?> piezas</p>
            <p><strong>Costo por unidad:</strong> $<?php echo $costo_unitario; ?></p>
            <h3 class="total">Valor total en almacén: $<?php echo $valor_total; ?></h3>
        </section>

        <!-- Caja 2: Formulario -->
        <section class="caja seccion-formulario">
            <h2>Registrar Nuevo Producto</h2>
            
            <form action="procesar.php" method="POST">
                <div class="grupo-input">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Teclado Mecánico">
                </div>

                <div class="grupo-input">
                    <label for="stock_nuevo">Cantidad a ingresar:</label>
                    <input type="number" id="stock_nuevo" name="stock_nuevo" placeholder="Ej. 10">
                </div>

                <div class="grupo-input">
                    <label for="precio">Precio del Producto ($):</label>
                    <input type="number" step="0.01" id="precio" name="precio" placeholder="Ej. 250.50">
                </div>

                <div class="grupo-input">
                    <label for="correo_proveedor">Correo del Proveedor:</label>
                    <input type="email" id="correo_proveedor" name="correo_proveedor" placeholder="contacto@proveedor.com">
                </div>

                <div class="grupo-input">
                    <label for="categoria">Categoría del Producto:</label>
                    <select id="categoria" name="categoria">
                        <option value="">-- Selecciona una categoría --</option>
                        <option value="Electronica">Electrónica</option>
                        <option value="Mobiliario">Mobiliario</option>
                        <option value="Limpieza">Limpieza</option>
                    </select>
                </div>

                <button type="submit" class="btn-guardar">Registrar Producto</button>
            </form>
        </section>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web - Proyecto Integrador</p>
    </footer>

</body>
</html>