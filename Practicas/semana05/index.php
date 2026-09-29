<?php require_once "conexion.php"; ?>
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
        
        <!-- Caja 1: Resumen (AHORA CONECTADA A MYSQL) -->
        <section class="caja resumen">
            <h2>Inventario Registrado</h2>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                <tr style="background-color: #3498db; color: white;">
                    <th style="padding: 10px; border: 1px solid #bdc3c7;">ID</th>
                    <th style="padding: 10px; border: 1px solid #bdc3c7;">Producto</th>
                    <th style="padding: 10px; border: 1px solid #bdc3c7;">Stock</th>
                    <th style="padding: 10px; border: 1px solid #bdc3c7;">Precio</th>
                </tr>
                <?php
                $sql = "SELECT id, nombre, stock, precio FROM productos ORDER BY id DESC";
                $resultado = $conexion->query($sql);
                $valor_total = 0;

                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        $valor_total += ($fila["stock"] * $fila["precio"]);
                        echo "<tr>";
                        echo "<td style='padding: 10px; border: 1px solid #bdc3c7; text-align: center;'>" . $fila["id"] . "</td>";
                        echo "<td style='padding: 10px; border: 1px solid #bdc3c7;'>" . $fila["nombre"] . "</td>";
                        echo "<td style='padding: 10px; border: 1px solid #bdc3c7; text-align: center;'>" . $fila["stock"] . "</td>";
                        echo "<td style='padding: 10px; border: 1px solid #bdc3c7; text-align: center;'>$" . $fila["precio"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='padding: 10px; text-align: center;'>No hay productos registrados.</td></tr>";
                }
                ?>
            </table>
            <h3 class="total">Valor total en almacén: $<?php echo number_format($valor_total, 2); ?></h3>
        </section>

        <!-- Caja 2: Formulario -->
        <section class="caja seccion-formulario">
            <h2>Registrar Nuevo Producto</h2>
            
            <!-- Caja para mensajes dinámicos de JS -->
            <div id="mensaje-sistema"></div>
            
            <!-- Se agregó el id="formulario-inventario" -->
            <form action="procesar.php" method="POST" id="formulario-inventario">
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

                <!-- Se agregó el id="btnGuardar" -->
                <button type="submit" class="btn-guardar" id="btnGuardar">Registrar Producto</button>
            </form>

            <!-- Botón y contenedor para mostrar/ocultar información -->
            <br>
            <button id="btnMostrarInfo" class="btn-guardar" style="background-color: #7f8c8d;">Mostrar Ayuda</button>
            <div id="info-extra" style="display: none; margin-top: 15px; padding: 15px; background: #ecf0f1; border-radius: 4px;">
                <p><strong>Tip:</strong> Todos los campos son obligatorios. El precio y el stock deben ser mayores a 0.</p>
            </div>
        </section>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web - Proyecto Integrador</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>