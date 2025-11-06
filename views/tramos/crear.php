<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <form method="POST" action="/logistica_global/controllers/tramoController.php?accion=crear">
    
    <!-- Ruta -->
    <label>Ruta:
      <select name="id_ruta" required>
        <option value="">-- Seleccionar ruta --</option>
        <?php foreach ($rutas as $r): ?>
          <option value="<?= $r['id_ruta'] ?>" <?= (($old['id_ruta'] ?? '')==$r['id_ruta'])?'selected':'' ?>>
            <?= htmlspecialchars($r['nombre_ruta']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <?php if (!empty($errores['id_ruta'])): ?><p class="error"><?= $errores['id_ruta'] ?></p><?php endif; ?>

    <!-- Tipo Carretera -->
    <label>Tipo de Carretera:
      <select name="id_tipo_carretera">
        <option value="">-- No especificado --</option>
        <?php foreach ($tipos as $t): ?>
          <option value="<?= $t['id_tipo_carretera'] ?>" <?= (($old['id_tipo_carretera'] ?? '')==$t['id_tipo_carretera'])?'selected':'' ?>>
            <?= htmlspecialchars($t['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <!-- Orden -->
    <label>Orden del Tramo:
      <input type="number" name="orden_tramo" required value="<?= htmlspecialchars($old['orden_tramo'] ?? '') ?>">
    </label>
    <?php if (!empty($errores['orden_tramo'])): ?><p class="error"><?= $errores['orden_tramo'] ?></p><?php endif; ?>

    <!-- Puntos -->
    <label>Punto de Inicio:
      <input type="text" name="punto_inicio" required value="<?= htmlspecialchars($old['punto_inicio'] ?? '') ?>">
    </label>
    <?php if (!empty($errores['punto_inicio'])): ?><p class="error"><?= $errores['punto_inicio'] ?></p><?php endif; ?>

    <label>Punto Final:
      <input type="text" name="punto_fin" required value="<?= htmlspecialchars($old['punto_fin'] ?? '') ?>">
    </label>
    <?php if (!empty($errores['punto_fin'])): ?><p class="error"><?= $errores['punto_fin'] ?></p><?php endif; ?>

    <!-- Distancia y Tiempo -->
    <div class="grid-2">
      <label>Distancia (km):
        <input type="number" step="0.01" name="distancia_km" value="<?= htmlspecialchars($old['distancia_km'] ?? '') ?>">
      </label>
      <label>Tiempo Estimado (hr):
        <input type="number" step="0.01" name="tiempo_estimado_hr" value="<?= htmlspecialchars($old['tiempo_estimado_hr'] ?? '') ?>">
      </label>
    </div>

    <!-- Observaciones -->
    <label>Observaciones:
      <textarea name="observaciones" rows="3"><?= htmlspecialchars($old['observaciones'] ?? '') ?></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/tramoController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Guardar</button>
    </div>
  </form>
</div>
