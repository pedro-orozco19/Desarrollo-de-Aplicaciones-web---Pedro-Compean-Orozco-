# Proyecto integrador — Desarrollo de Aplicaciones Web

**Alumno:** Pedro Compean Orozco  
**Fecha de entrega:** Octubre de 2026

---

## 1. Nombre del proyecto
**Gestor de Inventario** — Aplicación Web Integradora para la Administración de Almacén, Proveedores, Existencias y Auditoría.

---

## 2. Problema que resuelve
La administración manual o desorganizada de un almacén dificulta conocer en tiempo real cuántas unidades quedan de cada artículo, cuál es el valor económico total de la mercancía almacenada, a qué proveedor contactar cuando un producto entra en nivel crítico de stock y qué usuario realizó altas, modificaciones o bajas en el catálogo. El **Gestor de Inventario** resuelve este problema ofreciendo una plataforma centralizada en PHP y MySQL con validaciones de negocio, control de acceso por roles, reportes automáticos y trazabilidad completa.

---

## 3. Objetivo general
Desarrollar, asegurar, organizar y presentar una aplicación web funcional que integre progresivamente HTML5, CSS3, JavaScript, PHP, MySQL, operaciones CRUD, sesiones de autenticación, control de acceso basado en roles, seguridad en el servidor, peticiones asíncronas con Fetch API / JSON, arquitectura MVC, componentes reutilizables y funciones avanzadas de búsqueda, filtrado, reportes, exportación CSV y bitácora.

---

## 4. Usuarios y funciones principales

* **Administrador (`administrador`):**
  * Acceso total al catálogo de productos (crear, consultar, filtrar, editar y eliminar).
  * Administración de cuentas de usuario y asignación controlada de roles (`usuarios.php`).
  * Consulta de la bitácora de auditoría del sistema (`bitacora.php`).
  * Consulta de estadísticas ejecutivas (`reportes.php`) y exportación a CSV (`exportar_csv.php`).
* **Operador de Almacén (`operador`):**
  * Consulta, búsqueda y filtrado del inventario, alta de nuevos productos y edición de existencias/precios.
  * Sin permiso para eliminar productos ni acceder a la gestión de usuarios o bitácora.
* **Usuario de Consulta (`consulta`):**
  * Consulta del inventario en modo de solo lectura, uso de buscador, filtros, ordenamiento, paginación, visualización de reportes y descarga de CSV.
  * Bloqueo en el servidor (`HTTP 403`) ante cualquier intento de creación, edición o eliminación.

---

## 5. Tecnologías utilizadas

| Tecnología | Implementación concreta en el proyecto |
|---|---|
| **HTML5** | Estructura semántica (`<header>`, `<nav>`, `<main>`, `<section>`, `<table>`, `<form>`, `<footer>`) en las vistas de `app/views/`. |
| **CSS3** | Hoja de estilos unificada `estilos.css` con Modelo de Caja, Flexbox, CSS Grid para filtros y KPIs, insignias de estado y diseño adaptable (`@media`). |
| **JavaScript (ES6+)** | Archivo `script.js` para conversión a mayúsculas en vivo, validación previa de formularios, confirmación de bajas y manipulación segura del DOM con `createElement` y `textContent`. |
| **PHP 8.3** | Procesamiento en el servidor, validaciones con `filter_input` y `filter_var`, sesiones, control de acceso por roles, arquitectura MVC y generación de CSV/JSON. |
| **MySQL** | Base de datos relacional `gestor_inventario` con las tablas `productos`, `usuarios` y `bitacora` utilizando codificación `utf8mb4`. |
| **Fetch API / JSON** | Comunicación asíncrona entre `script.js` y los endpoints `api/listar.php`, `api/guardar.php` y `api/eliminar.php`. |

---

## 6. Estructura de la aplicación

```text
Desarrollo-de-Aplicaciones-web---Pedro-Compean-Orozco-/
├── app/
│   ├── controllers/
│   │   ├── ProductoController.php
│   │   ├── AuthController.php
│   │   └── UsuarioController.php
│   ├── models/
│   │   ├── Producto.php
│   │   ├── Usuario.php
│   │   └── Bitacora.php
│   └── views/
│       ├── layouts/
│       │   ├── header.php
│       │   ├── menu.php
│       │   ├── mensajes.php
│       │   └── footer.php
│       ├── componentes/
│       │   ├── tabla_productos.php
│       │   ├── boton_accion.php
│       │   └── paginacion.php
│       └── productos/
│           ├── index.php
│           └── editar.php
├── api/
│   ├── listar.php
│   ├── guardar.php
│   └── eliminar.php
├── config/
│   ├── database.php
│   └── env.example.php
├── sql/
│   └── schema_completo.sql
├── Practicas/
│   └── Semana01/ ... Semana15/
├── index.php, login_form.php, login.php, logout.php, proteger.php, autorizacion.php
├── crear.php, editar.php, actualizar.php, eliminar.php, listar.php, panel.php
├── usuarios.php, reportes.php, exportar_csv.php, bitacora.php
├── estilos.css, script.js
└── README.md
```

---

## 7. Diseño de la base de datos

La base de datos `gestor_inventario` está compuesta por tres tablas en motor `InnoDB` con juego de caracteres `utf8mb4`:

1. **`productos`:**
   * `id` (`INT AUTO_INCREMENT PRIMARY KEY`)
   * `nombre` (`VARCHAR(100) NOT NULL`)
   * `stock` (`INT NOT NULL`)
   * `precio` (`DECIMAL(10,2) NOT NULL`)
   * `correo_proveedor` (`VARCHAR(150) NOT NULL`)
   * `categoria` (`VARCHAR(50) NOT NULL`)
   * `activo` (`TINYINT(1) NOT NULL DEFAULT 1`)
   * `creado_en` (`TIMESTAMP DEFAULT CURRENT_TIMESTAMP`)
2. **`usuarios`:**
   * `id` (`INT AUTO_INCREMENT PRIMARY KEY`)
   * `nombre` (`VARCHAR(100) NOT NULL`)
   * `usuario` (`VARCHAR(50) NOT NULL UNIQUE`)
   * `correo` (`VARCHAR(150) NOT NULL UNIQUE`)
   * `password_hash` (`VARCHAR(255) NOT NULL`)
   * `rol` (`VARCHAR(30) NOT NULL DEFAULT 'consulta'`)
   * `activo` (`TINYINT(1) NOT NULL DEFAULT 1`)
   * `creado_en` (`TIMESTAMP DEFAULT CURRENT_TIMESTAMP`)
3. **`bitacora`:**
   * `id` (`INT AUTO_INCREMENT PRIMARY KEY`)
   * `usuario_id` (`INT NULL`)
   * `usuario_nombre` (`VARCHAR(100) NOT NULL`)
   * `accion` (`VARCHAR(50) NOT NULL`)
   * `modulo` (`VARCHAR(50) NOT NULL`)
   * `registro_id` (`INT NULL`)
   * `detalle` (`VARCHAR(255) NULL`)
   * `creado_en` (`TIMESTAMP DEFAULT CURRENT_TIMESTAMP`)

---

## 8. Autenticación, roles y seguridad

* **Contraseñas cifradas:** Se utiliza `password_hash($clave, PASSWORD_DEFAULT)` al crear usuarios y `password_verify()` en `AuthController::iniciarSesion()`. Ninguna contraseña se guarda en texto plano.
* **Gestión de sesiones:** `proteger.php` comprueba `$_SESSION['usuario_id']` antes de enviar cualquier salida HTML. Al autenticar correctamente se ejecuta `session_regenerate_id(true)`, y al cerrar sesión (`logout.php`) se destruye tanto la sesión en el servidor como la cookie en el navegador.
* **Autorización en el servidor:** `autorizacion.php` define `exigirRol(array $rolesPermitidos)`, función invocada al inicio de cada script y endpoint restringido.
* **Prevención de Inyección SQL:** Todas las consultas con datos externos emplean sentencias preparadas (`$conexion->prepare()` y `bind_param()`), y las cláusulas `ORDER BY` se validan contra la lista permitida `Producto::COLUMNAS_ORDEN`.
* **Prevención de XSS y CSRF:** Todas las salidas dinámicas en las vistas se escapan con `htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')`, y los formularios incluyen validación de `csrf_token`.
* **Resguardo de credenciales:** Las credenciales locales se leen desde `config/env.local.php` (excluido en `.gitignore`), dejando en el repositorio únicamente `config/env.example.php`.

---

## 9. Implementación de Fetch API

* Los archivos `api/listar.php`, `api/guardar.php` y `api/eliminar.php` procesan solicitudes HTTP asíncronas enviadas con `fetch()` desde `script.js`.
* Cada endpoint verifica sesión (`401`), permisos de rol (`403`) y validez de los datos (`400`), respondiendo con `Content-Type: application/json; charset=utf-8` sin mezclar fragmentos HTML.
* En el navegador, `script.js` verifica `respuesta.ok && resultado.ok`, construye los nodos `<tr>` y `<td>` mediante `document.createElement()` y `textContent`, y actualiza el valor total del almacén en vivo.

---

## 10. Organización MVC y componentes

* **Modelos (`app/models/`):** `Producto`, `Usuario` y `Bitacora` concentran el acceso a datos en MySQL.
* **Controladores (`app/controllers/`):** `ProductoController`, `AuthController` y `UsuarioController` validan las reglas de negocio y coordinan la operación entre los modelos y las vistas o endpoints JSON.
* **Plantilla y componentes (`app/views/layouts/` y `app/views/componentes/`):** `header.php`, `menu.php`, `mensajes.php`, `footer.php`, `tabla_productos.php`, `boton_accion.php` y `paginacion.php` garantizan una interfaz uniforme y libre de código repetido.

---

## 11. Función avanzada implementada

En la Práctica 13 se implementaron de forma integrada:
1. **Búsqueda multicriterio y filtros combinados** (por texto en nombre/proveedor, categoría y estado de stock bajo/suficiente).
2. **Ordenamiento dinámico seguro** validado contra lista blanca de columnas y direcciones (`ASC`/`DESC`).
3. **Paginación en el servidor** conservando todos los filtros activos entre páginas.
4. **Módulo de reportes estadísticos (`reportes.php`), exportación segura a CSV (`exportar_csv.php`) y bitácora de auditoría (`bitacora.php`).**

---

## 12. Pruebas realizadas y resultados

Durante la Semana 14 se ejecutaron 16 casos de prueba integrales (`PR-LOGIN-01` a `PR-ADV-02`) abarcando login, sesiones, validaciones, CRUD, permisos por rol, Fetch API, MVC, plantillas y funciones avanzadas. Tras aplicar las correcciones y ejecutar las pruebas de regresión, el 100% de los casos obtuvo resultado **Correcta**.

---

## 13. Errores encontrados y soluciones

1. **Conservación de filtros en Fetch API (`ERR-01`):** Se ajustó `cargarInventarioConFetch()` para enviar los parámetros activos de la barra de filtros a `api/listar.php`.
2. **Token CSRF en filas generadas dinámicamente (`ERR-02`):** Se incluyó `csrf_token` en los formularios de baja creados por `crearFilaProductoDOM()`.
3. **Persistencia de criterios en paginación (`ERR-03`):** Se utilizó `http_build_query()` en `paginacion.php` para mantener `q`, `categoria`, `estado_stock`, `orden` y `dir`.
4. **Rango de páginas fuera de límite (`ERR-04`):** Se acotó `$paginaActual` entre `1` y `$totalPaginas` después de calcular el total de registros.
5. **Codificación UTF-8 y fórmulas en CSV (`ERR-05`):** Se agregó cabecera BOM UTF-8 y la función `sanitizarCeldaCsv()` en `exportar_csv.php`.

---

## 14. Evidencias

Toda la documentación técnica, análisis, planes de prueba y bitácoras de resultados se encuentran organizados por semana dentro de la carpeta `Practicas/Semana01` a `Practicas/Semana15`.

---

## 15. Limitaciones conocidas

* El sistema está diseñado para ejecutarse en una sola sucursal o almacén principal; no divide aún las existencias por múltiples bodegas físicas independientes.
* Las notificaciones de stock bajo se muestran dentro del panel web (`index.php` y `reportes.php`) y aún no se envían automáticamente por correo electrónico SMTP al proveedor.

---

## 16. Mejoras futuras

1. Incorporar una tabla relacional `proveedores` e historial de movimientos de entrada/salida (`kardex`) con gráficas interactivas.
2. Agregar generación de reportes en PDF con código de barras o QR por artículo.
3. Implementar envío automático de alertas por correo cuando un producto baje del stock mínimo configurado.

---

## 17. Conclusión personal

Desarrollar el **Gestor de Inventario** a lo largo de las 15 semanas me permitió comprender cómo evoluciona una aplicación web real paso a paso: desde una primera página en PHP que calculaba una multiplicación en memoria en la Semana 01, hasta un sistema completo conectado a MySQL, asegurado contra inyección SQL y XSS, protegido con sesiones y roles, dinamizado con Fetch API y organizado profesionalmente bajo el patrón MVC y componentes reutilizables. Hoy no solo entrego un código que funciona, sino una arquitectura que comprendo en cada una de sus capas y que puedo explicar, mantener y escalar.
