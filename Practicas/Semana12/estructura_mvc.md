# 🏗️ Estructura Reorganizada con Arquitectura MVC — Semana 11

**Proyecto:** Gestor de Inventario  
**Alumno:** Pedro Compean Orozco

## 1. Árbol de Directorios Implementado

```text
Desarrollo-de-Aplicaciones-web---Pedro-Compean-Orozco-/
├── app/
│   ├── controllers/
│   │   ├── ProductoController.php   # Coordina validaciones, CRUD y bitácora de productos
│   │   ├── AuthController.php       # Coordina inicio de sesión y verificación de usuario activo
│   │   └── UsuarioController.php    # Coordina alta de usuarios y cambio de roles
│   ├── models/
│   │   ├── Producto.php             # Consultas preparadas sobre la tabla productos
│   │   ├── Usuario.php              # Consultas preparadas y hashes sobre la tabla usuarios
│   │   └── Bitacora.php             # Registro y consulta de eventos en la tabla bitacora
│   └── views/
│       ├── layouts/                 # Encabezado, menú, mensajes y pie de página
│       ├── componentes/             # Tabla de productos, botones de acción y paginación
│       └── productos/
│           ├── index.php            # Vista HTML del listado y formulario de inventario
│           └── editar.php           # Vista HTML de edición de producto
├── config/
│   ├── database.php                 # Conexión centralizada mysqli
│   └── env.example.php              # Ejemplo de configuración sin credenciales reales
├── api/
│   ├── listar.php                   # Endpoint JSON que reutiliza ProductoController::index()
│   ├── guardar.php                  # Endpoint JSON que reutiliza ProductoController::guardar()
│   └── eliminar.php                 # Endpoint JSON que reutiliza ProductoController::eliminar()
├── index.php                        # Punto de entrada principal (Controlador -> Modelo -> Vista)
├── crear.php / editar.php / actualizar.php / eliminar.php
├── login_form.php / login.php / logout.php / proteger.php / autorizacion.php
└── Practicas/
```

---

## 2. Separación de Responsabilidades

1. **Modelo (`app/models/Producto.php`):**
   * Recibe la instancia `mysqli $conexion` por inyección en su constructor.
   * Ejecuta exclusivamente las consultas SQL preparadas (`obtenerTodos()`, `obtenerPorId()`, `listarAvanzado()`, `crear()`, `actualizar()`, `eliminar()`, `obtenerEstadisticas()`) y devuelve arreglos asociativos sin imprimir nada de HTML.
2. **Controlador (`app/controllers/ProductoController.php`):**
   * Recibe los datos de entrada (`$_GET` o `$_POST`), aplica `validarDatosProducto()`, coordina la operación con el modelo `Producto`, registra la acción en el modelo `Bitacora` y entrega un arreglo estructurado a la vista o al endpoint JSON.
3. **Vista (`app/views/productos/index.php` y `editar.php`):**
   * Recibe las variables ya procesadas (`$registros`, `$valorTotal`, `$producto`) y las presenta en HTML escapando cada valor con `htmlspecialchars()`, sin ejecutar ninguna consulta SQL.
