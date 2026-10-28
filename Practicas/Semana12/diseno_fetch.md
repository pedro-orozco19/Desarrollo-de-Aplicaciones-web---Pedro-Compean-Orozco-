# ⚡ Diseño de Integración con Fetch API — Semana 10

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## Especificación Técnica de la Operación con Fetch API

| Aspecto | Definición en el Proyecto Integrador |
|---|---|
| **Módulo seleccionado** | Inventario de Productos (`productos`): Consulta/filtrado dinámico y alta asíncrona de productos, más baja rápida desde la tabla. |
| **Archivo de la interfaz que inicia la petición** | `index.php` (a través de `app/views/productos/index.php`) y `script.js`. |
| **Rutas de los archivos PHP que atienden las peticiones** | 1. `api/listar.php` (`GET`): Devuelve listado filtrado y valor total del almacén.<br>2. `api/guardar.php` (`POST`): Valida e inserta un nuevo producto.<br>3. `api/eliminar.php` (`POST`): Elimina un producto por su ID. |
| **Datos enviados por JavaScript** | • En `api/listar.php`: Parámetros `q`, `categoria`, `estado_stock`, `orden`, `dir` y `pagina` vía `URLSearchParams`.<br>• En `api/guardar.php`: Objeto `FormData` con `nombre`, `stock_nuevo`, `precio`, `correo_proveedor`, `categoria` y `csrf_token`.<br>• En `api/eliminar.php`: Objeto `FormData` con `id` y `csrf_token`. |
| **Formato de respuesta esperado** | JSON estructurado con cabecera `Content-Type: application/json; charset=utf-8`:<br>`{ "ok": true, "mensaje": "...", "datos": [...], "resumen": { "total_registros": 8, "valor_total": 312500.00 } }` |
| **Permisos necesarios en el servidor** | • `api/listar.php`: Sesión autenticada (`401` si no hay sesión).<br>• `api/guardar.php`: Sesión activa + rol `administrador` u `operador` (`403` si es `consulta`).<br>• `api/eliminar.php`: Sesión activa + rol `administrador` (`403` en cualquier otro rol). |
| **Elementos del DOM que se actualizan** | • `#cuerpo-tabla-productos` (filas `<tr>` reconstruidas de forma segura con `document.createElement` y `textContent`).<br>• `#valor-total-almacen` (suma actualizada en tiempo real).<br>• `#mensaje-sistema` (alerta de éxito o error sin recargar la página). |
