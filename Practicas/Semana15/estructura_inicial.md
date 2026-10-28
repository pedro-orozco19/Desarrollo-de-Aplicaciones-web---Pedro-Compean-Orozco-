# 🔍 Estructura Inicial Antes de MVC — Semana 11

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## 1. Diagnóstico de la organización previa (Semanas 05 a 10)

Antes de la reorganización en capas MVC, los archivos de la aplicación concentraban en un mismo lugar las consultas SQL, la validación de formularios y la presentación HTML:

| Responsabilidad | Ubicación antes de MVC | Oportunidad de mejora detectada |
|---|---|---|
| **Conexión a MySQL** | `conexion.php` en la raíz | Centralizar en `config/database.php` manteniendo `conexion.php` como puente compatible. |
| **Consultas SQL (`SELECT`, `INSERT`, `UPDATE`, `DELETE`)** | Mezcladas dentro de `index.php`, `crear.php`, `editar.php`, `actualizar.php`, `eliminar.php` y `login.php` | Extraer toda la lógica de acceso a datos hacia clases **Modelo** (`app/models/Producto.php`, `Usuario.php`, `Bitacora.php`). |
| **Procesamiento y validación de solicitudes** | Disperso al inicio de cada archivo PHP y duplicado entre el CRUD tradicional y los endpoints de `api/` | Concentrar las reglas de negocio y coordinación en **Controladores** (`app/controllers/ProductoController.php`, `AuthController.php`, `UsuarioController.php`) para reutilizarlos tanto en vistas HTML como en `api/`. |
| **Presentación HTML** | Impresa con `echo` dentro de `procesar.php` / `index.php` junto con las consultas SQL | Separar en **Vistas** limpias (`app/views/productos/index.php` y `editar.php`) que solo reciban arreglos de datos preparados y los escapen con `htmlspecialchars()`. |
| **Sesión y permisos** | `proteger.php` y `autorizacion.php` | Conservar en los puntos de entrada antes de invocar al controlador correspondiente. |
