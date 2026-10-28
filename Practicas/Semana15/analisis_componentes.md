# 🔎 Análisis de Elementos Repetidos y Componentes — Semana 12

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## Tabla de Identificación de Componentes Reutilizables

Tras revisar las páginas principales del sistema (`index.php`, `editar.php`, `reportes.php`, `usuarios.php`, `bitacora.php` y `login_form.php`), se identificaron los siguientes fragmentos repetidos y se extrajeron hacia `app/views/layouts/` y `app/views/componentes/`:

| Elemento repetido | Páginas donde aparece | Componente propuesto | Beneficio esperado |
|---|---|---|---|
| **Encabezado con nombre del sistema e insignia del usuario autenticado** | `index.php`, `editar.php`, `reportes.php`, `usuarios.php`, `bitacora.php`, `login_form.php` | `app/views/layouts/header.php` | Evitar duplicación del `<head>`, hoja de estilos `estilos.css` y presentación uniforme del usuario y su rol en sesión. |
| **Menú de navegación adaptable según permisos** | Todas las páginas privadas (`index.php`, `editar.php`, `reportes.php`, `usuarios.php`, `bitacora.php`) | `app/views/layouts/menu.php` | Navegación consistente en todos los módulos y ocultamiento automático de opciones restringidas según `$_SESSION['rol']`. |
| **Mensajes de estado (éxito, advertencia y error)** | Formularios de login, inventario, edición y administración de usuarios | `app/views/layouts/mensajes.php` | Mostrar avisos uniformes con escape `htmlspecialchars()`, limpiar `$_SESSION['flash_mensaje']` tras mostrarlo y proveer `#mensaje-sistema` para Fetch API. |
| **Pie de página y carga controlada de `script.js`** | Todas las páginas del sistema | `app/views/layouts/footer.php` | Facilitar cambios globales en el cierre del documento HTML y cargar `script.js` únicamente cuando la vista lo requiere. |
| **Tabla de inventario de productos y cálculo de total** | `index.php` y vistas de listado (`listar.php`, `panel.php`) | `app/views/componentes/tabla_productos.php` | Centralizar la estructura de la tabla sin ejecutar consultas SQL en la vista. |
| **Botones de acción (Editar / Eliminar) y etiquetas de nivel de stock** | Filas de `tabla_productos.php` | `app/views/componentes/boton_accion.php` | Respetar los permisos del rol (`administrador`, `operador`, `consulta`) y escapar salidas de forma centralizada. |
| **Barra de paginación con conservación de filtros** | Listado principal de inventario (`index.php`) | `app/views/componentes/paginacion.php` | Reutilizar los controles de navegación entre páginas conservando búsqueda, categoría y ordenamiento. |
