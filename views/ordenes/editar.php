<div class="container">
  <h1>✏️ Editar Orden</h1>

  <form method="POST" action="/logistica_global/controllers/ordenController.php?accion=editar&id=<?= $orden['id_orden'] ?>">
    <label>Dirección Origen:</label>
    <input type="text" name="direccion_origen" value="<?= htmlspecialchars($orden['direccion_origen'] ?? '') ?>">

    <label>Dirección Destino:</label>
    <input type="text" name="direccion_destino" value="<?= htmlspecialchars($orden['direccion_destino'] ?? '') ?>">

    <label>Peso estimado (kg):</label>
    <input type="number" step="0.01" name="peso_estimado_kg" value="<?= htmlspecialchars($orden['peso_estimado_kg'] ?? '') ?>">

    <label>Fecha de carga:</label>
    <input type="date" name="fecha_carga" value="<?= isset($orden['fecha_carga']) && $orden['fecha_carga'] instanceof DateTime ? $orden['fecha_carga']->format('Y-m-d') : '' ?>">

    <label>Fecha estimada de entrega:</label>
    <input type="date" name="fecha_entrega_estimada" value="<?= isset($orden['fecha_entrega_estimada']) && $orden['fecha_entrega_estimada'] instanceof DateTime ? $orden['fecha_entrega_estimada']->format('Y-m-d') : '' ?>">

    <label>Estado:</label>
    <select name="estado">
      <?php 
      $estados = ['Programada', 'En Ruta', 'Finalizada', 'Cancelada'];
      foreach ($estados as $estado): ?>
        <option value="<?= $estado ?>" <?= ($orden['estado'] ?? '') === $estado ? 'selected' : '' ?>><?= $estado ?></option>
      <?php endforeach; ?>
    </select>

    <label>Observaciones:</label>
    <textarea name="observaciones"><?= htmlspecialchars($orden['observaciones'] ?? '') ?></textarea>

    <button type="submit" class="btn success">💾 Actualizar</button>
    <a href="/logistica_global/controllers/ordenController.php?accion=listar" class="btn secondary">⬅️ Volver</a>
  </form>
</div>
