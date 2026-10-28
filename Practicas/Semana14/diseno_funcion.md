# 🔎 Diseño de Funciones Avanzadas — Semana 13

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## 1. Justificación y Alcance de las Funciones Elegidas

Para que el **Gestor de Inventario** responda a las necesidades reales de control de un almacén con múltiples artículos y proveedores, se diseñó e integró un conjunto coordinado de funciones avanzadas sobre la arquitectura MVC:

1. **Búsqueda multicriterio, Filtros combinados, Ordenamiento seguro y Paginación (`Producto::listarAvanzado`):**
   * **Problema que resuelve:** Cuando el inventario crece, buscar un producto visualmente o identificar cuáles artículos están por agotarse se vuelve lento. Esta función permite localizar al instante productos por nombre o correo de proveedor, filtrar por categoría (`Electronica`, `Mobiliario`, `Limpieza`) y por nivel de stock (`bajo <= 15` o `suficiente > 15`), ordenar por columna permitida y dividir el resultado en páginas.
2. **Reportes y Estadísticas Ejecutivas (`reportes.php` y `Producto::obtenerEstadisticas`):**
   * **Problema que resuelve:** Permite conocer de un vistazo el valor monetario por departamento/categoría, el promedio de precios y la lista de productos en stock crítico para reabastecimiento.
3. **Exportación Segura a CSV (`exportar_csv.php`):**
   * **Problema que resuelve:** Permite descargar los registros filtrados en un archivo compatible con hojas de cálculo aplicando sanitización contra inyección de fórmulas (*CSV Injection*).
4. **Bitácora de Auditoría (`bitacora.php` y `app/models/Bitacora.php`):**
   * **Problema que resuelve:** Registra quién creó, editó o eliminó un producto o modificó permisos, brindando trazabilidad administrativa.

---

## 2. Especificación Técnica

| Elemento | Detalle de Implementación |
|---|---|
| **Usuarios que pueden utilizarlas** | • **Búsqueda, filtros, ordenamiento, paginación, estadísticas y exportación CSV:** `administrador`, `operador` y `consulta` (requiere sesión activa).<br>• **Bitácora de auditoría:** Exclusiva para el rol `administrador` (`exigirRol(['administrador'])`). |
| **Datos y parámetros que recibe** | `q` (texto de búsqueda), `categoria` (filtro), `estado_stock` (`bajo` / `suficiente`), `orden` (columna), `dir` (`ASC` / `DESC`) y `pagina` (entero positivo). |
| **Validaciones y seguridad en PHP** | • `q`: `trim()` y límite de longitud (`<= 80` caracteres), enviado con `bind_param('ss', $like, $like)`.<br>• `categoria`: Validada contra `Producto::CATEGORIAS_PERMITIDAS`.<br>• `orden` y `dir`: Validados estrictamente contra la lista blanca `Producto::COLUMNAS_ORDEN = ['id', 'nombre', 'stock', 'precio', 'categoria']` y `['ASC', 'DESC']`. Nunca se inserta en SQL un nombre de columna sin validar.<br>• `pagina`: Validada con `FILTER_VALIDATE_INT` (`>= 1`) y ajustada al rango `[1, $totalPaginas]`.<br>• Exportación CSV: Función `sanitizarCeldaCsv()` que antepone `'` si un valor inicia con `=`, `+`, `-` o `@`. |
| **Archivos creados o modificados** | `app/models/Producto.php`, `app/models/Bitacora.php`, `app/controllers/ProductoController.php`, `app/views/productos/index.php`, `app/views/componentes/paginacion.php`, `reportes.php`, `exportar_csv.php`, `bitacora.php` y `api/listar.php`. |
