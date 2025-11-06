<?php
// ============================================================
// 🧭 Menú lateral dinámico según el rol del usuario
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$usuario = $_SESSION['usuario'] ?? null;
$rol = $usuario['rol'] ?? 'Invitado';
?>

<nav style="background:#134074; padding:15px; color:white; min-height:100vh; width:240px;">
  <ul style="list-style:none; padding:0; margin:0;">
    <!-- 🔹 SECCIÓN GENERAL -->
    <li><a href="/logistica_global/index.php" style="color:white; text-decoration:none;">🏠 Inicio</a></li>

    <?php if ($rol === 'Administrador' || $rol === 'Invitado'): ?>
      <li><a href="/logistica_global/controllers/clienteController.php" style="color:white; text-decoration:none;">👥 Clientes</a></li>
      <li><a href="/logistica_global/controllers/solicitudController.php" style="color:white; text-decoration:none;">📄 Solicitudes</a></li>
    <?php endif; ?>

    <!-- 🚚 CONDUCTOR -->
    <?php if (in_array($rol, ['Administrador', 'Conductor'])): ?>
      <li><a href="/logistica_global/controllers/ordenController.php" style="color:white; text-decoration:none;">📦 Órdenes</a></li>
      <li><a href="/logistica_global/controllers/viajeController.php" style="color:white; text-decoration:none;">🚚 Viajes</a></li>
      <li><a href="/logistica_global/controllers/incidenteController.php" style="color:white; text-decoration:none;">⚠️ Incidentes</a></li>
    <?php endif; ?>

    <!-- 🧰 SOPORTE -->
    <?php if (in_array($rol, ['Administrador', 'Soporte'])): ?>
      <li><a href="/logistica_global/controllers/vehiculoController.php" style="color:white; text-decoration:none;">🚗 Vehículos</a></li>
      <li><a href="/logistica_global/controllers/conductorController.php" style="color:white; text-decoration:none;">🧑‍✈️ Conductores</a></li>
      <li><a href="/logistica_global/controllers/mantenimientoController.php" style="color:white; text-decoration:none;">🛠️ Mantenimientos</a></li>
    <?php endif; ?>

    <!-- 💰 FACTURACIÓN -->
    <?php if (in_array($rol, ['Administrador', 'Facturacion'])): ?>
      <li><a href="/logistica_global/controllers/facturaController.php" style="color:white; text-decoration:none;">💰 Facturación</a></li>
    <?php endif; ?>

    <!-- 📦 LOGÍSTICA -->
    <?php if ($rol === 'Administrador'): ?>
      <li><a href="/logistica_global/controllers/mercanciaController.php" style="color:white; text-decoration:none;">📦 Mercancías</a></li>
      <li><a href="/logistica_global/controllers/cargaController.php" style="color:white; text-decoration:none;">🚛 Cargas</a></li>
      <li><a href="/logistica_global/controllers/rutaController.php" style="color:white; text-decoration:none;">📍 Rutas</a></li>
      <li><a href="/logistica_global/controllers/tramoController.php" style="color:white; text-decoration:none;">🛣️ Tramos de Ruta</a></li>
      <li><a href="/logistica_global/controllers/tipoCarreteraController.php" style="color:white; text-decoration:none;">⚙️ Tipos de Carretera</a></li>
      <li><a href="/logistica_global/controllers/eventoController.php" style="color:white; text-decoration:none;">🎉 Eventos</a></li>
      <li><a href="/logistica_global/controllers/tipoEventoController.php" style="color:white; text-decoration:none;">🏷️ Tipos de Evento</a></li>
    <?php endif; ?>

    <!-- 📊 REPORTES Y AUDITORÍA -->
    <?php if (in_array($rol, ['Administrador', 'Soporte', 'Conductor', 'Facturacion'])): ?>
      <li><a href="/logistica_global/controllers/reporteEficienciaController.php" style="color:white; text-decoration:none;">📊 Reportes de eficiencia</a></li>
    <?php endif; ?>

    <?php if ($rol === 'Administrador'): ?>
      <li><a href="/logistica_global/controllers/auditoriaController.php?accion=listar" style="color:white; text-decoration:none;">📜 Auditoría del Sistema</a></li>
      <li><a href="/logistica_global/controllers/usuarioController.php?accion=listar" style="color:white; text-decoration:none;">👤 Usuarios</a></li>
      <li><a href="/logistica_global/controllers/rolController.php?accion=listar" style="color:white; text-decoration:none;">🧩 Roles</a></li>
    <?php endif; ?>

    <hr style="border-color:white; opacity:0.3; margin:15px 0;">

    <!-- 👤 USUARIO ACTUAL / LOGIN / LOGOUT -->
    <?php if (!empty($_SESSION['usuario'])): ?>
      <li style="margin-top:10px; color:#fff;">
        <strong>👤 <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></strong><br>
        <small>(<?= htmlspecialchars($_SESSION['usuario']['rol']) ?>)</small>
      </li>
      <li style="margin-top:10px;">
        <a href="/logistica_global/controllers/loginController.php?logout=1" style="color:white; text-decoration:none;">🚪 Cerrar sesión</a>
      </li>
    <?php else: ?>
      <li><a href="/logistica_global/controllers/loginController.php" style="color:white; text-decoration:none;">🔐 Iniciar sesión</a></li>
    <?php endif; ?>
  </ul>
</nav>
