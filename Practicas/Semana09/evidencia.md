# 🔵 Práctica 09 — Desarrollo de Aplicaciones Web (Roles y Permisos)

## Objetivo
Implementar un control de acceso basado en roles (`administrador`, `operador` y `consulta`) para que cada usuario autenticado en el **Gestor de Inventario** pueda ejecutar únicamente las operaciones autorizadas, comprobando los permisos tanto en el menú dinámico como obligatoriamente en el servidor con PHP.

---

## Funcionalidad implementada
1. **Columna `rol` en la tabla `usuarios`:** Asignación controlada de roles con valor por defecto `'consulta'`.
2. **Middleware reutilizable de autorización (`autorizacion.php`):**
   * Función `exigirRol(array $rolesPermitidos, bool $respuestaJson = false)` que verifica `$_SESSION['rol']` en el servidor antes de ejecutar cualquier consulta SQL o mostrar formularios restringidos.
   * Devuelve código HTTP `403 Forbidden` cuando un usuario sin permiso intenta acceder por ruta directa o petición POST.
3. **Protección de cada operación del CRUD en el servidor:**
   * **Consultar (`index.php`, `listar.php`, `reportes.php`):** Permitido para `administrador`, `operador` y `consulta`.
   * **Crear y Editar (`crear.php`, `editar.php`, `actualizar.php`, `api/guardar.php`):** Restringido con `exigirRol(['administrador', 'operador'])`.
   * **Eliminar (`eliminar.php`, `api/eliminar.php`):** Restringido exclusivamente con `exigirRol(['administrador'])`.
   * **Gestión de usuarios y bitácora (`usuarios.php`, `bitacora.php`):** Restringido exclusivamente con `exigirRol(['administrador'])`.
4. **Menú e interfaz adaptables al rol (`app/views/layouts/menu.php` y `tabla_productos.php`):**
   * El administrador visualiza todas las opciones del menú y los botones **Editar** y **Eliminar**.
   * El operador visualiza el formulario de registro y el botón **Editar**, pero no el botón **Eliminar** ni los módulos administrativos.
   * El usuario de consulta visualiza la tabla en modo **Solo lectura** sin formulario de alta ni botones destructivos.

---

## Archivos modificados o creados
* `autorizacion.php`: Funciones `exigirRol()` y `tieneRol()`.
* `usuarios.php` y `app/controllers/UsuarioController.php`: Módulo administrativo para crear cuentas y cambiar roles sin exponer contraseñas.
* `crear.php`, `editar.php`, `actualizar.php`, `eliminar.php`: Incorporación de `exigirRol()` al inicio de cada archivo.
* `app/views/layouts/menu.php` y `app/views/componentes/boton_accion.php`: Adaptación visual según `$_SESSION['rol']`.

---

## Tablas de la base de datos utilizadas
* `usuarios` (con columna `rol` y cuentas de demostración `admin_prueba`, `operador_prueba` y `consulta_prueba`).
* `productos` y `bitacora`.

---

## Pruebas realizadas y Resultado de cada prueba
Se ejecutaron las 16 pruebas funcionales solicitadas en la guía utilizando las cuentas `admin_prueba`, `operador_prueba` y `consulta_prueba`. Los resultados se documentaron en `Practicas/Semana09/pruebas_roles_permisos.md`, confirmando que todas las rutas directas y peticiones POST no autorizadas son rechazadas por PHP sin alterar la base de datos.

---

## Errores encontrados
1. **Ocultamiento solo visual en la tabla:**
   * Al principio había ocultado el botón de eliminar para el rol `consulta`, pero aún faltaba incluir `exigirRol(['administrador'])` dentro de `eliminar.php`.
2. **Riesgo de auto-bloqueo del administrador:**
   * En `usuarios.php`, si el administrador cambiaba por error su propio rol a `consulta`, podía quedarse sin administrador activo.

## Soluciones aplicadas
1. Se agregó `exigirRol(...)` en la primera línea de `crear.php`, `editar.php`, `actualizar.php`, `eliminar.php`, `usuarios.php`, `bitacora.php` y en los endpoints de `api/`.
2. En `UsuarioController::cambiarRol()` se agregó una regla que impide al administrador en sesión degradar o desactivar su propia cuenta.

---

## Evidencias
* Diseño de matriz de roles en [`diseno_roles.md`](diseno_roles.md).
* Registro de las 16 pruebas en [`pruebas_roles_permisos.md`](pruebas_roles_permisos.md).

---

## Reflexión final
Diferenciar entre autenticación (*¿quién eres?*) y autorización (*¿qué tienes permitido hacer?*) es esencial en cualquier aplicación multiusuario. Aprendí que ocultar botones en HTML mejora la usabilidad, pero la verdadera seguridad consiste en validar el rol en `$_SESSION` desde el servidor antes de ejecutar cualquier acción sobre la base de datos.
