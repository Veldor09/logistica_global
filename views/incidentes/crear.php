<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/incidenteController.php?accion=crear">
    <label>Viaje:
      <select name="id_viaje" required>
        <option value="">-- Seleccionar --</option>
        <?php foreach ($viajes as $v): ?>
          <option value="<?= $v['id_viaje'] ?>"
            <?= (($old['id_viaje'] ?? '') == $v['id_viaje']) ? 'selected' : '' ?>>
            Viaje #<?= htmlspecialchars($v['id_viaje']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (!empty($errores['id_viaje'])): ?>
        <div class="error"><?= htmlspecialchars($errores['id_viaje']) ?></div>
      <?php endif; ?>
    </label>

    <label>Tipo de Incidente:
      <input type="text" name="tipo_incidente" required
             value="<?= htmlspecialchars($old['tipo_incidente'] ?? '') ?>" />
      <?php if (!empty($errores['tipo_incidente'])): ?>
        <div class="error"><?= htmlspecialchars($errores['tipo_incidente']) ?></div>
      <?php endif; ?>
    </label>

    <label>Gravedad:
      <select name="gravedad" required>
        <option value="">-- Seleccionar --</option>
        <?php foreach (['Leve', 'Moderado', 'Grave'] as $g): ?>
          <option value="<?= $g ?>" <?= (($old['gravedad'] ?? '') == $g) ? 'selected' : '' ?>>
            <?= $g ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (!empty($errores['gravedad'])): ?>
        <div class="error"><?= htmlspecialchars($errores['gravedad']) ?></div>
      <?php endif; ?>
    </label>

    <label>Descripción:
      <textarea name="descripcion" rows="4"><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/incidenteController.php?accion=listar" class="btn secondary">Cancelar</a>
      <button type="submit" class="btn primary">💾 Guardar</button>
    </div>
  </form>
</div>
