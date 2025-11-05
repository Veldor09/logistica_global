<div class="container">
  <h1><i class="fa-solid fa-truck-fast"></i> Lista de Viajes</h1>

  <a href="/logistica_global/controllers/viajeController.php?accion=crear" class="btn">
    <i class="fa-solid fa-plus"></i> Nuevo Viaje
  </a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Orden</th>
        <th>Conductor</th>
        <th>Vehículo</th>
        <th>Ruta</th>
        <th>Estado</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($viajes)): ?>
        <?php foreach ($viajes as $v): ?>
          <tr>
            <td><?= $v['id_viaje'] ?></td>
            <td>#<?= $v['id_orden'] ?></td>
            <td><?= htmlspecialchars($v['nombre_conductor'] ?? '-') ?></td>
            <td><?= htmlspecialchars($v['vehiculo_placa'] ?? '-') ?></td>
            <td><?= htmlspecialchars($v['nombre_ruta'] ?? '-') ?></td>
            <td><span class="chip <?= strtolower($v['estado']) ?>"><?= $v['estado'] ?></span></td>
            <td><?= $v['fecha_inicio'] ? date_format($v['fecha_inicio'], 'Y-m-d H:i') : '-' ?></td>
            <td><?= $v['fecha_fin'] ? date_format($v['fecha_fin'], 'Y-m-d H:i') : '-' ?></td>
            <td>
              <a href="/logistica_global/controllers/viajeController.php?accion=editar&id=<?= $v['id_viaje'] ?>" class="btn small edit">✏️</a>
              <a href="/logistica_global/controllers/viajeController.php?accion=eliminar&id=<?= $v['id_viaje'] ?>" class="btn small delete" onclick="return confirm('¿Eliminar viaje?')">🗑️</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">No hay viajes registrados.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
