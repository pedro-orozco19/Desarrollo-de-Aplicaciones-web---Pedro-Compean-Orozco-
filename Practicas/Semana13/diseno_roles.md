# 👥 Diseño de Roles y Permisos — Semana 09

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## 1. Definición de Roles de la Aplicación

Para atender las necesidades reales de un almacén, el sistema define tres roles con responsabilidades claramente separadas:

| Rol | Valor en BD (`usuarios.rol`) | Responsabilidad | Operaciones permitidas | Operaciones restringidas |
|---|---|---|---|---|
| **Administrador** | `administrador` | Administración integral del inventario, usuarios y auditoría | Consultar inventario, buscar/filtrar, exportar CSV, ver reportes, **crear productos**, **editar productos**, **eliminar productos**, **gestionar usuarios/roles** y **consultar bitácora** | No se permite degradar o desactivar su propia cuenta en sesión para evitar bloqueo accidental del sistema |
| **Operador de Almacén** | `operador` | Captura y actualización cotidiana de entradas y existencias | Consultar inventario, buscar/filtrar, exportar CSV, ver reportes, **crear productos** y **editar productos** | **No puede eliminar productos**, ni acceder a la gestión de usuarios ni a la bitácora de auditoría |
| **Usuario de Consulta** | `consulta` | Revisión de existencias, precios y reportes sin alterar datos | Consultar inventario, buscar/filtrar, ordenar, paginar, ver estadísticas y exportar CSV | **No puede crear, editar ni eliminar productos**, ni acceder a usuarios o bitácora |

---

## 2. Estructura de Datos Utilizada

Se eligió la **Opción A enriquecida** sobre la tabla `usuarios`, incorporando la columna `rol VARCHAR(30) NOT NULL DEFAULT 'consulta'` junto con el campo `activo TINYINT(1) NOT NULL DEFAULT 1`:

```sql
ALTER TABLE usuarios
ADD COLUMN rol VARCHAR(30) NOT NULL DEFAULT 'consulta' AFTER password_hash;
```

### Justificación de diseño
El sistema maneja un conjunto bien definido de tres roles operativos (`administrador`, `operador` y `consulta`), donde cada cuenta de usuario desempeña un único rol dentro del almacén. Almacenar el rol directamente en la tabla `usuarios` y cargarlo en `$_SESSION['rol']` desde la base de datos durante el inicio de sesión permite verificar permisos en cada script PHP mediante `exigirRol(array $rolesPermitidos)` en `autorizacion.php`, sin confiar jamás en datos enviados por `GET`, `POST` o JavaScript.

---

## 3. Cuentas de Demostración Preparadas

| Cuenta de prueba | Usuario | Rol asignado | Estado | Propósito en las pruebas |
|---|---|---|---|---|
| Administrador Demo | `admin_prueba` | `administrador` | Activo (`1`) | Demostrar CRUD completo, borrado, gestión de usuarios y bitácora |
| Operador Demo | `operador_prueba` | `operador` | Activo (`1`) | Demostrar alta y edición permitidas, pero borrado y administración restringidos |
| Consulta Demo | `consulta_prueba` | `consulta` | Activo (`1`) | Demostrar modo de solo lectura y rechazo HTTP `403` ante intentos de escritura |
| Inactivo Demo | `inactivo_prueba` | `consulta` | Inactivo (`0`) | Comprobar bloqueo de cuentas dadas de baja desde el login |
