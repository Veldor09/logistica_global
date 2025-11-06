<header class="header">
  <div class="header-left">
    <!-- Botón hamburguesa -->
    <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
      <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Logo e Identidad -->
    <a href="/logistica_global/index.php" class="brand">
      <i class="fa-solid fa-truck-fast"></i>
      <span>Logística Global S.A.</span>
    </a>
  </div>

  <div class="header-center">
    <nav class="topnav">
      <a href="/logistica_global/index.php" class="nav-link">
        <i class="fa-solid fa-house"></i> Inicio
      </a>
      <a href="/logistica_global/controllers/reporteEficienciaController.php" class="nav-link">
        <i class="fa-solid fa-chart-line"></i> Reportes
      </a>
      <a href="/logistica_global/controllers/clienteController.php" class="nav-link">
        <i class="fa-solid fa-users"></i> Clientes
      </a>
    </nav>
  </div>

  <div class="header-right">
    <div class="user-info">
      <i class="fa-solid fa-user-circle"></i>
      <?php if (!empty($_SESSION['usuario'])): ?>
        <span><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?> (<?= htmlspecialchars($_SESSION['usuario']['rol']) ?>)</span>
        <a href="/logistica_global/controllers/loginController.php?logout=1" class="logout-btn" title="Cerrar sesión">
          <i class="fa-solid fa-right-from-bracket"></i>
        </a>
      <?php else: ?>
        <span>Invitado</span>
      <?php endif; ?>
    </div>
  </div>
</header>
