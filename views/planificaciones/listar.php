<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>
  <a href="/logistica_global/controllers/planificacionController.php?accion=crear" class="btn-primary">+ Nueva Planificación</a>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th><th>Carga</th><th>Vehículo</th><th>% Distribución</th><th>Balance Eje</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($planificaciones)): ?>
        <tr><td colspan="6">No hay planificaciones registradas.</td></tr>
      <?php else: foreach ($planificaciones as $p): ?>
        <tr>
          <td><?= $p['id_planificacion'] ?></td>
          <td><?= $p['id_carga'] ?></td>
          <td><?= htmlspecialchars($p['placa']) ?></td>
          <td><?= $p['distribucion_porcentaje'] ?>%</td>
          <td><?= htmlspecialchars($p['balance_eje'] ?? '-') ?></td>
          <td style="display:flex;gap:5px;">
            <a href="/logistica_global/controllers/planificacionController.php?accion=editar&id=<?= $p['id_planificacion'] ?>" class="btn-edit">Editar</a>
            <a href="/logistica_global/controllers/planificacionController.php?accion=eliminar&id=<?= $p['id_planificacion'] ?>" class="btn-danger" onclick="return confirm('¿Eliminar planificación?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
