<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <a href="/logistica_global/controllers/incidenteController.php?accion=crear" class="btn success">
    ➕ Nuevo Incidente
  </a>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Viaje</th>
        <th>Tipo</th>
        <th>Gravedad</th>
        <th>Estado</th>
        <th>Fecha Reporte</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($incidentes)): ?>
        <tr>
          <td colspan="7" style="text-align:center;">🚫 No hay incidentes registrados.</td>
        </tr>
      <?php else: foreach ($incidentes as $i): ?>
        <tr>
          <td><?= htmlspecialchars($i['id_incidente']) ?></td>
          <td><?= htmlspecialchars($i['id_viaje']) ?></td>
          <td><?= htmlspecialchars($i['tipo_incidente']) ?></td>
          <td><?= htmlspecialchars($i['gravedad']) ?></td>
          <td>
            <span class="chip <?= strtolower($i['estado']) ?>">
              <?= htmlspecialchars($i['estado']) ?>
            </span>
          </td>
          <td>
            <?php
              $fecha = $i['fecha_reporte'] ?? null;
              if ($fecha instanceof DateTime) {
                echo $fecha->format('Y-m-d H:i');
              } elseif (is_string($fecha)) {
                echo htmlspecialchars(substr($fecha, 0, 16));
              } else {
                echo '-';
              }
            ?>
          </td>
          <td>
            <a href="/logistica_global/controllers/incidenteController.php?accion=editar&id=<?= urlencode($i['id_incidente']) ?>"
               class="btn small edit">✏️</a>
            <a href="/logistica_global/controllers/incidenteController.php?accion=eliminar&id=<?= urlencode($i['id_incidente']) ?>"
               class="btn small delete"
               onclick="return confirm('¿Eliminar este incidente?')">🗑️</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
