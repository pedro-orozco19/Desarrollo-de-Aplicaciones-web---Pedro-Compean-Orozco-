# 🧪 Pruebas de Integración de Arquitectura MVC — Semana 11

**Proyecto:** Gestor de Inventario  
**Módulos evaluados:** `Producto` (Modelo), `ProductoController` (Controlador), `app/views/productos/` (Vistas) y `api/` (Fetch API)

| N.º | Prueba de integración | Procedimiento | Resultado esperado | Resultado obtenido | Estado |
|---:|---|---|---|---|---|
| 1 | Abrir la aplicación después de reorganizar los archivos | Navegar a `index.php` en el servidor local WAMP | El punto de entrada carga configuración, controlador y vista sin errores de rutas | Aplicación cargada correctamente (`200 OK`) | **Correcta** |
| 2 | Iniciar sesión con una cuenta de demostración | Autenticar con `admin_prueba` a través de `login.php` (`AuthController`) | `AuthController` valida con `Usuario` y redirige al panel principal | Inicio de sesión exitoso | **Correcta** |
| 3 | Consultar el listado que ahora utiliza el modelo | Abrir `index.php` y revisar los registros mostrados | `Producto::listarAvanzado()` consulta MySQL y devuelve el arreglo de productos | Se listaron todos los productos activos desde el modelo | **Correcta** |
| 4 | Confirmar que el controlador coordina la operación | Revisar `index.php` y `ProductoController::index()` | `index.php` no contiene SQL; delega al controlador y pasa los datos a la vista | Flujo `index.php -> ProductoController -> Producto -> Vista` verificado | **Correcta** |
| 5 | Verificar que la vista presenta correctamente los datos | Inspeccionar `app/views/productos/index.php` y `editar.php` | La vista muestra la tabla y el valor total sin ejecutar consultas SQL directas | Presentación limpia y separada de la lógica de datos | **Correcta** |
| 6 | Comprobar que los datos dinámicos se muestran de forma segura | Verificar el uso de `htmlspecialchars()` en las vistas | Todos los textos dinámicos están escapados con `ENT_QUOTES, 'UTF-8'` | Salida HTML completamente escapada | **Correcta** |
| 7 | Probar una operación de creación y actualización reorganizada | Crear un producto y luego editarlo desde `editar.php` -> `actualizar.php` | `ProductoController::guardar()` y `actualizar()` validan y persisten los cambios mediante el modelo | Alta y edición completadas y registradas en bitácora | **Correcta** |
| 8 | Verificar que un usuario sin permiso no puede ejecutar acciones restringidas | Iniciar sesión con `consulta_prueba` e intentar invocar `crear.php` o `eliminar.php` | `exigirRol()` bloquea la solicitud antes de llamar al controlador | Solicitud rechazada con código `403` | **Correcta** |
| 9 | Probar el endpoint que utiliza Fetch API | Usar el botón **⚡ Actualizar con Fetch API** y guardar un producto con Fetch | `api/listar.php` y `api/guardar.php` invocan a `ProductoController` y responden correctamente | Peticiones Fetch procesadas sin recargar la página | **Correcta** |
| 10 | Confirmar que la respuesta JSON conserva el formato esperado | Revisar la respuesta de `api/listar.php` en las herramientas de desarrollador | Devuelve JSON válido con `ok`, `mensaje`, `datos` y `resumen` | Formato JSON idéntico y compatible con `script.js` | **Correcta** |
| 11 | Probar el cierre de sesión y el acceso directo a una página privada | Ejecutar `logout.php` e intentar abrir `index.php` y `editar.php?id=1` | El sistema bloquea el acceso y redirige a `login_form.php` | Redirección inmediata al formulario de acceso | **Correcta** |
| 12 | Revisar errores de PHP y del navegador | Inspeccionar consola de JS y logs de PHP durante todo el recorrido | Cero errores fatales, *warnings* o errores de rutas `require_once` | Ejecución limpia sin errores en consola ni en servidor | **Correcta** |
| 13 | Comparar las funciones principales antes y después de la reorganización | Verificar login, roles, CRUD, cálculo de valor total y ayuda interactiva | Ninguna funcionalidad previa se perdió al pasar a MVC | Todas las funciones previas se conservaron al 100% | **Correcta** |
| 14 | Corregir cualquier regresión encontrada | Estandarizar rutas con `__DIR__` entre `api/` y la raíz | Tanto la raíz como `api/` cargan los mismos modelos y controladores sin conflicto | Sin regresiones pendientes | **Correcta** |
