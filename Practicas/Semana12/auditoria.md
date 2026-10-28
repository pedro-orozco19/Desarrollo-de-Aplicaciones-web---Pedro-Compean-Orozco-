# 🗺️ Inventario y Auditoría Básica de Seguridad — Semana 08

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## Tabla de Auditoría de Archivos y Módulos

| Archivo o módulo | Datos que recibe | Riesgo que revisaré | Corrección realizada |
|---|---|---|---|
| `crear.php` y `actualizar.php` (Formulario de registro y edición) | `nombre`, `stock_nuevo`, `precio`, `correo_proveedor`, `categoria` | Datos vacíos, negativos, correos inválidos o categorías manipuladas fuera del `<select>` | Validación estricta en PHP (`ProductoController::validarDatosProducto`), comprobando tipos numéricos (`> 0`), `FILTER_VALIDATE_EMAIL` y lista blanca `Producto::CATEGORIAS_PERMITIDAS`. |
| `editar.php`, `actualizar.php`, `eliminar.php` (Procesamiento del CRUD) | Identificador `id` y campos del producto | Inyección SQL y manipulación de identificadores (`id` negativo, texto o inexistente) | Validación con `filter_input(..., 'id', FILTER_VALIDATE_INT)` comprobando `$id > 0`, verificación de existencia previa y consultas preparadas (`prepare` + `bind_param`) en todas las operaciones. |
| `index.php` y `app/views/componentes/tabla_productos.php` (Listado e interfaz) | Datos provenientes de MySQL y parámetros de búsqueda `$_GET['q']` | Cross-Site Scripting (XSS) por salida HTML no escapada | Aplicación sistemática de `htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')` en tablas, formularios, mensajes flash y encabezado de usuario. |
| `login.php` y `usuarios.php` (Autenticación y cuentas) | `usuario` y `contrasena` | Almacenamiento inseguro de claves, inyección SQL en login o enumeración de usuarios | Uso de `password_hash(..., PASSWORD_DEFAULT)`, `password_verify()`, consulta preparada y mensaje de error genérico que no revela si falló el usuario o la contraseña. |
| `proteger.php` y `logout.php` (Panel interno y sesiones) | Cookie e identificador de sesión `$_SESSION` | Acceso sin autenticación o fijación de sesión (*Session Fixation*) | Ejecución de `session_start()` antes de enviar HTML, `session_regenerate_id(true)` al autenticar y limpieza completa de cookie + `session_destroy()` al salir. |
| `config/database.php` y `.gitignore` (Configuración de conexión) | Credenciales de conexión a MySQL (`DB_HOST`, `DB_USER`, `DB_PASS`) | Exposición de contraseñas reales o trazas técnicas en GitHub y en pantalla | Separación de credenciales locales en `config/env.local.php` (ignorado en `.gitignore`), provisión de `config/env.example.php` sin secretos y registro interno con `error_log()` sin mostrar `$conexion->connect_error` al usuario. |
