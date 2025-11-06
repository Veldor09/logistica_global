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

<nav style="background:#134074; padding:20px; color:white; min-height:100vh; width:240px;">
  <ul style="list-style:none; padding:0; margin:0; font-size:15px;">

    <!-- 🔹 SECCIÓN GENERAL -->
    <li>
      <a href="/logistica_global/index.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">
        🏠 Inicio
      </a>
    </li>

    <?php if ($rol === 'Administrador' || $rol === 'Invitado'): ?>
      <li>
        <a href="/logistica_global/controllers/clienteController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">
          👥 Clientes
        </a>
      </li>

      <?php if ($rol === 'Invitado'): ?>
        <!-- 🌐 Modo público: solo lectura y registro -->
        <li>
          <a href="/logistica_global/controllers/solicitudController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">
            📄 Ver Solicitudes Públicas
          </a>
        </li>
        <li>
          <a href="/logistica_global/controllers/solicitudController.php?accion=crear_publica" style="color:white; text-decoration:none; display:block; padding:8px 0;">
            📝 Registrar Solicitud Pública
          </a>
        </li>
      <?php else: ?>
        <!-- 🔐 CRUD interno -->
        <li>
          <a href="/logistica_global/controllers/solicitudController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">
            📄 Solicitudes
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>

    <!-- 🚚 CONDUCTOR -->
    <?php if (in_array($rol, ['Administrador', 'Conductor'])): ?>
      <li><a href="/logistica_global/controllers/ordenController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">📦 Órdenes</a></li>
      <li><a href="/logistica_global/controllers/viajeController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🚚 Viajes</a></li>
      <li><a href="/logistica_global/controllers/incidenteController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">⚠️ Incidentes</a></li>
    <?php endif; ?>

    <!-- 🧰 SOPORTE -->
    <?php if (in_array($rol, ['Administrador', 'Soporte'])): ?>
      <li><a href="/logistica_global/controllers/vehiculoController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🚗 Vehículos</a></li>
      <li><a href="/logistica_global/controllers/conductorController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🧑‍✈️ Conductores</a></li>
      <li><a href="/logistica_global/controllers/mantenimientoController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🛠️ Mantenimientos</a></li>
    <?php endif; ?>

    <!-- 💰 FACTURACIÓN -->
    <?php if (in_array($rol, ['Administrador', 'Facturacion'])): ?>
      <li><a href="/logistica_global/controllers/facturaController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">💰 Facturación</a></li>
    <?php endif; ?>

    <!-- 📦 LOGÍSTICA -->
    <?php if ($rol === 'Administrador'): ?>
      <li><a href="/logistica_global/controllers/mercanciaController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">📦 Mercancías</a></li>
      <li><a href="/logistica_global/controllers/cargaController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🚛 Cargas</a></li>
      <li><a href="/logistica_global/controllers/rutaController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">📍 Rutas</a></li>
      <li><a href="/logistica_global/controllers/tramoController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🛣️ Tramos de Ruta</a></li>
      <li><a href="/logistica_global/controllers/tipoCarreteraController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">⚙️ Tipos de Carretera</a></li>
      <li><a href="/logistica_global/controllers/eventoController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🎉 Eventos</a></li>
      <li><a href="/logistica_global/controllers/tipoEventoController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">🏷️ Tipos de Evento</a></li>
    <?php endif; ?>

    <!-- 📊 REPORTES Y AUDITORÍA -->
    <?php if (in_array($rol, ['Administrador', 'Soporte', 'Conductor', 'Facturacion'])): ?>
      <li><a href="/logistica_global/controllers/reporteEficienciaController.php" style="color:white; text-decoration:none; display:block; padding:8px 0;">📊 Reportes de eficiencia</a></li>
    <?php endif; ?>

    <?php if ($rol === 'Administrador'): ?>
      <li><a href="/logistica_global/controllers/auditoriaController.php?accion=listar" style="color:white; text-decoration:none; display:block; padding:8px 0;">📜 Auditoría del Sistema</a></li>
      <li><a href="/logistica_global/controllers/usuarioController.php?accion=listar" style="color:white; text-decoration:none; display:block; padding:8px 0;">👤 Usuarios</a></li>
      <li><a href="/logistica_global/controllers/rolController.php?accion=listar" style="color:white; text-decoration:none; display:block; padding:8px 0;">🧩 Roles</a></li>
    <?php endif; ?>

    <hr style="border-color:white; opacity:0.3; margin:15px 0;">

    <!-- 👤 USUARIO ACTUAL / LOGIN / LOGOUT -->
    <?php if ($usuario): ?>
      <li style="margin-top:10px;">
        <strong>👤 <?= htmlspecialchars($usuario['nombre']) ?></strong><br>
        <small>(<?= htmlspecialchars($rol) ?>)</small>
      </li>
      <li style="margin-top:10px;">
        <a href="/logistica_global/controllers/loginController.php?logout=1"
           style="color:white; text-decoration:none; display:block; padding:8px 0;">🚪 Cerrar sesión</a>
      </li>
    <?php else: ?>
      <li>
        <a href="/logistica_global/controllers/loginController.php"
           style="color:white; text-decoration:none; display:block; padding:8px 0;">🔐 Iniciar sesión</a>
      </li>
    <?php endif; ?>
  </ul>

  <!-- 🧭 Estilos adicionales -->
  <style>
    nav ul li a:hover {
      background: rgba(255,255,255,0.15);
      border-radius:6px;
      transition: all 0.2s ease-in-out;
    }
  </style>
</nav>
