# 💡 Descripción del Proyecto Integrador — Semana 15

**Alumno:** Pedro Compean Orozco  
**Materia:** Desarrollo de Aplicaciones Web

---

## 1. Propósito del Proyecto

### ¿Cómo se llama tu aplicación?
**Gestor de Inventario** — Sistema Web de Control de Almacén, Proveedores y Existencias.

### ¿Qué problema resuelve?
En pequeños y medianos almacenes es común llevar el registro de entradas de mercancía, existencias, precios y correos de proveedores en hojas sueltas o archivos desconectados, lo que provoca pérdida de información, desconocimiento del valor total del inventario, desabasto por no detectar stock bajo a tiempo y falta de control sobre quién modifica o elimina registros. El **Gestor de Inventario** centraliza toda esa información en una base de datos MySQL accesible desde el navegador con control de acceso por roles y auditoría.

### ¿Quiénes son sus usuarios?
1. **Administrador de Almacén (`administrador`):** Supervisa todo el inventario, da de alta, edita y da de baja productos, administra las cuentas de usuario y sus roles, y audita la bitácora del sistema.
2. **Operador de Almacén (`operador`):** Registra la entrada de nuevos productos y actualiza existencias o precios, sin permiso para borrar registros ni alterar cuentas de usuario.
3. **Personal de Consulta / Ventas (`consulta`):** Consulta existencias, busca artículos por proveedor o categoría, revisa reportes estadísticos y descarga listados en CSV en modo de solo lectura.

### ¿Qué información administra?
* **Catálogo e inventario de productos (`productos`):** Identificador único, nombre del artículo, categoría (`Electronica`, `Mobiliario`, `Limpieza`), cantidad en stock, precio unitario, valor subtotal en almacén y correo electrónico del proveedor.
* **Usuarios y control de acceso (`usuarios`):** Nombre completo, usuario único, correo, contraseña cifrada (`password_hash`), rol asignado y estado activo/inactivo.
* **Bitácora de auditoría (`bitacora`):** Fecha y hora, usuario responsable, tipo de acción (`LOGIN`, `CREAR`, `ACTUALIZAR`, `ELIMINAR`, `CREAR_USUARIO`, `CAMBIO_ROL`), módulo afectado, ID del registro y detalle de la operación.

### ¿Cuáles son sus funciones principales?
1. Autenticación segura con sesiones de PHP, `password_verify()` y protección de rutas internas.
2. Control de permisos en el servidor por rol (`administrador`, `operador`, `consulta`) y menú dinámico.
3. CRUD completo de productos con validación en cliente y servidor y consultas preparadas (`mysqli`).
4. Comunicación asíncrona con **Fetch API y JSON** para actualizar la tabla, registrar y eliminar productos sin recargar toda la página.
5. Búsqueda multicriterio, filtros combinados por categoría y nivel de stock, ordenamiento seguro mediante lista blanca y paginación en el servidor.
6. Panel de reportes estadísticos con indicadores KPI, alertas de stock crítico (`<= 15` unidades), bitácora de auditoría y exportación segura a CSV.

### ¿Qué característica la distingue?
Combina una experiencia rápida e interactiva gracias a **Fetch API** con una arquitectura limpia **MVC + Componentes reutilizables** y una triple función avanzada para la gestión de almacenes: **buscador/filtrador multicriterio con ordenamiento seguro, tablero de alertas de stock crítico con exportación CSV protegida contra inyección de fórmulas, y bitácora de auditoría en tiempo real**.

---

## 2. Guion del Recorrido de Demostración Final

1. **Presentación inicial:** Mostrar `login_form.php` y explicar el problema que resuelve el Gestor de Inventario.
2. **Inicio de sesión como Administrador (`admin_prueba`):** Ingresar credenciales de prueba y mostrar en el encabezado el nombre del usuario y la insignia `Rol: Administrador`, así como el menú completo.
3. **Consulta, Búsqueda, Filtros y Paginación (Semana 13):** Buscar un término, filtrar por categoría y nivel de stock, ordenar por precio y navegar entre páginas.
4. **Alta asíncrona con Fetch API (Semana 10):** Registrar un producto nuevo desde el formulario lateral manteniendo activa la casilla de Fetch API, demostrando en la pestaña *Network* la respuesta JSON de `api/guardar.php` y la actualización inmediata de la tabla y del valor total sin recargar la página.
5. **Edición de registro (Semana 06 / 11):** Hacer clic en **✏️ Editar**, modificar el stock y el precio en `editar.php` y guardar en `actualizar.php`.
6. **Eliminación de registro de prueba:** Eliminar el producto de demostración confirmando el diálogo y observando la actualización vía `api/eliminar.php`.
7. **Reportes, Exportación CSV y Bitácora (Semana 13):** Abrir `reportes.php` para mostrar los KPIs y alertas de stock bajo, descargar el archivo en `exportar_csv.php` y abrir `bitacora.php` para mostrar que todas las acciones anteriores quedaron registradas.
8. **Demostración de Roles y Seguridad (Semanas 08 y 09):** Cerrar sesión, iniciar con `consulta_prueba`, mostrar cómo cambia el menú y la tabla pasa a *Solo lectura*, e intentar acceder directamente a `usuarios.php` o `editar.php?id=1` para demostrar el rechazo HTTP `403` del servidor.
9. **Cierre de sesión y bloqueo de páginas privadas (Semana 07):** Ejecutar `logout.php` e intentar abrir `index.php` directamente para mostrar la redirección automática a `login_form.php`.
10. **Explicación de código MVC y componentes (Semanas 11 y 12):** Mostrar la estructura `app/models/`, `app/controllers/` y `app/views/`.
