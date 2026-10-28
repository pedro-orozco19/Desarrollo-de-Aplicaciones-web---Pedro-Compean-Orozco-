<?php
/**
 * Componente reutilizable: Encabezado general (Semana 12).
 * Recibe $tituloPagina y muestra la identidad del sistema y el usuario autenticado.
 */
$tituloPagina = $tituloPagina ?? 'Gestor de Inventario';
$rutaCss = $rutaCss ?? 'estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?> — Sistema Integrador</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($rutaCss, ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <header class="encabezado">
        <div class="encabezado-contenido">
            <div>
                <h1>📦 Gestor de Inventario</h1>
                <p class="subtitulo-encabezado">Control de Almacén, Proveedores y Existencias · Proyecto Integrador</p>
            </div>
            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <div class="info-usuario-header">
                    <span class="badge-usuario">
                        👤 <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                    <span class="badge-rol rol-<?= htmlspecialchars($_SESSION['rol'] ?? 'consulta', ENT_QUOTES, 'UTF-8') ?>">
                        🛡️ Rol: <?= htmlspecialchars(ucfirst($_SESSION['rol'] ?? 'consulta'), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </header>
