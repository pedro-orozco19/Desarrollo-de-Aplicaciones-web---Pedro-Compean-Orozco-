<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando Registro...</title>
    <!-- Vinculación de la hoja de estilos externa -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <!-- ENCABEZADO -->
    <header class="encabezado">
        <h1>Gestor de Inventario</h1>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenedor-principal">
        <section class="caja" style="margin: 0 auto;">
            
            <?php
            // Verificar si los datos llegaron 
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                // Recoleccion de datos del formulario
                $nombre = $_POST['nombre'];
                $stock = $_POST['stock_nuevo'];
                $precio = $_POST['precio']; 
                $correo = $_POST['correo_proveedor'];
                $categoria = $_POST['categoria'];

                // Arreglo para guardar los errores
                $errores = [];

                // VALIDACIONES BÁSICAS
                if (empty($nombre)) {
                    $errores[] = "⚠️ El nombre del producto es obligatorio.";
                }
                
                if (empty($stock) || !is_numeric($stock) || $stock <= 0) {
                    $errores[] = "⚠️ La cantidad en stock debe ser un número válido mayor a 0.";
                }

                if (empty($precio) || !is_numeric($precio) || $precio <= 0) {
                    $errores[] = "⚠️ El precio debe ser un número válido mayor a 0.";
                }
                
                if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "⚠️ Debes proporcionar un correo electrónico con formato válido.";
                }
                
                if (empty($categoria)) {
                    $errores[] = "⚠️ Debes seleccionar una categoría para el producto.";
                }

                // MOSTRAR RESULTADOS
                if (count($errores) > 0) {
                    echo "<h2 style='color: #e74c3c; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;'>Se encontraron los siguientes problemas:</h2>";
                    echo "<ul>";
                    foreach ($errores as $error) {
                        echo "<li><strong>$error</strong></li>";
                    }
                    echo "</ul>";
                    echo '<br><a href="index.php" class="btn-guardar" style="display: block; text-align: center; text-decoration: none; box-sizing: border-box;">⬅️ Volver al formulario</a>';
                } else {
                    echo "<h2 style='color: #27ae60; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;'>✔️ Registro recibido correctamente.</h2>";
                    echo "<p><strong>Producto:</strong> $nombre</p>";
                    echo "<p><strong>Stock Inicial:</strong> $stock piezas</p>";
                    echo "<p><strong>Precio:</strong> $$precio</p>";
                    echo "<p><strong>Contacto Proveedor:</strong> $correo</p>";
                    echo "<p><strong>Categoría:</strong> $categoria</p>";
                    echo '<br><a href="index.php" class="btn-guardar" style="display: block; text-align: center; text-decoration: none; box-sizing: border-box;">⬅️ Registrar otro producto</a>';
                }

            } else {
                // Seguridad URL 
                echo "<h2 style='color: #e74c3c;'>⚠️ Acceso denegado</h2>";
                echo "<p>Por favor, utiliza el formulario para registrar productos.</p>";
                echo '<br><a href="index.php" class="btn-guardar" style="display: block; text-align: center; text-decoration: none; box-sizing: border-box;">⬅️ Ir al formulario</a>';
            }
            ?>
            
        </section>
    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="pie">
        <p>Desarrollo de Aplicaciones Web - Proyecto Integrador</p>
    </footer>
</body>
</html>