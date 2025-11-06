<div class="container">
  <h1>⚖️ Gestión de Cargas</h1>

  <table>
    <thead>
      <tr>
        <th>ID Viaje</th>
        <th>Ruta</th>
        <th>Vehículo</th>
        <th>Fecha Inicio</th>
        <th>Órdenes</th>
        <th>Peso Total (kg)</th>
        <th>Volumen Total (m³)</th>
      </tr>
    </thead>
 <tbody>
  <?php if (empty($viajes)): ?>
    <tr><td colspan="7" style="text-align:center;">Sin viajes registrados.</td></tr>
  <?php else: ?>
    <?php foreach ($viajes as $v): ?>
      <tr>
        <td>#<?= htmlspecialchars($v['id_viaje']) ?></td>
        <td><?= htmlspecialchars($v['ruta'] ?? '-') ?></td>
        <td><?= htmlspecialchars($v['vehiculo'] ?? '-') ?></td>
        <td><?= htmlspecialchars($v['fecha_inicio'] ?? '-') ?></td>
        <td><?= (int)($v['total_ordenes'] ?? 0) ?></td>
        <td><?= number_format((float)$v['peso_total_kg'], 2) ?></td>
        <td><?= number_format((float)$v['volumen_total_m3'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</tbody>

  </table>
</div>
