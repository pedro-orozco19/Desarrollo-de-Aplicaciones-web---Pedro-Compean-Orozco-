# 🗂️ Inventario de Módulos y Plan de Pruebas de Integración — Semana 14

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## 1. Inventario de Módulos de la Aplicación

| Módulo o función | Qué se probará | Resultado esperado |
|---|---|---|
| **Inicio de sesión (`login.php`)** | Credenciales válidas, contraseña incorrecta y cuenta inactiva | Permitir acceso a usuarios activos válidos y rechazar con mensaje genérico en caso contrario |
| **Sesiones (`proteger.php` / `logout.php`)** | Acceso directo a páginas privadas sin sesión y tras cerrar sesión | Bloquear usuarios no autenticados y redirigir a `login_form.php` |
| **CRUD de Productos (`ProductoController`)** | Crear, consultar, editar y eliminar registros de demostración | Operaciones persistidas correctamente en MySQL con consultas preparadas |
| **Roles y permisos (`autorizacion.php`)** | Acciones permitidas y restringidas para `administrador`, `operador` y `consulta` | Respetar autorizaciones en el menú y rechazar con HTTP `403` en el servidor |
| **Fetch API (`api/*.php` y `script.js`)** | Peticiones `GET` y `POST`, respuestas JSON y manejo de errores | Respuesta JSON válida (sin HTML accidental) y actualización parcial de la tabla y el total |
| **MVC (`app/models`, `controllers`, `views`)** | Separación entre acceso a datos, coordinación y vista | Vistas sin SQL directo y controladores reutilizados tanto en web como en `api/` |
| **Plantilla y componentes (`layouts/`, `componentes/`)** | Encabezado, menú dinámico, mensajes flash, tabla, paginación y pie | Presentación consistente en todas las pantallas y limpieza de mensajes en sesión |
| **Funciones avanzadas (Semana 13)** | Búsqueda, filtros, ordenamiento por lista blanca, paginación, reportes, CSV y bitácora | Resultados exactos, conservación de filtros entre páginas y protección de permisos |

---

## 2. Casos de Prueba Ejecutados

| ID | Módulo / Función | Condición previa | Datos de prueba | Pasos ejecutados | Resultado esperado | Resultado obtenido | Estado |
|---|---|---|---|---|---|---|---|
| `PR-LOGIN-01` | Inicio de sesión válido | Cuenta `admin_prueba` activa en BD | `usuario="admin_prueba"`, clave de demostración válida | Abrir `login_form.php`, ingresar datos y enviar | Regenera ID de sesión y redirige a `index.php` | Acceso autorizado al panel con rol Administrador | **Correcta** |
| `PR-LOGIN-02` | Inicio de sesión inválido | Cuenta `admin_prueba` existente | `usuario="admin_prueba"`, `contrasena="ClaveFalsa99"` | Enviar formulario de login | Rechaza acceso con mensaje genérico sin revelar qué falló | Mostró *"No fue posible iniciar sesión con esas credenciales"* | **Correcta** |
| `PR-LOGIN-03` | Cuenta inactiva | Cuenta `inactivo_prueba` con `activo=0` | Credenciales de `inactivo_prueba` | Intentar iniciar sesión | Rechaza el acceso aunque la contraseña coincida | Acceso bloqueado por validación `(int)$fila['activo'] === 1` | **Correcta** |
| `PR-SES-01` | Protección de rutas privadas | Usuario sin sesión (o tras ejecutar `logout.php`) | Acceso directo por URL a `index.php`, `editar.php?id=1` y `reportes.php` | Escribir la ruta privada en el navegador | Redirige a `login_form.php` antes de mostrar contenido | Redirección `302` inmediata a `login_form.php` | **Correcta** |
| `PR-FORM-01` | Validación de formulario y reglas de negocio | Sesión activa con rol `administrador` | `nombre=""`, `stock=-5`, `precio=0`, `correo="invalido"` | Enviar datos inválidos a `crear.php` / `api/guardar.php` | PHP rechaza la entrada y la BD no sufre cambios | `ProductoController` devolvió errores claros y no insertó en MySQL | **Correcta** |
| `PR-CRUD-01` | Crear y consultar producto | Sesión `administrador` | `nombre="ROUTER CISCO GIGABIT"`, `stock=18`, `precio=2850.00`, `correo="redes@cisco.com"`, `categoria="Electronica"` | Llenar formulario y guardar | Producto insertado en MySQL, listado en tabla y registrado en bitácora | Producto creado con éxito y reflejado en el inventario | **Correcta** |
| `PR-CRUD-02` | Editar producto existente | Producto de prueba existente | Cambiar `stock` a `25` y `precio` a `2700.00` | Abrir `editar.php?id=...`, modificar y enviar a `actualizar.php` | Se actualiza el registro en MySQL y se conserva al recargar | Cambios guardados y verificados en la tabla | **Correcta** |
| `PR-CRUD-03` | Eliminar producto de demostración | Producto de prueba existente y rol `administrador` | `id` del registro de demostración | Confirmar borrado en el diálogo y enviar `POST` | Registro eliminado de MySQL y fila retirada de la tabla | Baja ejecutada correctamente | **Correcta** |
| `PR-CRUD-04` | Manejo de ID inexistente | Sesión `administrador` | `editar.php?id=999999` | Abrir URL con ID inexistente | Muestra mensaje de error controlado y redirige al listado | Mostró *"El producto solicitado no existe en la base de datos"* | **Correcta** |
| `PR-ROL-01` | Restricción de escritura para rol Consulta | Sesión iniciada como `consulta_prueba` | Petición `POST` directa a `crear.php` y `api/guardar.php` | Intentar crear un producto sin permiso | El servidor rechaza con HTTP `403` y no altera la base de datos | Rechazo `403 Forbidden` confirmado | **Correcta** |
| `PR-ROL-02` | Restricción de eliminación para rol Operador | Sesión iniciada como `operador_prueba` | Petición `POST` a `eliminar.php` y `api/eliminar.php` | Intentar eliminar un producto con rol Operador | El servidor permite crear/editar pero rechaza eliminar con HTTP `403` | Rechazo `403`: solo el administrador puede eliminar | **Correcta** |
| `PR-ROL-03` | Protección de módulos administrativos | Sesión `consulta_prueba` u `operador_prueba` | Abrir `usuarios.php` y `bitacora.php` directamente | Escribir la ruta en la barra de direcciones | `exigirRol(['administrador'])` bloquea el acceso | Acceso denegado y redirección segura al panel | **Correcta** |
| `PR-FETCH-01` | Actualización y alta con Fetch API | Sesión `administrador` | Clic en **⚡ Actualizar con Fetch API** y alta con casilla Fetch activa | Ejecutar operación y revisar pestaña *Network* | Endpoint devuelve JSON limpio y el DOM se actualiza sin recargar | JSON válido (`200 OK`) y tabla actualizada con `textContent` | **Correcta** |
| `PR-MVC-01` | Revisión de MVC y plantilla reutilizable | Sesión activa | Navegar por `index.php`, `editar.php`, `reportes.php`, `usuarios.php`, `bitacora.php` | Comprobar encabezado, menú, mensajes, tabla y pie | Todas las vistas comparten la plantilla y delegan SQL a los modelos | Estructura MVC y componentes verificados al 100% | **Correcta** |
| `PR-ADV-01` | Búsqueda, filtros, ordenamiento y paginación | Sesión activa | `q="MONITOR"`, `categoria="Electronica"`, `orden="precio"`, `dir="ASC"`, `pagina=1` | Aplicar filtros y navegar entre páginas | Filtra con parámetros preparados, valida columna en lista blanca y conserva filtros | Resultados exactos y URL de paginación consistente | **Correcta** |
| `PR-ADV-02` | Reportes, Bitácora y Exportación CSV | Sesión `administrador` | Abrir `reportes.php`, `bitacora.php` y descargar `exportar_csv.php` | Verificar sumas, historial de acciones y archivo CSV descargado | Estadísticas exactas, bitácora sin datos sensibles y CSV con BOM UTF-8 | Reportes, bitácora y archivo CSV generados correctamente | **Correcta** |
