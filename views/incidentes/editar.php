<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/incidenteController.php?accion=editar&id=<?= htmlspecialchars($incidente['id_incidente']) ?>">
    <label>Viaje:
      <select name="id_viaje" required>
        <?php foreach ($viajes as $v): ?>
          <option value="<?= $v['id_viaje'] ?>" <?= ($incidente['id_viaje'] == $v['id_viaje']) ? 'selected' : '' ?>>
            Viaje #<?= htmlspecialchars($v['id_viaje']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Tipo de Incidente:
      <input type="text" name="tipo_incidente" required
             value="<?= htmlspecialchars($incidente['tipo_incidente']) ?>" />
    </label>

    <label>Gravedad:
      <select name="gravedad" required>
        <?php foreach (['Leve', 'Moderado', 'Grave'] as $g): ?>
          <option value="<?= $g ?>" <?= ($incidente['gravedad'] == $g) ? 'selected' : '' ?>><?= $g ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Descripción:
      <textarea name="descripcion" rows="4"><?= htmlspecialchars($incidente['descripcion']) ?></textarea>
    </label>

    <label>Estado:
      <select name="estado" required>
        <?php foreach (['Abierto', 'En Proceso', 'Cerrado'] as $e): ?>
          <option value="<?= $e ?>" <?= ($incidente['estado'] == $e) ? 'selected' : '' ?>><?= $e ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/incidenteController.php?accion=listar" class="btn secondary">Cancelar</a>
      <button type="submit" class="btn primary">💾 Actualizar</button>
    </div>
  </form>
</div>
