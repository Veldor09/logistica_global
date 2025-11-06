<div class="form-container">
  <h1>🧰 Registrar Mantenimiento</h1>

  <form method="POST" action="/logistica_global/controllers/mantenimientoController.php?accion=crear">
    <div class="form-grid">

      <!-- 🚗 Selección por placa -->
      <label>Vehículo (Placa)</label>
      <select name="id_vehiculo" required>
        <option value="">-- Seleccione vehículo --</option>
        <?php foreach ($vehiculos as $v): ?>
          <option value="<?= $v['id_vehiculo'] ?>">
            <?= htmlspecialchars($v['placa']) ?> - <?= htmlspecialchars($v['marca'] ?? '') ?> <?= htmlspecialchars($v['modelo'] ?? '') ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- 📅 Fecha -->
      <label>Fecha</label>
      <input type="date" name="fecha" required>

      <!-- 🧩 Tipo de mantenimiento -->
      <label>Tipo de Mantenimiento</label>
      <select name="id_tipo_mantenimiento" required>
        <option value="">-- Seleccione tipo --</option>
        <?php foreach ($tipos as $t): ?>
          <option value="<?= $t['id_tipo_mantenimiento'] ?>">
            <?= htmlspecialchars($t['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- 📝 Descripción -->
      <label>Descripción</label>
      <textarea name="descripcion" rows="2"></textarea>

      <!-- 💰 Costo -->
      <label>Costo (₡)</label>
      <input type="number" step="0.01" name="costo">

      <!-- 🔄 Estado -->
      <label>Estado</label>
      <select name="estado">
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
      </select>

      <!-- 💬 Observaciones -->
      <label>Observaciones</label>
      <textarea name="observaciones" rows="2"></textarea>
    </div>

    <div class="buttons">
      <button type="submit" class="btn-primary">💾 Guardar</button>
      <a href="/logistica_global/controllers/mantenimientoController.php?accion=listar" class="btn-secondary">⬅️ Volver</a>
    </div>
  </form>
</div>
