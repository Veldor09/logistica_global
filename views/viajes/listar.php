<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <a href="/logistica_global/controllers/viajeController.php?accion=crear" class="btn-primary">+ Nuevo Viaje</a>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Órdenes</th>
        <th>Conductor</th>
        <th>Vehículo</th>
        <th>Ruta</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php if (empty($viajes)): ?>
        <tr><td colspan="9" style="text-align:center;">No hay viajes registrados.</td></tr>
      <?php else: foreach ($viajes as $v): ?>
        <tr>
          <td>#<?= $v['id_viaje'] ?></td>
          <td><?= htmlspecialchars($v['ordenes_txt'] ?? '-') ?></td>
          <td><?= htmlspecialchars($v['conductor'] ?? '-') ?></td>
          <td><?= htmlspecialchars($v['vehiculo'] ?? '-') ?></td>
          <td><?= htmlspecialchars($v['nombre_ruta'] ?? '-') ?></td>
          <td><?= htmlspecialchars($v['fecha_inicio'] ?? '-') ?></td>
          <td><?= htmlspecialchars($v['fecha_fin'] ?? '-') ?></td>
          <td>
            <?php
              $estado = $v['estado'] ?? '-';
              $color = match($estado) {
                'Pendiente' => 'gray',
                'En_Ruta' => 'blue',
                'Finalizado' => 'green',
                'Cancelado' => 'red',
                default => 'black'
              };
            ?>
            <span style="color:<?= $color ?>;font-weight:600;"><?= htmlspecialchars($estado) ?></span>
          </td>
          <td style="display:flex; gap:5px; flex-wrap:wrap;">
            <a href="/logistica_global/controllers/viajeController.php?accion=detallar&id=<?= $v['id_viaje'] ?>" class="btn-view">Ver Detalle</a>
            <a href="/logistica_global/controllers/viajeController.php?accion=editar&id=<?= $v['id_viaje'] ?>" class="btn-edit">Editar</a>
            <a href="/logistica_global/controllers/viajeController.php?accion=eliminar&id=<?= $v['id_viaje'] ?>" class="btn-danger" onclick="return confirm('¿Eliminar este viaje?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<style>
.container { max-width: 1100px; margin: 0 auto; }
.table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
.table th, .table td { border-bottom: 1px solid #ddd; padding: 8px; }
.table th { background: #0d6efd; color: #fff; text-align: left; }
.btn-primary, .btn-view, .btn-edit, .btn-danger {
  text-decoration: none; padding: 6px 10px; border-radius: 6px;
  color: white; font-size: 0.9rem;
}
.btn-primary { background: #0d6efd; }
.btn-view { background: #20c997; }
.btn-edit { background: #ffc107; color: #000; }
.btn-danger { background: #dc3545; }
</style>
