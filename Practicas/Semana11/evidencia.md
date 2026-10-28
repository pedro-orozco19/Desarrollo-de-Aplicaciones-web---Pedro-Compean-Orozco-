# 🏗️ Práctica 11 — Desarrollo de Aplicaciones Web (Arquitectura MVC)

## Objetivo
Reorganizar progresivamente el **Gestor de Inventario** mediante una arquitectura inspirada en el patrón **Modelo-Vista-Controlador (MVC)**, separando el acceso a los datos (`app/models/`), la coordinación y validación de solicitudes (`app/controllers/`) y la presentación HTML (`app/views/`), conservando el login, los roles, la seguridad y los endpoints de Fetch API.

---

## Funcionalidad implementada
1. **Capa de Modelos (`app/models/`):**
   * `Producto.php`: Encapsula todas las consultas SQL hacia la tabla `productos`.
   * `Usuario.php`: Encapsula la búsqueda por usuario, el listado seguro y la creación/actualización de roles con `password_hash()`.
   * `Bitacora.php`: Encapsula el registro de eventos de auditoría.
2. **Capa de Controladores (`app/controllers/`):**
   * `ProductoController.php`: Centraliza la validación del servidor (`validarDatosProducto()`) y coordina los métodos `index()`, `guardar()`, `actualizar()` y `eliminar()`. Gracias a esto, tanto los archivos tradicionales (`crear.php`, `eliminar.php`) como los endpoints asíncronos (`api/listar.php`, `api/guardar.php`, `api/eliminar.php`) reutilizan exactamente el mismo controlador sin duplicar lógica.
   * `AuthController.php` y `UsuarioController.php`: Coordinan el inicio de sesión y la gestión de cuentas.
3. **Capa de Vistas (`app/views/productos/`):**
   * `index.php` y `editar.php` presentan los datos recibidos sin realizar llamadas directas a MySQL.

---

## Archivos creados o modificados
* `app/models/Producto.php`, `app/models/Usuario.php`, `app/models/Bitacora.php`.
* `app/controllers/ProductoController.php`, `app/controllers/AuthController.php`, `app/controllers/UsuarioController.php`.
* `app/views/productos/index.php` y `app/views/productos/editar.php`.
* `config/database.php`.
* Puntos de entrada actualizados: `index.php`, `crear.php`, `editar.php`, `actualizar.php`, `eliminar.php` y `api/*.php`.

---

## Tablas de la base de datos involucradas
* `productos`, `usuarios` y `bitacora` en la base de datos `gestor_inventario`.

---

## Pruebas realizadas y Resultado de cada prueba
Se ejecutaron las 14 pruebas de integración MVC documentadas en `Practicas/Semana11/pruebas_mvc.md`, verificando el flujo Controlador → Modelo → Vista, la reutilización del controlador desde los endpoints JSON de Fetch API y la preservación de sesiones y permisos. Todas las pruebas resultaron **Correctas**.

---

## Errores encontrados
1. **Rutas relativas al incluir archivos desde `app/controllers/` y `api/`:**
   * Al mover las clases a subcarpetas (`app/models/`, `app/controllers/`), un `require_once "Producto.php"` relativo fallaba dependiendo de si se llamaba desde la raíz o desde `api/listar.php`.
2. **Duplicación de validaciones entre `crear.php` y `api/guardar.php`:**
   * Antes de MVC las reglas de validación estaban escritas dos veces.

## Soluciones aplicadas
1. Utilicé la constante mágica `__DIR__` en todos los `require_once` (por ejemplo, `require_once __DIR__ . '/../models/Producto.php'`), garantizando rutas absolutas confiables sin importar desde qué carpeta se invoque.
2. Unifiqué la validación en `ProductoController::validarDatosProducto()` para que tanto el formulario tradicional como Fetch API ejecuten la misma regla de negocio.

---

## Evidencias
* Diagnóstico previo en [`estructura_inicial.md`](estructura_inicial.md).
* Documentación de arquitectura en [`estructura_mvc.md`](estructura_mvc.md).
* Resultados de las 14 pruebas en [`pruebas_mvc.md`](pruebas_mvc.md).

---

## Reflexión final
Aplicar la arquitectura MVC hizo que el código pasara de ser archivos largos con SQL y HTML mezclados a piezas ordenadas y fáciles de mantener. Ahora, si cambia una columna en MySQL solo toco el Modelo; si cambia una regla de validación solo toco el Controlador; y si cambia el diseño visual solo toco la Vista.
