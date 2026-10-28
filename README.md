# 📦 Desarrollo de Aplicaciones Web — Proyecto Integrador (Gestor de Inventario)

**Alumno:** Pedro Compean Orozco  
**Tecnologías:** PHP 8+ · MySQL (`mysqli`) · HTML5 · CSS3 · JavaScript (ES6+ / Fetch API) · Arquitectura MVC

---

## 🎯 Propósito del Proyecto

El **Gestor de Inventario** es una aplicación web desarrollada de forma **acumulativa y progresiva** a lo largo de las 15 semanas del semestre dentro de la carpeta [`Practicas/`](Practicas/). Cada semana retoma todo el trabajo de la carpeta anterior e incorpora una nueva capa tecnológica para resolver el control de existencias, precios unitarios, categorías, proveedores, usuarios, roles y auditoría de un almacén.

---

## 🧭 Evolución Progresiva por Carpeta (`Practicas/Semana01` a `Semana15`)

Cada carpeta dentro de `Practicas/` es **autónoma y acumulativa** (contiene todos los archivos de las semanas previas más el avance correspondiente a esa práctica):

| Carpeta | Práctica / Tema | Avance acumulado que incorpora |
|---|---|---|
| [`Practicas/Semana01/`](Practicas/Semana01/) | Primera página web con PHP | Estructura inicial HTML + variables y cálculos en PHP (`index.php`, [`evidencia.md`](Practicas/Semana01/evidencia.md)). |
| [`Practicas/Semana02/`](Practicas/Semana02/) | Formularios HTML y validación PHP | Todo lo de Semana 01 + formulario HTML y recepción/validación `POST` (`procesar.php`, [`evidencia.md`](Practicas/Semana02/evidencia.md)). |
| [`Practicas/semana03/`](Practicas/semana03/) | Diseño visual con CSS | Todo lo de Semana 02 + hoja de estilos externa (`estilos.css`), Modelo de Caja y Flexbox ([`evidencia.md`](Practicas/semana03/evidencia.md)). |
| [`Practicas/semana04/`](Practicas/semana04/) | Interactividad con JavaScript | Todo lo de Semana 03 + eventos, DOM y validación en el navegador (`script.js`, [`evidencia.md`](Practicas/semana04/evidencia.md)). |
| [`Practicas/semana05/`](Practicas/semana05/) | Persistencia con PHP + MySQL | Todo lo de Semana 04 + conexión `mysqli` (`conexion.php`) y base de datos `gestor_inventario` (`consultas.sql`, [`evidencia.md`](Practicas/semana05/evidencia.md)). |
| [`Practicas/Semana06/`](Practicas/Semana06/) | **CRUD Completo** | Todo lo de Semana 05 + `crear.php`, `listar.php`, `editar.php`, `actualizar.php`, `eliminar.php` con consultas preparadas ([`evidencia.md`](Practicas/Semana06/evidencia.md), [`pruebas_crud.md`](Practicas/Semana06/pruebas_crud.md)). |
| [`Practicas/Semana07/`](Practicas/Semana07/) | **Login, Sesiones y Páginas Protegidas** | Todo lo de Semana 06 + tabla `usuarios`, `login_form.php`, `login.php` (`password_verify`), `proteger.php`, `panel.php`, `logout.php` y `crear_usuario_prueba.php` ([`evidencia.md`](Practicas/Semana07/evidencia.md), [`pruebas_login.md`](Practicas/Semana07/pruebas_login.md)). |
| [`Practicas/Semana08/`](Practicas/Semana08/) | **Auditoría y Seguridad Básica** | Todo lo de Semana 07 + validaciones estrictas en servidor, escape `htmlspecialchars()`, resguardo de credenciales (`conexion.example.php`), [`auditoria.md`](Practicas/Semana08/auditoria.md), [`evidencia.md`](Practicas/Semana08/evidencia.md) y [`pruebas_seguridad.md`](Practicas/Semana08/pruebas_seguridad.md). |
| [`Practicas/Semana09/`](Practicas/Semana09/) | **Roles y Permisos** | Todo lo de Semana 08 + control de acceso por rol en el servidor (`autorizacion.php` con `exigirRol()`), módulo de administración de usuarios (`usuarios.php`), menú dinámico, [`diseno_roles.md`](Practicas/Semana09/diseno_roles.md), [`evidencia.md`](Practicas/Semana09/evidencia.md) y [`pruebas_roles_permisos.md`](Practicas/Semana09/pruebas_roles_permisos.md). |
| [`Practicas/Semana10/`](Practicas/Semana10/) | **AJAX y Fetch API (JSON)** | Todo lo de Semana 09 + endpoints JSON en `api/` (`listar.php`, `guardar.php`, `eliminar.php`) y actualización parcial del DOM sin recargar página ([`diseno_fetch.md`](Practicas/Semana10/diseno_fetch.md), [`evidencia.md`](Practicas/Semana10/evidencia.md), [`pruebas_fetch.md`](Practicas/Semana10/pruebas_fetch.md)). |
| [`Practicas/Semana11/`](Practicas/Semana11/) | **Arquitectura MVC** | Todo lo de Semana 10 reorganizado con `config/database.php`, Modelos (`app/models/`), Controladores (`app/controllers/`) y Vistas (`app/views/`), conservando Fetch API ([`estructura_inicial.md`](Practicas/Semana11/estructura_inicial.md), [`estructura_mvc.md`](Practicas/Semana11/estructura_mvc.md), [`evidencia.md`](Practicas/Semana11/evidencia.md), [`pruebas_mvc.md`](Practicas/Semana11/pruebas_mvc.md)). |
| [`Practicas/Semana12/`](Practicas/Semana12/) | **Componentes y Plantillas** | Todo lo de Semana 11 + plantilla general (`app/views/layouts/`: `header.php`, `menu.php`, `mensajes.php`, `footer.php`) y componentes (`app/views/componentes/`: `tabla_productos.php`, `boton_accion.php`), [`analisis_componentes.md`](Practicas/Semana12/analisis_componentes.md), [`evidencia.md`](Practicas/Semana12/evidencia.md) y [`pruebas_plantilla.md`](Practicas/Semana12/pruebas_plantilla.md). |
| [`Practicas/Semana13/`](Practicas/Semana13/) | **Funciones Avanzadas** | Todo lo de Semana 12 + búsqueda multicriterio, filtros combinados, ordenamiento con lista permitida, paginación (`paginacion.php`), estadísticas (`reportes.php`), exportación segura a CSV (`exportar_csv.php`) y bitácora (`bitacora.php`), [`diseno_funcion.md`](Practicas/Semana13/diseno_funcion.md), [`evidencia.md`](Practicas/Semana13/evidencia.md) y [`pruebas_funcion.md`](Practicas/Semana13/pruebas_funcion.md). |
| [`Practicas/Semana14/`](Practicas/Semana14/) | **Pruebas, Integración y Correcciones** | Todo lo de Semana 13 + correcciones de integración y regresión, [`estado_inicial.md`](Practicas/Semana14/estado_inicial.md), [`plan_pruebas.md`](Practicas/Semana14/plan_pruebas.md), [`registro_errores.md`](Practicas/Semana14/registro_errores.md) y [`evidencia.md`](Practicas/Semana14/evidencia.md). |
| [`Practicas/Semana15/`](Practicas/Semana15/) | **Presentación del Proyecto Final** | Versión final integradora completa lista para demostración + [`descripcion_proyecto.md`](Practicas/Semana15/descripcion_proyecto.md), [`informe_final.md`](Practicas/Semana15/informe_final.md) y [`evidencia.md`](Practicas/Semana15/evidencia.md). |

---

## ⚙️ Requisitos y Preparación del Entorno

1. **Requisitos:**
   * WAMP Server / XAMPP (Apache + PHP 8.1+ y MySQL 8.0+ con extensión `mysqli`).
2. **Configuración de Base de Datos sin Publicar Secretos en GitHub:**
   * Para evitar subir contraseñas reales al repositorio de GitHub, el archivo [`.gitignore`](.gitignore) excluye `Practicas/.env.local.php` y `**/env.local.php`.
   * En tu entorno local, el archivo `Practicas/.env.local.php` proporciona las credenciales locales de WAMP; si descargas el proyecto en otro equipo, puedes tomar como referencia [`Practicas/Semana15/config/env.example.php`](Practicas/Semana15/config/env.example.php).
   * El esquema completo se encuentra en [`Practicas/Semana15/sql/schema_completo.sql`](Practicas/Semana15/sql/schema_completo.sql) y se auto-inicializa al abrir cualquiera de las carpetas en el navegador.
3. **Ejecución en el navegador:**
   * Versión final integradora (Semana 15):  
     `http://localhost/Desarrollo-de-Aplicaciones-web---Pedro-Compean-Orozco-/Practicas/Semana15/`
   * También puedes abrir individualmente cualquier semana (`Practicas/Semana06/`, `Practicas/Semana07/`, ..., `Practicas/Semana14/`).

---

## 🧪 Cuentas de Demostración (Entorno de Pruebas)

| Rol | Usuario | Contraseña de demostración | Permisos |
|---|---|---|---|
| **Administrador** | `admin_prueba` | `Admin_Demo2026!` | CRUD completo, Gestión de Usuarios/Roles y Bitácora de Auditoría |
| **Operador de Almacén** | `operador_prueba` | `Operador_Demo2026!` | Consultar, Buscar/Filtrar, Crear y Editar productos |
| **Usuario de Consulta** | `consulta_prueba` | `Consulta_Demo2026!` | Solo lectura: Consultar inventario, aplicar filtros, ver reportes y exportar CSV |
| **Cuenta Inactiva** | `inactivo_prueba` | `Inactivo_Demo2026!` | Cuenta desactivada (`activo = 0`) para probar rechazo en login |
