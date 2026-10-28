# ⚡ Práctica 10 — Desarrollo de Aplicaciones Web (AJAX y Fetch API)

## Objetivo
Modificar el módulo CRUD de productos para que JavaScript se comunique de forma asíncrona con PHP mediante `fetch()`, intercambie datos en formato JSON y actualice la tabla de inventario, el valor total del almacén y los mensajes de estado sin recargar toda la página, conservando las validaciones en el servidor, las consultas preparadas y los permisos por rol.

---

## Funcionalidad implementada
1. **Endpoints PHP con respuesta JSON (`api/listar.php`, `api/guardar.php`, `api/eliminar.php`):**
   * Definen `header('Content-Type: application/json; charset=utf-8')`.
   * Verifican `$_SESSION['usuario_id']` (devolviendo HTTP `401` si no hay sesión) y `$_SESSION['rol']` (devolviendo HTTP `403` si el rol no tiene permiso).
   * Validan los datos en el servidor y responden con JSON limpio usando `json_encode(..., JSON_UNESCAPED_UNICODE)` y códigos HTTP semánticos (`200`, `400`, `401`, `403`, `405`, `500`).
2. **Peticiones asíncronas con `fetch()` en `script.js`:**
   * `cargarInventarioConFetch()`: Envía una solicitud `GET` a `api/listar.php` y reconstruye `#cuerpo-tabla-productos` y `#valor-total-almacen`.
   * Alta de productos con `FormData`: Intercepta el evento `submit` de `#formulario-inventario`, envía los campos por `POST` a `api/guardar.php`, limpia el formulario tras confirmar `respuesta.ok && resultado.ok` y refresca la tabla automáticamente.
   * Eliminación asíncrona: Intercepta el formulario `.form-eliminar-inline`, solicita confirmación y envía el `POST` a `api/eliminar.php`.
3. **Actualización segura del DOM:**
   * Se utilizó `document.createElement()` y `textContent` en `crearFilaProductoDOM()` para evitar inyectar HTML no confiable mediante `innerHTML`.

---

## Archivos creados o modificados
* `api/listar.php`, `api/guardar.php`, `api/eliminar.php`.
* `script.js`: Funciones `cargarInventarioConFetch()` y `crearFilaProductoDOM()`.
* `app/views/productos/index.php`: Botón `⚡ Actualizar con Fetch API` y casilla para envío asíncrono.

---

## Estructura utilizada y Tablas involucradas
* Carpeta `api/` para los endpoints JSON separados de las vistas HTML.
* Tablas `productos`, `usuarios` y `bitacora` en `gestor_inventario`.

---

## Pruebas realizadas y Resultado de cada prueba
Las 12 pruebas funcionales de Fetch API (carga inicial, actualización parcial sin recarga, respuesta JSON válida, alta exitosa, rechazo de campo vacío con código `400`, ID inválido, rechazo `403` con cuenta de consulta y manejo de errores en consola) se documentaron en `Practicas/Semana10/pruebas_fetch.md`, resultando todas **Correctas**.

---

## Errores encontrados
1. **Encabezado `Content-Type` manual con `FormData`:**
   * En una primera prueba agregué manualmente `'Content-Type': 'multipart/form-data'` en `fetch()`, lo que ocasionó que PHP recibiera `$_POST` vacío porque faltaba el `boundary` automático del navegador.
2. **Revisión de `respuesta.ok`:**
   * Noté que cuando `api/guardar.php` devolvía código HTTP `400`, `fetch()` no entraba automáticamente al `catch` a menos que verificara `!respuesta.ok || !resultado.ok`.

## Soluciones aplicadas
1. Eliminé el encabezado manual `Content-Type` al enviar `FormData`, dejando únicamente `'Accept': 'application/json'` para que el navegador genere automáticamente el `boundary`.
2. Agregué la validación `if (!respuesta.ok || !resultado.ok) { throw new Error(resultado.mensaje); }` después de leer el JSON.

---

## Evidencias
* Diseño del flujo asíncrono en [`diseno_fetch.md`](diseno_fetch.md).
* Bitácora de las 12 pruebas en [`pruebas_fetch.md`](pruebas_fetch.md).

---

## Reflexión final
Integrar Fetch API mejoró notablemente la fluidez del Gestor de Inventario: ahora al registrar o eliminar un producto la tabla y el valor total del almacén se actualizan al instante sin parpadeos ni recargas completas, manteniendo intacta la validación y autorización en PHP.
