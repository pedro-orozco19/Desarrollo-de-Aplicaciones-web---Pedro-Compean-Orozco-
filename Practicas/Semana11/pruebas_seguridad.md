# 🧪 Pruebas de Seguridad — Semana 08

**Proyecto:** Gestor de Inventario  
**Entorno:** WAMP Server (PHP 8.3 + MySQL) — Datos de demostración

| N.º | Prueba de seguridad | Entrada / Acción realizada | Resultado esperado | Resultado obtenido | Estado |
|---:|---|---|---|---|---|
| 1 | Enviar formulario con campo obligatorio vacío | Enviar `crear.php` con `nombre=""` omitiendo validación JS | PHP rechaza la petición (código 400 / mensaje de error) y no guarda en MySQL | `ProductoController` rechazó la entrada e informó que el nombre es obligatorio | **Correcta** |
| 2 | Enviar un identificador con formato inválido | Abrir `editar.php?id=-5` y `editar.php?id=1 OR 1=1` | `filter_input(..., FILTER_VALIDATE_INT)` detecta el ID inválido y redirige sin ejecutar cambios | El servidor rechazó el identificador inválido y mostró aviso controlado | **Correcta** |
| 3 | Intentar consultar o editar un identificador inexistente | Abrir `editar.php?id=88888` | El sistema comprueba que no existe el registro y redirige con mensaje claro | Se mostró *"El producto solicitado no existe en la base de datos"* | **Correcta** |
| 4 | Revisar consultas preparadas en el CRUD | Registrar un producto con comillas simples: `MONITOR 27' PRO` | La consulta preparada guarda el texto literal sin romper la sintaxis SQL | El producto se guardó e imprimió correctamente sin error SQL | **Correcta** |
| 5 | Introducir texto con caracteres especiales HTML (Prueba XSS) | Registrar producto con nombre `<script>alert('XSS')</script>CABLE HDMI` | El texto se muestra literalmente en la tabla sin ejecutar ningún script en el navegador | `htmlspecialchars()` convirtió `<` y `>` en `&lt;` y `&gt;`; no se ejecutó código JS | **Correcta** |
| 6 | Iniciar sesión con contraseña de prueba correcta | Usuario `admin_prueba` y su clave de demostración | Acceso exitoso y regeneración del ID de sesión (`session_regenerate_id(true)`) | Sesión iniciada correctamente con nueva cookie `PHPSESSID` | **Correcta** |
| 7 | Intentar iniciar sesión con contraseña incorrecta | Usuario `admin_prueba` y clave `incorrecta` | Acceso denegado con mensaje genérico (no indica si falló usuario o clave) | Se mostró *"No fue posible iniciar sesión con esas credenciales"* | **Correcta** |
| 8 | Verificar que la base de datos no almacene la contraseña original | Inspeccionar columna `password_hash` en tabla `usuarios` | Solo existen hashes generados con `password_hash()` (`$2y$10$...`) | Confirmado: ninguna contraseña se almacena en texto plano | **Correcta** |
| 9 | Abrir una página protegida sin sesión | Invocar `index.php`, `editar.php?id=1` y `usuarios.php` sin sesión | El servidor bloquea el acceso y redirige a `login_form.php` | Todas las páginas privadas rechazaron el acceso sin sesión | **Correcta** |
| 10 | Cerrar sesión e intentar volver al panel por ruta directa | Ejecutar `logout.php` y luego abrir `panel.php` | La sesión destruida impide abrir el panel | Redirigido inmediatamente a `login_form.php` | **Correcta** |
| 11 | Revisar que los mensajes de error no revelen credenciales ni trazas | Provocar entradas inválidas en formularios y endpoints | Se muestran mensajes amigables sin rutas del sistema, ni SQL, ni contraseñas | Ningún mensaje expone detalles internos del servidor | **Correcta** |
| 12 | Confirmar que el CRUD sigue funcionando tras las correcciones | Crear, consultar, editar y eliminar un registro de prueba | Todas las operaciones funcionan con normalidad para usuarios autenticados | CRUD 100% operativo con todas las capas de seguridad activas | **Correcta** |
