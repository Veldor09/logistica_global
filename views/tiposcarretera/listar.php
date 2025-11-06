<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <a href="/logistica_global/controllers/tipoCarreteraController.php?accion=crear" class="btn-primary">+ Nuevo Tipo</a>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Descripción</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($tipos)): ?>
        <tr><td colspan="4">No hay tipos registrados.</td></tr>
      <?php else: foreach ($tipos as $t): ?>
        <tr>
          <td><?= $t['id_tipo_carretera'] ?></td>
          <td><?= htmlspecialchars($t['nombre']) ?></td>
          <td><?= htmlspecialchars($t['descripcion'] ?? '-') ?></td>
          <td style="display:flex;gap:5px;">
            <a href="/logistica_global/controllers/tipoCarreteraController.php?accion=editar&id=<?= $t['id_tipo_carretera'] ?>" class="btn-edit">Editar</a>
            <a href="/logistica_global/controllers/tipoCarreteraController.php?accion=eliminar&id=<?= $t['id_tipo_carretera'] ?>" class="btn-danger" onclick="return confirm('¿Eliminar este tipo?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
