# 🧪 Pruebas Funcionales de Login y Sesiones — Semana 07

**Proyecto:** Gestor de Inventario  
**Base de datos:** `gestor_inventario` · **Tabla:** `usuarios`

| N.º | Prueba realizada | Datos / Condición de prueba | Resultado esperado | Resultado obtenido | Estado |
|---:|---|---|---|---|---|
| 1 | Iniciar sesión con credenciales de prueba correctas | Usuario: `admin_prueba` con contraseña de demostración válida | Se regenera el ID de sesión y redirige al panel principal (`index.php` / `panel.php`) | Acceso concedido al panel mostrando el nombre del usuario en el encabezado | **Correcta** |
| 2 | Intentar iniciar sesión con una contraseña incorrecta | Usuario: `admin_prueba`, contraseña errónea `ClaveIncorrecta123` | Se rechaza el acceso y se muestra un mensaje genérico sin revelar qué dato falló | Se mostró *"No fue posible iniciar sesión con esas credenciales"* y no se creó sesión | **Correcta** |
| 3 | Intentar iniciar sesión con un usuario inexistente | Usuario: `usuario_fantasma_99`, contraseña cualquiera | Se rechaza el acceso con el mismo mensaje genérico | Se rechazó el acceso mostrando el mensaje genérico de error | **Correcta** |
| 4 | Intentar acceder directamente a `panel.php` sin iniciar sesión | Abrir `panel.php` o `index.php` en una ventana privada sin sesión | `proteger.php` intercepta la petición y redirige a `login_form.php` | Redirección inmediata a `login_form.php` con código `302` | **Correcta** |
| 5 | Iniciar sesión y navegar a una página protegida | Iniciar sesión y abrir `editar.php?id=1` y `reportes.php` | La sesión se conserva entre páginas y permite operar normalmente | El usuario permaneció autenticado durante toda la navegación | **Correcta** |
| 6 | Confirmar que se muestra el nombre del usuario autenticado | Revisar la barra superior del panel tras iniciar sesión | Se visualiza el nombre real almacenado en `$_SESSION['nombre']` escapado con `htmlspecialchars()` | El encabezado mostró `👤 Pedro Compean (Administrador)` | **Correcta** |
| 7 | Cerrar sesión | Hacer clic en el botón **Cerrar sesión** (`logout.php`) | Se vacía `$_SESSION`, se borra la cookie y se destruye la sesión | Sesión finalizada y redirección a `login_form.php` con aviso de confirmación | **Correcta** |
| 8 | Intentar abrir de nuevo el panel mediante dirección directa | Escribir manualmente `panel.php` en la barra de direcciones tras cerrar sesión | El servidor detecta que la sesión ya no existe y bloquea el acceso | El sistema redirigió nuevamente al formulario de login | **Correcta** |
| 9 | Comprobar que el acceso se rechaza después del cierre de sesión | Presionar el botón "Atrás" o recargar una ruta privada tras el logout | Ninguna acción protegida puede ejecutarse sin volver a autenticarse | El servidor exigió iniciar sesión nuevamente | **Correcta** |
| 10 | Comprobar que un usuario marcado como inactivo no puede entrar | Usuario: `inactivo_prueba` (`activo = 0`) con su contraseña correcta | PHP verifica `(int)$fila['activo'] === 1` y rechaza el inicio de sesión | El acceso fue denegado aunque la contraseña coincidía | **Correcta** |
| 11 | Verificar que la contraseña en la base de datos es un hash | Ejecutar `SELECT usuario, password_hash FROM usuarios` | La columna contiene un hash Bcrypt (`$2y$10$...`) y nunca texto plano | Se confirmó que todas las cuentas almacenan hashes de 60 caracteres | **Correcta** |
| 12 | Confirmar que el CRUD continúa funcionando con el login integrado | Crear, consultar, editar y eliminar un producto de prueba con sesión iniciada | Todas las operaciones del CRUD funcionan normalmente dentro de la sesión | El CRUD operó sin errores y manteniendo la protección de sesión | **Correcta** |
