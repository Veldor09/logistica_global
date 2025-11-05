<div class="container">
  <h1><i class="fa-solid fa-file-signature"></i> Lista de Solicitudes de Transporte</h1>

  <!-- 🔔 Alertas visuales -->
  <?php if (isset($_GET['success'])): ?>
    <div class="alert success">✅ Solicitud registrada correctamente.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert info">✏️ Solicitud actualizada correctamente.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert danger">🗑️ Solicitud eliminada correctamente.</div>
  <?php endif; ?>

  <!-- 🟢 Botón para crear nueva solicitud -->
  <a href="/logistica_global/controllers/solicitudController.php?accion=crear" class="btn">
    <i class="fa-solid fa-plus"></i> Nueva Solicitud
  </a>

  <!-- 📋 Tabla -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Remitente</th>
        <th>Destinatario</th>
        <th>Tipo de Servicio</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Estado</th>
        <th>Prioridad</th>
        <th>Fecha</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($solicitudes)): ?>
        <?php foreach ($solicitudes as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['id_solicitud']) ?></td>
            <td><?= htmlspecialchars($s['correo_remitente'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['correo_destinatario'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['tipo_servicio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['origen'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['destino_general'] ?? '-') ?></td>
            <td>
              <span class="chip <?= strtolower($s['estado']) ?>">
                <?= htmlspecialchars($s['estado']) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($s['prioridad'] ?? '-') ?></td>
            <td><?= isset($s['fecha_solicitud']) ? date_format($s['fecha_solicitud'], 'Y-m-d') : '-' ?></td>
            <td>
              <a href="/logistica_global/controllers/solicitudController.php?accion=editar&id=<?= $s['id_solicitud'] ?>" class="btn small edit">
                ✏️ Editar
              </a>
              <a href="/logistica_global/controllers/solicitudController.php?accion=eliminar&id=<?= $s['id_solicitud'] ?>"
                 class="btn small delete"
                 onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?')">
                🗑️ Eliminar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="10" style="text-align:center;">No hay solicitudes registradas.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
