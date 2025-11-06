<div class="container">
  <h1>🧰 Mantenimientos</h1>
  <a href="/logistica_global/controllers/mantenimientoController.php?accion=crear" class="btn success">➕ Nuevo Mantenimiento</a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Vehículo</th>
        <th>Tipo</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Costo</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($mantenimientos)): ?>
        <?php foreach ($mantenimientos as $m): ?>
          <tr>
            <td><?= $m['id_mantenimiento'] ?></td>
            <td><?= htmlspecialchars($m['vehiculo']) ?></td>
            <td><?= htmlspecialchars($m['tipo_mantenimiento']) ?></td>
            <td><?= $m['fecha'] ? $m['fecha']->format('Y-m-d') : '-' ?></td>
            <td><?= htmlspecialchars($m['descripcion']) ?></td>
            <td>₡<?= number_format($m['costo'], 2) ?></td>
            <td><span class="chip <?= strtolower($m['estado']) ?>"><?= $m['estado'] ?></span></td>
            <td>
              <a href="/logistica_global/controllers/mantenimientoController.php?accion=editar&id=<?= $m['id_mantenimiento'] ?>" class="btn small edit">✏️</a>
              <a href="/logistica_global/controllers/mantenimientoController.php?accion=eliminar&id=<?= $m['id_mantenimiento'] ?>" class="btn small delete" onclick="return confirm('¿Eliminar mantenimiento?')">🗑️</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="8" style="text-align:center;">No hay mantenimientos registrados.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
