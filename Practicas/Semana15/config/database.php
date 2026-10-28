<?php
/**
 * Conexión centralizada a MySQL (mysqli) e inicialización segura del esquema.
 * Lee las credenciales locales desde Practicas/.env.local.php (ignorado por Git)
 * para no exponer contraseñas en el repositorio de GitHub.
 */

$posiblesRutasEnv = [
    __DIR__ . '/env.local.php',
    __DIR__ . '/../.env.local.php',
    __DIR__ . '/../../.env.local.php',
];

$config = [
    'DB_HOST' => getenv('DB_HOST') ?: 'localhost',
    'DB_USER' => getenv('DB_USER') ?: 'root',
    'DB_PASS' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
    'DB_NAME' => getenv('DB_NAME') ?: 'gestor_inventario',
    'DB_PORT' => (int)(getenv('DB_PORT') ?: 3306),
];

foreach ($posiblesRutasEnv as $rutaEnv) {
    if (file_exists($rutaEnv)) {
        $cargado = require $rutaEnv;
        if (is_array($cargado)) {
            $config = array_merge($config, $cargado);
            break;
        }
    }
}

mysqli_report(MYSQLI_REPORT_OFF);

$conexion = @new mysqli(
    $config['DB_HOST'],
    $config['DB_USER'],
    $config['DB_PASS'],
    '',
    $config['DB_PORT'] ?? 3306
);

if ($conexion->connect_errno && $config['DB_PASS'] !== '') {
    $conexion = @new mysqli($config['DB_HOST'], $config['DB_USER'], '', '', $config['DB_PORT'] ?? 3306);
}

if ($conexion->connect_errno) {
    error_log('Error de conexión MySQL: ' . $conexion->connect_error);
    http_response_code(500);
    exit('No fue posible conectar con el servidor de base de datos en este momento.');
}

$conexion->set_charset('utf8mb4');

$dbName = $conexion->real_escape_string($config['DB_NAME']);
$conexion->query("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conexion->select_db($config['DB_NAME']);

// 1. Asegurar tabla productos
$conexion->query("CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    stock INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    correo_proveedor VARCHAR(150) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$colsProd = [];
if ($resCols = $conexion->query("SHOW COLUMNS FROM productos")) {
    while ($c = $resCols->fetch_assoc()) {
        $colsProd[$c['Field']] = true;
    }
}
if (!isset($colsProd['activo'])) {
    $conexion->query("ALTER TABLE productos ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1");
}
if (!isset($colsProd['creado_en'])) {
    $conexion->query("ALTER TABLE productos ADD COLUMN creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
}

// 2. Asegurar tabla usuarios (Semanas 07 y 09)
$conexion->query("CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(30) NOT NULL DEFAULT 'consulta',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$colsUser = [];
if ($resUserCols = $conexion->query("SHOW COLUMNS FROM usuarios")) {
    while ($c = $resUserCols->fetch_assoc()) {
        $colsUser[$c['Field']] = true;
    }
}
if (!isset($colsUser['rol'])) {
    $conexion->query("ALTER TABLE usuarios ADD COLUMN rol VARCHAR(30) NOT NULL DEFAULT 'consulta' AFTER password_hash");
}
if (!isset($colsUser['activo'])) {
    $conexion->query("ALTER TABLE usuarios ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1 AFTER rol");
}

// 3. Asegurar tabla bitacora (Semana 13)
$conexion->query("CREATE TABLE IF NOT EXISTS bitacora (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    usuario_nombre VARCHAR(100) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    registro_id INT NULL,
    detalle VARCHAR(255) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Semilla de cuentas de demostración
$checkUsers = $conexion->query("SELECT COUNT(*) AS total FROM usuarios");
if ($checkUsers && ($rowU = $checkUsers->fetch_assoc()) && (int)$rowU['total'] === 0) {
    $cuentasDemo = [
        ['Pedro Compean (Administrador)', 'admin_prueba', 'admin.demo@inventario.local', 'Admin_Demo2026!', 'administrador', 1],
        ['Usuario de Consulta Demo', 'consulta_prueba', 'consulta.demo@inventario.local', 'Consulta_Demo2026!', 'consulta', 1],
        ['Operador de Almacén Demo', 'operador_prueba', 'operador.demo@inventario.local', 'Operador_Demo2026!', 'operador', 1],
        ['Cuenta Inactiva Demo', 'inactivo_prueba', 'inactivo.demo@inventario.local', 'Inactivo_Demo2026!', 'consulta', 0],
    ];
    $stmtSeedU = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, correo, password_hash, rol, activo) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmtSeedU) {
        foreach ($cuentasDemo as $u) {
            $hash = password_hash($u[3], PASSWORD_DEFAULT);
            $stmtSeedU->bind_param('sssssi', $u[0], $u[1], $u[2], $hash, $u[4], $u[5]);
            $stmtSeedU->execute();
        }
        $stmtSeedU->close();
    }
}

// Semilla de productos de demostración
$checkProds = $conexion->query("SELECT COUNT(*) AS total FROM productos");
if ($checkProds && ($rowP = $checkProds->fetch_assoc()) && (int)$rowP['total'] === 0) {
    $productosDemo = [
        ['MONITOR DELL 24 PULGADAS', 45, 3200.00, 'contacto@dell-proveedor.com', 'Electronica'],
        ['TECLADO MECANICO RGB LOGITECH', 30, 1450.50, 'ventas@logitech-distribuidor.com', 'Electronica'],
        ['SILLA ERGONOMICA EJECUTIVA', 15, 4100.00, 'mobiliario@oficinas-norte.com', 'Mobiliario'],
        ['ESCRITORIO ELEVABLE MODULAR', 8, 6850.00, 'mobiliario@oficinas-norte.com', 'Mobiliario'],
        ['KIT LIMPIEZA PANTALLAS ANTIESTATICO', 120, 185.00, 'insumos@limpiezapro.com', 'Limpieza'],
        ['MOUSE INALAMBRICO MX MASTER', 25, 1950.00, 'ventas@logitech-distribuidor.com', 'Electronica'],
        ['ARCHIVERO METALICO 4 GAVETAS', 12, 3490.00, 'catalogo@mueblesindustriales.com', 'Mobiliario'],
        ['CAJA TOALLAS DESINFECTANTES INDUSTRIALES', 60, 310.00, 'insumos@limpiezapro.com', 'Limpieza'],
    ];
    $stmtSeedP = $conexion->prepare("INSERT INTO productos (nombre, stock, precio, correo_proveedor, categoria) VALUES (?, ?, ?, ?, ?)");
    if ($stmtSeedP) {
        foreach ($productosDemo as $p) {
            $stmtSeedP->bind_param('sidss', $p[0], $p[1], $p[2], $p[3], $p[4]);
            $stmtSeedP->execute();
        }
        $stmtSeedP->close();
    }
}
