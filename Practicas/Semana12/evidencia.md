# 🧩 Práctica 12 — Desarrollo de Aplicaciones Web (Componentes y Plantillas)

## Objetivo
Crear una plantilla general para el **Gestor de Inventario** y separar los elementos repetidos de la interfaz en componentes reutilizables (`header.php`, `menu.php`, `mensajes.php`, `footer.php`, `tabla_productos.php`, `boton_accion.php` y `paginacion.php`), manteniendo una apariencia consistente, respetando los permisos del usuario y asegurando que los endpoints JSON de Fetch API permanezcan libres de HTML accidental.

---

## Funcionalidad implementada
1. **Plantilla general en `app/views/layouts/`:**
   * `header.php`: Configura `<meta charset="UTF-8">`, `viewport`, título dinámico escapado con `htmlspecialchars()`, enlace a `estilos.css` y muestra el nombre y rol del usuario autenticado.
   * `menu.php`: Renderiza la barra de navegación resaltando el módulo activo (`$paginaActiva`) y mostrando los enlaces de **Usuarios y Roles** y **Bitácora** únicamente al rol `administrador`.
   * `mensajes.php`: Valida el tipo de alerta contra una lista permitida (`exito`, `advertencia`, `error`), escapa el texto y limpia `$_SESSION['flash_mensaje']` tras renderizarlo.
   * `footer.php`: Cierra la estructura HTML e incluye `script.js` de forma controlada.
2. **Componentes visuales en `app/views/componentes/`:**
   * `tabla_productos.php`: Presenta las filas de productos y el valor total en almacén sin realizar consultas SQL.
   * `boton_accion.php`: Genera las insignias de stock (`Bajo` / `OK`) y los botones **Editar** y **Eliminar** de acuerdo con el rol en sesión.
   * `paginacion.php`: Genera los enlaces de paginación conservando los parámetros de búsqueda y filtrado.
3. **Separación estricta entre plantilla HTML y endpoints JSON:**
   * Los archivos de `api/` (`api/listar.php`, `api/guardar.php`, `api/eliminar.php`) no incluyen `header.php` ni `footer.php`, garantizando respuestas JSON puras.

---

## Archivos creados o modificados
* `app/views/layouts/header.php`, `menu.php`, `mensajes.php`, `footer.php`.
* `app/views/componentes/tabla_productos.php`, `boton_accion.php`, `paginacion.php`.
* Vistas integradas a la plantilla: `app/views/productos/index.php`, `app/views/productos/editar.php`, `login_form.php`, `usuarios.php`, `reportes.php` y `bitacora.php`.
* `estilos.css`: Unificación de estilos para el encabezado, menú, insignias, tablas y diseño adaptable (`@media`).

---

## Tablas de la base de datos utilizadas
* `productos`, `usuarios` y `bitacora` (consultadas exclusivamente desde los modelos, nunca dentro de los componentes visuales).

---

## Pruebas realizadas y Resultado de cada prueba
Las 14 pruebas funcionales de la plantilla y los componentes se documentaron en `Practicas/Semana12/pruebas_plantilla.md`. Se comprobó la consistencia visual en 6 pantallas distintas, la adaptación del menú por rol y la pureza del JSON en `api/listar.php`. Todas las pruebas resultaron **Correctas**.

---

## Errores encontrados
1. **Persistencia indefinida del mensaje de éxito al recargar:**
   * Inicialmente guardaba el aviso en `$_SESSION['flash_mensaje']` pero olvidaba borrarlo después de imprimirlo, por lo que aparecía en cada recarga.
2. **Inclusión segura de componentes:**
   * Verifiqué que ninguna ruta de `require` proviniera de parámetros `$_GET` o `$_POST` para evitar vulnerabilidades de inclusión de archivos (*LFI*).

## Soluciones aplicadas
1. En `app/views/layouts/mensajes.php` copié el valor a una variable local `$mensajeFlash` e inmediatamente ejecuté `unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo'])`.
2. Todas las inclusiones de plantillas y componentes usan rutas fijas con `__DIR__`.

---

## Evidencias
* Tabla de análisis en [`analisis_componentes.md`](analisis_componentes.md).
* Registro de pruebas en [`pruebas_plantilla.md`](pruebas_plantilla.md).

---

## Reflexión final
Separar la interfaz en componentes reutilizables eliminó decenas de líneas repetidas entre páginas. Ahora, agregar una nueva opción al menú o ajustar el diseño del encabezado se hace en un solo archivo (`menu.php` o `header.php`) y se refleja automáticamente en todo el sistema.
