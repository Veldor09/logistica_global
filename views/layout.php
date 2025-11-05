<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $titulo ?? 'Panel Logística Global S.A.' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/logistica_global/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php echo "<!-- DEBUG Layout activo -->"; ?>


  <!-- HEADER -->
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <!-- BOTÓN HAMBURGUESA -->
  <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
    <i class="fa-solid fa-bars"></i>
  </button>

  <!-- LAYOUT GENERAL -->
  <div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    </aside>

    <!-- CONTENIDO -->
    <main class="content">

      <!-- 🔔 MENSAJES GLOBALES -->
      <?php
      // Detectar módulo dinámicamente (por ejemplo, "Cliente", "Solicitud", etc.)
      $modulo = $titulo ?? 'Elemento';

      $alert = '';

      if (isset($_GET['success'])) {
          $alert = "<div class='alert success'>✅ $modulo creado correctamente.</div>";
      } elseif (isset($_GET['updated'])) {
          $alert = "<div class='alert success'>✅ $modulo actualizado correctamente.</div>";
      } elseif (isset($_GET['deleted'])) {
          $alert = "<div class='alert danger'>🗑️ $modulo eliminado correctamente.</div>";
      } elseif (isset($_GET['error'])) {
          $alert = "<div class='alert danger'>❌ Error al procesar $modulo. Inténtalo nuevamente.</div>";
      }

      echo $alert;
      ?>

      <!-- 🔹 CONTENIDO PRINCIPAL -->
      <?= $contenido ?? '' ?>

    </main>
  </div>

  <!-- FOOTER -->
  <?php include __DIR__ . '/../includes/footer.php'; ?>

  <!-- SCRIPT -->
  <script src="/logistica_global/assets/js/app.js"></script>
</body>
</html>