# 🧪 Pruebas Funcionales de las Funciones Avanzadas — Semana 13

**Proyecto:** Gestor de Inventario  
**Funciones probadas:** Búsqueda, Filtros, Ordenamiento, Paginación, Estadísticas, Exportación CSV y Bitácora

| N.º | Caso de prueba | Entrada / Procedimiento | Resultado esperado | Resultado obtenido | Estado |
|---:|---|---|---|---|---|
| 1 | Ejecutar búsqueda y filtros con datos válidos | Buscar `q="DELL"` o filtrar `categoria="Mobiliario"` | Muestra únicamente los registros coincidentes y recalcula el valor total filtrado | Se mostraron los artículos de Mobiliario y el subtotal exacto | **Correcta** |
| 2 | Comprobar que el resultado coincide con MySQL | Comparar el conteo y suma de `reportes.php` e `index.php` contra `SELECT SUM(stock*precio) FROM productos` | Los totales coinciden exactamente con la base de datos | Coincidencia exacta al 100% en unidades y valor monetario | **Correcta** |
| 3 | Probar un criterio que no produzca resultados | Buscar `q="PRODUCTO_INEXISTENTE_XYZ"` | La tabla muestra el mensaje *"No se encontraron productos que coincidan con los criterios seleccionados"* | Se presentó el mensaje amigable sin errores de PHP | **Correcta** |
| 4 | Enviar un parámetro vacío o de página inválido | Abrir `index.php?pagina=-5` e `index.php?pagina=9999` | PHP ajusta automáticamente la página al rango válido `[1, total_paginas]` | El servidor mostró la página 1 o la última página disponible sin fallar | **Correcta** |
| 5 | Probar valores fuera de las opciones permitidas en `orden` y `categoria` | Abrir `index.php?orden=DROP_TABLE&dir=HACK&categoria=Otra` | La lista blanca descarta los valores inválidos y usa `orden=id` y `dir=DESC` | La consulta se ejecutó de forma segura ordenando por `id DESC` | **Correcta** |
| 6 | Confirmar que las consultas utilizan parámetros preparados | Buscar texto con comillas y comodines: `q="10%' OR '1'='1"` | `bind_param()` trata la entrada como texto literal y no altera la lógica SQL | No se produjo inyección SQL; se buscó literalmente la cadena | **Correcta** |
| 7 | Comprobar que los datos dinámicos se muestran de forma segura | Introducir `q="<script>alert(1)</script>"` en el buscador | El valor en el input y en el chip de filtro activo se escapa con `htmlspecialchars()` | El texto se imprimió escapado sin ejecutar script alguno | **Correcta** |
| 8 | Probar acceso a función administrativa con usuario sin permiso | Abrir `bitacora.php` con la cuenta `consulta_prueba` | `exigirRol(['administrador'])` bloquea el acceso con código `403` | Acceso rechazado por el servidor y redirigido con alerta | **Correcta** |
| 9 | Confirmar que la exportación y bitácora no revelan información sensible | Descargar `exportar_csv.php` y revisar `bitacora.php` | Ni el CSV ni la bitácora incluyen `password_hash`, tokens ni credenciales | Solo se exportan/registran datos operativos autorizados | **Correcta** |
| 10 | Verificar combinación de búsqueda, filtros, orden y paginación | Filtrar por `Electronica`, ordenar por `precio ASC` y cambiar de página | Los enlaces de paginación conservan todos los parámetros en la URL | La navegación mantuvo los criterios de búsqueda y ordenamiento | **Correcta** |
| 11 | Verificar que el CRUD y Fetch API siguen funcionando con los filtros | Filtrar la tabla y pulsar **⚡ Actualizar con Fetch API** | `api/listar.php` aplica los mismos filtros y actualiza la tabla vía JSON | Respuesta JSON filtrada correctamente | **Correcta** |
| 12 | Revisar errores de PHP y de la consola del navegador | Inspeccionar consola (`F12`) y respuestas HTTP en todas las funciones avanzadas | Cero errores en consola o en el servidor | Ejecución limpia y documentada | **Correcta** |
