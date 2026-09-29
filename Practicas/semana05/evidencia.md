# 🗄️ Semana 05 - PHP + MySQL: Persistencia de datos

## Objetivo
Incorporar una base de datos MySQL al proyecto integrador para dejar de usar la memoria temporal y comenzar a almacenar los registros de nuestro inventario de manera permanente.

## Base de datos y Tabla principal
* **Base de datos:** `gestor_inventario`
* **Tabla principal:** `productos`
* **Campos:** 
  * `id` (INT)
  * `nombre` (VARCHAR)
  * `stock` (INT)
  * `precio` (DECIMAL)
  * `correo_proveedor` (VARCHAR)
  * `categoria` (VARCHAR)
* **Llave primaria:** El campo `id`. Se utilizó la propiedad `AUTO_INCREMENT` para que MySQL asigne un identificador único automático a cada producto nuevo sin que el usuario tenga que capturarlo.

## Comandos SQL utilizados
* **`CREATE DATABASE` y `CREATE TABLE`:** Para estructurar la bóveda de información desde el archivo `consultas.sql`.
* **`INSERT`:** Utilizado en `procesar.php` para guardar de forma definitiva los datos que llegan limpios desde el formulario.
* **`SELECT`:** Utilizado en `index.php` para consultar toda la tabla de productos y dibujarlos en la pantalla principal.

## Conexión PHP + MySQL
Se creó el archivo `conexion.php` utilizando la clase `mysqli`. Este archivo actúa como puente y centraliza las credenciales de acceso al servidor (localhost, usuario root, y la contraseña). Aislar este código permite utilizar la instrucción `require_once "conexion.php"` en lugar de repetir las credenciales en cada archivo.

## Flujo Completo de la Aplicación
1. **Captura (HTML/JS):** El usuario llena el formulario. JavaScript revisa que no haya campos vacíos y que los números sean mayores a 0.
2. **Envío (PHP):** `procesar.php` recibe los datos, hace una segunda validación de seguridad por el lado del servidor y se conecta a la base de datos a través de `conexion.php`.
3. **Guardado (MySQL):** Se ejecuta la consulta `INSERT`. Si es exitosa, el producto queda inmortalizado en la tabla `productos`.
4. **Consulta (PHP a HTML):** Al regresar al `index.php`, PHP ejecuta un `SELECT`, extrae todos los registros guardados y los imprime dinámicamente dentro de una tabla que conserva los estilos CSS de las semanas pasadas.

## Problemas encontrados y Experimentos
Durante la práctica ocurrieron dos errores reales de conexión que tuve que solucionar:

1. **Error de Acceso Denegado (Contraseña de WAMP):** 
   * **Problema:** Obtuve un error crítico `Access denied for user 'root'@'localhost' (using password: NO)`. 
   * **Causa y Solución:** PHP intentaba entrar a la base de datos sin contraseña, pero la instalación local requería autenticación. Lo solucioné abriendo `conexion.php` e ingresando la contraseña exacta con la que inicio sesión en MySQL Workbench.
2. **Error de Base de Datos Desconocida:**
   * **Problema:** Una vez arreglada la contraseña, me saltó el error `Unknown database 'gestor_inventario'`.
   * **Causa y Solución:** Me había conectado al motor de MySQL con éxito, pero la base de datos aún no estaba construida. Lo solucioné yendo a Workbench y ejecutando mi código de `consultas.sql` (`CREATE DATABASE...`) para crear la estructura. Al recargar la página, la tabla cargó a la perfección.

## Relación Tecnológica Actual (Reflexión)
Esta semana comprendí por fin el flujo completo:
* **HTML:** Crea la estructura visual y los formularios.
* **CSS:** Da presentación, diseño y colores.
* **JavaScript:** Da interactividad rápida y validaciones en la cara del cliente sin recargar la página.
* **PHP:** Es el cerebro en el backend; valida con seguridad y procesa los datos.
* **MySQL:** Es la memoria permanente. Guarda lo que PHP le manda y se lo devuelve cuando PHP necesita mostrarlo en pantalla.