<?php
// ============================================================
// 🛡️ common/auth_guard.php
// Middleware de protección + control de acceso por rol
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// ============================================================
// 🟢 EXCEPCIONES DE ACCESO PÚBLICO (sin iniciar sesión)
// ============================================================
// Estos controladores pueden ser visitados por usuarios sin sesión activa.
$publicos = [
  'index.php',
  'loginController.php',
  'solicitudController.php' // Permite acceso público a solicitudes (ej: formulario público)
];

$archivoActual = basename($_SERVER['PHP_SELF']);

// Si el archivo actual está en la lista pública → permitir sin restricción
if (in_array($archivoActual, $publicos)) {
  return;
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
    'participanteController.php',
    'index.php',
    'loginController.php'
  ],

  'Conductor' => [ // 🚚 Operativa
    'viajeController.php',
    'ordenController.php',
    'incidenteController.php',
    'reporteEficienciaController.php',
    'loginController.php',
    'index.php'
  ],

  'Soporte' => [ // 🔧 Flota y mantenimiento
    'vehiculoController.php',
    'mantenimientoController.php',
    'reporteEficienciaController.php',
    'loginController.php',
    'index.php'
  ],

  'Facturacion' => [ // 💰 Control financiero
    'facturaController.php',
    'reporteEficienciaController.php',
    'loginController.php',
    'index.php'
  ],

  'Cliente' => [ // 👤 Solo reportes
    'reporteEficienciaController.php',
    'loginController.php',
    'index.php'
  ],

  'Invitado' => [ // 🌐 Visitante sin iniciar sesión
    'solicitudController.php',
    'loginController.php',
    'index.php'
  ]
];

// ============================================================
// 🔍 VALIDAR ACCESO AL CONTROLADOR ACTUAL
// ============================================================
$accesos = $permisos[$rolActual] ?? [];

// Si el rol no tiene acceso a este módulo → redirigir al error de restricción
if (!in_array($archivoActual, $accesos)) {
  header('Location: /logistica_global/views/error/unauthorized.php');
  exit;
}
?>
