# 🧪 Pruebas Funcionales de Roles y Permisos — Semana 09

**Proyecto:** Gestor de Inventario  
**Cuentas utilizadas:** `admin_prueba` (`administrador`), `operador_prueba` (`operador`) y `consulta_prueba` (`consulta`)

| N.º | Caso de prueba | Cuenta / Rol | Resultado esperado | Resultado obtenido | Estado |
|---:|---|---|---|---|---|
| 1 | Iniciar sesión con la cuenta de administrador | `admin_prueba` (`administrador`) | Acceso correcto y carga de `$_SESSION['rol'] = 'administrador'` | Sesión iniciada con insignia `Rol: Administrador` | **Correcta** |
| 2 | Confirmar que el menú del administrador muestra las opciones autorizadas | `admin_prueba` | Se muestran Inventario, Registrar Producto, Estadísticas, Exportar CSV, **Usuarios y Roles** y **Bitácora** | Todas las opciones administrativas aparecieron en el menú | **Correcta** |
| 3 | Consultar registros con la cuenta de administrador | `admin_prueba` | Visualiza la tabla completa con botones **Editar** y **Eliminar** | Tabla mostrada con todas las acciones habilitadas | **Correcta** |
| 4 | Crear un registro con la cuenta de administrador | `admin_prueba` | El producto se guarda en MySQL y se registra el alta | Producto creado correctamente | **Correcta** |
| 5 | Editar un registro con la cuenta de administrador | `admin_prueba` | `editar.php` y `actualizar.php` permiten modificar el producto | Cambios guardados en MySQL | **Correcta** |
| 6 | Eliminar un registro de demostración con la cuenta de administrador | `admin_prueba` | `eliminar.php` autoriza el `POST` y borra el registro de prueba | Producto eliminado satisfactoriamente | **Correcta** |
| 7 | Cerrar sesión e iniciar con la cuenta de consulta | `consulta_prueba` (`consulta`) | Inicia sesión con `$_SESSION['rol'] = 'consulta'` | Sesión iniciada con insignia `Rol: Consulta` | **Correcta** |
| 8 | Confirmar que el menú de consulta no muestra operaciones restringidas | `consulta_prueba` | No aparecen enlaces a **Usuarios y Roles**, **Bitácora** ni formulario de alta; la tabla indica *Solo lectura* | El menú y la tabla ocultaron todas las opciones de escritura | **Correcta** |
| 9 | Consultar registros con la cuenta de consulta | `consulta_prueba` | Permite ver el inventario, usar filtros y ver reportes sin problema | Consulta y filtrado ejecutados correctamente | **Correcta** |
| 10 | Intentar acceder directamente a creación con cuenta de consulta | Enviar `POST` a `crear.php` o `api/guardar.php` con `consulta_prueba` | `exigirRol(['administrador', 'operador'])` rechaza la operación con HTTP `403` | El servidor rechazó la solicitud con código `403` y mensaje de acceso denegado | **Correcta** |
| 11 | Intentar abrir o ejecutar edición con cuenta de consulta | Abrir `editar.php?id=1` o enviar `POST` a `actualizar.php` con `consulta_prueba` | PHP bloquea el acceso (HTTP `403`) y redirige sin mostrar formulario ni editar | Operación rechazada por el servidor | **Correcta** |
| 12 | Intentar ejecutar directamente eliminación con cuenta de consulta | Enviar `POST` a `eliminar.php` o `api/eliminar.php` con `id=1` | PHP rechaza la petición con HTTP `403` | Petición bloqueada por `exigirRol(['administrador'])` | **Correcta** |
| 13 | Verificar que las operaciones rechazadas no modifiquen la base de datos | Consultar `SELECT * FROM productos` tras las pruebas 10, 11 y 12 | Ningún registro fue creado, modificado ni eliminado por `consulta_prueba` | La base de datos permaneció intacta | **Correcta** |
| 14 | Intentar acceder al módulo de gestión de usuarios sin rol autorizado | Abrir `usuarios.php` con `consulta_prueba` u `operador_prueba` | `exigirRol(['administrador'])` rechaza el acceso con HTTP `403` | Acceso denegado y redirección al panel con alerta de error | **Correcta** |
| 15 | Cerrar sesión e intentar abrir una página privada por ruta directa | Cerrar sesión y escribir `usuarios.php` o `index.php` | `proteger.php` exige autenticación previa y redirige a `login_form.php` | Redirigido a `login_form.php` | **Correcta** |
| 16 | Confirmar que el CRUD y el login continúan funcionando para roles autorizados | Probar alta y edición con `operador_prueba` y flujo completo con `admin_prueba` | Cada rol ejecuta exactamente las acciones que tiene permitidas | Todos los módulos funcionaron de forma estable | **Correcta** |
