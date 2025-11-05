<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>
  <a href="/logistica_global/controllers/eventoController.php?accion=crear" class="btn-primary">+ Nuevo Evento</a>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Viaje</th>
        <th>Tipo</th>
        <th>Fecha</th>
        <th>Ubicación</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($eventos)): ?>
        <tr><td colspan="7">Sin registros</td></tr>
      <?php else: foreach ($eventos as $e): ?>
        <tr>
          <td><?= (int)$e['id_evento'] ?></td>
          <td><?= htmlspecialchars($e['id_viaje']) ?></td>
          <td><?= htmlspecialchars($e['tipo_evento']) ?></td>
          <td><?= htmlspecialchars($e['fecha']) ?></td>
          <td><?= htmlspecialchars($e['ubicacion'] ?? '-') ?></td>
          <td><?= htmlspecialchars($e['estado']) ?></td>
          <td>
            <a href="/logistica_global/controllers/eventoController.php?accion=editar&id=<?= (int)$e['id_evento'] ?>" class="btn-edit">Editar</a>
            <a href="/logistica_global/controllers/eventoController.php?accion=eliminar&id=<?= (int)$e['id_evento'] ?>" class="btn-danger" onclick="return confirm('¿Eliminar evento?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
