<?php /** @var array $ruta */ /** @var array $errores */ ?>
<div class="card">
  <h2 class="mb-2" style="margin-top:0">Editar Ruta</h2>

  <?php if (!empty($errores['general'])): ?>
    <p class="error"><?= htmlspecialchars($errores['general']) ?></p>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/rutaController.php?accion=editar&id=<?= (int)($ruta['id_ruta'] ?? 0) ?>">
    <div class="grid grid-2">
      <div>
        <label>Nombre de la ruta</label>
        <input type="text" name="nombre_ruta" value="<?= htmlspecialchars($ruta['nombre_ruta'] ?? '') ?>" required>
        <?php if (!empty($errores['nombre_ruta'])): ?><div class="error"><?= htmlspecialchars($errores['nombre_ruta']) ?></div><?php endif; ?>
      </div>

      <div>
        <label>Estado</label>
        <?php $estado = $ruta['estado'] ?? 'Activa'; ?>
        <select name="estado">
          <option value="Activa"   <?= $estado === 'Activa'   ? 'selected' : '' ?>>Activa</option>
          <option value="Inactiva" <?= $estado === 'Inactiva' ? 'selected' : '' ?>>Inactiva</option>
        </select>
      </div>

      <div>
        <label>Origen</label>
        <input type="text" name="origen" value="<?= htmlspecialchars($ruta['origen'] ?? '') ?>" required>
        <?php if (!empty($errores['origen'])): ?><div class="error"><?= htmlspecialchars($errores['origen']) ?></div><?php endif; ?>
      </div>

      <div>
        <label>Destino</label>
        <input type="text" name="destino" value="<?= htmlspecialchars($ruta['destino'] ?? '') ?>" required>
        <?php if (!empty($errores['destino'])): ?><div class="error"><?= htmlspecialchars($errores['destino']) ?></div><?php endif; ?>
      </div>

      <div>
        <label>Distancia total (km)</label>
        <input type="number" step="0.01" name="distancia_total_km" value="<?= htmlspecialchars($ruta['distancia_total_km'] ?? '') ?>">
      </div>

      <div>
        <label>Tiempo estimado (hr)</label>
        <input type="number" step="0.01" name="tiempo_estimado_hr" value="<?= htmlspecialchars($ruta['tiempo_estimado_hr'] ?? '') ?>">
      </div>
    </div>

    <div class="mb-2" style="margin-top:14px">
      <a class="btn" href="/logistica_global/controllers/rutaController.php?accion=listar">← Volver</a>
      <button class="btn btn-primary" type="submit">Guardar cambios</button>
    </div>
  </form>
</div>
