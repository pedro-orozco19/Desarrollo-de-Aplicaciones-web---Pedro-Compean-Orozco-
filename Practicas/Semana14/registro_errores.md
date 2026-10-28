# 🐛 Registro de Errores y Correcciones Aplicadas — Semana 14

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## 1. Bitácora de Errores Detectados y Solucionados

| ID | Descripción del error | Causa identificada | Corrección aplicada | Nueva prueba ejecutada |
|---|---|---|---|---|
| `ERR-01` | Al registrar o eliminar un producto con Fetch API teniendo un filtro activo (ej. `categoria=Mobiliario`), la tabla recargada mostraba todos los productos sin respetar el filtro actual. | `cargarInventarioConFetch()` en `script.js` llamaba a `api/listar.php` sin adjuntar los valores actuales de `#filtro-q`, `#filtro-categoria`, `#filtro-stock` y `#filtro-orden`. | Se actualizó `cargarInventarioConFetch()` para construir un objeto `URLSearchParams` leyendo los controles de filtrado antes de invocar `fetch('api/listar.php?' + params.toString())`. | `PR-FETCH-01` y `PR-ADV-01`: **Correctas** (la tabla conserva los filtros tras guardar o eliminar vía Fetch). |
| `ERR-02` | Al reconstruir las filas de la tabla en el cliente después de una llamada Fetch API, el formulario de eliminar no incluía el campo oculto `csrf_token`. | La función `crearFilaProductoDOM()` en `script.js` solo creaba el `<input type="hidden" name="id">` y omitía el token de sesión. | Se expuso el token en `data-csrf` dentro de `<main class="contenedor-principal">` y se agregó el nodo `<input type="hidden" name="csrf_token">` en `crearFilaProductoDOM()`. | `PR-CRUD-03` y `PR-FETCH-01`: **Correctas**. |
| `ERR-03` | Los enlaces de la barra de paginación perdían el ordenamiento y el filtro de nivel de stock al pasar a la página 2. | El componente `paginacion.php` solo conservaba el parámetro `q` al armar el query string. | Se actualizó `app/views/componentes/paginacion.php` para incluir `q`, `categoria`, `estado_stock`, `orden` y `dir` mediante `http_build_query()`. | `PR-ADV-01`: **Correcta**. |
| `ERR-04` | Si se enviaba manualmente por URL un número de página mayor al total de páginas disponibles (ej. `pagina=99`), la consulta `OFFSET` devolvía una tabla vacía en vez de ubicarse en la última página válida. | En `Producto::listarAvanzado()` se calculaba el `$offset` antes de limitar `$pagina` al máximo `$totalPaginas`. | Primero se ejecuta el `COUNT(*)` para calcular `$totalPaginas` y luego se acota `$paginaActual = max(1, min($pagina, $totalPaginas))` antes de calcular el `$offset`. | `PR-ADV-01`: **Correcta**. |
| `ERR-05` | Al abrir en Microsoft Excel el archivo generado por `exportar_csv.php`, las palabras con tilde (como *Electrónica*) mostraban caracteres mal codificados y existía riesgo si un texto iniciaba con `=`. | Excel en Windows requiere la marca de orden de bytes (BOM UTF-8) al inicio del CSV, y faltaba sanitizar prefijos de fórmulas. | Se agregó `fwrite($salida, "\xEF\xBB\xBF")` al inicio de `exportar_csv.php` y se implementó `sanitizarCeldaCsv()` para anteponer `'` a valores que comiencen con `=`, `+`, `-` o `@`. | `PR-ADV-02`: **Correcta**. |

---

## 2. Verificación de Pruebas de Regresión

Después de aplicar las 5 correcciones anteriores, se ejecutó nuevamente la batería de pruebas de regresión sobre todos los módulos:

- [x] **Inicio y cierre de sesión:** Funciona correctamente con regeneración de ID y destrucción de cookie (`PR-LOGIN-01` a `PR-LOGIN-03`).
- [x] **Acceso a páginas privadas:** Bloqueado al 100% sin sesión activa (`PR-SES-01`).
- [x] **Operaciones principales del CRUD:** Crear, consultar, editar y eliminar operan sin regresiones (`PR-CRUD-01` a `PR-CRUD-04`).
- [x] **Roles y permisos:** El servidor rechaza con HTTP `403` cualquier acción fuera del rol autorizado (`PR-ROL-01` a `PR-ROL-03`).
- [x] **Módulo con Fetch API:** Actualización parcial, alta y baja asíncrona funcionando y respetando filtros (`PR-FETCH-01`).
- [x] **Plantilla y navegación:** Encabezado, menú dinámico, mensajes flash y pie consistentes (`PR-MVC-01`).
- [x] **Funciones avanzadas:** Búsqueda, filtros, ordenamiento, paginación, estadísticas, bitácora y exportación CSV verificados (`PR-ADV-01` y `PR-ADV-02`).
- [x] **Conexión con MySQL:** Estable y con codificación `utf8mb4`.
