# 🔄 Práctica 06 — Desarrollo de Aplicaciones Web (CRUD Completo)

## Objetivo
Construir un módulo funcional dentro del proyecto integrador (**Gestor de Inventario**) que permita **crear (CREATE), consultar (READ), modificar (UPDATE) y eliminar (DELETE)** registros de la entidad principal `productos` en MySQL, utilizando validaciones en el servidor con PHP, consultas preparadas (`prepare` y `bind_param`) y salida segura con `htmlspecialchars()`.

---

## Elección de la Entidad del CRUD
```text
Nombre del proyecto: Gestor de Inventario
Entidad que administraré: Productos de almacén
Tabla de MySQL: productos
Clave primaria: id (INT AUTO_INCREMENT PRIMARY KEY)
Campos principales: id, nombre, stock, precio, correo_proveedor, categoria
¿Por qué elegí esta entidad? Porque representa el núcleo del sistema de inventario construido desde la Semana 01 hasta la Semana 05; administrar altas, cambios de existencias/precios y bajas de productos responde directamente al problema del almacén.
```

---

## Funcionalidad implementada
1. **CREATE (`crear.php`):** Formulario con validación en cliente (`script.js`) y validación estricta en servidor (`crear.php`). Inserta registros en `productos` mediante consulta preparada (`INSERT INTO productos ... VALUES (?, ?, ?, ?, ?)`).
2. **READ (`index.php` y `listar.php`):** Consulta los productos almacenados en `gestor_inventario`, calcula el valor total en almacén (`stock * precio`) y presenta cada fila escapando todos los valores con `htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')`.
3. **UPDATE (`editar.php` y `actualizar.php`):** Carga los datos actuales del producto identificado por su clave primaria `id`, permite modificarlos y guarda los cambios mediante una consulta preparada (`UPDATE productos SET ... WHERE id = ?`).
4. **DELETE (`eliminar.php`):** Ejecuta la baja del registro únicamente mediante petición `POST` (nunca por enlace `GET` directo), pidiendo confirmación previa al usuario en el navegador y validando el identificador con `filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)`.

---

## Archivos modificados o creados
* `conexion.php`: Conexión centralizada a `gestor_inventario` con soporte de `utf8mb4`.
* `index.php` / `listar.php`: Vista principal con tabla de productos, botones de acción (**Editar** y **Eliminar**) y formulario de registro.
* `crear.php`: Procesamiento seguro del alta de productos con validación en PHP y `prepare()`.
* `editar.php`: Formulario precargado con los datos del producto consultado por su `id`.
* `actualizar.php`: Procesamiento del `UPDATE` validando clave primaria y campos obligatorios.
* `eliminar.php`: Procesamiento del `DELETE` por método `POST` con consulta preparada.
* `estilos.css` y `script.js`: Estilos para botones de edición/eliminación y confirmación de borrado.

---

## Tablas de la base de datos utilizadas
* **Base de datos:** `gestor_inventario`
* **Tabla:** `productos` (`id`, `nombre`, `stock`, `precio`, `correo_proveedor`, `categoria`, `activo`, `creado_en`).

---

## Pruebas realizadas y Resultado de cada prueba
El detalle completo de las 10 pruebas funcionales se documentó en `Practicas/Semana06/pruebas_crud.md`. Todas las pruebas (alta válida, rechazo de campos vacíos, lectura, edición, persistencia tras recarga, ID inexistente, cancelación de borrado y eliminación por POST) resultaron **Correctas**.

---

## Errores encontrados
1. **Eliminación accidental por enlace GET en el primer borrador:**
   * Inicialmente había colocado un enlace `<a href="eliminar.php?id=...">`, lo cual incumplía la regla de no borrar registros mediante peticiones `GET`.
2. **Concatenación directa de variables en el `INSERT` de la Semana 05:**
   * En la versión de la Semana 05 la consulta `INSERT` concatenaba `$nombre` y `$precio` directamente dentro del string SQL.

## Soluciones aplicadas
1. Reemplacé el enlace de borrado por un mini-formulario `<form action="eliminar.php" method="POST">` con un `<input type="hidden" name="id">` y una confirmación `confirm()` en JavaScript, además de bloquear cualquier solicitud distinta de `POST` en `eliminar.php`.
2. Migré todas las consultas (`INSERT`, `SELECT WHERE id = ?`, `UPDATE` y `DELETE`) a consultas preparadas con `$conexion->prepare()` y `$stmt->bind_param()`.

---

## Evidencias
* Código fuente funcional del CRUD integrado en el proyecto y respaldado en `Practicas/Semana06/`.
* Bitácora detallada de pruebas en [`pruebas_crud.md`](pruebas_crud.md).

---

## Reflexión final
Implementar las cuatro operaciones del CRUD me permitió transformar una página que solo insertaba y leía registros en un sistema de administración completo. Comprendí por qué las operaciones que modifican o eliminan información deben usar `POST`, validar siempre la clave primaria en PHP y utilizar consultas preparadas para proteger la integridad de la base de datos.
