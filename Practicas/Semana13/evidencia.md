# 🔎 Práctica 13 — Desarrollo de Aplicaciones Web (Funciones Avanzadas)

## Objetivo
Incorporar al **Gestor de Inventario** funciones avanzadas que aporten valor operativo real al almacén: **búsqueda por producto o proveedor, filtros combinados por categoría y nivel de stock, ordenamiento seguro mediante lista blanca, paginación en el servidor, panel de estadísticas/reportes, exportación segura a CSV y bitácora de auditoría**, integradas con MVC, componentes reutilizables y Fetch API.

---

## Funcionalidad implementada
1. **Búsqueda y Filtros combinados (`Producto::listarAvanzado`):**
   * Permite buscar coincidencias parciales (`LIKE ?`) tanto en `nombre` como en `correo_proveedor`.
   * Combina el término de búsqueda con el filtro de `categoria` y el filtro de `estado_stock` (`bajo <= 15` o `suficiente > 15`) usando consultas preparadas dinámicas.
   * Muestra indicadores visuales de los filtros activos y un botón **Limpiar** para restablecer el listado.
2. **Ordenamiento con lista permitida (Whitelist):**
   * Permite ordenar por `id`, `nombre`, `stock`, `precio` o `categoria` en dirección `ASC` o `DESC`.
   * Si un usuario envía por URL una columna no permitida (ej. `orden=password_hash`), PHP la descarta y aplica el valor seguro por defecto (`id DESC`).
3. **Paginación en el servidor (`app/views/componentes/paginacion.php`):**
   * Calcula el total de registros filtrados, determina las páginas disponibles (`LIMIT ? OFFSET ?`) y conserva todos los criterios de búsqueda y orden al cambiar de página.
4. **Reportes, Exportación CSV y Bitácora (`reportes.php`, `exportar_csv.php`, `bitacora.php`):**
   * Resumen estadístico con tarjetas KPI (total de productos, unidades, valor global y alertas de stock crítico).
   * Descarga en formato CSV codificado en UTF-8 con BOM y protección contra inyección de fórmulas.
   * Bitácora de auditoría exclusiva para administradores.

---

## Archivos creados o modificados
* `app/models/Producto.php` y `app/models/Bitacora.php`.
* `app/controllers/ProductoController.php`.
* `app/views/productos/index.php` y `app/views/componentes/paginacion.php`.
* `reportes.php`, `exportar_csv.php` y `bitacora.php`.
* `api/listar.php` y `script.js`.

---

## Tablas de la base de datos utilizadas
* `productos` (para búsqueda, filtros, ordenamiento, paginación, estadísticas y exportación CSV).
* `bitacora` (para el registro y consulta de acciones relevantes del sistema).

---

## Pruebas realizadas y Resultado de cada prueba
Se ejecutaron las 12 pruebas funcionales detalladas en `Practicas/Semana13/pruebas_funcion.md`, evaluando búsquedas con y sin resultados, combinación de filtros, parámetros de ordenamiento inválidos, paginación fuera de rango, permisos en bitácora y exportación CSV. Todas las pruebas resultaron **Correctas**.

---

## Errores encontrados
1. **Pérdida de filtros al cambiar de página en la paginación:**
   * En el primer intento, los enlaces de página apuntaban solo a `index.php?pagina=2`, perdiendo el filtro de categoría o el texto buscado.
2. **Acentos en Excel al abrir el archivo CSV exportado:**
   * Al abrir el CSV en Excel en Windows, palabras como *"Electrónica"* se veían con caracteres extraños si no llevaba cabecera BOM UTF-8.

## Soluciones aplicadas
1. En `app/views/componentes/paginacion.php` utilicé `http_build_query()` combinando los parámetros activos (`q`, `categoria`, `estado_stock`, `orden`, `dir`) con el número de página destino.
2. En `exportar_csv.php` escribí la secuencia BOM UTF-8 (`"\xEF\xBB\xBF"`) al inicio del flujo antes de `fputcsv()`, logrando que Excel reconozca los acentos automáticamente.

---

## Evidencias
* Documento de diseño en [`diseno_funcion.md`](diseno_funcion.md).
* Resultados de las 12 pruebas en [`pruebas_funcion.md`](pruebas_funcion.md).

---

## Reflexión final
Agregar búsqueda, filtros, ordenamiento con lista permitida, reportes y exportación convirtió al proyecto en una herramienta verdaderamente útil para la toma de decisiones en un almacén. Aprendí que los parámetros preparados protegen los valores en `WHERE`, mientras que las listas blancas en PHP son indispensables para proteger elementos estructurales como `ORDER BY`.
