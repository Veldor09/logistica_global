<?php
// ============================================================
// 🧭 Panel principal de Logística Global S.A.
// Muestra módulos según el rol del usuario logueado
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$usuario = $_SESSION['usuario'] ?? null;
$rol = $usuario['rol'] ?? 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Logística Global S.A.</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <!-- HEADER -->
  <?php include('includes/header.php'); ?>

  <!-- CONTENEDOR PRINCIPAL -->
  <div class="layout">
    
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <?php include('includes/sidebar.php'); ?>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="content">
      <section class="dashboard">
        <h1><i class="fa-solid fa-truck"></i> Sistema de Gestión Logística Global S.A.</h1>

        <p>Bienvenido <?= htmlspecialchars($usuario['nombre'] ?? 'Invitado') ?>.</p>
        <p>Selecciona un módulo para comenzar:</p>

        <!-- TARJETAS DE ACCESO SEGÚN ROL -->
        <div class="cards-grid">
          
          <!-- 🔹 ADMINISTRADOR -->
          <?php if ($rol === 'Administrador'): ?>
            <a href="controllers/clienteController.php" class="card blue">
              <i class="fa-solid fa-users"></i>
              <h3>Clientes</h3>
              <p>Gestión de clientes físicos y jurídicos.</p>
            </a>

            <a href="controllers/solicitudController.php" class="card green">
              <i class="fa-solid fa-file-signature"></i>
              <h3>Solicitudes</h3>
              <p>Revisión y aprobación de solicitudes.</p>
            </a>

            <a href="controllers/ordenController.php" class="card orange">
              <i class="fa-solid fa-boxes-stacked"></i>
              <h3>Órdenes</h3>
              <p>Creación y seguimiento de órdenes.</p>
            </a>

            <a href="controllers/viajeController.php" class="card red">
              <i class="fa-solid fa-route"></i>
              <h3>Viajes</h3>
              <p>Control de viajes y entregas.</p>
            </a>

            <a href="controllers/vehiculoController.php" class="card purple">
              <i class="fa-solid fa-truck-moving"></i>
              <h3>Vehículos</h3>
              <p>Administración de flota y mantenimiento.</p>
            </a>

            <a href="controllers/facturaController.php" class="card teal">
              <i class="fa-solid fa-file-invoice-dollar"></i>
              <h3>Facturación</h3>
              <p>Emitir y consultar facturas de servicios.</p>
            </a>

            <a href="controllers/reporteEficienciaController.php" class="card gray">
              <i class="fa-solid fa-chart-line"></i>
              <h3>Reportes</h3>
              <p>Ver reportes de eficiencia y auditoría.</p>
            </a>

          <?php endif; ?>

          <!-- 🔹 CONDUCTOR -->
          <?php if ($rol === 'Conductor'): ?>
            <a href="controllers/ordenController.php" class="card orange">
              <i class="fa-solid fa-boxes-stacked"></i>
              <h3>Órdenes</h3>
              <p>Visualiza y gestiona tus órdenes asignadas.</p>
            </a>

            <a href="controllers/viajeController.php" class="card red">
              <i class="fa-solid fa-route"></i>
              <h3>Viajes</h3>
              <p>Gestiona tus viajes y entregas.</p>
            </a>

            <a href="controllers/incidenteController.php" class="card yellow">
              <i class="fa-solid fa-triangle-exclamation"></i>
              <h3>Incidentes</h3>
              <p>Reporta novedades o accidentes en ruta.</p>
            </a>

            <a href="controllers/reporteEficienciaController.php" class="card gray">
              <i class="fa-solid fa-chart-line"></i>
              <h3>Reportes</h3>
              <p>Consulta tus reportes de rendimiento.</p>
            </a>
          <?php endif; ?>

          <!-- 🔹 SOPORTE -->
          <?php if ($rol === 'Soporte'): ?>
            <a href="controllers/vehiculoController.php" class="card purple">
              <i class="fa-solid fa-truck-moving"></i>
              <h3>Vehículos</h3>
              <p>Gestión de flota y asignación.</p>
            </a>

            <a href="controllers/mantenimientoController.php" class="card gray">
              <i class="fa-solid fa-screwdriver-wrench"></i>
              <h3>Mantenimientos</h3>
              <p>Registra y controla mantenimientos.</p>
            </a>

            <a href="controllers/reporteEficienciaController.php" class="card blue">
              <i class="fa-solid fa-chart-line"></i>
              <h3>Reportes</h3>
              <p>Consulta reportes técnicos de flota.</p>
            </a>
          <?php endif; ?>

          <!-- 🔹 FACTURACIÓN -->
          <?php if ($rol === 'Facturacion'): ?>
            <a href="controllers/facturaController.php" class="card teal">
              <i class="fa-solid fa-file-invoice-dollar"></i>
              <h3>Facturación</h3>
              <p>Gestión financiera y cobros.</p>
            </a>

            <a href="controllers/reporteEficienciaController.php" class="card gray">
              <i class="fa-solid fa-chart-line"></i>
              <h3>Reportes</h3>
              <p>Consulta reportes financieros.</p>
            </a>
          <?php endif; ?>

          <!-- 🔹 CLIENTE -->
          <?php if ($rol === 'Cliente'): ?>
            <a href="controllers/reporteEficienciaController.php" class="card gray">
              <i class="fa-solid fa-chart-column"></i>
              <h3>Reportes</h3>
              <p>Consulta de reportes disponibles.</p>
            </a>
          <?php endif; ?>

          <!-- 🔹 INVITADO -->
          <?php if ($rol === 'Invitado'): ?>
            <a href="controllers/loginController.php" class="card blue">
              <i class="fa-solid fa-right-to-bracket"></i>
              <h3>Iniciar sesión</h3>
              <p>Accede con tus credenciales al sistema.</p>
            </a>
          <?php endif; ?>

        </div>
      </section>
    </main>
  </div>

  <!-- FOOTER -->
  <?php include('includes/footer.php'); ?>

  <script src="assets/js/app.js"></script>
</body>
</html>
