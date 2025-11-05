<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/eventoController.php?accion=crear">
    <label>Viaje:
      <select name="id_viaje">
        <option value="">-- Seleccionar --</option>
        <?php foreach ($viajes as $v): ?>
          <option value="<?= (int)$v['id_viaje'] ?>" <?= (($old['id_viaje'] ?? '') == $v['id_viaje']) ? 'selected' : '' ?>>
            Viaje #<?= $v['id_viaje'] ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (!empty($errores['id_viaje'])): ?><div class="error"><?= $errores['id_viaje'] ?></div><?php endif; ?>
    </label>

    <label>Tipo de Evento:
      <select name="id_tipo_evento">
        <option value="">-- Seleccionar --</option>
        <?php foreach ($tipos as $t): ?>
          <option value="<?= (int)$t['id_tipo_evento'] ?>" <?= (($old['id_tipo_evento'] ?? '') == $t['id_tipo_evento']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (!empty($errores['id_tipo_evento'])): ?><div class="error"><?= $errores['id_tipo_evento'] ?></div><?php endif; ?>
    </label>

    <label>Ubicación:
      <input type="text" name="ubicacion" value="<?= htmlspecialchars($old['ubicacion'] ?? '') ?>" />
    </label>

    <label>Observaciones:
      <textarea name="observaciones"><?= htmlspecialchars($old['observaciones'] ?? '') ?></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/eventoController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Guardar</button>
    </div>
  </form>
</div>
