<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/cargaController.php?accion=editar&id=<?= $carga['id_carga'] ?>">
    <label>Viaje:
      <select name="id_viaje">
        <?php foreach ($viajes as $v): ?>
          <option value="<?= $v['id_viaje'] ?>" <?= ($carga['id_viaje']==$v['id_viaje'])?'selected':'' ?>>
            Viaje #<?= $v['id_viaje'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Peso (kg):
      <input type="number" step="0.01" name="peso_kg" value="<?= htmlspecialchars($carga['peso_kg']) ?>" />
    </label>

    <label>Volumen (m³):
      <input type="number" step="0.01" name="volumen_m3" value="<?= htmlspecialchars($carga['volumen_m3'] ?? '') ?>" />
    </label>

    <label>Descripción:
      <textarea name="descripcion"><?= htmlspecialchars($carga['descripcion'] ?? '') ?></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/cargaController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Actualizar</button>
    </div>
  </form>
</div>
