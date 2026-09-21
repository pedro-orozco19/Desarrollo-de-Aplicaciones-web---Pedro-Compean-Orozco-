// PARTE 4 & 5: Variables y Funciones
let sistemaActivo = true;

function iniciarSistema() {
    console.log("Sistema de inventario inicializado. Estado activo:", sistemaActivo);
}
iniciarSistema();

// PARTE 7 & 8: Seleccionar elementos del DOM
const formulario = document.getElementById("formulario-inventario");
const mensajeDiv = document.getElementById("mensaje-sistema");
const btnMostrarInfo = document.getElementById("btnMostrarInfo");
const infoExtra = document.getElementById("info-extra");
const inputNombre = document.getElementById("nombre");

// DESAFÍO DE LA SEMANA: Interacción propia (Autocompletar en mayúsculas)
inputNombre.addEventListener("input", function() {
    this.value = this.value.toUpperCase();
});

// PARTE 14: Mostrar y ocultar elementos
btnMostrarInfo.addEventListener("click", function(event) {
    event.preventDefault(); // Evita que recargue la página si está dentro de un form
    if (infoExtra.style.display === "none") {
        infoExtra.style.display = "block";
        btnMostrarInfo.textContent = "Ocultar Ayuda";
    } else {
        infoExtra.style.display = "none";
        btnMostrarInfo.textContent = "Mostrar Ayuda";
    }
});

// PARTE 10 & 11: Validación del formulario antes de enviar a PHP
formulario.addEventListener("submit", function(event) {
    // Obtener valores capturados
    let nombre = inputNombre.value.trim();
    let stock = document.getElementById("stock_nuevo").value;
    let precio = document.getElementById("precio").value;
    let categoria = document.getElementById("categoria").value;

    // Limpiar mensajes anteriores
    mensajeDiv.className = "";
    mensajeDiv.textContent = "";

    // Validaciones
    if (nombre === "" || stock === "" || precio === "" || categoria === "") {
        event.preventDefault(); // PARTE 11: Detener el envío
        mensajeDiv.textContent = "⚠️ Error: Todos los campos son obligatorios.";
        mensajeDiv.classList.add("mensaje-error"); // PARTE 13: Mensajes dinámicos
        return; // Cortar la ejecución aquí
    }

    if (stock <= 0 || precio <= 0) {
        event.preventDefault();
        mensajeDiv.textContent = "⚠️ Error: El stock y el precio deben ser mayores a 0.";
        mensajeDiv.classList.add("mensaje-error");
        return;
    }

    // Si pasa todas las validaciones, dejamos que el formulario se envíe a procesar.php
    mensajeDiv.textContent = "✔️ Datos correctos, enviando al servidor...";
    mensajeDiv.classList.add("mensaje-exito");
});