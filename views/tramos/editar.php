<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <form method="POST" action="/logistica_global/controllers/tramoController.php?accion=editar&id=<?= $tramo['id_tramo'] ?>">
    
    <label>Ruta:
      <select name="id_ruta" required>
        <?php foreach ($rutas as $r): ?>
          <option value="<?= $r['id_ruta'] ?>" <?= ($tramo['id_ruta']==$r['id_ruta'])?'selected':'' ?>>
            <?= htmlspecialchars($r['nombre_ruta']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Tipo de Carretera:
      <select name="id_tipo_carretera">
        <option value="">-- No especificado --</option>
        <?php foreach ($tipos as $t): ?>
          <option value="<?= $t['id_tipo_carretera'] ?>" <?= ($tramo['id_tipo_carretera']==$t['id_tipo_carretera'])?'selected':'' ?>>
            <?= htmlspecialchars($t['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Orden del Tramo:
      <input type="number" name="orden_tramo" required value="<?= htmlspecialchars($tramo['orden_tramo'] ?? '') ?>">
    </label>

    <label>Punto de Inicio:
      <input type="text" name="punto_inicio" required value="<?= htmlspecialchars($tramo['punto_inicio'] ?? '') ?>">
    </label>

    <label>Punto Final:
      <input type="text" name="punto_fin" required value="<?= htmlspecialchars($tramo['punto_fin'] ?? '') ?>">
    </label>

    <div class="grid-2">
      <label>Distancia (km):
        <input type="number" step="0.01" name="distancia_km" value="<?= htmlspecialchars($tramo['distancia_km'] ?? '') ?>">
      </label>
      <label>Tiempo Estimado (hr):
        <input type="number" step="0.01" name="tiempo_estimado_hr" value="<?= htmlspecialchars($tramo['tiempo_estimado_hr'] ?? '') ?>">
      </label>
    </div>

    <label>Observaciones:
      <textarea name="observaciones" rows="3"><?= htmlspecialchars($tramo['observaciones'] ?? '') ?></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/tramoController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Actualizar</button>
    </div>
  </form>
</div>
