<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Logística Global S.A.</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Importante para el responsive -->
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
        <p>Bienvenido al sistema central de gestión de clientes, solicitudes, órdenes y viajes.</p>
        <p>Selecciona un módulo para comenzar:</p>

        <!-- TARJETAS DE ACCESO -->
        <div class="cards-grid">
          <!-- ✅ CAMBIO: enlaces apuntan a CONTROLADORES -->
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
        </div>
      </section>
    </main>
  </div>

  <!-- FOOTER -->
  <?php include('includes/footer.php'); ?>

  <!-- SCRIPT -->
  <script src="assets/js/app.js"></script>

</body>
</html>
