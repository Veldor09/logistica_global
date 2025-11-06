<div class="container">
  <h1>📋 Órdenes de Transporte</h1>
  <a href="/logistica_global/controllers/ordenController.php?accion=crear" class="btn success">➕ Nueva Orden</a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Solicitud</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Peso (kg)</th>
        <th>Tipo de Mercancía</th>
        <th>Volumen (m³)</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($ordenes)): ?>
        <?php foreach ($ordenes as $o): ?>
          <tr>
            <td><?= $o['id_orden'] ?></td>
            <td><?= $o['id_solicitud'] ?></td>
            
            <td><?= htmlspecialchars($o['direccion_origen'] ?: $o['solicitud_origen'] ?? '-') ?></td>
            <td><?= htmlspecialchars($o['direccion_destino'] ?: $o['solicitud_destino'] ?? '-') ?></td>

            <td><?= number_format($o['peso_estimado_kg'], 2) ?></td>
            <td><?= htmlspecialchars($o['tipo_mercancia'] ?? 'No asignado') ?></td>
            <td><?= number_format($o['volumen_total_m3'] ?? 0, 2) ?></td>
            <td><?= htmlspecialchars($o['estado']) ?></td>
            
            <td>
              <a href="/logistica_global/controllers/ordenController.php?accion=editar&id=<?= $o['id_orden'] ?>" class="btn small edit">✏️</a>
              <a href="/logistica_global/controllers/ordenController.php?accion=eliminar&id=<?= $o['id_orden'] ?>" class="btn small delete" onclick="return confirm('¿Eliminar esta orden?')">🗑️</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">No hay órdenes registradas.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
