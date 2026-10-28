/**
 * Interactividad del lado del cliente y comunicación asíncrona con Fetch API (Semanas 04, 10, 11, 12, 13 y 14).
 * - Conserva las validaciones y eventos originales de la Semana 04.
 * - Integra Fetch API con respuestas JSON para consultar/filtrar, registrar y eliminar productos
 *   actualizando el DOM de forma segura con textContent (sin inyectar HTML no confiable).
 */

let sistemaActivo = true;

function iniciarSistema() {
    console.log("Sistema de inventario inicializado. Estado activo:", sistemaActivo);
}
iniciarSistema();

const formulario = document.getElementById("formulario-inventario");
const mensajeDiv = document.getElementById("mensaje-sistema");
const btnMostrarInfo = document.getElementById("btnMostrarInfo");
const infoExtra = document.getElementById("info-extra");
const inputNombre = document.getElementById("nombre");
const btnRefrescarFetch = document.getElementById("btnRefrescarFetch");
const cuerpoTabla = document.getElementById("cuerpo-tabla-productos");
const valorTotalSpan = document.getElementById("valor-total-almacen");
const contenedorPrincipal = document.querySelector(".contenedor-principal");

const rolActual = contenedorPrincipal ? (contenedorPrincipal.dataset.rol || "consulta") : "consulta";
const csrfToken = contenedorPrincipal ? (contenedorPrincipal.dataset.csrf || "") : "";

/**
 * Muestra un mensaje en el contenedor #mensaje-sistema de forma segura con textContent.
 */
function mostrarMensaje(texto, tipo = "exito") {
    if (!mensajeDiv) return;
    mensajeDiv.className = tipo === "error" ? "mensaje-error" : "mensaje-exito";
    mensajeDiv.textContent = texto;
}

// Autocompletar nombre del producto en mayúsculas (Semana 04)
if (inputNombre) {
    inputNombre.addEventListener("input", function () {
        this.value = this.value.toUpperCase();
    });
}

// Mostrar y ocultar cuadro de ayuda (Semana 04)
if (btnMostrarInfo && infoExtra) {
    btnMostrarInfo.addEventListener("click", function (event) {
        event.preventDefault();
        if (infoExtra.style.display === "none") {
            infoExtra.style.display = "block";
            btnMostrarInfo.textContent = "Ocultar Ayuda";
        } else {
            infoExtra.style.display = "none";
            btnMostrarInfo.textContent = "Mostrar Ayuda";
        }
    });
}

/**
 * Construye una fila <tr> segura para la tabla de productos usando creación de nodos DOM y textContent.
 */
function crearFilaProductoDOM(prod) {
    const tr = document.createElement("tr");
    tr.id = `fila-producto-${Number(prod.id)}`;

    const tdId = document.createElement("td");
    tdId.className = "celda-centro";
    tdId.textContent = `#${Number(prod.id)}`;
    tr.appendChild(tdId);

    const tdNombre = document.createElement("td");
    const strongNombre = document.createElement("strong");
    strongNombre.textContent = prod.nombre;
    tdNombre.appendChild(strongNombre);
    tr.appendChild(tdNombre);

    const tdCat = document.createElement("td");
    const spanCat = document.createElement("span");
    spanCat.className = "badge-categoria";
    spanCat.textContent = prod.categoria;
    tdCat.appendChild(spanCat);
    tr.appendChild(tdCat);

    const tdStock = document.createElement("td");
    tdStock.className = "celda-centro";
    const spanStock = document.createElement("span");
    const stockNum = Number(prod.stock);
    spanStock.className = stockNum <= 15 ? "badge-stock stock-bajo" : "badge-stock stock-ok";
    spanStock.textContent = stockNum <= 15 ? `${stockNum} u. (Bajo)` : `${stockNum} u.`;
    tdStock.appendChild(spanStock);
    tr.appendChild(tdStock);

    const tdPrecio = document.createElement("td");
    tdPrecio.className = "celda-derecha";
    tdPrecio.textContent = `$${Number(prod.precio).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    tr.appendChild(tdPrecio);

    const tdCorreo = document.createElement("td");
    tdCorreo.textContent = prod.correo_proveedor;
    tr.appendChild(tdCorreo);

    const tdAcciones = document.createElement("td");
    tdAcciones.className = "celda-centro";

    const puedeEditar = rolActual === "administrador" || rolActual === "operador";
    const puedeEliminar = rolActual === "administrador";

    if (!puedeEditar && !puedeEliminar) {
        const spanLectura = document.createElement("span");
        spanLectura.className = "texto-solo-lectura";
        spanLectura.textContent = "Solo lectura";
        tdAcciones.appendChild(spanLectura);
    } else {
        const divAcciones = document.createElement("div");
        divAcciones.className = "acciones-fila";

        if (puedeEditar) {
            const linkEditar = document.createElement("a");
            linkEditar.href = `editar.php?id=${Number(prod.id)}`;
            linkEditar.className = "btn-accion btn-editar";
            linkEditar.textContent = "✏️ Editar";
            divAcciones.appendChild(linkEditar);
        }

        if (puedeEliminar) {
            const formDel = document.createElement("form");
            formDel.action = "eliminar.php";
            formDel.method = "POST";
            formDel.className = "form-eliminar-inline";
            formDel.dataset.id = String(prod.id);
            formDel.dataset.nombre = prod.nombre;

            const inputId = document.createElement("input");
            inputId.type = "hidden";
            inputId.name = "id";
            inputId.value = String(prod.id);

            const inputCsrf = document.createElement("input");
            inputCsrf.type = "hidden";
            inputCsrf.name = "csrf_token";
            inputCsrf.value = csrfToken;

            const btnDel = document.createElement("button");
            btnDel.type = "submit";
            btnDel.className = "btn-accion btn-eliminar";
            btnDel.textContent = "🗑️ Eliminar";

            formDel.appendChild(inputId);
            formDel.appendChild(inputCsrf);
            formDel.appendChild(btnDel);
            divAcciones.appendChild(formDel);
        }

        tdAcciones.appendChild(divAcciones);
    }

    tr.appendChild(tdAcciones);
    return tr;
}

/**
 * Consulta asíncrona del inventario con Fetch API (Semana 10).
 */
async function cargarInventarioConFetch() {
    if (!cuerpoTabla) return;

    const params = new URLSearchParams(window.location.search);
    const inputQ = document.getElementById("filtro-q");
    const selectCat = document.getElementById("filtro-categoria");
    const selectStock = document.getElementById("filtro-stock");
    const selectOrden = document.getElementById("filtro-orden");
    const selectDir = document.getElementById("filtro-dir");

    if (inputQ) params.set("q", inputQ.value.trim());
    if (selectCat) params.set("categoria", selectCat.value);
    if (selectStock) params.set("estado_stock", selectStock.value);
    if (selectOrden) params.set("orden", selectOrden.value);
    if (selectDir) params.set("dir", selectDir.value);

    try {
        const respuesta = await fetch(`api/listar.php?${params.toString()}`, {
            headers: { "Accept": "application/json" }
        });

        const resultado = await respuesta.json();

        if (!respuesta.ok || !resultado.ok) {
            throw new Error(resultado.mensaje || `Error HTTP ${respuesta.status}`);
        }

        cuerpoTabla.textContent = "";

        if (!Array.isArray(resultado.datos) || resultado.datos.length === 0) {
            const trVacia = document.createElement("tr");
            const tdVacia = document.createElement("td");
            tdVacia.colSpan = 7;
            tdVacia.className = "celda-vacia";
            tdVacia.textContent = "No se encontraron productos para los filtros seleccionados.";
            trVacia.appendChild(tdVacia);
            cuerpoTabla.appendChild(trVacia);
        } else {
            resultado.datos.forEach((prod) => {
                cuerpoTabla.appendChild(crearFilaProductoDOM(prod));
            });
        }

        if (valorTotalSpan && resultado.resumen) {
            const val = Number(resultado.resumen.valor_total || 0);
            valorTotalSpan.textContent = `$${val.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }
    } catch (error) {
        mostrarMensaje(error.message || "No fue posible actualizar los datos mediante Fetch API.", "error");
    }
}

if (btnRefrescarFetch) {
    btnRefrescarFetch.addEventListener("click", async () => {
        await cargarInventarioConFetch();
        mostrarMensaje("✔️ Tabla de inventario actualizada sin recargar la página (Fetch API).", "exito");
    });
}

// Validación del formulario y envío mediante Fetch API (Semanas 04 y 10)
if (formulario) {
    formulario.addEventListener("submit", async function (event) {
        const nombre = inputNombre ? inputNombre.value.trim() : "";
        const stock = document.getElementById("stock_nuevo").value;
        const precio = document.getElementById("precio").value;
        const correo = document.getElementById("correo_proveedor").value.trim();
        const categoria = document.getElementById("categoria").value;
        const usarFetch = document.getElementById("usarFetchGuardar");
        const btnGuardar = document.getElementById("btnGuardar");

        if (mensajeDiv) {
            mensajeDiv.className = "";
            mensajeDiv.textContent = "";
        }

        if (nombre === "" || stock === "" || precio === "" || correo === "" || categoria === "") {
            event.preventDefault();
            mostrarMensaje("⚠️ Error: Todos los campos son obligatorios.", "error");
            return;
        }

        if (Number(stock) <= 0 || Number(precio) <= 0) {
            event.preventDefault();
            mostrarMensaje("⚠️ Error: El stock y el precio deben ser mayores a 0.", "error");
            return;
        }

        // Si está activada la casilla de Fetch API, enviamos por AJAX sin recargar
        if (usarFetch && usarFetch.checked) {
            event.preventDefault();
            const datosFormulario = new FormData(formulario);

            if (btnGuardar) btnGuardar.disabled = true;

            try {
                const respuesta = await fetch("api/guardar.php", {
                    method: "POST",
                    body: datosFormulario,
                    headers: { "Accept": "application/json" }
                });

                const resultado = await respuesta.json();

                if (!respuesta.ok || !resultado.ok) {
                    throw new Error(resultado.mensaje || "No fue posible guardar el producto.");
                }

                mostrarMensaje(`✔️ ${resultado.mensaje}`, "exito");
                formulario.reset();
                if (usarFetch) usarFetch.checked = true;
                await cargarInventarioConFetch();
            } catch (error) {
                mostrarMensaje(`⚠️ ${error.message}`, "error");
            } finally {
                if (btnGuardar) btnGuardar.disabled = false;
            }
        } else {
            mostrarMensaje("✔️ Datos correctos, enviando al servidor...", "exito");
        }
    });
}

// Confirmación antes de eliminar un registro (Semana 06 y Fetch API Semana 10)
document.addEventListener("submit", async function (event) {
    const form = event.target;
    if (!form.classList || !form.classList.contains("form-eliminar-inline")) {
        return;
    }

    const nombreProducto = form.dataset.nombre || "este registro";
    const confirmar = window.confirm(`¿Estás seguro de que deseas eliminar el producto "${nombreProducto}"?`);
    if (!confirmar) {
        event.preventDefault();
        return;
    }

    // Ejecutamos la baja vía Fetch API para actualizar la tabla sin recargar toda la página
    event.preventDefault();
    const datos = new FormData(form);

    try {
        const respuesta = await fetch("api/eliminar.php", {
            method: "POST",
            body: datos,
            headers: { "Accept": "application/json" }
        });

        const resultado = await respuesta.json();
        if (!respuesta.ok || !resultado.ok) {
            throw new Error(resultado.mensaje || "No fue posible eliminar el producto.");
        }

        mostrarMensaje(`✔️ ${resultado.mensaje}`, "exito");
        await cargarInventarioConFetch();
    } catch (error) {
        mostrarMensaje(`⚠️ ${error.message}`, "error");
    }
});
