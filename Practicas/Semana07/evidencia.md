# 🔐 Práctica 07 — Desarrollo de Aplicaciones Web (Login, Sesiones y Páginas Protegidas)

## Objetivo
Implementar un sistema de autenticación real en PHP y MySQL dentro del **Gestor de Inventario** que permita identificar a los usuarios registrados en la tabla `usuarios`, verificar sus contraseñas cifradas con `password_verify()`, mantener su sesión activa con `$_SESSION` regenerando el identificador tras autenticar, proteger todas las páginas internas desde el servidor y cerrar sesión destruyendo la cookie y los datos de sesión.

---

## Funcionalidad implementada
1. **Tabla `usuarios` en MySQL:** Estructura con `id`, `nombre`, `usuario` (`UNIQUE`), `correo` (`UNIQUE`), `password_hash` (hash generado con `password_hash($clave, PASSWORD_DEFAULT)`), `activo` y `creado_en`.
2. **Formulario de acceso (`login_form.php`):** Formulario HTML estilizado que envía credenciales mediante método `POST` sin contraseñas precargadas.
3. **Verificación de credenciales (`login.php`):**
   * Inicia sesión con `session_start()`.
   * Consulta al usuario mediante sentencia preparada (`SELECT ... FROM usuarios WHERE usuario = ? LIMIT 1`).
   * Comprueba que la cuenta esté activa (`activo === 1`) y verifica la contraseña con `password_verify()`.
   * Regenera el ID de sesión (`session_regenerate_id(true)`) para prevenir fijación de sesión y almacena `usuario_id`, `nombre` y `usuario` en `$_SESSION`.
   * Devuelve un mensaje genérico (`"No fue posible iniciar sesión con esas credenciales."`) cuando falla la autenticación.
4. **Protección de páginas internas (`proteger.php` y `panel.php`):**
   * Verifica en el servidor que `isset($_SESSION["usuario_id"])` se cumpla antes de renderizar cualquier HTML o ejecutar operaciones del CRUD.
   * Si un visitante sin sesión intenta abrir directamente `index.php`, `panel.php` o `editar.php`, es redirigido inmediatamente a `login_form.php`.
5. **Cierre de sesión (`logout.php`):**
   * Vacía `$_SESSION`, elimina la cookie de sesión en el navegador y ejecuta `session_destroy()`.

---

## Archivos modificados o creados
* `login_form.php`: Interfaz de inicio de sesión.
* `login.php`: Verificación en el servidor con consulta preparada y `password_verify()`.
* `proteger.php`: Guardián de sesión incluido al inicio de las páginas privadas.
* `panel.php` e `index.php`: Panel principal protegido que muestra el nombre del usuario autenticado, el CRUD de productos y el enlace de cierre de sesión.
* `logout.php`: Destrucción completa de la sesión activa.
* `sql/schema_completo.sql`: Definición de la tabla `usuarios`.

---

## Tablas de la base de datos utilizadas
* `usuarios`: Almacena las cuentas autorizadas con su `password_hash` (Bcrypt) y estado `activo`.
* `productos`: Mantiene las cuatro operaciones del CRUD ahora protegidas bajo sesión activa.

---

## Pruebas realizadas y Resultado de cada prueba
Las 12 pruebas funcionales solicitadas en la guía (acceso válido, contraseña incorrecta, usuario inexistente, acceso directo sin sesión, regeneración de sesión, cierre de sesión, bloqueo posterior al logout, cuenta inactiva y verificación de hash en MySQL) se registraron en `Practicas/Semana07/pruebas_login.md`, obteniendo resultado **Correcta** en todas.

---

## Errores encontrados
1. **Advertencia de encabezados ya enviados (`Headers already sent`):**
   * En una primera prueba incluí `proteger.php` después de una línea en blanco en HTML, lo que impedía que `header("Location: login_form.php")` redirigiera.
2. **Comparación de contraseñas:**
   * Comprobé que en MySQL el campo `password_hash` guarda cadenas de 60 caracteres con formato `$2y$10$...`, por lo que nunca debe compararse con `=` en SQL sino con `password_verify()` en PHP.

## Soluciones aplicadas
1. Colocamos `<?php require_once __DIR__ . '/proteger.php'; ?>` en la primera línea exacta de cada archivo privado, antes de cualquier etiqueta `<!DOCTYPE html>`.
2. Implementamos `password_hash($contrasena, PASSWORD_DEFAULT)` al registrar usuarios y `password_verify($contrasena, $fila['password_hash'])` al autenticar.

---

## Evidencias
* Módulo de login, sesiones y protección integrado en la aplicación y documentado en [`pruebas_login.md`](pruebas_login.md).

---

## Reflexión final
Con esta práctica entendí cómo HTTP (que por naturaleza no guarda estado entre una petición y otra) logra recordar quién es el usuario gracias a las sesiones de PHP. Además, comprobé que la seguridad real reside en verificar la sesión en el servidor al inicio de cada script PHP y en almacenar únicamente hashes irreversibles de las contraseñas.
