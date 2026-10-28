# 🧪 Pruebas Funcionales de Fetch API — Semana 10

**Proyecto:** Gestor de Inventario  
**Endpoints evaluados:** `api/listar.php`, `api/guardar.php` y `api/eliminar.php`

| N.º | Prueba realizada | Acción / Datos | Resultado esperado | Resultado obtenido | Estado |
|---:|---|---|---|---|---|
| 1 | Abrir el módulo y comprobar que carga normalmente | Iniciar sesión y abrir `index.php` | La página carga la tabla de inventario, filtros y formulario sin errores | Carga exitosa con estado `200 OK` | **Correcta** |
| 2 | Ejecutar la operación que utiliza Fetch | Clic en el botón **⚡ Actualizar con Fetch API** o registrar un producto | JavaScript invoca `fetch()` hacia `api/listar.php` / `api/guardar.php` | Petición registrada en la pestaña *Network (Fetch/XHR)* | **Correcta** |
| 3 | Confirmar que el navegador recibe una respuesta JSON válida | Inspeccionar pestaña *Response* en DevTools (`F12`) | Encabezado `Content-Type: application/json; charset=utf-8` y estructura JSON limpia sin HTML | JSON válido con claves `ok`, `mensaje`, `datos` y `resumen` | **Correcta** |
| 4 | Comprobar que la interfaz se actualiza sin recargar toda la página | Registrar un producto con la opción Fetch activa | Se agrega la fila a `#cuerpo-tabla-productos` y cambia `#valor-total-almacen` sin recargar el navegador | La tabla y el total se actualizaron dinámicamente en el DOM | **Correcta** |
| 5 | Enviar datos válidos y comprobar el resultado esperado | `nombre="IMPRESORA LASER HP"`, `stock=10`, `precio=4200`, `correo="hp@proveedor.com"`, `categoria="Electronica"` | `api/guardar.php` devuelve `ok: true`, inserta en MySQL y actualiza la tabla | Producto guardado en MySQL y mostrado al instante en la tabla | **Correcta** |
| 6 | Enviar un campo obligatorio vacío y verificar que PHP lo rechace | Enviar petición `POST` a `api/guardar.php` con `nombre=""` | PHP devuelve HTTP `400` y `{ "ok": false, "mensaje": "El nombre del producto es obligatorio..." }` | El servidor rechazó los datos con código `400` y mostró alerta roja | **Correcta** |
| 7 | Probar un identificador inválido o inexistente | Enviar `POST` a `api/eliminar.php` con `id=99999` o `id=-1` | PHP responde con HTTP `400` e informa que el producto no existe o el ID es inválido | Respuesta JSON controlada con `ok: false` y código `400` | **Correcta** |
| 8 | Probar la operación con un usuario sin el permiso necesario | Iniciar sesión con `consulta_prueba` y enviar `POST` a `api/guardar.php` | El endpoint comprueba `$_SESSION['rol']` y responde con HTTP `403 Forbidden` | Respuesta `{ "ok": false, "mensaje": "No tienes permiso para registrar productos." }` con estado `403` | **Correcta** |
| 9 | Confirmar que la solicitud no autorizada no modifica la base de datos | Consultar tabla `productos` tras la prueba 8 | No se insertó ni eliminó ningún registro | La tabla `productos` permaneció sin cambios | **Correcta** |
| 10 | Provocar una respuesta de error controlada y verificar el mensaje | Enviar precio negativo (`precio=-50`) a `api/guardar.php` | `script.js` captura el error en el bloque `catch` y lo presenta en `#mensaje-sistema` | Se mostró el mensaje claro sin exponer trazas técnicas | **Correcta** |
| 11 | Revisar la consola del navegador y corregir errores | Abrir pestaña *Console* (`F12`) durante las operaciones Fetch | Sin errores de sintaxis ni promesas no manejadas (`Uncaught (in promise)`) | Consola limpia con el mensaje de inicio del sistema | **Correcta** |
| 12 | Confirmar que el CRUD tradicional, el login y los roles siguen funcionando | Probar edición en `editar.php`, desmarcar casilla Fetch para envío tradicional y probar logout | Todo el sistema mantiene compatibilidad completa | CRUD tradicional y asíncrono operando en armonía | **Correcta** |
