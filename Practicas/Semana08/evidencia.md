# 🛡️ Práctica 08 — Desarrollo de Aplicaciones Web (Auditoría y Seguridad Básica)

## Objetivo
Revisar y fortalecer la seguridad del proyecto integrador (**Gestor de Inventario**) mediante validaciones del lado del servidor en PHP, prevención de inyección SQL con consultas preparadas, protección de contraseñas con `password_hash()` y `password_verify()`, escape de salida HTML con `htmlspecialchars()` y manejo prudente de errores y credenciales.

---

## Funcionalidad implementada
1. **Validación en el servidor con PHP:**
   * Comprobación de campos obligatorios (`nombre`, `stock`, `precio`, `correo_proveedor`, `categoria`).
   * Validación de identificadores numéricos enteros positivos con `filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)` y `filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)`.
   * **Reglas de negocio implementadas:**
     1. El stock y el precio unitario no pueden ser cero ni negativos (`> 0`).
     2. El correo del proveedor debe cumplir `FILTER_VALIDATE_EMAIL`.
     3. El campo `categoria` solo acepta valores incluidos en la lista permitida (`['Electronica', 'Mobiliario', 'Limpieza']`).
     4. El nombre de usuario en `usuarios` no puede repetirse.
2. **Prevención de Inyección SQL:**
   * Todas las consultas `SELECT`, `INSERT`, `UPDATE` y `DELETE` que reciben datos externos utilizan `$conexion->prepare()` y `$stmt->bind_param()`.
3. **Protección de contraseñas:**
   * Generación de hashes seguros con `password_hash($contrasena, PASSWORD_DEFAULT)` y verificación exclusiva mediante `password_verify()`.
4. **Escape de salida HTML (Prevención de XSS):**
   * Todos los valores dinámicos impresos en tablas, atributos `value` de formularios, alertas y nombre del usuario en sesión se escapan con `htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')`.
5. **Protección de secretos y manejo de errores:**
   * Se creó `config/env.example.php` como plantilla y se agregó `config/env.local.php` a `.gitignore` para evitar subir contraseñas locales al repositorio.
   * Los fallos de base de datos se registran con `error_log()` y muestran al usuario un mensaje controlado sin revelar credenciales ni rutas internas.

---

## Archivos modificados o creados
* `config/database.php`, `config/env.example.php` y `.gitignore`.
* `app/controllers/ProductoController.php` y `app/models/Producto.php`.
* `app/views/componentes/tabla_productos.php` y `app/views/productos/editar.php`.
* `Practicas/Semana08/auditoria.md` y `Practicas/Semana08/pruebas_seguridad.md`.

---

## Tablas de la base de datos utilizadas
* `productos` y `usuarios` dentro de `gestor_inventario`.

---

## Pruebas realizadas y Resultado de cada prueba
Se ejecutaron las 12 pruebas de seguridad detalladas en `Practicas/Semana08/pruebas_seguridad.md`, incluyendo inyección de etiquetas `<script>alert(1)</script>` en nombres de producto, envío de identificadores negativos/alfanuméricos, validación de reglas de negocio y comprobación de hashes en MySQL. Todas las pruebas resultaron **Correctas**.

---

## Errores encontrados
1. **Exposición de contraseña en `conexion.php` de la Semana 05:**
   * En la Semana 05 la contraseña de MySQL estaba escrita directamente en un archivo rastreado por Git y en caso de fallo se ejecutaba `die("Error: " . $conexion->connect_error)`.
2. **Impresión directa en la tabla HTML:**
   * En la primera versión del listado se concatenaba `$fila["nombre"]` directamente dentro de `<td>`, lo que permitía que caracteres como `<` o `>` alteraran el DOM.

## Soluciones aplicadas
1. Se aisló la configuración sensible en `config/env.local.php` (excluido mediante `.gitignore`) y se reemplazó `die($conexion->connect_error)` por `error_log()` más un mensaje genérico seguro.
2. Se aplicó `htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8')` en todas las vistas y componentes.

---

## Evidencias
* Inventario de auditoría en [`auditoria.md`](auditoria.md).
* Resultados de las 12 pruebas de seguridad en [`pruebas_seguridad.md`](pruebas_seguridad.md).

---

## Reflexión final
Esta práctica demostró que la validación en JavaScript es solo una ayuda estética para el usuario, mientras que la verdadera defensa ocurre en PHP y MySQL. Separar secretos fuera del repositorio, usar consultas preparadas y escapar toda salida dinámica son hábitos indispensables en cualquier aplicación web profesional.
