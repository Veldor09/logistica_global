<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <form method="GET" action="/logistica_global/controllers/auditoriaController.php" class="filter-form">
    <input type="hidden" name="accion" value="listar">

    <div class="grid-4">
      <label>Usuario:
        <input type="text" name="usuario" value="<?= htmlspecialchars($filtros['usuario']) ?>" placeholder="Buscar por usuario...">
      </label>

      <label>Rol:
        <input type="text" name="rol" value="<?= htmlspecialchars($filtros['rol']) ?>" placeholder="Ej: ADMIN">
      </label>

      <label>Módulo:
        <input type="text" name="modulo" value="<?= htmlspecialchars($filtros['modulo']) ?>" placeholder="Ej: Viaje, Evento...">
      </label>

      <label>Acción:
        <select name="accionFiltro">
          <option value="">-- Todas --</option>
          <?php foreach (['INSERT','UPDATE','DELETE','LOGIN'] as $a): ?>
            <option value="<?= $a ?>" <?= ($filtros['accion']===$a)?'selected':'' ?>><?= $a ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>

    <button type="submit" class="btn-primary">🔍 Filtrar</button>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Rol</th>
        <th>Módulo</th>
        <th>Acción</th>
        <th>Descripción</th>
      </tr>
    </thead>

    <tbody>
      <?php if (empty($registros)): ?>
        <tr><td colspan="6">No hay registros de auditoría.</td></tr>
      <?php else: foreach ($registros as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['fecha']) ?></td>
          <td><?= htmlspecialchars($r['usuario'] ?? '-') ?></td>
          <td><?= htmlspecialchars($r['rol'] ?? '-') ?></td>
          <td><?= htmlspecialchars($r['modulo']) ?></td>
          <td><b><?= htmlspecialchars($r['accion']) ?></b></td>
          <td><?= htmlspecialchars($r['descripcion'] ?? '-') ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
