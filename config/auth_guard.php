<?php
// ============================================================
// 🛡️ common/auth_guard.php
// Middleware de protección + control de acceso por rol
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// ============================================================
// 🚪 Verificar sesión activa
// ============================================================
if (empty($_SESSION['usuario'])) {
  // No hay sesión → redirigir al login
  header('Location: /logistica_global/controllers/loginController.php');
  exit;
}

// Rol actual del usuario logueado
$rolActual = $_SESSION['usuario']['rol'] ?? null;

// ============================================================
// 🚫 MATRIZ DE PERMISOS POR ROL
// ============================================================
// Define qué controladores puede acceder cada tipo de rol.
// Los nombres deben coincidir con los archivos en /controllers/

$permisos = [
  'Administrador' => [ // 🔓 Acceso total
    'usuarioController.php',
    'rolController.php',
    'vehiculoController.php',
    'conductorController.php',
    'mantenimientoController.php',
    'facturaController.php',
    'reporteEficienciaController.php',
    'clienteController.php',
    'solicitudController.php',
    'ordenController.php',
    'viajeController.php',
    'mercanciaController.php',
    'incidenteController.php',
    'rutaController.php',
    'tipoCarreteraController.php',
    'tipoEventoController.php',
    'eventoController.php',
    'tramoController.php',
    'planificacionController.php',
    'auditoriaController.php',
    'cargaController.php',
    'participanteController.php'
  ],

  'Conductor' => [ // 🚚 Operativa
    'usuarioController.php',
    'rolController.php',
    'viajeController.php',
    'conductorController.php',
    'ordenController.php',
    'incidenteController.php',
    'reporteEficienciaController.php'
  ],

  'Soporte' => [ // 🔧 Flota y mantenimiento
    'vehiculoController.php',
    'mantenimientoController.php',
    'reporteEficienciaController.php'
  ],

  'Facturacion' => [ // 💰 Control financiero
    'facturaController.php',
    'reporteEficienciaController.php'
  ],

  'Cliente' => [ // 👤 Solo reportes
    'reporteEficienciaController.php'
  ]
];

// ============================================================
// 🔍 VALIDAR ACCESO AL CONTROLADOR ACTUAL
// ============================================================
$archivoActual = basename($_SERVER['PHP_SELF']); // nombre del script ejecutado
$accesos = $permisos[$rolActual] ?? [];

// Si el rol no tiene acceso a este módulo → redirigir a error 401
if (!in_array($archivoActual, $accesos)) {
  header('Location: /logistica_global/views/error/unauthorized.php');
  exit;
}
?>
