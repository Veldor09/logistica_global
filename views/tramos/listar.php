<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <a href="/logistica_global/controllers/tramoController.php?accion=crear" class="btn-primary">+ Nuevo Tramo</a>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Ruta</th>
        <th>Orden</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Tipo Carretera</th>
        <th>Distancia (km)</th>
        <th>Tiempo (hr)</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($tramos)): ?>
        <tr><td colspan="9">No hay tramos registrados.</td></tr>
      <?php else: foreach ($tramos as $t): ?>
        <tr>
          <td><?= $t['id_tramo'] ?></td>
          <td><?= htmlspecialchars($t['nombre_ruta']) ?></td>
          <td><?= $t['orden_tramo'] ?></td>
          <td><?= htmlspecialchars($t['punto_inicio']) ?></td>
          <td><?= htmlspecialchars($t['punto_fin']) ?></td>
          <td><?= htmlspecialchars($t['tipo_carretera'] ?? '-') ?></td>
          <td><?= $t['distancia_km'] ?? '-' ?></td>
          <td><?= $t['tiempo_estimado_hr'] ?? '-' ?></td>
          <td style="display:flex; gap:5px; flex-wrap:wrap;">
            <a href="/logistica_global/controllers/tramoController.php?accion=editar&id=<?= $t['id_tramo'] ?>" class="btn-edit">Editar</a>
            <a href="/logistica_global/controllers/tramoController.php?accion=eliminar&id=<?= $t['id_tramo'] ?>" class="btn-danger" onclick="return confirm('¿Eliminar este tramo?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
