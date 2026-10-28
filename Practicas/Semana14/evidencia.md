# 🧪 Práctica 14 — Desarrollo de Aplicaciones Web (Pruebas, Integración y Correcciones)

## Objetivo
Realizar una revisión integral del **Gestor de Inventario**, ejecutar pruebas funcionales y de integración sobre todos los módulos construidos de la Semana 01 a la Semana 13, documentar y corregir los defectos detectados y ejecutar pruebas de regresión para garantizar una versión final estable.

---

## Funcionalidad probada e integrada
1. **Validación de formularios y reglas de negocio en PHP (`PR-FORM-01`):** Comprobación de campos obligatorios, cantidades mayores a cero, correos válidos y categorías dentro de la lista permitida.
2. **Autenticación, sesiones y seguridad (`PR-LOGIN-01..03`, `PR-SES-01`):** Inicio y cierre de sesión, bloqueo de cuentas inactivas, regeneración de sesión, contraseñas con `password_hash()` / `password_verify()`, consultas preparadas y escape `htmlspecialchars()`.
3. **Roles y permisos en el servidor (`PR-ROL-01..03`):** Verificación con las cuentas `admin_prueba`, `operador_prueba` y `consulta_prueba`.
4. **CRUD y Fetch API (`PR-CRUD-01..04`, `PR-FETCH-01`):** Creación, lectura, edición y eliminación tanto por flujo tradicional como mediante `fetch()` y respuestas JSON.
5. **Arquitectura MVC, plantillas y funciones avanzadas (`PR-MVC-01`, `PR-ADV-01..02`):** Revisión de modelos, controladores, vistas, componentes reutilizables, búsqueda, filtros, ordenamiento, paginación, reportes, bitácora y exportación CSV.

---

## Archivos creados o modificados
* `Practicas/Semana14/estado_inicial.md`: Diagnóstico previo y lista de verificación.
* `Practicas/Semana14/plan_pruebas.md`: Inventario de módulos y matriz de 16 casos de prueba (`PR-LOGIN-01` a `PR-ADV-02`).
* `Practicas/Semana14/registro_errores.md`: Bitácora de 5 errores corregidos (`ERR-01` a `ERR-05`) y pruebas de regresión.
* Correcciones aplicadas en `script.js`, `app/views/componentes/paginacion.php`, `app/models/Producto.php` y `exportar_csv.php`.

---

## Tablas de la base de datos utilizadas
* `productos`, `usuarios` y `bitacora` en `gestor_inventario`.

---

## Resumen de resultados
* **Casos de prueba ejecutados:** 16 casos de integración.
* **Errores detectados y corregidos:** 5 (`ERR-01` a `ERR-05`).
* **Resultado tras pruebas de regresión:** 16 de 16 casos en estado **Correcta**.

---

## Reflexión final
Ejecutar un plan formal de pruebas de integración me permitió descubrir detalles que pasan desapercibidos cuando se prueba cada módulo por separado, como la interacción entre los filtros de la Semana 13 y la recarga asíncrona de Fetch API de la Semana 10. Corregir cada fallo y correr pruebas de regresión dejó la aplicación lista y confiable para la presentación final.
