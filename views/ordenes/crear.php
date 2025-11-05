<div class="form-container bg-white p-6 rounded-2xl shadow-md border">
  <h2 class="text-2xl font-bold text-center mb-6">Registrar Nueva Orden de Transporte</h2>

  <form method="POST" action="/logistica_global/controllers/ordenController.php?accion=crear" class="space-y-4">

    <!-- 🔽 Selección de Solicitud -->
    <div>
      <label for="id_solicitud" class="block font-semibold mb-1">Solicitud Asociada:</label>
      <select name="id_solicitud" id="id_solicitud" class="input-field" required>
        <option value="">-- Seleccione una solicitud --</option>
        <?php foreach ($solicitudes as $s): ?>
          <option value="<?= $s['id_solicitud'] ?>">
            #<?= $s['id_solicitud'] ?> - <?= htmlspecialchars($s['tipo_servicio']) ?> (<?= htmlspecialchars($s['origen']) ?> → <?= htmlspecialchars($s['destino_general']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- 🏠 Dirección de origen -->
    <div>
      <label for="direccion_origen" class="block font-semibold mb-1">Dirección de Origen:</label>
      <input type="text" name="direccion_origen" id="direccion_origen" class="input-field" required>
    </div>

    <!-- 🎯 Dirección de destino -->
    <div>
      <label for="direccion_destino" class="block font-semibold mb-1">Dirección de Destino:</label>
      <input type="text" name="direccion_destino" id="direccion_destino" class="input-field" required>
    </div>

    <!-- ⚖️ Peso estimado -->
    <div>
      <label for="peso_estimado_kg" class="block font-semibold mb-1">Peso Estimado (kg):</label>
      <input type="number" step="0.01" name="peso_estimado_kg" id="peso_estimado_kg" class="input-field" required>
    </div>

    <!-- 📅 Fechas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label for="fecha_carga" class="block font-semibold mb-1">Fecha de Carga:</label>
        <input type="date" name="fecha_carga" id="fecha_carga" class="input-field">
      </div>

      <div>
        <label for="fecha_entrega_estimada" class="block font-semibold mb-1">Fecha Entrega Estimada:</label>
        <input type="date" name="fecha_entrega_estimada" id="fecha_entrega_estimada" class="input-field">
      </div>
    </div>

    <!-- 🚦 Estado -->
    <div>
      <label for="estado" class="block font-semibold mb-1">Estado:</label>
      <select name="estado" id="estado" class="input-field">
        <option value="Programada">Programada</option>
        <option value="En Progreso">En Progreso</option>
        <option value="Completada">Completada</option>
        <option value="Cancelada">Cancelada</option>
      </select>
    </div>

    <!-- 📝 Observaciones -->
    <div>
      <label for="observaciones" class="block font-semibold mb-1">Observaciones:</label>
      <textarea name="observaciones" id="observaciones" class="input-field" rows="3"></textarea>
    </div>

    <!-- BOTONES -->
    <div class="flex justify-between items-center pt-4">
      <a href="/logistica_global/controllers/ordenController.php" class="btn btn-secondary">← Volver</a>
      <button type="submit" class="btn btn-primary">💾 Guardar Orden</button>
    </div>
  </form>
</div>
