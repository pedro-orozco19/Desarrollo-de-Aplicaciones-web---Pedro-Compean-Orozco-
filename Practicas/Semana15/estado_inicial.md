# 🔍 Estado Inicial del Proyecto antes de las Pruebas de Integración — Semana 14

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco  
**Fecha de revisión:** Octubre de 2026

## 1. Lista de Verificación del Entorno y Versión Estable

- [x] La aplicación abre correctamente en el servidor local WAMP (`PHP 8.3.14`).
- [x] El servidor PHP se ejecuta sin errores de sintaxis (`php -l` verificado en todos los archivos).
- [x] La conexión a MySQL (`gestor_inventario`) funciona mediante `config/database.php`.
- [x] La estructura de archivos MVC, componentes y endpoints `api/` está documentada.
- [x] El repositorio cuenta con respaldos por semana (`Practicas/Semana01` a `Practicas/Semana15`).
- [x] Existe una forma inmediata de recuperar la versión estable mediante Git y `sql/schema_completo.sql`.
- [x] Hay productos y cuentas de demostración preparados (`admin_prueba`, `operador_prueba`, `consulta_prueba`, `inactivo_prueba`).
- [x] Las credenciales locales están aisladas en `config/env.local.php` (ignorado en `.gitignore`) y no se exponen contraseñas reales en las evidencias.

---

## 2. Estado General al Iniciar la Semana 14

Al concluir la Semana 13, la aplicación integradora cuenta con:
* **Autenticación y sesiones (`login_form.php`, `login.php`, `proteger.php`, `logout.php`):** Verificación con `password_verify()` y regeneración de ID de sesión.
* **Control de acceso por roles (`autorizacion.php`):** Tres roles (`administrador`, `operador` y `consulta`) validados en el servidor.
* **CRUD de productos organizado en MVC (`app/models/Producto.php`, `app/controllers/ProductoController.php`, `app/views/productos/`):** Altas, consultas, ediciones y bajas con consultas preparadas.
* **Endpoints asíncronos con Fetch API (`api/listar.php`, `api/guardar.php`, `api/eliminar.php`):** Respuestas JSON limpias y actualización parcial del DOM.
* **Plantilla y componentes reutilizables (`app/views/layouts/` y `app/views/componentes/`):** Encabezado, menú dinámico, mensajes flash, pie de página, tabla y paginación.
* **Funciones avanzadas:** Búsqueda multicriterio, filtros combinados, ordenamiento por lista blanca, paginación, panel de reportes/estadísticas, exportación segura a CSV y bitácora de auditoría.

---

## 3. Detalles a Verificar y Ajustar durante la Práctica 14

1. Confirmar que al eliminar o registrar un producto mediante Fetch API se mantengan activos los filtros seleccionados actualmente en la barra de búsqueda.
2. Comprobar que el token CSRF se incluya también en los formularios de eliminación reconstruidos dinámicamente en el DOM por `script.js`.
3. Verificar que la exportación a CSV sanitice cualquier carácter inicial potencialmente peligroso (`=`, `+`, `-`, `@`) para hojas de cálculo.
