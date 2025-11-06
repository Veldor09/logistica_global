<div class="form-container">
  <h1>✏️ Editar Mantenimiento</h1>

  <form method="POST" action="/logistica_global/controllers/mantenimientoController.php?accion=editar&id=<?= $mantenimiento['id_mantenimiento'] ?>">
    <div class="form-grid">

      <label>Vehículo (ID)</label>
      <input type="number" name="id_vehiculo" value="<?= $mantenimiento['id_vehiculo'] ?>" required>

      <label>Fecha</label>
      <input type="date" name="fecha" value="<?= $mantenimiento['fecha'] ? $mantenimiento['fecha']->format('Y-m-d') : '' ?>" required>

      <label>Tipo de Mantenimiento (ID)</label>
      <input type="number" name="id_tipo_mantenimiento" value="<?= $mantenimiento['id_tipo_mantenimiento'] ?>" required>

      <label>Descripción</label>
      <textarea name="descripcion" rows="2"><?= htmlspecialchars($mantenimiento['descripcion']) ?></textarea>

      <label>Costo (₡)</label>
      <input type="number" step="0.01" name="costo" value="<?= htmlspecialchars($mantenimiento['costo']) ?>">

      <label>Estado</label>
      <select name="estado">
        <option value="Activo" <?= $mantenimiento['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
        <option value="Inactivo" <?= $mantenimiento['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
      </select>

      <label>Observaciones</label>
      <textarea name="observaciones" rows="2"><?= htmlspecialchars($mantenimiento['observaciones']) ?></textarea>
    </div>

    <div class="buttons">
      <button type="submit" class="btn-primary">💾 Actualizar</button>
      <a href="/logistica_global/controllers/mantenimientoController.php?accion=listar" class="btn-secondary">⬅️ Cancelar</a>
    </div>
  </form>
</div>
