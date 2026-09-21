# ⚡ Semana 04 - JavaScript

---

## 🎯 Objetivo
El objetivo de esta semana fue incorporar JavaScript a la versión funcional del Sistema de Inventario. Con esto, logramos que la interfaz responda a las acciones del usuario, validando la información en el navegador en tiempo real antes de enviarla al servidor.

## 💻 Aplicación web
El proyecto integrador es un **Sistema de Inventario**. En esta cuarta etapa, se le agregó una capa de interactividad que revisa automáticamente que los datos del producto (nombre, stock, precio, categoría) sean correctos sin necesidad de recargar la página.

## 📁 Archivo JavaScript
Todo el código dinámico fue separado correctamente en un archivo externo llamado `script.js`. Se conectó al HTML insertando la etiqueta `<script src="script.js"></script>` justo antes de cerrar la etiqueta `</body>`, asegurando que la página cargue visualmente primero.

## 📦 Variables
Se declararon variables utilizando `let` y `const` para guardar temporalmente la información que el usuario teclea en el formulario y para atrapar los elementos de la página. 
Ejemplo: `let nombre = inputNombre.value.trim();` y `const mensajeDiv = document.getElementById("mensaje-sistema");`

## 🔧 Funciones
Se creó una función propia llamada `iniciarSistema()` que agrupa instrucciones para ejecutarse cuando arranca la aplicación, mandando un registro a la consola confirmando que el sistema está activo.

## 🖱️ Eventos
La aplicación reacciona a las acciones del usuario gracias a tres eventos:
* `submit`: Intercepta el momento en que el usuario intenta enviar el formulario para validarlo.
* `click`: Se dispara cuando el usuario presiona el botón de "Mostrar Ayuda" para revelar información oculta.
* `input`: Se dispara cada vez que el usuario teclea en el nombre del producto (Desafío de la semana).

## 🌳 DOM y Manipulación de elementos
Se utilizó el DOM para que JavaScript pudiera leer y modificar la estructura de la página. Utilicé `document.getElementById` para "enganchar" los elementos HTML a mis variables y luego manipulé su texto usando `textContent` y sus estilos usando `classList.add`.

## 📝 Validación del formulario
Antes de enviar el registro a PHP, JavaScript detiene el proceso y verifica:
1. Que ningún campo obligatorio esté vacío.
2. Que el stock y el precio sean números mayores a 0.
Si detecta un error, frena el envío usando la instrucción `event.preventDefault()`.

## 💬 Mensajes dinámicos
El sistema inyecta mensajes dinámicos directamente en un contenedor vacío (`<div id="mensaje-sistema"></div>`). Dependiendo de si los datos son correctos o incorrectos, JavaScript le inyecta la clase CSS `.mensaje-error` (rojo) o `.mensaje-exito` (verde) en tiempo real.

## 🙈 Mostrar y ocultar elementos
Se programó un botón para la sección de ayuda. Al darle clic, JavaScript lee el estilo; si la caja está oculta (`display: none`), la hace aparecer (`display: block`) y cambia dinámicamente el texto del botón a "Ocultar Ayuda".

## 🔥 Desafío de la semana (Interacción extra)
Se agregó un evento `input` al campo de "Nombre del Producto". Conforme el usuario teclea, JavaScript toma ese valor y lo convierte automáticamente a mayúsculas utilizando la función `toUpperCase()`.

---

## 🔬 Experimentos y Herramientas de desarrollador

### Experimento 1 - Cambiar el contenido
Usé JavaScript para cambiar el texto de un botón usando `textContent`. Esperaba que el texto cambiara al instante de dar clic, y ocurrió exactamente eso. Aprendí que JS puede reescribir cualquier texto en pantalla sin consultar al servidor.

### Experimento 2 - Romper JavaScript
**Problema provocado:** Escribí mal la instrucción cambiando `getElementById` por `getElementByID` (con D mayúscula).
**Resultado:** El botón de registrar dejó de funcionar por completo y el formulario se enviaba vacío.
**Solución:** Revisé la consola (F12) y me marcó que la función no existía. Corregí la letra a minúscula y volvió a funcionar. Aprendí que JavaScript es 100% sensible a mayúsculas y minúsculas.

### Experimento 3 - Cambiar el ID en HTML
Cambié el ID del botón a `btnEnviar` en el HTML, pero dejé `btnGuardar` en JS. El sistema falló porque JavaScript intentó buscar un elemento que ya no existía en el DOM. Esto demuestra que JS depende estructuralmente de las etiquetas de HTML.

### Experimento 4 - Herramientas de desarrollador
En la pestaña `Console` encontré los mensajes internos (`console.log`) y los errores de código en color rojo. En la pestaña `Elements` pude modificar el texto y colores del formulario en vivo, pero noté que los cambios desaparecen al recargar la página porque solo son modificaciones temporales en mi pantalla.

---

## 🔎 Investigación de conceptos

1. **JavaScript:** Lenguaje de programación que se ejecuta en el navegador para hacer las páginas web dinámicas e interactivas.
2. **¿Para qué sirve?** Para validar datos al instante, cambiar el diseño, hacer cálculos y reaccionar a lo que hace el usuario.
3. **Diferencia entre JS y PHP:** JS trabaja en la computadora del usuario (cliente) al instante; PHP trabaja en la bóveda del servidor (backend) procesando datos de forma segura.
4. **Variable:** Un espacio en memoria donde guardamos datos temporales.
5. **Función:** Un bloque de código empaquetado que ejecuta una tarea específica solo cuando lo mandamos a llamar.
6. **Evento:** Una acción del usuario (clic, escribir, mover el mouse) que JavaScript puede detectar para reaccionar.
7. **DOM:** Es el árbol de la página. Convierte el HTML en "objetos" que JS puede leer, mover o borrar.
8. **getElementById():** Busca un solo elemento en el HTML basándose en su ID exacto.
9. **querySelector():** Busca el primer elemento que coincida con un selector CSS (como clases o etiquetas).
10. **addEventListener():** Pone a un elemento en modo vigilante para ejecutar una función cuando ocurra un evento (ej. "escuchar un clic").
11. **textContent:** Modifica o lee únicamente el texto plano que está dentro de un elemento.
12. **classList:** Permite agregar o quitar clases CSS a un elemento para cambiar su diseño de golpe.
13. **preventDefault():** Cancela el comportamiento automático del navegador, como frenar un formulario para que no recargue la página.
14. **Validación del lado del cliente:** Es el filtro rápido que hace el navegador con JS para verificar los datos antes de enviarlos por internet.
15. **¿Por qué validar en PHP también?** Porque el usuario podría apagar JavaScript en su navegador o hackear el envío. PHP es la revisión final obligatoria.
16. **Consola del navegador:** La herramienta donde JS imprime registros o avisa de errores a los programadores.
17. **Error de JavaScript:** Un fallo de escritura o lógica que rompe el programa y detiene la ejecución.
18. **Página interactiva:** Una web que reacciona de inmediato al usuario sin necesidad de quedarse en blanco y cargar de nuevo.

---

## 🔗 Relación entre Tecnologías
* 🌐 **HTML:** Armó el esqueleto y las cajas de texto de mi inventario.
* 🎨 **CSS:** Pintó las cajas, separó las secciones y coloreó los mensajes de error.
* ⚡ **JavaScript:** Actuó como el supervisor rápido en la entrada, revisando que los datos no vengan vacíos y encendiendo alertas instantáneas.
* 🐘 **PHP:** Actuó como el gerente en el servidor, recibiendo los datos filtrados para dar el visto bueno final.

---

## 🧠 Reflexión Final
En esta práctica comprobé la diferencia entre el cliente (mi navegador) y el servidor (PHP). Antes, para saber si me equivocaba en un campo, los datos tenían que viajar y la página recargarse por completo. Al integrar JavaScript, el sistema se siente rápido y profesional porque los errores se detectan de inmediato. Entendí que una buena aplicación web usa HTML para la estructura, CSS para verse bien, JS para reaccionar rápido y PHP para procesar con seguridad.

