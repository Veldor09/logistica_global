<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/cargaController.php?accion=crear">
    <label>Viaje:
      <select name="id_viaje">
        <option value="">-- Seleccionar --</option>
        <?php foreach ($viajes as $v): ?>
          <option value="<?= $v['id_viaje'] ?>">Viaje #<?= $v['id_viaje'] ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (!empty($errores['id_viaje'])): ?><div class="error"><?= $errores['id_viaje'] ?></div><?php endif; ?>
    </label>

    <label>Peso (kg):
      <input type="number" step="0.01" name="peso_kg" />
      <?php if (!empty($errores['peso_kg'])): ?><div class="error"><?= $errores['peso_kg'] ?></div><?php endif; ?>
    </label>

    <label>Volumen (m³):
      <input type="number" step="0.01" name="volumen_m3" />
    </label>

    <label>Descripción:
      <textarea name="descripcion"></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/cargaController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Guardar</button>
    </div>
  </form>
</div>
