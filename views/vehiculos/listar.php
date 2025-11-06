<div class="container">
  <h1>🚚 Lista de Vehículos</h1>

  <a href="/logistica_global/controllers/vehiculoController.php?accion=crear" class="btn">➕ Nuevo Vehículo</a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Placa</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Tipo</th>
        <th>Capacidad (kg)</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($vehiculos)): ?>
        <?php foreach ($vehiculos as $v): ?>
          <tr>
            <td><?= htmlspecialchars($v['id_vehiculo']) ?></td>
            <td><?= htmlspecialchars($v['placa']) ?></td>
            <td><?= htmlspecialchars($v['marca'] ?? '-') ?></td>
            <td><?= htmlspecialchars($v['modelo'] ?? '-') ?></td>
            <td><?= htmlspecialchars($v['tipo_camion'] ?? '-') ?></td>
            <td><?= htmlspecialchars($v['capacidad_kg'] ?? '-') ?></td>
            <td><span class="chip <?= strtolower($v['estado']) ?>"><?= htmlspecialchars($v['estado']) ?></span></td>
            <td>
              <a href="/logistica_global/controllers/vehiculoController.php?accion=editar&id=<?= $v['id_vehiculo'] ?>" class="btn small edit">✏️ Editar</a>
              <a href="/logistica_global/controllers/vehiculoController.php?accion=eliminar&id=<?= $v['id_vehiculo'] ?>" class="btn small delete" onclick="return confirm('¿Eliminar vehículo?')">🗑️ Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="8" style="text-align:center;">No hay vehículos registrados.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
